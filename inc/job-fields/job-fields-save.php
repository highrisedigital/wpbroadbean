<?php
/**
 * These functions are associated with saving a job.
 *
 * @package WP_Broadbean
 */

/**
 * Checks and measures for firing a hook when an job post is saved.
 *
 * @param  int $post_id the post of the current job being saved.
 * @param  obj $post    the post object for the current job being saved.
 */
function wpbb_save_job( $post_id, $post ) {

	// if the post id is empty, the post object is empty or there is no poted data.
	if ( empty( $post_id ) || empty( $post ) || empty( $_POST ) ) {
		return; // do nothing.
	}

	// if an autosave is in process.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return; // do nothing.
	}

	// if this is a revision or auto save post.
	if ( is_int( wp_is_post_revision( $post ) ) || is_int( wp_is_post_autosave( $post ) ) ) {
		return; // do nothing.
	}

	// if our nonce field fails to validate.
	if ( empty( $_POST['wpbb_job_fields_nonce'] ) || ! wp_verify_nonce( $_POST['wpbb_job_fields_nonce'], 'wpbb_save_job_fields' ) ) {
		return; // do nothing.
	}

	// if the current user cannot edit the post.
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return; // do nothing.
	}

	// all is good - run an action to attach our post saving functions to.
	do_action( 'wpbb_save_job', $post_id, $post );

}

add_action( 'save_post_wpbb_job', 'wpbb_save_job', 1, 2 );

/**
 * Saves the job fields on the job post edit screen.
 *
 * @param  int $post_id the post of the current job being saved.
 * @param  obj $post    the post object for the current job being saved.
 */
function wpbb_save_job_fields( $post_id, $post ) {

	// lets get all the registered fields.
	$fields = wpbb_get_job_fields();

	// loop through each of the fields.
	foreach ( $fields as $field ) {

		// handle any fields were a type is not specified.
		$type = ! empty( $field['type'] ) ? $field['type'] : '';

		// switch depending on the field type.
		switch ( $type ) {

			// if this field is of type textarea.
			case 'textarea' :

				// update the post meta santiziing through wp_kses_post.
				update_post_meta( $post_id, $field['id'], wp_kses_post( stripslashes( $_POST[ $field['id'] ] ) ) );

				// break of the switch statement.
				break;

			// if the field type is a number.
			case 'number' :

				// update the post meta santiziing through wp_kses_post.
				update_post_meta( $post_id, $field['id'], intval( $_POST[ $field['id'] ] ) );

				// break of the switch statement.
				break;

			// if the field type is a email.
			case 'email' :

				// update the post meta santiziing through wp_kses_post.
				update_post_meta( $post_id, $field['id'], is_email( $_POST[ $field['id'] ] ) );

				// break of the switch statement.
				break;

			// if the field type is a text.
			case 'text' :

				// update the post meta santiziing through wp_kses_post.
				update_post_meta( $post_id, $field['id'], sanitize_text_field( $_POST[ $field['id'] ] ) );

				// break of the switch statement.
				break;

			// if the field type is a select.
			case 'select' :

				// update the post meta santiziing through wp_kses_post.
				update_post_meta( $post_id, $field['id'], sanitize_text_field( $_POST[ $field['id'] ] ) );

				// break of the switch statement.
				break;

				// if the field type is a checkbox.
			case 'checkbox' :

				// if the field has been posted.
				if ( isset( $_POST[ $field['id'] ] ) ) {

					// save checked.
					update_post_meta( $post_id, $field['id'], 1 );

				} else { // nothing posted - must be unchecked.

					// save unchecked.
					update_post_meta( $post_id, $field['id'], 0 );

				}

				// break of the switch statement.
				break;

			// if the type didn't match statements above.
			default :

				// if the type is empty.
				if ( '' === $type ) {

					// run an action for unknown types.
					do_action( 'wpbb_save_field_type', $field, $post_id, $post );

				} else { // we have a type.

					// run a dynamic action based on the type.
					do_action( 'wpbb_save_field_type_' . $type, $field, $post_id, $post );

				} // End if().

				// break of the switch statement.
				break;

		} // End switch().
	} // End foreach().

}

add_action( 'wpbb_save_job', 'wpbb_save_job_fields', 20, 2 );
