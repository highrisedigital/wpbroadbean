<?php
/**
 * function wpbb_get_job_fields()
 * gets all the fields that have been registered for jobs
 * devs can add/remove fields using this filter - wpbb_job_fields
 */
function wpbb_get_job_fields() {

	$fields = apply_filters(
		'wpbb_job_fields',
		array()
	);
	
	return $fields;	
	
}

/**
 * function wpbb_convert_cat_terms_to_ids()
 *
 * takes a category term name and converts it to its id
 * @param $tax_broadbean_field
 * @param $wpbb_params
 * @param $taxonomy
 *
 * @return
 */
function wpbb_convert_cat_terms_to_ids( $tax_broadbean_field, $wpbb_xml_params, $taxonomy ) {

	if ( empty( $wpbb_xml_params->$tax_broadbean_field ) ) {
		return;
	}
	
	/* turn category terms into arrays */
	$wpbb_category = wp_strip_all_tags( $wpbb_xml_params->$tax_broadbean_field );
	$wpbb_category_terms = explode( ',', $wpbb_category );

	/* setup array to store the category term ids in */
	$wpbb_category_term_ids = array();

	/* loop through each term in array getting its id */
	foreach( $wpbb_category_terms as $wpbb_category_term ) {
		
		/* 	check whether the term exists, and return its ID if it does, 
			if it doesn't exist then create it 
			either way add it to our array 
		*/
		if ( $term_id = term_exists( $wpbb_category_term ) ) {
			$wpbb_category_term_ids[] = $term_id;
		} else {
			$new_term = wp_insert_term(
				$wpbb_category_term, // term to insert
				$taxonomy['taxonomy_name'], // taxonomy to add the term to
				array(
					'slug' => sanitize_title( $wpbb_category_term )
				)
			);
			$wpbb_category_term_ids[] = $new_term['term_id'];
		}
		
		
	} // end loop through each term

	return $wpbb_category_term_ids;
}

/**
 * Calculate Job Expiry Date
 * takes today's date and calculates a future date
 * by adding the number of days that the Broadbean data
 * has provided as the number of days to advertise
 */
function wpbb_calculate_job_expiry_date( $days_to_advertise ) {
	
	/* make sure the number if days provided is an integer */
	$days_to_advertise = (int) $days_to_advertise;
	
	/* return the expiry date of a job according to the days to advertise provided in the bb feed */
	return Date( 'y-m-d', strtotime( "+{$days_to_advertise} days" ) );
	
}

/**
 * function wpbb_job_post_type_name()
 * returns the name of the custom post type for jobs
 * allows developers to use a different custom post type for jobs
 * rather than the post type that comes with the plugin
 */
function wpbb_job_post_type_name() {
	
	return apply_filters( 'wpbb_job_post_type', 'wpbb_job' );
	
}

/**
 * Function wpbb_get_current_post_type()
 * Returns the post type of the current post in wp admin.
 */
function wpbb_get_current_post_type() {
	
	global $post, $typenow, $current_screen;
	
	/* we have a post so we can just get the post type from that */
	if ( $post && $post->post_type )
		return $post->post_type;
	
	/* check the global $typenow - set in admin.php */
	elseif( $typenow )
		return $typenow;
	
	/* check the global $current_screen object - set in sceen.php */
	elseif( $current_screen && $current_screen->post_type )
		return $current_screen->post_type;
	
	/* lastly check the post_type querystring */
	elseif( isset( $_REQUEST['post_type'] ) )
		return sanitize_key( $_REQUEST['post_type'] );
	
	/* we do not know the post type! */
	return null;
	
}

/**
 * Function wpbb_get_apply_url()
 * Returns the apply link for a job.
 */
function wpbb_get_apply_url( $post_id = '' ) {

	global $post;
	
	/* if there is not post id provided use current post id */
	if( empty( $post_id ) )
		$post_id = $post->ID;
	
	/* check current post is a job post */
	if( wpbb_job_post_type_name() != get_post_type( $post_id ) )
		return;
	
	/* get the page id for the apply now page from settings */
	$wpbb_apply_page_id = get_option( 'wpbb_apply_page_id' );
		
	/* set some filterable query args */
	$wpbb_apply_query_args = apply_filters(
		'wpbb_apply_query_args',
		array(
			'job_id' => get_post_meta( $post_id, '_wpbb_job_reference', true )
		),
		$post_id
	);
	
	/* build apply link from url */
	$wpbb_apply_link = add_query_arg( $wpbb_apply_query_args, get_permalink( $wpbb_apply_page_id ) );
	
	/* return the apply url */
	return esc_url( apply_filters( 'wpbb_apply_url', $wpbb_apply_link, $post_id ) );
	
}

/**
 * Function wpbb_apply_button()
 * Outputs the apply now button after the loop on job single posts
 */
function wpbb_apply_button( $content ) {
	
	/* check this is a single job post */
	if( ! is_singular( 'wpbb_job' ) )
		return $content;
	
	return $content . '<p class="apply-button"><a href="' . wpbb_get_apply_url( $post->ID ) . '">Apply Now</a></p>';
	
}

