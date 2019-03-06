<?php
/**
 * Registered the default settings for the plugin.
 *
 * @package WP_Broadbean
 */

/**
 * Registers the plugin default general settings shown on the settings screen.
 *
 * @param  array $settings these are the current settings registered.
 * @return array           the modified array of settings.
 */
function wpbb_register_default_general_settings( $settings ) {

	// add the feed username.
	$settings['username'] = array(
		'option_name'    => 'wpbb_username',
		'label'          => __( 'Username', 'wpbroadbean' ),
		'description'    => __( 'Enter a username for your feed.', 'wpbroadbean' ),
		'input_type'     => 'text',
		'settings_group' => 'wpbb_settings',
		'order'          => 10,
	);

	// add the feed password.
	$settings['password'] = array(
		'option_name'    => 'wpbb_password',
		'label'          => __( 'Password', 'wpbroadbean' ),
		'description'    => __( 'Enter a password for your feed. Longer the better!', 'wpbroadbean' ),
		'input_type'     => 'text',
		'settings_group' => 'wpbb_settings',
		'order'          => 20,
	);

	// add the setting to hide the job data on a single job listing.
	$settings['hide_job_data_output'] = array(
		'label'          => __( 'Hide Job Data', 'wpbroadbean' ),
		'option_name'    => 'wpbb_hide_job_data_output',
		'input_type'     => 'checkbox',
		'description'    => __( 'Check this to prevent the plugin outputting any job taxonomy term or meta data on a single job.', 'wpbroadbean' ),
		'settings_group' => 'wpbb_settings',
		'order'          => 30,
	);

	// add the feed password.
	$settings['plugin_credit'] = array(
		'option_name'    => 'wpbb_plugin_credit',
		'label'          => __( 'Show Plugin Credit', 'wpbroadbean' ),
		'description'    => __( 'Show a credit beneath each job on your site for the WP Broadbean developers.', 'wpbroadbean' ),
		'input_type'     => 'checkbox',
		'settings_group' => 'wpbb_settings',
		'order'          => 40,
	);

	// return the modified settings array.
	return $settings;

}

add_filter( 'wpbb_plugin_settings', 'wpbb_register_default_general_settings' );
