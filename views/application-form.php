<?php
/**
 * Creates the application form view.
 *
 * @package WP_Broadbean
 */

?>

<div class="wpbb-application-form__wrapper" id="apply-form-<?php echo esc_attr( $data['post_id'] ); ?>" data-post-id="<?php echo esc_attr( $data['post_id'] ); ?>">

	<?php

	/**
	 * Fires before the application form.
	 *
	 * @param array $data an array of data which can be used in this view.
	 * @hooked wpbb_application_form_title - 10
	 */
	do_action( 'wpbb_before_application_form', $data );

	?>

	<form action="#wpbb-application-messages-<?php echo esc_attr( $data['post_id'] ); ?>" method="post" enctype="multipart/form-data" class="wpbb-application-form" id="wpbb-application-form">

		<?php

		/**
		 * Fires before the application form fields.
		 *
		 * @param array $data an array of data which can be used in this view.
		 */
		do_action( 'wpbb_before_application_form_fields', $data );

		// output the fields for the application form.
		wpbb_output_application_form_fields( $data );

		/**
		 * Fires after the application form fields.
		 *
		 * @param array $data an array of data which can be used in this view.
		 * @hooked wpbb_required_fields_instruction_text - 10
		 * @hooked wpbb_application_form_submit - 20
		 */
		do_action( 'wpbb_after_application_form_fields', $data );

		?>

	</form>

	<?php

	/**
	 * Fires after the application form.
	 *
	 * @param array $data an array of data which can be used in this view.
	 */
	do_action( 'wpbb_after_application_form', $data );

	?>

</div><!-- // wpbb-application-form -->
