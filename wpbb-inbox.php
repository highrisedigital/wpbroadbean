<?php
/*
* this file accepts the feed from Broadbean AdCourier. It then
* parses that feed and inserts a new job post into wordpress,
* including any meta data and taxonomies.
*/

/* get the username and password set by the plugin */
$wpbb_username = get_option( 'wpbb_username' );
$wpbb_password = get_option( 'wpbb_password' );

/******************************************************
* get the contents of the feed provided by adcourier
* to run testing, you can change the part in the brackets
* to a url that contains your testing xml
*******************************************************/
if ( defined( 'WP_DEBUG' ) && WP_DEBUG === true ) {
	$wpbb_xml_content = file_get_contents( constant('WPBB_JOB_TEST_DATA_URL') );
} else {
	$wpbb_xml_content = file_get_contents( 'php://input' );
}

/* parse the retreived xml file */
$wpbb_params = json_decode( json_encode( simplexml_load_string( $wpbb_xml_content ) ), 1 );

/* check username and password match the stored informaiton */
if( wp_strip_all_tags( $wpbb_params[ 'username' ] ) != $wpbb_username || wp_strip_all_tags( $wpbb_params[ 'password' ] != $wpbb_password ) ) {
	
	/* username and/or password are not correct, show an error message and stop file loading any further */
	wp_die( __( 'Sorry username and/or password are not valid.' ) );
	

} // if end username/password authenticate

