<?php
/**
 * Functions which output the fields in the WordPress admin area. Functions are
 * provided for the following fields types.
 * text
 * textarea
 * number
 * email
 * select
 * checkbox
 *
 * @package HD_Job_Integrator
 */

/**
 * Provides the output for the text field type.
 *
 * @param  array $field this is the field array for the field being rendered.
 * @param  obj   $post  the post object for the current post being edited.
 * @return html         field input markup
 */
function wpbb_input_type_text( $field, $post ) {

	// if the field has no id.
	if ( ! isset( $field['id'] ) ) {
		return;
	}

	// get any existing field value.
	$value = get_post_meta( $post->ID, $field['id'], true );

	?>

	<div class="<?php echo esc_attr( wpbb_get_input_field_wrapper_class( $field ) ); ?>">

		<?php

		/**
		 * Fires before the input field label and input itself are outputted.
		 *
		 * @param array  $field      this is the field array
		 * @param string $value      the current saved value for this field
		 * @param int    $post       this is the post object for the current post
		 * @param string $input_type the type of input this field for this field
		 */
		do_action( 'wpbb_before_field_input', $field, $value, $post, $field['type'] );

		?>

		<label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo esc_html( $field['name'] ); ?></label>

		<input type="text" name="<?php echo esc_attr( $field['id'] ); ?>" class="<?php echo esc_attr( wpbb_get_input_field_class( $field ) ); ?>" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php echo esc_attr( wpbb_get_input_field_placeholder( $field ) ); ?>" id="<?php echo esc_attr( $field['id'] ); ?>" />

		<?php

		/**
		 * Fires after the input field label and input itself are outputted.
		 *
		 * @param array  $field      this is the field array
		 * @param string $value      the current saved value for this field
		 * @param int    $post       this is the post object for the current post
		 * @param string $input_type the type of input this field for this field
		 */
		do_action( 'wpbb_after_field_input', $field, $value, $post, $field['type'] );

		?>

	</div><!-- // form-field -->

	<?php

}

/**
 * Provides the output for the textarea field type.
 *
 * @param  array $field this is the field array for the field being rendered.
 * @param  obj   $post  the post object for the current post being edited.
 * @return html        field input markup
 */
function wpbb_input_type_textarea( $field, $post ) {

	// if the field has no id.
	if ( ! isset( $field['id'] ) ) {
		return;
	}

	// get any existing field value.
	$value = get_post_meta( $post->ID, $field['id'], true );

	?>
	
	<div class="<?php echo esc_attr( wpbb_get_input_field_wrapper_class( $field ) ); ?>">

		<?php

		/**
		 * Fires before the input field label and input itself are outputted.
		 *
		 * @param array  $field      this is the field array
		 * @param string $value      the current saved value for this field
		 * @param int    $post       this is the post object for the current post
		 * @param string $input_type the type of input this field for this field
		 */
		do_action( 'wpbb_before_field_input', $field, $value, $post, $field['type'] );

		?>

		<label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo esc_html( $field['name'] ); ?></label>

		<textarea name="<?php echo esc_attr( $field['id'] ); ?>" class="<?php echo esc_attr( wpbb_get_input_field_class( $field ) ); ?>" placeholder="<?php echo esc_attr( wpbb_get_input_field_placeholder( $field ) ); ?>" id="<?php echo esc_attr( $field['id'] ); ?>"><?php echo esc_attr( $value ); ?></textarea>

		<?php

		/**
		 * Fires after the input field label and input itself are outputted.
		 *
		 * @param array  $field      this is the field array
		 * @param string $value      the current saved value for this field
		 * @param int    $post       this is the post object for the current post
		 * @param string $input_type the type of input this field for this field
		 */
		do_action( 'wpbb_after_field_input', $field, $value, $post, $field['type'] );

		?>

	</div><!-- // form-field -->

	<?php

}

/**
 * Provides the output for the number field type.
 *
 * @param  array $field this is the field array for the field being rendered.
 * @param  obj   $post  the post object for the current post being edited.
 * @return html         field input markup
 */
function wpbb_input_type_number( $field, $post ) {

	// if the field has no id.
	if ( ! isset( $field['id'] ) ) {
		return;
	}

	// get any existing field value.
	$value = get_post_meta( $post->ID, $field['id'], true );

	?>

	<div class="<?php echo esc_attr( wpbb_get_input_field_wrapper_class( $field ) ); ?>">

		<?php

		/**
		 * Fires before the input field label and input itself are outputted.
		 *
		 * @param array  $field      this is the field array
		 * @param string $value      the current saved value for this field
		 * @param int    $post       this is the post object for the current post
		 * @param string $input_type the type of input this field for this field
		 */
		do_action( 'wpbb_before_field_input', $field, $value, $post, $field['type'] );

		?>

		<label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo esc_html( $field['name'] ); ?></label>

		<input type="number" name="<?php echo esc_attr( $field['id'] ); ?>" class="<?php echo esc_attr( wpbb_get_input_field_class( $field ) ); ?>" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php echo esc_attr( wpbb_get_input_field_placeholder( $field ) ); ?>" id="<?php echo esc_attr( $field['id'] ); ?>" />

		<?php

		/**
		 * Fires after the input field label and input itself are outputted.
		 *
		 * @param array  $field      this is the field array
		 * @param string $value      the current saved value for this field
		 * @param int    $post       this is the post object for the current post
		 * @param string $input_type the type of input this field for this field
		 */
		do_action( 'wpbb_after_field_input', $field, $value, $post, $field['type'] );

		?>

	</div><!-- // form-field -->

	<?php

}

