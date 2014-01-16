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
$wpbb_xml_content = file_get_contents( 'http://local.dev/dev/sample.xml' ); // php://input

/* parse the retreived xml file */
$wpbb_params = json_decode( json_encode( simplexml_load_string( $wpbb_xml_content ) ), 1 );

/* check username and password match the stored informaiton */
if( wp_strip_all_tags( $wpbb_params[ 'username' ] ) != $wpbb_username || wp_strip_all_tags( $wpbb_params[ 'password' ] != $wpbb_password ) ) {
	
	/* username and/or password are not correct, show an error message */
	echo '<p>Sorry username and/or password are not valid</p>';
	
	/* stop file loading any further */
	die();

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
		
		/* output an error message, as a job with this job reference already exists */
		echo '<p class="error">Oops, this job was not added, as a job with this jobs job reference already exists.</p>';
		
		/* stop any further loading */
		die();
		
	}

	/*****************************************************
	* add all the location ids to an array
	*****************************************************/
		
	/* turn terms into arrays */
	$wpbb_location = wp_strip_all_tags( $wpbb_params[ 'job_location' ] );
	$wpbb_location_terms = explode( ',', $wpbb_location );
	
	/* setup array to store the term ids in */
	$wpbb_location_term_ids = array();
	
	/* loop through each term in array getting its id */
	foreach( $wpbb_location_terms as $wpbb_location_term ) {
		
		/* check whether the term exists, and return its ID if it does, adding it to our array */
		$wpbb_location_term_ids[] = term_exists( $wpbb_location_term );
		
	} // end loop through each term
	
	/*****************************************************
	* add all the category ids to an array
	*****************************************************/
	
	/* turn category terms into arrays */
	$wpbb_category = wp_strip_all_tags( $wpbb_params[ 'job_category' ] );
	$wpbb_category_terms = explode( ',', $wpbb_category );
	
	/* setup array to store the category term ids in */
	$wpbb_category_term_ids = array();
	
	/* loop through each term in array getting its id */
	foreach( $wpbb_category_terms as $wpbb_category_term ) {
		
		/* check whether the term exists, and return its ID if it does, adding it to our array */
		$wpbb_category_term_ids[] = term_exists( $wpbb_category_term );
		
	} // end loop through each term
	
	/*****************************************************
	* setup all the type terms into an array
	*****************************************************/
	
	/* turn type terms into arrays */
	$wpbb_job_type = wp_strip_all_tags( $wpbb_params[ 'job_type' ] );
	$wpbb_job_type_terms = explode( ',', $wpbb_job_type );
	
	/* setup array to store the category term ids in */
	$wpbb_job_type_term_ids = array();
	
	/* loop through each term in array getting its id */
	foreach( $wpbb_job_type_terms as $wpbb_job_type_term ) {
		
		/* check whether the term exists, and return its ID if it does, adding it to our array */
		$wpbb_job_type_term_ids[] = term_exists( $wpbb_job_type_term );
		
	} // end loop through each term
	
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
	
		/* set the location post terms for the newly created job */
		wp_set_post_terms( $wpbb_job_post_id, $wpbb_location_term_ids, 'wpbb_job_location' );
		
		/* set the category post terms for the newly created job */
		wp_set_post_terms( $wpbb_job_post_id, $wpbb_category_term_ids, 'wpbb_job_category' );
		
		/* set the job type post terms for the newly created job */
		wp_set_post_terms( $wpbb_job_post_id, $wpbb_job_type_term_ids, 'wpbb_job_type' );
		
		/* set the location tag terms for then newly created job */
		wp_set_post_terms( $wpbb_job_post_id, wp_strip_all_tags( $wpbb_params[ 'job_location_tags' ] ), 'wpbb_job_location_tag' );
		
		/* set the post meta data (custom fields) */
		add_post_meta( $wpbb_job_post_id, '_wpbb_job_reference', wp_strip_all_tags( $wpbb_params[ 'job_reference' ] ), true );
		
		/* only set the data if they are sent */
		if( !empty( $wpbb_params[ 'salary_from' ] ) ) {
		
			add_post_meta( $wpbb_job_post_id, '_wpbb_job_salary_from', wp_strip_all_tags( $wpbb_params[ 'salary_from' ] ), true );
			
		}
		
		if( isset( $wpbb_params[ 'salary_to' ] ) ) {
		
			add_post_meta( $wpbb_job_post_id, '_wpbb_job_salary_to', wp_strip_all_tags( $wpbb_params[ 'salary_to' ] ), true );
			
		}
		
		if( isset( $wpbb_params[ 'salary_to' ] ) ) {
		
			add_post_meta( $wpbb_job_post_id, '_wpbb_job_salary_to', wp_strip_all_tags( $wpbb_params[ 'salary_to' ] ), true );
			
		}
		
		if( isset( $wpbb_params[ 'salary_per' ] ) ) {
		
			add_post_meta( $wpbb_job_post_id, '_wpbb_job_salary_per', wp_strip_all_tags( $wpbb_params[ 'salary_per' ] ), true );
			
		}
		
		if( isset( $wpbb_params[ 'salary_currency' ] ) ) {
		
			add_post_meta( $wpbb_job_post_id, '_wpbb_job_salary_currency', wp_strip_all_tags( $wpbb_params[ 'salary_currency' ] ), true );
			
		}
		if( isset( $wpbb_params[ 'job_startdate' ] ) ) {
		
			add_post_meta( $wpbb_job_post_id, '_wpbb_job_start_date', wp_strip_all_tags( $wpbb_params[ 'job_startdate' ] ), true );
			
		}
		
		if( isset( $wpbb_params[ 'job_duration' ] ) ) {
		
			add_post_meta( $wpbb_job_post_id, '_wpbb_job_duration', wp_strip_all_tags( $wpbb_params[ 'job_duration' ] ), true );
			
		}
		
		if( isset( $wpbb_params[ 'application_email' ] ) ) {
		
			add_post_meta( $wpbb_job_post_id, '_wpbb_job_application_email', wp_strip_all_tags( $wpbb_params[ 'application_email' ] ), true );
			
		}
		if( isset( $wpbb_params[ 'contact_email' ] ) ) {
		
			add_post_meta( $wpbb_job_post_id, '_wpbb_job_contact_email', wp_strip_all_tags( $wpbb_params[ 'contact_email' ] ), true );
			
		}
		
		echo '<p>Success! - This Job has been added and has a post ID of ' . $wpbb_job_post_id . '. The permalink to this job is: ' . get_permalink( $wpbb_job_post_id ) . '</p>';
	
	/* no post id exists for the newly created job - an error occured */
	} else {
		
		echo '<p>There was an error, the job was not published.</p>';
		
	} // end if insert post has an id
	
}

/* check whether we are deleting a post */
if( strtolower( wp_strip_all_tags( $wpbb_params[ 'command' ] ) ) == 'delete' ) {
	
	/* get the job reference for this job */
	$wpbb_job_reference = wp_strip_all_tags( $wpbb_params[ 'job_reference' ] );
	
	/* setup args to get posts with this job reference */
	$wpbb_posts_args = array(
		'post_type' => 'wpbb_job',
		'meta_key' => 'wpbb_job_reference',
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