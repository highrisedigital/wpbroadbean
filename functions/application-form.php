<?php
/**
 * function wpbb_application_form
 * renders the application form on the page selected for apply in settings
 * @param (string) $content is the current post content
 * @return (string) $content is the new content with the form added after
 */
function wpbb_application_form( $content ) {
	
	/* get the apply page from the settings */
	$apply_pageid = get_option( 'wpbb_apply_page_id' );

	/* check a page id is returned */
	if( $apply_pageid == false && $apply_pageid == 'zero' ) {
		return $content;
	}
	
	/* check this is the apply page */
	if( ! is_page( $apply_pageid ) )
		return $content;
	
	/* get the job id */
	if( isset( $_GET[ 'job_id' ] ) ) {
		$job_id = sanitize_text_field( $_GET[ 'job_id' ] );
	} else {
		$job_id = '';
	}
	
	/* check we have a job id passed */
	if( $job_id != '' ) {
		
		/* get the post for this job reference */
		$job_post = wpbb_get_job_by_reference( $job_id );

		/* check if we have a job post for this reference */
		if( $job_post == false ) {
			
			/* create an error message as no job has this reference */
			return '<p class="message error">Error: No job exists with this job reference.</p>' . $content;
			
		}
		
		/* get the contact email and broadbean tracking email for this job */
		$contact_email = get_post_meta( $job_post, '_wpbb_job_contact_email', true );
		$bb_email = get_post_meta( $job_post, '_wpbb_job_broadbean_application_email', true );
		
		/* check whether the form has already been posted */
		if( ! isset( $_POST[ 'wpbb_submit' ] ) ) {
			
			/* start the form */
			$form = '<div class="wpbb-form-wrapper"><p class="applying-for">' . __( 'You are applying for: ', 'wpbroadbean' ) . '<a href="' . esc_url( get_permalink( $job_post ) ) . '">' . get_the_title( $job_post ) . '</a></p><form enctype="multipart/form-data" id="wpbb-application-form" method="post" action="">';
			
			/* add a hidden input field for the job ref, contact email and broadbean email */
			$form .= '<input class="wpbb-input" type="hidden" name="wpbb_job_reference" id="wpbb-job-reference" value="' . esc_attr( $job_id ) . '" />';
			
			$form .= '<input class="wpbb-input" type="hidden" name="wpbb_contact_email" id="wpbb-contact-email" value="' . esc_attr( $contact_email ) . '" /><input class="wpbb-input" type="hidden" name="wpbb_broadbean_application_email" id="wpbb-broadbean-application-email" value="' . esc_attr( $bb_email ) . '" />';
			
			/* add inputs for name and email address */
			$form .= '<div class="wpbb-input"><label for="wpbb_name" class="require">' . __( 'Name', 'wpbroadbean' ) . '</label><input class="wpbb-input" type="text" name="wpbb_name" id="wpbb-name" value="" tabindex="3" required><label class="error" for="wpbb_name">Please enter your name.</label></div>';
			
			$form .= '<div class="wpbb-input"><label for="wpbb_email" class="require">' . __( 'Email', 'wpbroadbean' ) . '</label><input class="wpbb-input" type="email" name="wpbb_email" id="wpbb-email" value="" tabindex="4" required><p class="wpbb_description">' . __( 'Please enter a valid email address as this will be used to contact you.', 'wpbroadbean' ) . '</p></div>';
			
			$form .= '<div class="wpbb-input"><label for="wpbb_message" class="require">' . __( 'Message', 'wpbroadbean' ) . '</label><textarea class="wpbb-input wpbb-input-textarea" name="wpbb_message" id="wpbb-message" value="" tabindex="5"></textarea><p class="wpbb_description">Add an optional message.</p></div>';
			
			/* add the upload input field for the cv */
			$form .= '<div class="wpbb-input"><label for="wpbb_file">' . __( 'Attach a CV', 'wpbroadbean' ) . '</label><input type="file" name="wpbb_file" /><p class="wpbb_description">' . __( 'Please attach your CV.', 'wpbroadbean' ) . '</p></div>';
			
			/* add the submit button */
			$form .= '<div class="wpbb_submit"><input type="submit" value="' . __( 'Submit', 'wpbroadbean' ) . '" name="wpbb_submit"></div>';
			
			/* end the form */
			$form .= '</form></div>';
		
		/* form has been posted */	
		} else {
			
			/* set a string to store all messages in */
			$wpbb_message_output = array();
			
			/* get any messages */
			global $wpbb_messages;

			/* run messages through a filter so devs can alter them */
			$wpbb_messages = apply_filters(
				'wpbb_application_form_messages',
				$wpbb_messages
			);

			/* if we have some messages to loop through */
			if( ! empty( $wpbb_messages ) ) {

				/* loop through each message adding to string */
				foreach( $wpbb_messages as $key => $message ) {

					$wpbb_message_output[] = '<p class="message" id="' . esc_attr( $message[ 'type' ] ) . '">' . esc_html( $message[ 'message' ] ) . '</p>';
				}

			}

			/* prevent undefined variable */
			$form = '';
			
		}
	
	/* no job ref was passed in the query string */	
	} else {
		
		/* set an output message rather than the form */
		$form = '<p class="message error">Error: No job reference detected!</p>';
		
	}
	
	/* have we any message */
	if( ! empty( $wpbb_message_output ) ) {

		$wpbb_message_string = '<div class="wpbb-messages">';

		/* loop through each message */
		foreach( $wpbb_message_output as $message_output ) {

			/* add message to our string */
			$wpbb_message_string .= $message_output;

		}

		$wpbb_message_string .= '</div>';

	/* no messages */
	} else {

		/* set an empty string for messages */
		$wpbb_message_string = '';

	}
	
	/* make the form markup filterable */
	$form = apply_filters(
		'wpbb_application_form_html',
		$form,
		$job_id
	);
	
	/* add message to the form content */
	$form = $form . $wpbb_message_string;
	
	/* check we have something in our form variable */
	if( empty( $form ) )
		return $content;
	
	/* return the form with content */
	return $content . $form;
	
}

