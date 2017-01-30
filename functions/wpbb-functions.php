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
 * function wpbb_prepare_terms()
 * prepare terms sent via the broadbean feed to adding to a job post once created
 * returns an array of mixed term ids and terms names
 * hierarchical taxonomies have term ids added whereas non hierarchical taxonomies have term name added 
 * @param (string) $sent_terms are the terms sent via the broadbean feed for this taxonomy
 * @param (sstring) $taxonomy is the taxomony name associated with the terms in $sent_terms
 *
 * @return
 */
function wpbb_prepare_terms( $sent_terms, $taxonomy ) {
	
	if ( empty( $sent_terms ) ) {
		return;
	}
	
	/* turn category terms into arrays */
	$wpbb_tax = wp_strip_all_tags( $sent_terms );
	$wpbb_taxonomy_terms = explode( ',', $wpbb_tax );

	/* setup array to store the category term ids in */
	$wpbb_tax_terms = array();

	/* loop through each term in array getting its id */
	foreach( $wpbb_taxonomy_terms as $wpbb_taxonomy_term ) {
		
		/* 	check whether the term exists, and return its ID if it does, 
			if it doesn't exist then create it 
			either way add it to our array 
		*/
		/* check whether the term exists */
		if ( $term_id = term_exists( $wpbb_taxonomy_term, $taxonomy[ 'taxonomy_name' ] ) ) {
			
			/* check if the taxonomy is hierarchical */
			if( $taxonomy[ 'hierarchical' ] == true ) {
				
				/* add to term id to our terms array */
				$wpbb_tax_terms[] = $term_id[ 'term_id' ];
				
			} else {
			
				/* add the term name to our terms array */
				$wpbb_tax_terms[] = $wpbb_taxonomy_term;
				
			}

		} else {

			/* check term we are adding is not an empty string */
			if( $wpbb_taxonomy_term != '' ) {

				$new_term = wp_insert_term(
					$wpbb_taxonomy_term, // term to insert
					$taxonomy[ 'taxonomy_name' ], // taxonomy to add the term to
					array(
						'slug' => sanitize_title( $wpbb_taxonomy_term )
					)
				);
				
				/* check if the taxonomy is hierarchical */
				if( $taxonomy[ 'hierarchical' ] == true ) {
					
					/* add to term id to our terms array */
					$wpbb_tax_terms[] = $new_term['term_id'];
					
				} else {
					
					/* get the term name from its id */
					$new_term_obj = get_term_by(
						'id',
						$new_term['term_id'],
						$taxonomy[ 'taxonomy_name' ]
					);
				
					/* add the term name to our terms array */
					$wpbb_tax_terms[] = $new_term_obj->name;
					
				}

			}
		
		}
		
		
	} // end loop through each term

	return $wpbb_tax_terms;
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
		
	/**
	 * set some filterable query args
	 * devs can use this filter to add other query args to the application url
	 * this could be used to pre-populate the form with content
	 */
	$wpbb_apply_query_args = apply_filters(
		'wpbb_apply_query_args',
		array(
			'job_id' => get_post_meta( $post_id, '_wpbb_job_reference', true )
		),
		$post_id
	);
	
	/* build apply link from url */
	$wpbb_apply_link = add_query_arg( $wpbb_apply_query_args, get_permalink( $wpbb_apply_page_id ) );
	
	/**
	 * return the apply url
	 * devs can filter this to use different URL e.g. a gravity form page etc.
	 */
	return esc_url( apply_filters( 'wpbb_apply_url', $wpbb_apply_link, $post_id ) );
	
}

/**
 * Function wpbb_apply_button()
 * Outputs the apply now button after the loop on job single posts
 */
function wpbb_apply_button( $content ) {
	
	global $post;
	
	/* check this is a single job post */
	if( ! is_singular( wpbb_job_post_type_name() ) )
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
 * @param (string) $prefix is the prefix to use for the custom field key. Defaults to _wpbb_
 * return (string) $field the value of the field
 */
function wpbb_get_field( $field, $post_id = '', $prefix = '_wpbb_' ) {
	
	global $post;
	
	/* if no post id is provided use the current post id in the loop */
	if( empty( $post_id ) )
		$post_id = $post->ID;
	
	/* if we have no field name passed go no further */
	if( empty( $field ) )
		return false;
	
	/* build the meta key to return the value for */
	$key = $prefix . $field;
	
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
		apply_filters(
			'wpbb_get_job_by_reference_args',
			array(
				'post_type'		=> wpbb_job_post_type_name(),
				'post_status'	=> 'publish',
				'meta_key'		=> '_wpbb_job_reference',
				'meta_value'	=> $job_ref,
				'fields'		=> 'ids'
			)
		)
	);

	// get the post ids into an array
	$wpbb_jobs = $wpbb_jobs->posts;

	// reset the query
	wp_reset_query();

	// return the post id of the found job
	return apply_filters( 'wpbb_get_job_by_reference', array_shift( $wpbb_jobs ), $job_ref );
	
}

/**
 * function wpbb_job_fields_output()
 * adds the job fields to the bottom of the job post content
 * @param (string) @content is the current content of the job post
 */
