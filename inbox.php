<?php
/**
 * this file accepts and processes the data sent by broadbean
 * it takes the feed, processes the data from the feed and then
 * creates the job post from that data.
 *
 * if you want to make changes the way in which your site adds job posts
 * please DO NOT edit this file. Create a folder named wpbb in your themes
 * root and then create a copy of this file named the same in that folder.
 * that file will then be used instead of this one.
 */

/**
 * start by getting the stored user name and password used to the feed
 */
$wpbb_username = wpbb_get_setting( 'username' );
$wpbb_password = wpbb_get_setting( 'password' );

/* set logging to false - filterable */
$wpbb_logging = apply_filters( 'wpbb_logging', false );

/**
 * get the contents of the feed provided by adcourier
 * if you want to test the feed, by pulling an XML feed from a specific url
 * you can use the wpbb_xml_feed_url filter
 */
$wpbb_xml_content = file_get_contents( apply_filters( 'wpbb_xml_feed_url', 'php://input' ) );

/**
 * turn the raw posted data into a more usable object
 */
$wpbb_xml_params = simplexml_load_string( $wpbb_xml_content );

/**
 * before we go any further - lets authenticate
 * check the username / password sent matches that stored
 */
if( wp_strip_all_tags( (string) $wpbb_xml_params->username ) != $wpbb_username || wp_strip_all_tags( (string) $wpbb_xml_params->password != $wpbb_password ) ) {
	
	/* username and/or password are not correct, show an error message and stop file loading any further */
	wp_die( __( 'Error: Sorry username and/or password are not valid.' ) );
	

} // if end username/password authenticate

/**
 * we are authenticated now and therefore we need to check what to do
 * are we adding a job or deleting a job
 */
