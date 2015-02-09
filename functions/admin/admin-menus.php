<?php
/**
 * function wpbb_add_settings_menu()
 * adds the plugin settings menu under the main menu
 * @hooked - wpbb_admin_sub_menus 99
 * @param (array) $submenus is the existing array of plugin submenus to add
 * @return (array) $submneus is the modified array of submenus to add
 */
function wpbb_add_settings_menu( $submenus ) {
	
		/* add the settings menu to any other added menus */
		$submenus[ 'wpbb_settings' ] = array(
			'label'		=> 'Settings',
			'cap'		=> 'manage_options',
			'slug'		=> 'wpbb_broadbean_settings',
			'callback'	=> 'wpbb_settings_page_content'
		);
	
	/* return the modified submenus */
	return $submenus;
	
}

add_filter( 'wpbb_admin_sub_menus', 'wpbb_add_settings_menu', 99 );

/**
 * function wpbb_add_taxonomy_sub_menus()
 * adds all the menus for the plugins registered taxonmies under the main plugin admin menu
 * @hooked - wpbb_admin_sub_menus 99
 * @param (array) $submenus is the existing array of plugin submenus to add
 * @return (array) $submneus is the modified array of submenus to add
 */
function wpbb_add_taxonomy_sub_menus( $submenus ) {
	
	/* get the plugins regsitered taxonomies */
	$taxonomies = wpbb_get_registered_taxonomies();
	
	/* loop through each taxonomy */
	foreach( $taxonomies as $taxonomy ) {
	
		/* add the settings menu to any other added menus */
		$submenus[ $taxonomy[ 'taxonomy_name' ] ] = array(
			'label'		=> $taxonomy[ 'menu_label' ],
			'cap'		=> 'edit_others_posts',
			'slug'		=> 'edit-tags.php?taxonomy=' . $taxonomy[ 'taxonomy_name' ],
		);
	
	} // end taxonomy loop through
	
	/* return the modified submenus */
	return $submenus;
	
}

add_filter( 'wpbb_admin_sub_menus', 'wpbb_add_taxonomy_sub_menus', 20 );