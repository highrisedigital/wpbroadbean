<?php
/**
 * This file is loaded when the endpoint /wpbb/jobfeed is reached.
 * This file can be overriden by copying it to your active theme in the following location /wpbb/inbox.php
 * You can then make your changes and that version is used instead. These changes will persits even if the plugin is updated.
 *
 * @package WP_Broadbean
 */

/**
 * Get the contents of the feed provided by logicmelon
 * if you want to test the feed, by pulling an XML feed from a specific url
 * you can use the wpbb_xml_feed_url filter
 */
$xml_posted = file_get_contents( apply_filters( 'wpbb_xml_feed_url', 'php://input' ) );

// check the data sent has content.
if ( false === $xml_posted || '' === $xml_posted ) {

	/* echo an error */
	header( 'HTTP/1.1 206 Partial Content' );
	esc_html_e( 'Feed contains no valid XML data.', 'wpbroadbean' );
	die();

}

// turn the posted data into a php object.
$xml = simplexml_load_string( $xml_posted );

// get the username and password from the wplm settings.
$username = wpbb_get_feed_username();
$password = wpbb_get_feed_password();

// if the username and or password don't match.
if ( (string) $xml->username !== $username || (string) $xml->password !== $password ) {

	/* echo an error */
	header( 'HTTP/1.1 401 Unauthorized' );
	esc_html_e( 'Authorisation failed. Incorrect username or password.', 'wpbroadbean' );
	die();

}

/**
 * We are now authenticated and seeminly have valid XML data to work with
 * the next stage is to check what command we are to process.
 */

// get the sent command.
$command = wp_strip_all_tags( trim( $xml->command ) );

// get the sent job reference.
$job_reference = wp_strip_all_tags( trim( $xml->job_reference ) );

// get any job with the posted job reference.
$update_post_id = wpbb_get_job_by_reference( $job_reference );

