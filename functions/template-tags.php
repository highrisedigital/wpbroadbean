<?php
/***************************************************************
* Function wpbb_get_current_post_type()
* Returns the post type of the current post in wp admin.
***************************************************************/
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

/***************************************************************
* Function wpbb_get_apply_url()
* Returns the apply link for a job.
***************************************************************/
function wpbb_get_apply_url( $post_id = '' ) {

	global $post;
	
	/* if there is not post id provided use current post id */
	if( empty( $post_id ) )
		$post_id = $post->ID;
	
	/* check current post is a job post */
	if( 'wpbb_job' != get_post_type( $post_id ) )
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

/*****************************************************************
* function wpbb_page_dropdown_array()
* Creates an array of pages to use in a select list.
*****************************************************************/
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

/*****************************************************************
* function wpbb_job_salary_currency_symbol()
* Returns the currency symbol for the current jobs salary.
*****************************************************************/
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

/*****************************************************************
* function wpbb_get_job_salary()
* Gets the salary for the current job, checking whether there is 
* a salary range and whehter it is per year, month etc.
*****************************************************************/
function wpbb_get_job_salary( $post_id = '' ) {
	
	global $post;
	
	/* use current post id if none given */
	if( empty( $post_id ) )
		$post_id = $post->ID;
	
	/* get salary meta for this job */
	$wpbb_salary_from = get_post_meta( $post_id, '_wpbb_job_salary_from', true );
	$wpbb_salary_to = get_post_meta( $post_id, '_wpbb_job_salary_to', true );
	$wpbb_salary_per = get_post_meta( $post_id, '_wpbb_job_salary_per', true );
	
	/* check whether the from a to salaries match */
	if( $wpbb_salary_from == $wpbb_salary_to ) {
		
		/* set string to return for output */
		$wpbb_salary_output = wpbb_job_salary_currency_symbol( $post_id ) . $wpbb_salary_to . '/' . $wpbb_salary_per;
		
	/* from and to salaries are different */
	} else {
		
		/* set string to return for output */
		$wpbb_salary_output = wpbb_job_salary_currency_symbol( $post_id ) . $wpbb_salary_from . ' - ' . wpbb_job_salary_currency_symbol( $post_id ) . $wpbb_salary_to . '/' . $wpbb_salary_per;
		
	}
	
	return apply_filters( 'wpbb_job_salary_html', $wpbb_salary_output, $post_id );
	
}