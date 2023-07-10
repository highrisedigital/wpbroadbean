<?php
/**
 * Functions hooked into the applications parts of the plugin.
 *
 * @package WP_Broadbean
 */

/**
 * Outputs a title on the application form.
 */
function wpbb_application_form_title( $data ) {

	?>
	<h3 class="wpbb-application-form__title" id="wpbb-application-form__title"><?php esc_html_e( 'Apply for this Job', 'wpbroadbean' ); ?></h3>
	<?php

}

add_action( 'wpbb_before_application_form', 'wpbb_application_form_title', 20 );

/**
 * Output instructional text about required fields.
 */
function wpbb_required_fields_instruction_text() {

	// output our instructional message.
	?>
	<p class="wpbb-required-message"><?php esc_html_e( 'Fields marked with * are required.', 'wpbroadbean' ); ?></p>
	<?php

}

add_action( 'wpbb_after_application_form_fields', 'wpbb_required_fields_instruction_text', 10 );

/**
 * Outputs the submit application form field.
 *
 * @param  array $data an array of data passed about the current job applying for.
 */
function wpbb_application_form_submit( $data ) {

	?>
	<input type="submit" name="wpbb_application_data[submit]" value="<?php esc_html_e( 'Apply now', 'wpbroadbean' ); ?>" class="wpbb-input-submit" id="wpbb-input-submit" />
	<?php

}

add_action( 'wpbb_after_application_form_fields', 'wpbb_application_form_submit', 20, 1 );

/**
 * Output the application field descriptions unless it is a checkbox field.
 *
 * @param  array $field The current application field array.
 */
function wpbb_output_application_field_description( $field ) {

	// if the field is not a checkbox.
	if ( 'checkbox' !== $field['input_type'] ) {

		// if we have a desc.
		if ( ! empty( $field['desc'] ) ) {

			// output the description of this field.
			echo wp_kses_post( '<p class="wpbb-application-field__description">' . $field['desc'] . '</p>' );

		}
	}

}

add_action( 'wpbb_after_application_field_output', 'wpbb_output_application_field_description', 10, 1 );

/**
 * Sends the email notifications when applications are submitted. Sends tracking back to Broadbean.
 *
 * @param  integer $application_id The post ID of the newly created application.
 * @param  array $attachment_ids An array of any attachment IDs added to the application.
 */
function wpbb_send_application_email_notification( $application_id, $attachment_ids ) {

	// get the job id for the job this application applied for.
	$job_id = get_post_meta( $application_id, 'job_post_id', true );

	// get the applicant tracking email for the job this application was against.
	$tracking_email = wpbb_get_job_applicant_tracking_email( $job_id );

	// create an array of the information to include in this notification, about the application.
	$notification_data = apply_filters(
		'wpbb_application_email_data',
		array(),
		$application_id,
		$attachment_ids,
		$job_id
	);

	// setup array for fill with data for the email.
	$recipients = array();
	$headers = array();
	$attachments = array();

	// build the email content using the view template.
	$notification_content = wpbb_load_view( 'application-email', $notification_data );

	// if we have a tracking email - add it to the recipients array.
	$recipients['applicant_tracking_email'] = $tracking_email;

	// get the applicants name and email address.
	$name = wpbb_get_application_applicant_name( $application_id );
	$email = wpbb_get_application_applicant_email( $application_id );

	// add the from email header.
	$headers['from'] = 'From: ' . esc_html( $name ) . ' <' . $email . '>';

	// get an array of all the attachments for this application.
	$application_attachments = get_attached_media(
		apply_filters( 'wpbb_application_attachment_mime_type', 'application' ),
		$application_id
	);

	// if we have attachments.
	if ( ! empty( $application_attachments ) ) {

		// loop through each media item.
		foreach ( $application_attachments as $attachment ) {

			// add the attachment file path to the email attachments array.
			$attachments[] = get_attached_file( $attachment->ID );

		}
	}

	// set the notification subject.
	$subject = __( 'New Job Application Submitted', 'wpbroadbean' );

	// allow all the notification parts to be filtered.
	$recipients = apply_filters( 'wpbb_default_notification_recipients', $recipients, $application_id, $attachment_ids, $job_id );
	$headers = apply_filters( 'wpbb_default_notification_headers', $headers, $application_id, $attachment_ids, $job_id );
	$attachments = apply_filters( 'wpbb_default_notification_attachments', $attachments, $application_id, $attachment_ids, $job_id );
	$notification_content = apply_filters( 'wpbb_default_notification_content', $notification_content, $application_id, $attachment_ids, $job_id );

	// set the email content type to html.
	add_filter( 'wp_mail_content_type', 'wpbb_text_html_email_type' );

	// finally - send the notification email.
	$send_notification = wp_mail(
		$recipients,
		$subject,
		$notification_content,
		$headers,
		$attachments
	);

	// reset the html email content type - clean up after ourselves!
	remove_filter( 'wp_mail_content_type', 'wpbb_text_html_email_type' );

	// if the email sent successfully.
	if ( false !== $send_notification ) {

		/**
		 * Fire an action which runs once the notification is sent.
		 */
		do_action( 'wpbb_default_notification_sent', $application_id, $job_id );

	}

}

add_action( 'wpbb_application_processing_complete', 'wpbb_send_application_email_notification', 10, 2 );

/**
 * Sets the array of data to be included in the application email.
 *
 * @param  array   $data           The array of data to include.
 * @param  integer $application_id The post ID of the application.
 * @param  array   $attachment_ids An array of attachment IDs.
 * @return array                   The modifed array of data.
 */
function wpbb_set_application_email_data( $data, $application_id, $attachment_ids, $job_id ) {

	// get the application fields.
	$application_fields = wpbb_get_application_fields();

	// if we have no fields.
	if ( empty( $application_fields ) ) {
		return $data;
	}

	// if the application fields array includes a CV upload field.
	if ( isset( $application_fields['cv'] ) ) {

		// remove it from the array.
		unset( $application_fields['cv'] );

	}

	// loop through each field.
	foreach ( $application_fields as $application_field ) {

		// add this field to the data array.
		$data[ $application_field['id'] ] = array(
			'label' => $application_field['name'],
			'value' => get_post_meta( $application_id, $application_field['id'], true ),
		);

	}

	// add the job title applied for.
	$data['job_post_title'] = array(
		'label' => __( 'Job Applied for Title', 'wpbroadbean' ),
		'value' => get_the_title( $job_id ),
	);

	// add the job post permalink for the job applied for.
	$data['job_post_url'] = array(
		'label' => __( 'Job Applied for URL', 'wpbroadbean' ),
		'value' => get_permalink( $job_id ),
	);

	return $data;

}

add_filter( 'wpbb_application_email_data', 'wpbb_set_application_email_data', 10, 4 );

/**
 * Removes the application created and the attachments.
 *
 * @param  integer $application_id The post ID of the application post.
 * @param  array   $attachment_ids An array of attachment IDs for attachments addded to the application.
 */
function wpbb_remove_application( $application_id, $attachment_ids ) {

	// if we have attachments to remove from this application.
	if ( ! empty( $attachment_ids ) ) {

		// loop through each attachment.
		foreach ( $attachment_ids as $attachment_id ) {

			// delete the attachment.
			wp_delete_attachment( $attachment_id, true );

		}
	}

	// delete the application post.
	wp_delete_post( $application_id, true );

}

add_action( 'wpbb_application_processing_complete', 'wpbb_remove_application', 20, 2 );
