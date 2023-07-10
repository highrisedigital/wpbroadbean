<?php
/*
Plugin Name: WP Broadbean
Plugin URI: https://highrise.digital/products/wpbroadbean-wordpress-plugin/
Description: A plugin which integrates <a href="https://www.broadbean.com/uk/products/features/job-posting-distribution/">Broadbean job posting and distribution</a> with WordPress. It allows jobs written in Broadbean to be distributed to a WordPress site and have applications made to those jobs in WordPress delivered back to Broadbean.
Version: 3.0.7
Author: Highrise Digital
Author URI: https://highrise.digital
License: GPLv3 or later
Text Domain: wpbroadbean
Domain Path: /langauges

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/

// exist if directly accessed.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// define variable for path to this plugin file.
define( 'WPBB_LOCATION', dirname( __FILE__ ) );
define( 'WPBB_LOCATION_URL', plugins_url( '', __FILE__ ) );

// Define plugin version constant and db version constant.
define( 'WPBB_PLUGIN_VERSION', '3.0.7' );
define( 'WPBB_PLUGIN_DB_VERSION', 2 );

/**
 * Load the plugins translated strings.
 */
function wpbb_load_plugin_textdomain() {

	// load the plugin text domain.
	load_plugin_textdomain(
		'wpbroadbean',
		false,
		dirname( __FILE__ ) . '/languages'
	);

}

add_action( 'init', 'wpbb_load_plugin_textdomain' );

/**
 * Function to run when the plugin is activated.
 */
function wpbb_on_activation() {

	// store the plugin version number on activation.
	update_option( 'wpbroadbean_version', WPBB_PLUGIN_VERSION );

	// store the plugin db version on activation.
	update_option( 'wpbroadbean_db_version', WPBB_PLUGIN_DB_VERSION );

	// flush the rewrite rules.
	flush_rewrite_rules();

}

register_activation_hook( __FILE__, 'wpbb_on_activation' );

/**
 * Handles plugin update routines.
 */
function wpbb_plugin_upgrade_routines() {

	// get the current db version of the plugin stored in the sites database.
	$wpbb_site_db_version = absint( get_option( 'wpbroadbean_db_version' ) );

	// require updates defaults to false.
	$require_update = false;

	// if we have no stored site db version.
	if ( empty( $wpbb_site_db_version ) ) {

		// updates are needed as the version is not present.
		$require_update = true;

	}

	// if the site db version is less than 2.
	if ( $wpbb_site_db_version < 2 ) {

		// an update is required.
		$require_update = true;

		// start with removing the plugin version as we don't need this - using a db version instead as is best practice.
		delete_option( 'wpbroadbean_version' );

	}

	// if updates are required.
	if ( true === $require_update ) {

		// update the plugins db version on this site.
		update_option( 'wpbroadbean_db_version', WPBB_PLUGIN_DB_VERSION );

	}

}

add_action( 'admin_init', 'wpbb_plugin_upgrade_routines' );

/* load required files & functions */
require_once( dirname( __FILE__ ) . '/inc/loader.php' );
