<?php
/**
 * Registers the default settings with WordPress, hooking them into the settings API.
 *
 * @package WP_Broadbean
 */

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
				'settings_group'    => 'wpbb_settings',
				'description'       => '',
				'sanitize_callback' => null,
				'show_in_rest'      => false,
				'settings_page'     => 'settings',
				'order'             => 10,
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
				$args['settings_group'], // setting group name.
				$args['option_name'], // setting name - the option key.
				array(
					'type'              => $args['type'],
					'group'             => $args['settings_group'],
					'description'       => $args['description'],
					'sanitize_callback' => $args['sanitize_callback'],
					'show_in_rest'      => $args['show_in_rest'],
				)
			);
		} // End foreach().
	} // End if().

}

add_action( 'admin_init', 'wpbb_register_settings' );