/* check whether we are adding a post */
if( strtolower( wp_strip_all_tags( $wpbb_params[ 'command' ] ) ) == 'add' ) {
	
	/*****************************************************
	* check no other job has this job reference
	*****************************************************/
	
	/* get the job reference */
	$wpbb_job_reference = wp_strip_all_tags( $wpbb_params[ 'job_reference' ] );
	
	/* create query args to find job posts with the above job reference */
	$wpbb_job_ref_args = array(
		'post_type' => 'wpbb_job',
		'meta_key' => '_wpbb_job_reference',
		'meta_value' => $wpbb_job_reference
	);
	
	/* get posts with the submitted job reference */
	$wpbb_job_ref_posts = new WP_Query( $wpbb_job_ref_args );
	
	/* check whether we have any job returned with the submitted reference */
	if( $wpbb_job_ref_posts->have_posts() ) {
		
		/* output an error message, as a job with this job reference already exists and stop any further loading */
		wp_die( __( 'Oops, this job was not added, as a job with this jobs job reference already exists.' ) );
		
	}

	/******************************************************
	/* Handle Taxonomies setup
	*******************************************************/
	
	// Fetch all the registered taxonomies and set up holding array
	$wpbb_taxonomies = wpbb_get_registered_taxonomies();
	$wpbb_taxonomies_term_ids  = array();

	// add all the registered taxonomy term ids to an array
	foreach ($wpbb_taxonomies as $taxonomy) {
		$tax_bb_field = $taxonomy['broadbean_field'];
		$wpbb_taxonomies_term_ids[ $tax_bb_field ] = wpbb_convert_cat_terms_to_ids($tax_bb_field, $wpbb_params, $taxonomy);
	}


	/*****************************************************
	* setup the args to insert the job post
	*****************************************************/
	
	$wpbb_job_post_args = array(
		'post_type' => 'wpbb_job',
		'post_title' => wp_strip_all_tags( $wpbb_params[ 'job_title' ] ),
		'post_content' => wp_kses( $wpbb_params[ 'job_description' ], wp_kses_allowed_html( 'post' ) ),
		'post_status' => 'publish'
	);
	
	/*****************************************************
	* start adding the job post
	*****************************************************/
	
	/* insert the post returning the post id */
	$wpbb_job_post_id = wp_insert_post( $wpbb_job_post_args );
		
	/* check the post has been added */
	if( $wpbb_job_post_id != 0 ) {
		
		/* set the post terms for the newly created job for each registered job taxonomy */
		foreach ($wpbb_taxonomies as $taxonomy) {
			wp_set_post_terms( $wpbb_job_post_id, $wpbb_taxonomies_term_ids[ $taxonomy['broadbean_field'] ], $taxonomy['taxonomy_name'] );
			//wp_set_object_terms( $wpbb_job_post_id, $wpbb_taxonomies_term_ids[ $taxonomy['broadbean_field'] ], $taxonomy['taxonomy_name'] );
		}
		
		
		/* set the post meta data (custom fields) first for job reference */
		add_post_meta( $wpbb_job_post_id, '_wpbb_job_reference', wp_strip_all_tags( $wpbb_params[ 'job_reference' ] ), true );
		
		/* only set the data if they are sent */
		if( isset( $wpbb_params[ 'salary' ] ) )
			add_post_meta( $wpbb_job_post_id, '_wpbb_job_salary', wp_strip_all_tags( $wpbb_params[ 'salary' ] ), true );
		
		if( isset( $wpbb_params[ 'salary_per' ] ) )
			add_post_meta( $wpbb_job_post_id, '_wpbb_job_salary_per', wp_strip_all_tags( $wpbb_params[ 'salary_per' ] ), true );
		
		if( isset( $wpbb_params[ 'salary_currency' ] ) )
			add_post_meta( $wpbb_job_post_id, '_wpbb_job_salary_currency', wp_strip_all_tags( $wpbb_params[ 'salary_currency' ] ), true );
		
		if( isset( $wpbb_params[ 'job_startdate' ] ) )
			add_post_meta( $wpbb_job_post_id, '_wpbb_job_start_date', wp_strip_all_tags( $wpbb_params[ 'job_startdate' ] ), true );

		// Dynamically calculate an expiry date for the Job
		if( isset( $wpbb_params[ 'days_to_advertise' ] ) )
			$days_to_advertise = wp_strip_all_tags( $wpbb_params[ 'days_to_advertise' ] );
			$expiry_date = wpbb_calculate_job_expiry_date( $days_to_advertise );
			add_post_meta( $wpbb_job_post_id, '_wpbb_job_expiry_date', $expiry_date, true );
		
		if( isset( $wpbb_params[ 'job_duration' ] ) )
			add_post_meta( $wpbb_job_post_id, '_wpbb_job_duration', wp_strip_all_tags( $wpbb_params[ 'job_duration' ] ), true );
			
		if( isset( $wpbb_params[ 'application_email' ] ) )
			add_post_meta( $wpbb_job_post_id, '_wpbb_job_broadbean_application_email', wp_strip_all_tags( $wpbb_params[ 'application_email' ] ), true );

		if( isset( $wpbb_params[ 'contact_email' ] ) )
			add_post_meta( $wpbb_job_post_id, '_wpbb_job_contact_email', wp_strip_all_tags( $wpbb_params[ 'contact_email' ] ), true );
			
		echo '<p class="success">' . apply_filters( 'wpbb_job_added_success_message', 'Success! - This Job has been added and has a post ID of ' . $wpbb_job_post_id . '. The permalink to this job is: ' . get_permalink( $wpbb_job_post_id ) ) . '</p>';
	
	/* no post id exists for the newly created job - an error occured */
	} else {
		
		echo '<p class="error">' . apply_filters( 'wpbb_job_added_failure_message', 'There was an error, the job was not published.' ) . '</p>';
		
	} // end if insert post has an id
	
}

/* check whether we are deleting a job */
if( strtolower( wp_strip_all_tags( $wpbb_params[ 'command' ] ) ) == 'delete' ) {
	
	/* get the job reference for this job */
	$wpbb_job_reference = wp_strip_all_tags( $wpbb_params[ 'job_reference' ] );
	
	/* setup args to get posts with this job reference */
	$wpbb_posts_args = array(
		'post_type' => 'wpbb_job',
		'meta_key' => '_wpbb_job_reference',
		'meta_value' => $wpbb_job_reference
	);
	
	/* get posts that have this job reference */
	$wpbb_posts = get_posts( $wpbb_posts_args );
	
	/* setup string of deleted posts */
	$wpbb_deleted_posts = '<p>Post or posts deleted. The following post or posts were deleted: ';
	
	/* loop through each of the posts returned */
	foreach( $wpbb_posts as $wpbb_post ) {
		
		/* delete the post */
		wp_delete_post( $wpbb_post->ID );
		
		/* add to delete posts string */
		$wpbb_deleted_posts .= $wpbb_post->ID . ' | Job Reference: ' . $wpbb_job_reference;
		
	} // end loop through each post
	
	/* output confirmation message */
	echo $wpbb_deleted_posts . '</p>';
	
}

/* stop any further loading */
die();