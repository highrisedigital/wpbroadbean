<?php
/**
 * Loads in the plugins required files.
 *
 * @package WP_Broadbean
 */

// load in all the required inc files.
$includes_files = glob( plugin_dir_path( __FILE__ ) . '*.php' );

// if we have any includes files.
if ( ! empty( $includes_files ) ) {

	// loop through each file.
	foreach ( $includes_files as $includes_file ) {

		// if this file in the loop is this file we are now in.
		if ( strpos( $includes_file, 'loader.php' ) !== false ) {
			continue; // move to the next file.
		}

		// require this file in the plugin.
		require_once( $includes_file );

	}
}

// load in all the required fields files.
$fields = glob( plugin_dir_path( __FILE__ ) . 'job-fields/*.php' );

// if we have any fields files.
if ( ! empty( $fields ) ) {

	// loop through each file.
	foreach ( $fields as $field ) {

		// require this file in the plugin.
		require_once( $field );

	}
}

// load in all the required admin files.
$admins = glob( plugin_dir_path( __FILE__ ) . 'admin/*.php' );

// if we have any admin files.
if ( ! empty( $admins ) ) {

	// loop through each file.
	foreach ( $admins as $admin ) {

		// require this file in the plugin.
		require_once( $admin );

	}
}

// load in all the required applications files.
$applications = glob( plugin_dir_path( __FILE__ ) . 'applications/*.php' );

// if we have any applications files.
if ( ! empty( $applications ) ) {

	// loop through each file.
	foreach ( $applications as $application ) {

		// require this file in the plugin.
		require_once( $application );

	}
}
