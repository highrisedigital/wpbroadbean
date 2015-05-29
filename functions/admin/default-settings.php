<?php
/**
 * Function wpbb_create_default_settings()
 * Create the default settings by filtering the settings that are
 * registered.
 */
function wpbb_create_default_settings( $settings ) {
	
	$settings[] = 'wpbb_username';
	$settings[] = 'wpbb_password';
	$settings[] = 'wpbb_apply_page_id';
	
	return $settings;
	
}

add_filter( 'wpbb_registered_settings', 'wpbb_create_default_settings' );

/**
 * Function wpbb_create_default_settings_output()
 * Adds the output to the settings page for the settings
 * register above with wpbb_create_default_settings()
 */
function wpbb_create_default_settings_output( $settings ) {
	
	$settings[ 'wpbb_username' ] = array(
		'label' => 'Feed Username',
		'name' => 'wpbb_username',
		'type' => 'text',
		'description' => 'Choose a username for your feed. Please note once you have set this, it should not be edited here or your feed may break.'
	);
	
	$settings[ 'wpbb_password' ] = array(
		'label' => 'Feed Password',
		'name' => 'wpbb_password',
		'type' => 'text',
		'description' => 'Choose a password for your feed. Please note once you have set this, it should not be edited here or your feed may break.'
	);
	
	$settings[ 'wpbb_apply_page_id' ] = array(
		'label' => 'Apply Page',
		'name' => 'wpbb_apply_page_id',
		'type' => 'select',
		'description' => 'Choose which page to use for your application form. An application form is added after the pages content once the page is chosen here.',
		'options' => wpbb_page_dropdown_array()
	);
	
	return $settings;
	
}

add_filter( 'wpbb_settings_output', 'wpbb_create_default_settings_output' );