function wpbb_job_fields_output( $content ) {
	
	/* only carry on if we are on a single job post view */
	if( ! is_singular( wpbb_job_post_type_name() ) )
		return $content;
	
	global $post;
	
	/**
	 * now we need to handle the job fields that should be outputted for this job
	 * lets start by getting all the job fields as an array
	 */
	$wpbb_job_fields = wpbb_get_job_fields();
	
	/* check we have any fields */
	if( ! empty( $wpbb_job_fields ) ) {
		
		/* how many field have we got to output */
		$field_count = count( $wpbb_job_fields );
		
		/* lets setup some markup */
		$output = '<div class="' . apply_filters( 'wpbb_job_fields_wrapper_class', 'wpbb-job-fields-wrapper' ) . '">';
		
		/* start a counter */
		$counter = 1;
		
		/**
		 * we need to loop through each of these fields
		 * start a loop to loop through each field
		 */
		foreach( $wpbb_job_fields as $field ) {
			
			/* check whether this is front end output field - if not move to the next field */
			if( $field[ 'show_on_frontend' ] == false )
				continue;
			
			/* build a field class to use for this field */
			$class = 'wpbb-job-field wpbb-job-field-' . $counter . ' ' . $field[ 'bb_field' ];
			
			/* check if this is the last field */
			if( $counter == $field_count ) {
				
				/* add to our class */
				$class .= ' last-field';
				
			}
			
			/* start the markup with a field wrapper div */
			$output .= '<div class="' . esc_attr( apply_filters( 'wpbb_job_field_class', $class, $field[ 'bb_field' ] ) ) . '" id="' . $field[ 'bb_field' ] . '">';
			
				$output .= '<p><span class="wpbb-job-field-label">' . esc_html( $field[ 'name' ] ) . ':</span> <span class="wpbb-job-field-value">' . ucfirst( get_post_meta( $post->ID, $field[ 'id' ], true ) ) . '</span></p>';
			
			/* close out the field markup */
			$output .= '</div>';
			
			/* increment the counter */
			$counter++;
		
		}
		
		/* close out the markup */
		$output .= '</div>';
		
		/* check we have markup to output */
		if( ! empty( $output ) )
			return $content . apply_filters( 'wpbb_job_fields_output', $output, $post->ID );
		
	}
	
	return $content;
	
}

add_filter( 'the_content', 'wpbb_job_fields_output', 20 );

/**
 * function wpbb_job_terms_output()
 * adds the job terms from registered wpbb taxonomies to the bottom of the job post content
 * @param (string) @content is the current content of the job post
 */
function wpbb_job_terms_output( $content ) {
	
	global $post;
	
	/* only carry on if we are on a single job post view */
	if( ! is_singular( wpbb_job_post_type_name() ) )
		return $content;
	
	/* get the registered wpbb taxonomies */
	$taxonomies = wpbb_get_registered_taxonomies();
	
	/* check we have any taxonomies to deal with */
	if( ! empty( $taxonomies ) ) {
		
		/* how many field have we got to output */
		$tax_count = count( $taxonomies );
		
		/* lets setup some markup */
		$output = '<div class="' . apply_filters( 'wpbb_job_tax_wrapper_class', 'wpbb-job-tax-wrapper' ) . '">';
		
		/* start a counter */
		$counter = 1;
		
		/**
		 * we need to loop through each of these taxonomies
		 * start a loop to loop through each field
		 */
		foreach( $taxonomies as $tax ) {
			
			/* check whether this is front end output tax - if not move to the next taxonomy */
			if( $tax[ 'show_on_frontend' ] == false )
				continue;
			
			/* build a field class to use for this field */
			$class = 'wpbb-job-tax wpbb-job-tax-' . $counter . ' ' . $tax[ 'bb_field' ];
			
			/* check if this is the last field */
			if( $counter == $tax_count ) {
				
				/* add to our class */
				$class .= ' last-tax';
				
			}
			
			$output .= get_the_term_list(
				$post->ID,
				$tax[ 'taxonomy_name' ],
				'<div class="' . esc_attr( apply_filters( 'wpbb_job_tax_class', $class, $tax[ 'bb_field' ] ) ) . '" id="' . $tax[ 'bb_field' ] . '"><p><span class="wpbb-job-tax-label">' . esc_html( $tax[ 'singular' ] ) . ':</span> ',
				', ',
				'</span></p></div>'
			);
			
			/* increment the counter */
			$counter++;	
			
		}
		
		/* close out the markup */
		$output .= '</div>';
		
		/* check we have markup to output */
		if( ! empty( $output ) )
			return $content . apply_filters( 'wpbb_job_taxonomies_output', $output, $post->ID );
		
	}

}

add_filter( 'the_content', 'wpbb_job_terms_output', 30 );

/**
 * function wpbb_get_setting()
 *
 * gets a named plugin settings returning its value
 * @param	mixed	key name to retrieve - this is the key of the stored option
 * @return	mixed	the value of the key
 */
function wpbb_get_setting( $name = '' ) {
	
	/* if no name is passed */
	if( empty( $name ) ) {
		return false;
	}
	
	/* get the option */
	$setting = get_option( 'wpbb_' . $name );
	
	/* check we have a value returned */
	if( empty( $setting ) ) {
		return false;
	}
	
	return apply_filters( 'wpbb_get_setting', $setting );
	
}

/**
 * returns string for use when setting wp_mail content type
 */
function wpbb_text_html_email_type() {
	return 'text/html';
}

/**
 * 
 */
function wpbb_maybe_remove_application_attachments() {
	return apply_filters( 'wpbb_remove_application_attachments_after_send', true );
}