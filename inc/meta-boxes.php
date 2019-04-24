<?php
/**
 * Registers plugin meta box and functions for meta box output.
 *
 * @package WP_Broadbean
 */

/**
 * Registers the job information meta box to appear on the job post edit screens.
 */
function wpbb_register_job_information_meta_box() {

	// add the job information meta box.
	add_meta_box(
		'wpbb-job-info',
		__( 'Job Information', 'wpbroadbean' ),
		'wpbb_job_meta_box_output',
		wpbb_job_post_type_name(),
		'normal',
		'default'
	);

}

add_action( 'add_meta_boxes', 'wpbb_register_job_information_meta_box', 10, 2 );

/**
 * Outputs the contents of the job information meta box.
 *
 * @param  obj   $post the post object for the current job being edited.
 * @param  array $args an array of meta box args.
 */
function wpbb_job_meta_box_output( $post, $args ) {

	// get the job fields registered.
	$fields = wpbb_get_job_fields();

	// if we have job fields.
	if ( ! empty( $fields ) ) {

		?>
		<div class="wpbb-fields">
		<?php

		// output a nonce field.
		wp_nonce_field( 'wpbb_save_job_fields', 'wpbb_job_fields_nonce' );

		// run an action before the fields are outputted.
		do_action( 'wpbb_fields_before_admin_output', $post );

		// loop through each field.
		foreach ( $fields as $field ) {

			// handle a type not defined - default to a text type.
			$type = ! empty( $field['type'] ) ? $field['type'] : 'text';

			// if we have any functions hooked to a dynamic action based on the field type.
			if ( has_action( 'wpbb_input_type_' . $type ) ) {

				// run an action for this field type - it has registered functions.
				do_action( 'wpbb_input_type_' . $type, $field, $post );

			} else { // no functions registered against this action.

				// check if there is a function for this field type output.
				if ( function_exists( 'wpbb_input_type_' . $type ) ) {

					// call the function for this input type.
					call_user_func( 'wpbb_input_type_' . $type, $field, $post );

				} else { // no user defined function for this field type.

					// default to a text field type.
					call_user_func( 'wpbb_input_type_text', $field, $post );

				} // End if().
			} // End if().
		} // End foreach().

		// run an action after the fields are outputted.
		do_action( 'wpbb_fields_after_admin_output', $post );

		?>
		<span class="wpbb-clearfix"></span>
		</div>
		<?php

	} // End if().

}