/**
 * Provides the output for the email field type.
 *
 * @param  array $field this is the field array for the field being rendered.
 * @param  obj   $post  the post object for the current post being edited.
 * @return html         field input markup
 */
function wpbb_input_type_email( $field, $post ) {

	// if the field has no id.
	if ( ! isset( $field['id'] ) ) {
		return;
	}

	// get any existing field value.
	$value = get_post_meta( $post->ID, $field['id'], true );

	?>

	<div class="<?php echo esc_attr( wpbb_get_input_field_wrapper_class( $field ) ); ?>">

		<?php

		/**
		 * Fires before the input field label and input itself are outputted.
		 *
		 * @param array  $field      this is the field array
		 * @param string $value      the current saved value for this field
		 * @param int    $post       this is the post object for the current post
		 * @param string $input_type the type of input this field for this field
		 */
		do_action( 'wpbb_before_field_input', $field, $value, $post, $field['type'] );

		?>

		<label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo esc_html( $field['name'] ); ?></label>

		<input type="email" name="<?php echo esc_attr( $field['id'] ); ?>" class="<?php echo esc_attr( wpbb_get_input_field_class( $field ) ); ?>" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php echo esc_attr( wpbb_get_input_field_placeholder( $field ) ); ?>" id="<?php echo esc_attr( $field['id'] ); ?>" />

		<?php

		/**
		 * Fires after the input field label and input itself are outputted.
		 *
		 * @param array  $field      this is the field array
		 * @param string $value      the current saved value for this field
		 * @param int    $post       this is the post object for the current post
		 * @param string $input_type the type of input this field for this field
		 */
		do_action( 'wpbb_after_field_input', $field, $value, $post, $field['type'] );

		?>

	</div><!-- // form-field -->

	<?php

}

/**
 * Provides the output for the select field type.
 *
 * @param  array $field this is the field array for the field being rendered.
 * @param  obj   $post  the post object for the current post being edited.
 * @return html         field input markup
 */
function wpbb_input_type_select( $field, $post ) {

	// if the field has no id.
	if ( ! isset( $field['id'] ) ) {
		return;
	}

	// get any existing field value.
	$value = get_post_meta( $post->ID, $field['id'], true );

	?>

	<div class="<?php echo esc_attr( wpbb_get_input_field_wrapper_class( $field ) ); ?>">

		<?php

		/**
		 * Fires before the input field label and input itself are outputted.
		 *
		 * @param array  $field      this is the field array
		 * @param string $value      the current saved value for this field
		 * @param int    $post       this is the post object for the current post
		 * @param string $input_type the type of input this field for this field
		 */
		do_action( 'wpbb_before_field_input', $field, $value, $post, $field['type'] );

		?>

		<label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo esc_html( $field['name'] ); ?></label>

		<select name="<?php echo esc_attr( $field['id'] ); ?>" id="<?php echo esc_attr( $field['id'] ); ?>" class="<?php echo esc_attr( wpbb_get_input_field_class( $field ) ); ?>">

			<?php

			// if we have options set for the select input.
			if ( isset( $field['options'] ) ) {

				// loop through the select options.
				foreach ( $field['options'] as $option_value => $option_label ) {

					?>
					<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $value, $option_value ); ?>><?php echo esc_attr( $option_label ); ?></option>
					<?php

				} // End foreach().
			} // End if().

			?>

		</select>

		<?php

		/**
		 * Fires after the input field label and input itself are outputted.
		 *
		 * @param array  $field      this is the field array
		 * @param string $value      the current saved value for this field
		 * @param int    $post       this is the post object for the current post
		 * @param string $input_type the type of input this field for this field
		 */
		do_action( 'wpbb_after_field_input', $field, $value, $post, $field['type'] );

		?>

	</div><!-- // form-field -->

	<?php

}

/**
 * Provides the output for the checkbox field type.
 *
 * @param  array $field this is the field array for the field being rendered.
 * @param  obj   $post  the post object for the current post being edited.
 * @return html         field input markup
 */
function wpbb_input_type_checkbox( $field, $post ) {

	// if the field has no id.
	if ( ! isset( $field['id'] ) ) {
		return;
	}

	// get any existing field value.
	$value = get_post_meta( $post->ID, $field['id'], true );

	?>

	<div class="<?php echo esc_attr( wpbb_get_input_field_wrapper_class( $field ) ); ?>">

		<?php

		/**
		 * Fires before the input field label and input itself are outputted.
		 *
		 * @param array  $field      this is the field array
		 * @param string $value      the current saved value for this field
		 * @param int    $post       this is the post object for the current post
		 * @param string $input_type the type of input this field for this field
		 */
		do_action( 'wpbb_before_field_input', $field, $value, $post, $field['type'] );

		?>

		<label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo esc_html( $field['name'] ); ?></label>

		<input type="checkbox" name="<?php echo esc_attr( $field['id'] ); ?>" class="<?php echo esc_attr( wpbb_get_input_field_class( $field ) ); ?>" value="1" id="<?php echo esc_attr( $field['id'] ); ?>" <?php checked( $value, 1 ); ?> />

		<?php

		/**
		 * Fires after the input field label and input itself are outputted.
		 *
		 * @param array  $field      this is the field array
		 * @param string $value      the current saved value for this field
		 * @param int    $post       this is the post object for the current post
		 * @param string $input_type the type of input this field for this field
		 */
		do_action( 'wpbb_after_field_input', $field, $value, $post, $field['type'] );

		?>

	</div><!-- // form-field -->

	<?php

}
