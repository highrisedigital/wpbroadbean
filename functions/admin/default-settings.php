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
	$settings[] = 'wpbb_remove_application_attachments';
	$settings[] = 'wpbb_remove_application_posts';
	
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

	$settings['wpbb_remove_application_attachments'] = array(
		'label' => 'Remove application attachments',
		'name' => 'wpbb_remove_application_attachments',
		'type' => 'select',
		'description' => '<strong>Highly recommended to be set to yes</strong> as it prevents application attachments being stored on this server. Once processed and sent to Broadbean they are then deleted.',
		'options' => array(
			array(
				'name' => 'No',
				'value' => false,
			),
			array(
				'name' => 'Yes',
				'value' => true,
			),
		),
	);

	$settings['wpbb_remove_application_posts'] = array(
		'label' => 'Remove applications',
		'name' => 'wpbb_remove_application_posts',
		'type' => 'select',
		'description' => '<strong>Highly recommended to be set to yes</strong> as it prevent application data being stored on this server. Once processed and sent to Broadbean they are then deleted.',
		'options' => array(
			array(
				'name' => 'No',
				'value' => false,
			),
			array(
				'name' => 'Yes',
				'value' => true,
			),
		),
	);

	return $settings;

}

add_filter( 'wpbb_settings_output', 'wpbb_create_default_settings_output' );