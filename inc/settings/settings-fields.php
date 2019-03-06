<?php
/**
 * Functions asssociated with registering plugin settings.
 *
 * @package WP_Broadbean
 */

/**
 * Controls the output of text input setting.
 *
 * @param  array $setting an array of the current setting.
 * @param  mixed $value   the current value of this setting saved in the database.
 */
function wpbb_setting_input_type_text( $setting, $value ) {

	// handle output for a text input.
	?>

	<input type="text" name="<?php echo esc_attr( $setting['option_name'] ); ?>" id="<?php echo esc_attr( $setting['option_name'] ); ?>" class="regular-text" value="<?php echo esc_attr( $value ); ?>" />

	<?php

}

add_action( 'wpbb_setting_type_text', 'wpbb_setting_input_type_text', 10, 2 );

/**
 * Controls the output of textarea input setting.
 *
 * @param  array $setting an array of the current setting.
 * @param  mixed $value   the current value of this setting saved in the database.
 */
function wpbb_setting_input_type_textarea( $setting, $value ) {

	// handle output for a text input.
	?>

	<textarea name="<?php echo esc_attr( $setting['option_name'] ); ?>" id="<?php echo esc_attr( $setting['option_name'] ); ?>" class="regular-text" value="<?php echo esc_attr( $value ); ?>"></textarea>

	<?php

}

add_action( 'wpbb_setting_type_textarea', 'wpbb_setting_input_type_text', 10, 2 );

/**
 * Controls the output of select input setting.
 *
 * @param  array $setting an array of the current setting.
 * @param  mixed $value   the current value of this setting saved in the database.
 */
function wpbb_setting_input_type_select( $setting, $value ) {

	// handle the output for a select input type setting.
	?>

	<select name="<?php echo esc_attr( $setting['option_name'] ); ?>" id="<?php echo esc_attr( $setting['option_name'] ); ?>">

		<?php

		// check we have some options.
		if ( isset( $setting['options'] ) ) {

			// loop through each select option.
			foreach ( $setting['options'] as $option_value => $option_label ) {

				?>

				<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $value, $option_value ); ?>><?php echo esc_attr( $option_label ); ?></option>

				<?php

			} // End foreach().
		} // End if().

		?>

	</select>

	<?php

}

add_action( 'wpbb_setting_type_select', 'wpbb_setting_input_type_select', 10, 2 );

/**
 * Controls the output of checkbox input setting.
 *
 * @param  array $setting an array of the current setting.
 * @param  mixed $value   the current value of this setting saved in the database.
 */
function wpbb_setting_input_type_checkbox( $setting, $value ) {

	// handle output for a text input.
	?>

	<label for="<?php echo esc_attr( $setting['option_name'] ); ?>">
		<input type="checkbox" name="<?php echo esc_attr( $setting['option_name'] ); ?>" id="<?php echo esc_attr( $setting['option_name'] ); ?>" class="regular-text" value="1" <?php checked( $value, 1 ); ?> />
		<span style="line-height: 1.8;"><?php echo wp_kses_post( $setting['description'] ); ?></span>

	<?php

}

add_action( 'wpbb_setting_type_checkbox', 'wpbb_setting_input_type_checkbox', 10, 2 );
