<?php
/**
 * Registers the plugins admin menus with the WordPress menu system.
 *
 * @package WP_Broadbean
 */

/**
 * Adds the wpbroadbean admin menus under a parent menu
 */
function wpbb_add_admin_menu() {

	// add the main page for wpbroadbean info.
	add_menu_page(
		esc_html__( 'WP Broadbean' ), // page_title,
		esc_html__( 'WP Broadbean' ), // menu_title,
		'edit_posts', // capability,
		'wp_broadbean_home', // menu_slug,
		'__return_false', // function,
		'dashicons-businessman', // icon url
		'90' // position
	);

}

add_action( 'admin_menu', 'wpbb_add_admin_menu', 10 );


function wpbb_register_settings_submenu_pages() {

	// get the plugin settings groups.
	$settings_groups = wpbb_get_settings_groups();

	// if we have settings groups.
	if ( ! empty( $settings_groups ) ) {

		// loop through each settings page.
		foreach ( $settings_groups as $settings_group ) {

			// remove the preifx of the page for the setting page titles.
			$settings_title = str_replace( 'wpbb_', '', $settings_group );

			// add the sub menu page foe this settings group.
			add_submenu_page(
				'wp_broadbean_home', // parent_slug,
				ucfirst( $settings_title ), // page_title,
				ucfirst( $settings_title ), // menu_title,
				apply_filters( 'wpbb_settings_group_cap', 'manage_options', $settings_group ), // capability,
				esc_html( $settings_group ), // menu slug,
				apply_filters( 'wpbb_settings_group_cap', 'wpbb_settings_page_output', $settings_group ) // callback function for the pages content
			);

		}
	}

}

add_action( 'admin_menu', 'wpbb_register_settings_submenu_pages' );
