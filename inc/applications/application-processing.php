<?php
/**
 * Handles processing of the application form.
 *
 * @package WP_Broadbean
 */

/**
 * Process the application form.
 */
function wpbb_process_application_form() {

	// store message on success/failure in this array.
	global $wpbb_messages, $post;
	$wpbb_messages = array();

	// check our nonce verifies.
	if ( ! isset( $_POST['wpbb_application_nonce_field'] ) || ! wp_verify_nonce( $_POST['wpbb_application_nonce_field'], 'wpbb_application_nonce_action' ) ) {

		// add an error message.
		$wpbb_messages['nonce_error'] = array(
			'type'    => 'error',
			'message' => __( 'Error: Nonce problem.', 'wpbroadbean' ),
		);

		// go no further as application not added.
		return;

	}

	// if the form has been posted.
	if ( ! isset( $_POST['wpbb_application_data'] ) ) {
		return;
	}

	// store our posted data array.
	$application_data = $_POST['wpbb_application_data'];

	// sanitize the job post id for which this application was made against.
	$job_post_id = absint( $post->ID );

	// create a title for the application - based on the current timestamp.
	$application_title = time();

	// insert the new application post for this entry - temporarily of course!.
	$application_post_id = wp_insert_post(
		apply_filters(
			'wpbb_insert_application_post_args',
			array(
				'post_type'   => 'wpbb_application',
				'post_title'  => sanitize_text_field( $application_title ),
				'post_status' => 'draft',
			),
			$application_data
		)
	);

	// check the application post was created.
	if ( 0 === $application_post_id ) {

		// add an error message.
		$wpbb_messages['application_not_saved'] = array(
			'type'    => 'error',
			'message' => __( 'Error: Application could not be saved.', 'wpbroadbean' ),
		);

		// go no further as application not added.
		return;

	} else {

		// add an success message.
		$wpbb_messages['application_saved'] = array(
			'type'    => 'success',
			'message' => __( 'Success: Application received.', 'wpbroadbean' ),
		);

	}

	// get all the registered application fields.
	$fields = wpbb_get_application_fields();

	// if we have application fields, there must be fields in the form to process.
	if ( ! empty( $fields ) ) {

		// create an array of attachment ids.
		$attachment_ids = array();

		// loop through each field.
		foreach ( $fields as $field ) {

			// if this is a file upload field.
			if ( 'file' === $field['input_type'] ) {

				// action uploading the file and attaching to the application post.
				$attachment_ids[] = wpbb_action_file_upload_field( $application_post_id, $field );

			} else {

				// find this field in our posted data.
				$field_value = $application_data[ $field['id'] ];

				// check if we have a sanitization callback.
				if ( ! isset( $field['sanitization_cb'] ) ) {

					// set a default sanitization callback function.
					$field['sanitization_cb'] = 'sanitize_text_field';

				}

				// sanitize the posted field value.
				$field_value = call_user_func( $field['sanitization_cb'], $field_value );

				// update the value as post meta to the created application post type.
				$field_added = update_post_meta( $application_post_id, $field['id'], $field_value );

				// if the field failed to add.
				if ( false === $field_added ) {

					// add an error message.
					$wpbb_messages['application_field_not_saved'] = array(
						'type'    => 'error',
						'message' => sprintf(
							/* Translators: 1: Field name or label */
							__( 'Error: %s could not be added to the application.', 'wpbroadbean' ),
							$field['name']
						),
					);

				} // End if().
			} // End if().
		} // End foreach().
	} // End if().

	// save the job id of the job being applied for with the application.
	update_post_meta( $application_post_id, 'job_post_id', absint( $post->ID ) );

	/**
	 * Fires after the application processing is completed.
	 *
	 * @param integer $application_post_id The newly created application post id.
	 * @param array   $attachment_ids      An array of the application attachment ids - uploaded files.
	 *
	 * @hooked wpbb_send_application_email_notification - 10.
	 * @hooked wpbb_remove_application - 20.
	 */
	do_action( 'wpbb_application_processing_complete', $application_post_id, $attachment_ids );

}

add_action( 'wp', 'wpbb_process_application_form' );