// if we are adding a job - the add command was sent.
if ( 'add' === $command ) {

	// build an array of args for inserting our new job.
	$insert_job_args = array(
		'post_type'    => wpbb_job_post_type_name(),
		'post_title'   => wp_strip_all_tags( trim( $xml->job_title ) ),
		'post_content' => wp_kses_post( $xml->job_description ),
		'post_status'  => 'publish',
	);

	// check if there is a job that already exists with this reference.
	if ( ! empty( $update_post_id ) ) {

		// set the job post id for the job found for this reference.
		$insert_job_args['ID'] = absint( $update_post_id );

	}

	// set a default author ID to zero.
	$author = 0;

	// do we have a consultant email in the feed.
	if ( ! empty( $xml->consultant_email ) ) {

		// check if consultant email is a valid email.
		if ( is_email( $xml->consultant_email ) ) {

			// check for a WordPress user with this email address.
			$user = get_user_by( 'email', $xml->consultant_email );

			// if we have a user returned.
			if ( false !== $user ) {

				// set the author as the users user id.
				$author = $user->ID;

			}
		}
	}

	// add the author to the insert post args.
	$insert_job_args['post_author'] = absint( $author );

	/**
	 * Lets now insert the post for this job.
	 * Uses the standard wp_insert_post function.
	 * On success it will return the newly added job post id.
	 */
	$job_id = wp_insert_post(
		apply_filters(
			'wpbb_insert_job_post_args',
			$insert_job_args,
			$xml
		)
	);

	// lets check whether the job was created successfully.
	if ( 0 === $job_id ) {

		/* echo an error */
		header( 'HTTP/1.1 406 Not Acceptable' );
		esc_html_e( 'All looked good, but for some reason the job was not added.', 'wpbroadbean' );
		die();

	}

	// update the post slug to include the post id - makes sure all jobs have a unique url.
	wp_update_post(
		apply_filters(
			'wpbb_update_job_post_args',
			array(
				'ID' => $job_id,
				'post_name' => sanitize_title_with_dashes( trim( $xml->job_title ) ) . '-' . $job_id,
			)
		)
	);

	/**
	 * Fire an action that runs directly after a successful job posting.
	 */
	do_action( 'wpbb_inbox_after_job_inserted', $job_id, $xml );

	/**
	 * Lets start by adding this jobs meta data.
	 * First lets get all the fields for the jobs.
	 */
	$job_fields = wpbb_get_job_fields();

	// if we have job fields.
	if ( ! empty( $job_fields ) ) {

		// loop through each field to add as post meta.
		foreach ( $job_fields as $job_field ) {

			// if the xml_field value is empty or not present skip to the next.
			if ( empty( $xml->{ $job_field['xml_field'] } ) || '' === $xml->{ $job_field['xml_field'] } ) {
				continue;
			}

			// make sure the value is sent as a string.
			$field_value = (string) $xml->{ $job_field['xml_field'] };

			// add the sent data as post meta for this job post.
			$job_field_added = update_post_meta( $job_id, $job_field['id'], $field_value );

			// if this job field was added successfully.
			if ( false !== $job_field_added ) {

				/**
				 * Fires after the job field was added.
				 *
				 * @param int    $job_id     the id of the newly created job
				 * @param string $meta_key   this is the meta key of the created field
				 * @param string $meta_value this is the sent meta value in the xml
				 * @param object $xml        The XML sent across as an object.
				 */
				do_action( 'wpbb_inbox_job_field_added', $job_id, $job_field['id'], $field_value, $xml );

			}
		} // End foreach().
	}

	/**
	 * Lets prepare the taxonomy terms for adding
	 */
	$taxonomies = wpbb_get_registered_taxonomies();

	// if we have registered taxonomies.
	if ( ! empty( $taxonomies ) ) {

		/* set up holding array */
		$job_tax_terms = array();

		/**
		 * Loop through each of the taxonomies we have preparing it for adding to the post
		 */
		foreach ( $taxonomies as $taxonomy ) {

			// if no tax terms sent - continue.
			if ( '' === $xml->{ $taxonomy['xml_field'] } ) {
				continue;
			}

			// add the prepared terms to our terms array.
			$job_tax_terms[ $taxonomy['xml_field'] ] = wpbb_prepare_terms( $xml->{ $taxonomy['xml_field'] }, $taxonomy );

			// lets check we have some prepared terms to process.
			if ( ! empty( $job_tax_terms ) ) {

				// if the sent xml field is present but has no valid - continue to the next.
				if ( '' === $xml->{ $taxonomy['xml_field'] } ) {
					continue;
				}

				// set the terms against this job post.
				$term_added = wp_set_post_terms(
					$job_id,
					$job_tax_terms[ $taxonomy['xml_field'] ],
					$taxonomy['taxonomy_name']
				);

				// if the terms were added.
				if ( false !== $term_added && ! is_wp_error( $term_added ) ) {

					/**
					 * Fires after the term has been added.
					 *
					 * @param (int)    $job_id is the post id for the added job
					 * @param (string) $wpbb_term term to be added
					 * @param (string) $taxonomy taxonomy of the term
					 */
					do_action( 'wpbb_inbox_job_term_added', $job_id, $job_tax_terms[ $taxonomy['xml_field'] ], $taxonomy['taxonomy_name'], $xml );

				}
			} // End if().
		} // End foreach().
	} // End if().

	/**
	 * Fires after a job has been successfully added and after taxonomy terms and
	 * meta data are added.
	 *
	 * @param int    $job_id is the post id for the added job.
	 * @param object $xml    sent XML object.
	 */
	do_action( 'wpbb_inbox_job_added', $job_id, $xml );

	/**
	 * If would appear the job is added, so tell the user.
	 */
	/* echo success message */
	header( 'HTTP/1.1 200 OK' );

	// set some echo text.
	$text = sprintf(
		// translators: 1: job post id.
		__( 'Success! Job added/updated with a post ID of %1s. You can view the job here: %2s', 'wpbroadbean' ),
		$job_id,
		get_permalink( $job_id )
	);

	echo esc_html( $text );
	die();

} elseif ( 'delete' === $command ) {

	// if no job exists with this reference.
	if ( empty( $update_post_id ) ) {

		// echo an error.
		header( 'HTTP/1.1 406 Not Acceptable' );
		esc_html_e( 'Sorry, a job with that job reference could not be found.', 'wpbroadbean' );
		die();

	}

	// delete the post found.
	$deleted = wp_delete_post( $update_post_id, true );

	/* if delete failed */
	if ( false === $deleted ) {

		/* echo an error */
		header( 'HTTP/1.1 406 Not Acceptable' );
		esc_html_e( 'Error: job was not deleted. Adding to trash failed.', 'wpbroadbean' );
		die();

	} else { /* job deleted */

		/**
		 * Fires after a job has been deleted.
		 *
		 * @param int    $update_post_id The ID of the job removed.
		 * @param object $xml            The XML sent.
		 */
		do_action( 'wpbb_inbox_job_deleted', $update_post_id, $xml );

		// echo success message.
		header( 'HTTP/1.1 200 OK' );

		// set some echo text.
		$text = sprintf(
			// translators: 1: job post id.
			__( 'The job (ID: %s) was successfully trashed.', 'hd-job-integrator' ),
			$deleted->ID
		);

		// output the text.
		echo esc_html( $text );
		die();

	}
} else {

	// echo an error as the command is not recognised.
	header( 'HTTP/1.1 405 Method not allowed' );
	esc_html_e( 'Invalid command sent.', 'wpbroadbean' );
	die();

} // End if().