add_filter( 'the_content', 'wpbb_apply_button', 90 );

/**
 * function wpbb_page_dropdown_array()
 * Creates an array of pages to use in a select list.
 */
function wpbb_page_dropdown_array() {

	/* set args for getting pages */
	$wpbb_pages_args = array(
		'post_type' => 'page',
		'posts_per_page' => -1,
	);

	/* get the pages */
	$wpbb_pages = get_posts( $wpbb_pages_args );

	/* check we have pages */
	if( empty( $wpbb_pages ) )
		return false;

	/* create array to store our pages arrays in */
	$wpbb_pages_array = array(
		array(
			'name' => 'Choose a Page',
			'value' => 'zero',
		),
	);

	/* loop through each page */
	foreach( $wpbb_pages as $wpbb_page ) {

		/* setup the posts data to get access to post functions */
		setup_postdata( $wpbb_page );

		/* push page id and name into pages array */
		$wpbb_pages_array[] = array(
			'name' => $wpbb_page->post_title,
			'value' => $wpbb_page->ID,
		);

	}

	return $wpbb_pages_array;

}

/**
 * function wpbb_job_salary_currency_symbol()
 * Returns the currency symbol for the current jobs salary.
 */
function wpbb_job_salary_currency_symbol( $post_id = '' ) {
	
	global $post;
	
	/* use current post id if none given */
	if( empty( $post_id ) )
		$post_id = $post->ID;
	
	/* get the salary currency value */
	$wpbb_salary_currency = get_post_meta( $post_id, '_wpbb_job_salary_currency', true );
	
	/* switch statement for which currency */
	switch( $wpbb_salary_currency ) {
		
		case 'gbp' :
			
			/* set currency symbol to pound sterling */
			$wpbb_salary_currency_symbol = '&pound;';
			
			/* break out of switch */
			break;
		
		case 'eur' :
			
			/* set currency symbol to euro */
			$wpbb_salary_currency_symbol = '&euro;';
			
			/* break out of switch */
			break;
		
		default :
			
			/* set currency symbol to pound sterling */
			$wpbb_salary_currency_symbol = '&pound;';
			
	}
	
	return $wpbb_salary_currency_symbol;
	
}

/**
 * function wpbb_get_job_salary()
 * Gets the salary for the current job, checking whether there is 
 * a salary range and whehter it is per year, month etc.
 */
function wpbb_get_job_salary( $post_id = '' ) {
	
	global $post;
	
	/* use current post id if none given */
	if( empty( $post_id ) )
		$post_id = $post->ID;
	
	/* get salary meta for this job */
	$wpbb_salary = get_post_meta( $post_id, '_wpbb_job_salary', true );
	$wpbb_salary_per = get_post_meta( $post_id, '_wpbb_job_salary_per', true );

	/* set string to return for output */
	$wpbb_salary_output = wpbb_job_salary_currency_symbol( $post_id ) . number_format( $wpbb_salary ) . '/' . $wpbb_salary_per;
			
	return apply_filters( 'wpbb_job_salary_html', $wpbb_salary_output, $post_id );
	
}

/**
 * function wpbb_get_field()
 * gets the value of a meta box field for a wpbb post
 * @param (string) $field is the name of the field to return
 * @param (int) $post_id is the id of the post for which to look for the field in - defaults to current loop post
 * return (string) $field the value of the field
 */
function wpbb_get_field( $field, $post_id = '' ) {
	
	global $post;
	
	/* if no post id is provided use the current post id in the loop */
	if( empty( $post_id ) )
		$post_id = $post->ID;
	
	/* if we have no field name passed go no further */
	if( empty( $field ) )
		return false;
	
	/* build the meta key to return the value for */
	$key = '_wpbb_' . $field;
	
	/* gete the post meta value for this field name of meta key */
	$field = get_post_meta( $post_id, $key, true );
	
	return apply_filters( 'wpbb_field_value', $field );
	
}

/**
 * function wpbb_get_job_by_reference()
 * gets the job post id for a job given its reference
 * @param (string) $job_ref is the job refrerence to check by
 * @return (int/false) $post_id returns the post of the job if a job exists with that reference or false
 */
function wpbb_get_job_by_reference( $job_ref ) {
		
	/* get posts according to args above */
	$wpbb_jobs = new WP_Query(
		array(
			'post_type' => wpbb_job_post_type_name(),
			'post_status'=> 'publish',
			'meta_key' => '_wpbb_job_reference',
			'meta_value' => $job_ref,
			'relation' => 'AND'
		)
	);
	
	/* check there is a post with this job reference */
	if( $wpbb_jobs->have_posts() ) {
		
		/* loop through each post returned */
		while( $wpbb_jobs->have_posts() ) : $wpbb_jobs->the_post();
		
			/* return the post id */
			return $post->ID;
		
		/* end the loop */
		endwhile;
		
	}
	
	/* reset query */
	wp_reset_query();
	
	return false;
	
}