if( strtolower( wp_strip_all_tags( (string) $wpbb_xml_params->command ) ) == 'add' ) {
	
	/**
	 * we are adding a job
	 * first check no other job already in the system has this job reference
	 */
	if( wpbb_get_job_by_reference( (string) $wpbb_xml_params->job_reference ) != false ) {
		
		/**
		 * there is already a job in the system with this job reference
		 * fail and tell the user why
		 */
		wp_die( __( 'Error: Oops, this job was not added, as a job with this jobs job reference already exists.' ) );
		
	}
	
	/**
	 * we are good to go in terms of adding the job to the system
	 * lets start by handling the jobs taxonomies
	 * get a list of all the wpbb registered taxonomies
	 * once this has run all the terms to add to this job are stored in the array named $wpbb_tax_terms
	 */
	$wpbb_taxonomies = wpbb_get_registered_taxonomies();
	
	/* set up holding array */
	$wpbb_tax_terms = array();
	
	/** 
	 * loop through each of the taxonomies we have preparing it for adding to the post
	 */
	foreach( $wpbb_taxonomies as $taxonomy ) {
		
		/* if no tax terms sent - continue */
		if( $wpbb_xml_params->$taxonomy[ 'bb_field' ] == '' ) {
			continue;
		}
		
		/* add the prepared terms to our terms array */
		$wpbb_tax_terms[ $taxonomy[ 'bb_field' ] ] = wpbb_prepare_terms( $wpbb_xml_params->$taxonomy[ 'bb_field' ], $taxonomy );
		
	}
		
	/**
	 * lets now insert the actual post into wordpress
	 * uses the wp_insert_post function to do this
	 * if this works it will return the post id of the job added
	 */
	$wpbb_job_post_id = wp_insert_post(
		apply_filters(
			'wpbb_insert_job_post_args',
			array(
				'post_type' => wpbb_job_post_type_name(),
				'post_title' => wp_strip_all_tags( (string) $wpbb_xml_params->job_title ),
				'post_content' => wp_kses( $wpbb_xml_params->job_description, wp_kses_allowed_html( 'post' ) ),
				'post_status' => 'publish'
			),
			$wpbb_xml_params
		)
	);
	
	/**
	 * lets check that the job was added
	 * checking for a job id present in the variable
	 */
	if( $wpbb_job_post_id != 0 ) {
		
		/**
		 * if wpbb logging is set to true the plugin will save the raw incoming XML feed
		 * as post meta for this job with the key being _wpbb_raw_bb_feed
		 * it also add the date before the XML output.
		 */
		if( $wpbb_logging == true ) {
			
			$wpbb_debug_content = array();
			
			/* combine the XML with the current date stamp */
			$wpbb_debug_content[ 'sent_date' ] = date( 'd:m:Y H:i:s' );
			$wpbb_debug_content[ 'sent_xml' ] = $wpbb_xml_content;
			
			/* lets save the raw posted data in post meta for this job */
			add_post_meta(
				$wpbb_job_post_id, // this is id of the job we have just added
				'_wpbb_raw_bb_feed', // this is the meta key to store the post meta in
				$wpbb_debug_content, // this is value to store - sent from broadbean
				true
			);
			
		} // end if we should be logging errors
		
		/**
		 * job was added successfully
		 * start by looping through the tax terms ids to add to this job
		 */
		foreach( $wpbb_taxonomies as $taxonomy ) {
			
			/* if no tax terms sent - continue */
			if( $wpbb_xml_params->$taxonomy[ 'bb_field' ] == '' ) {
				continue;
			}
			
			wp_set_post_terms(
				$wpbb_job_post_id,
				$wpbb_tax_terms[ $taxonomy[ 'bb_field' ] ],
				$taxonomy[ 'taxonomy_name' ]
			);
			
			/**
			 * @hook wpbb_job_term_added
			 * fires after the term has been added
			 * @param (int) $wpbb_job_post_id is the post id for the added job
			 * @param (string) $wpbb_tax_term term to be added
			 * @param (array) $taxonomy taxonomy of the term
			 */
			do_action( 'wpbb_job_term_added', $wpbb_job_post_id, $wpbb_tax_terms[ $taxonomy[ 'bb_field' ] ], $taxonomy[ 'taxonomy_name' ] );
			
		} // end loop through terms
		
		/**
		 * now we need to handle the job fields that should be added to this job
		 * lets start by getting all the job fields as an array
		 */
		$wpbb_job_fields = wpbb_get_job_fields();
		
		/* check we have any fields */
		if( ! empty( $wpbb_job_fields ) ) {
			
			/**
			 * we need to loop through each of these fields
			 * using info stored in the array we need to add each field to the job
			 * start a loop to loop through each field
			 */
			foreach( $wpbb_job_fields as $field ) {
				
				/**
				 * we cannot carry on if this field does not have a bb_field array element
				 * lets check it is present and if not move onto the next field
				 */
				if( empty( $field[ 'bb_field' ] ) )
					continue;
				
				/**
				 * check that the XML tag containing the bb_field is actually set to something
				 * i.e. do we have data sent to actually store
				 */
				if( ! empty( $wpbb_xml_params->$field[ 'bb_field' ] ) ) {
					
					/* lets add the sent data as post meta for this job */
					add_post_meta(
						$wpbb_job_post_id, // this is id of the job we have just added
						$field[ 'id' ], // this is the meta key to store the post meta in
						wp_strip_all_tags( (string) $wpbb_xml_params->$field[ 'bb_field' ] ), // this is value to store - sent from broadbean
						true
					);
					
					/**
					 * @hook wpbb_job_field_added
					 * fires after the field/meta has been added
					 * @param (int) $wpbb_job_post_id is the post id for the added job
					 * @param (string) $field[ 'id' ] is the post meta key for this field
					 * @param (string) $wpbb_xml_params->$field[ 'bb_field' ] the value of the meta key
					 */
					do_action( 'wpbb_job_field_added', $wpbb_job_post_id, $field[ 'id' ], (string) $wpbb_xml_params->$field[ 'bb_field' ] );
					
				} // end if have field data sent to add
				
			} // end loop through all fields to add
			
			/**
			 * job has been added now
			 * @hook - wpbb_job_added
			 */
			do_action( 'wpbb_job_added', $wpbb_job_post_id );
			
			/**
			 * everything appears to have worked
			 * therefore lets output a success message
			 */
			echo apply_filters( 'wpbb_job_added_success_message', 'Success: This Job has been added and has a post ID of ' . $wpbb_job_post_id . '. The permalink to this job is: ' . get_permalink( $wpbb_job_post_id ) );
			
		} // end if have job fields
			
	/**
	 * wp_insert_post returned zero
	 * this means the post was not added
	 */
	} else {
	
		/* output a error to the indicate the problem */
		echo apply_filters( 'wpbb_job_added_failure_message', 'Error: There was an error, the job was not published.' );
		
	} // end job post added successfully

/**
 * we are not adding a job
 * are we deleting one then?
 */
} elseif( strtolower( wp_strip_all_tags( (string) $wpbb_xml_params->command ) ) == 'delete' ) {
	
	/**
	 * we are therefore deleting a job from the system
	 * we will used the job reference for the job to delete to first find
	 * the job in wordpress
	 */
	$job_post = wpbb_get_job_by_reference( (string) $wpbb_xml_params->job_reference );
	
	/* check we have a job with that reference to delete */
	if( $job_post != false ) {
		
		/* setup string of deleted posts */
		$wpbb_deleted_posts = '<p>Post or posts deleted. The following post or posts were deleted: ';
		
		/* delete the post */
		$deleted = wp_delete_post( $job_post );
		
		/* add to delete posts string */
		$wpbb_deleted_posts .= $job_post . ' | Job Reference: ' . $wpbb_xml_params->job_reference;
	
		/* output confirmation message */
		echo $wpbb_deleted_posts . '</p>';
		
	} // end if have job with this reference

/**
 * so we are not adding a job
 * we are not deleting a job
 * therefore the command sent must be wrong - must be one of add/delete
 */
} else {
	
	/* output an error message indicating the problem */
	wp_die( __( 'Error: Unknown <command>. Must be either add or delete.' ) );
	
} // end if adding or deleting a job

/* stop any further loading */
die();