add_filter( 'the_content', 'wpbb_application_form' );

/**
 * function wpbb_application_processing()
 * process the application form submitted creating an application post
 * @param (int) $job_ref is the job reference for the job being applied for
 */
function wpbb_application_processing() {
	
	/* if this is the admin then bail early */
	if( is_admin() ) {
		return;
	}
	
	/* check whether the form has already been posted */
	if( isset( $_POST[ 'wpbb_submit' ] ) ) {

		/* lets sanitize the posted data */
		$applicant_name = sanitize_text_field( $_POST[ 'wpbb_name' ] );
		$applicant_message = wp_kses_post( $_POST[ 'wpbb_message' ] );
		$application_job_ref = sanitize_text_field( $_POST[ 'wpbb_job_reference' ] );
		$applicant_email = sanitize_email( $_POST[ 'wpbb_email' ] );
		
		/* get the post for this job reference */
		$job_post = wpbb_get_job_by_reference( sanitize_text_field( $_GET[ 'job_id' ] ) );
	
		/* store message on success/failure in this array */
		global $wpbb_messages;
		$wpbb_messages = array();
		
		/* check that the wp_handle_upload function is loaded */		
		if ( ! function_exists( 'wp_handle_upload' ) )
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
		
		/* get the uploaded file information */
		$wpbb_uploaded_file = $_FILES[ 'wpbb_file' ];

		/* check that the $_FILES var is an array */
		if( ! is_array( $wpbb_uploaded_file ) ) {

			/* add an error message */
			$wpbb_messages[ 'attachment_failed' ] = array(
				'type'		=> 'error',
				'message'	=> 'Error: File attachment failed.'
			);

			/* go no further as file type not allowed */
			return;

		}

		/* sanitize the uploaded file name */
		$wpbb_uploaded_file_name = sanitize_text_field( $wpbb_uploaded_file[ 'name' ] );

		/* check we have a file to upload */
		if( $wpbb_uploaded_file_name != '' ) {
			
			/* set overides to make it work */
			$wpbb_upload_overrides = array( 'test_form' => false );
			
			/* upload the file to wp uploads dir */
			$wpbb_moved_file = wp_handle_upload( $wpbb_uploaded_file, $wpbb_upload_overrides );
			
			/* get file type of the uploaded file */
			$wpbb_filetype = wp_check_filetype( $wpbb_moved_file[ 'url' ], null );
			
			/* generate array of allowed mime types */
			$wpbb_allowed_mime_types = apply_filters(
				'wpbb_application_allowed_file_types',
				array(
					'pdf'		=> 'application/pdf',
					'word'		=> 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
					'word_old'	=> 'application/msword',
					'pages'		=> 'application/vnd.apple.pages',
					'open_text'	=> 'application/vnd.oasis.opendocument.text',
					'rich_text'	=> 'application/rtf',
					'text'		=> 'text/plain'
				)
			);
			
			/* check uploaded file is in allowed mime types array */
			if( ! in_array( $wpbb_filetype[ 'type' ], $wpbb_allowed_mime_types) ) {
				
				/* add an error message */
				$wpbb_messages[ 'cv_type_failed' ] = array(
					'type'		=> 'error',
					'message'	=> 'Error: CV is not an allowed file type.'
				);

				/* go no further as file type not allowed */
				return;

			}
		
		}		
			
		/* get the wp upload directory */
		$wpbb_wp_upload_dir = wp_upload_dir();
		
		/* setup the attachment data */
		$wpbb_attachment = array(
		     'post_mime_type' => $wpbb_filetype[ 'type' ],
		     'post_title' => preg_replace('/\.[^.]+$/', '', $wpbb_uploaded_file_name ),
		     'post_content' => '',
		     'guid' => $wpbb_wp_upload_dir[ 'url' ] . '/' . basename( $wpbb_moved_file[ 'file' ] ),
		     'post_status' => 'inherit'
		);
		
		/* insert the application post */
		$wpbb_application_id = wp_insert_post(
			array(
				'post_type'		=> 'wpbb_application',
				'post_title'	=> esc_html( $applicant_name ),
				'post_status'	=> 'publish',
				'post_content'	=> $applicant_message
			)
		);
		
		/* check the application post has been added */
		if( $wpbb_application_id != 0 ) {
			
			/* set the post meta data (custom fields) */
			add_post_meta( $wpbb_application_id, '_wpbb_job_reference', $application_job_ref, true );
			add_post_meta( $wpbb_application_id, '_wpbb_applicant_email', $applicant_email, true );
			
			/* check we have a file to attach */
			if( $wpbb_uploaded_file_name != '' ) {
			
				/* add the attachment from the uploaded file */
				$wpbb_attach_id = wp_insert_attachment( $wpbb_attachment, $wpbb_moved_file[ 'file' ], $wpbb_application_id );
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
				$wpbb_attach_data = wp_generate_attachment_metadata( $wpbb_attach_id, $wpbb_moved_file[ 'file' ] );
				wp_update_attachment_metadata( $wpbb_attach_id, $wpbb_attach_data );
			
			}

			/* add an error message */
			$wpbb_messages[ 'application_success' ] = array(
				'type'		=> 'success',
				'message'	=> 'Thank you. Your application has been received.'
			);
			
		} // end check application post added
				
		/* add filter below to allow / force mail to send as html */
		add_filter( 'wp_mail_content_type', 'wpbb_text_html_email_type' );
		
		/* get the post object for the job being applied for */
		$job_post = get_post( $job_post );
		
		/* build the content of the email */
		$wpbb_email_content = '
		
			<p>' . $applicant_name . ' has completed an application for ' . $job_post->the_title . ' which has the job reference of ' . $application_job_ref . '. The applicants email address is ' . $applicant_email. '. Below is a summary of their responses:</p>
			
			<ul>
				<li>Applicant Name: ' . esc_html( get_the_title( $wpbb_application_id ) ) . '</li>
				<li>Applicant Email Address: ' . esc_html( get_post_meta( $wpbb_application_id, '_wpbb_applicant_email', true ) ) . '</li>
				<li>Job Title: ' . esc_html( get_the_title( $job_post->ID ) ) . '</li>
				<li>Job Reference: ' . esc_html( get_post_meta( $job_post->ID, '_wpbb_job_reference', true ) ) . '</li>
				<li>Job Permalink: <a href="' . esc_url( get_permalink( $job_post->ID ) ) . '">' . esc_url( get_permalink( $job_post->ID ) ) . '</a></li>
				<li><a href="' . get_edit_post_link( $wpbb_application_id ) . '">Application Edit Link</a></li>
			</ul>
			<br />' . wpautop( $applicant_message ) . '<br />
			
			<p>Email sent by <a href="http://wpbroadbean.com">WP Broadbean WordPress plugin</a>.</p>
			
		';
		
		/* set up the mail variables */
		$wpbb_mail_subject = 'New Job Application Submitted - ' . esc_html( get_the_title( $wpbb_application_id ) );
		$wpbb_email_headers = array();
		$wpbb_email_headers[] = 'From: ' . esc_html( $applicant_name ) . ' <' . $applicant_email . '>';
		
		/**
		 * set the content of the email as a variable
		 * this is made filterable and is passed the job post object being applied for
		 * along with the application post id
		 * devs can use this filter to change the contents of the email sent
		 */
		$wpbb_mail_content = wpbb_generate_email_content( apply_filters( 'wpbb_application_email_content', $wpbb_email_content, $job_post, $wpbb_application_id ) );
		
		/* setup an array for recipients */
		$wpbb_mail_recipients = array();
		
		/* get the contact email */
		$wpbb_contact_email = get_post_meta( $job_post->ID, '_wpbb_job_contact_email', true );
		
		/* if we have a contact email add it to the recipients array */
		if( $wpbb_contact_email != '' ) {
			$wpbb_mail_recipients[] = sanitize_email( $wpbb_contact_email );
		}
		
		/* get the tracking email */
		$wpbb_tracking_email = get_post_meta( $job_post->ID, '_wpbb_job_broadbean_application_email', true );
		
		/* if we have a tracking email add it to the recipients array */
		if( $wpbb_tracking_email != '' ) {
			$wpbb_mail_recipients[] = sanitize_email( $wpbb_tracking_email );
		}
		
		/* set attachments - the cv */
		$wpbb_attachments = array( $wpbb_wp_upload_dir[ 'path' ] . '/' . basename( $wpbb_moved_file[ 'file' ] ) );
		
		/* send the mail */
		$wpbb_send_email = wp_mail(
			apply_filters( 'wpbb_application_email_recipients', $wpbb_mail_recipients, $wpbb_application_id, $job_post ),
			apply_filters( 'wpbb_application_email_subject', $wpbb_mail_subject, $wpbb_application_id, $job_post ),
			apply_filters( 'wpbb_application_email_content', $wpbb_mail_content, $wpbb_application_id, $job_post ),
			apply_filters( 'wpbb_application_email_headers', $wpbb_email_headers, $wpbb_application_id, $job_post ),
			apply_filters( 'wpbb_application_email_attachments', $wpbb_attachments, $wpbb_application_id, $job_post )
		);
		
		/* remove filter below to allow / force mail to send as html */
		remove_filter( 'wp_mail_content_type', 'wpbb_text_html_email_type' );

		/**
		 * @hook wpbb_after_application_form_processing
		 * @param int $wpbb_application_id 	the application post id of the application submitted
		 * @param obj $job_post the post object for the job being applied for
		 */
		do_action( 'wpbb_after_application_form_processing', $wpbb_application_id, $job_post );

		// should we remove the application cv just uploaded
		if( true === wpbb_maybe_remove_application_attachments() ) {
			
			// lets remove the file that was just uploaded
			wp_delete_attachment( $wpbb_attach_id, true );
			
		}
	
	} // end if form posted
	
}

add_action( 'wp', 'wpbb_application_processing', 10 );