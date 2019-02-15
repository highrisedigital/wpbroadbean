<?php
/**
 * Functions asssociated with registering plugin settings.
 *
 * @package HD_ACF_Blocks
 */

/**
 * Gets an array of the registered plugin settings.
 *
 * @return array Registered plugins settings.
 */
function wpbb_get_plugin_settings() {
	return apply_filters(
		'wpbb_plugin_settings',
		array()
	);
}

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

/**
 * Registers the default plugin settings
 */
function wpbb_register_settings() {

	// get the current registered settings.
	$settings = wpbb_get_plugin_settings();

	// if we have settings.
	if ( ! empty( $settings ) ) {

		// loop through each setting.
		foreach ( $settings as $setting ) {

			// set up default option args.
			$defaults = array(
				'label'             => '',
				'option_name'       => '',
				'input_type'        => 'text',
				'type'              => 'string',
				'group'             => 'wpbb_settings',
				'description'       => '',
				'sanitize_callback' => null,
				'show_in_rest'      => false,
			);

			// merge the args with defaults.
			$args = wp_parse_args( $setting, $defaults );

			// if no setting key is set.
			if ( '' === $args['option_name'] ) {

				// don't register the setting.
				continue;

			}

			// register this setting.
			register_setting(
				'wpbb_settings', // setting group name.
				$args['option_name'], // setting name - the option key.
				array(
					'type'              => $args['type'],
					'group'             => $args['group'],
					'description'       => $args['description'],
					'sanitize_callback' => $args['sanitize_callback'],
					'show_in_rest'      => $args['show_in_rest'],
				)
			);
		} // End foreach().
	} // End if().

}

add_action( 'admin_init', 'wpbb_register_settings' );

/**
 * Registers the plugin default settings shown on the settings screen.
 *
 * @param  array $settings these are the current settings registered.
 * @return array           the modified array of settings.
 */
function wpbb_register_default_settings( $settings ) {

	// add the feed username.
	$settings['username'] = array(
		'option_name' => 'wpbb_username',
		'label'       => __( 'Username', 'wpbroadbean' ),
		'description' => __( 'Enter a username for your feed.', 'wpbroadbean' ),
		'input_type'  => 'text',
	);

	// add the feed password.
	$settings['password'] = array(
		'option_name' => 'wpbb_password',
		'label'       => __( 'Password', 'wpbroadbean' ),
		'description' => __( 'Enter a password for your feed. Longer the better!', 'wpbroadbean' ),
		'input_type'  => 'text',
	);

	// add the setting to hide the job data on a single job listing.
	$settings['hide_job_data_output'] = array(
		'label'       => __( 'Hide Job Data', 'wpbroadbean' ),
		'option_name' => 'wpbb_hide_job_data_output',
		'input_type'  => 'checkbox',
		'description' => __( 'Check this to prevent the plugin outputting any job taxonomy term or meta data on a single job.', 'wpbroadbean' ),
	);

	// add the feed password.
	$settings['plugin_credit'] = array(
		'option_name' => 'wpbb_plugin_credit',
		'label'       => __( 'Show Plugin Credit', 'wpbroadbean' ),
		'description' => __( 'Show a credit beneath each job on your site for the WP Broadbean developers.', 'wpbroadbean' ),
		'input_type'  => 'checkbox',
	);

	// return the modified settings array.
	return $settings;

}

add_filter( 'wpbb_plugin_settings', 'wpbb_register_default_settings' );
