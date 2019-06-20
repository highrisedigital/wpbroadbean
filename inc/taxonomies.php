<?php
/**
 * Registers the taxonomy with the plugin.
 *
 * @package WP_Broadbean
 */

/**
 * Get an array of the taxonomies registered for use within the plugin.
 *
 * @return array An array of the registered taxonomies.
 */
function wpbb_get_registered_taxonomies() {
	return apply_filters(
		'wpbb_registered_taxonomies',
		array()
	);
}

/**
 * Add the default registered taxonomies.
 */
function wpbb_register_default_taxonomies( $taxonomies ) {

	// add the job industry taxonomy.
	$taxonomies['job_industry'] = array(
		'taxonomy_name'     => 'wpbb_job_industry',
		'xml_field'         => 'job_industry',
		'plural'            => __( 'Job Industries', 'wpbroadbean' ),
		'singular'          => __( 'Job Industry', 'wpbroadbean' ),
		'slug'              => __( 'job-industry', 'wpbroadbean' ),
		'menu_label'        => __( 'Industries', 'wpbroadbean' ),
		'hierarchical'      => true,
		'show_admin_column' => true,
		'show_on_frontend'  => true,
		'jobfeed_notes'    => array(
			'data_type'     => 'string',
			'input_type'    => 'text',
			'default_value' => '',
			'example_value' => 'Accounting|Computing',
			'notes'         => __( 'A pipe seperated string of job industries.', 'wpbroadbean' ),
		),
	);

	// add the job location taxonomy.
	$taxonomies['job_location'] = array(
		'taxonomy_name'     => 'wpbb_job_location',
		'xml_field'         => 'job_location',
		'plural'            => __( 'Job Locations', 'wpbroadbean' ),
		'singular'          => __( 'Job Location', 'wpbroadbean' ),
		'slug'              => __( 'job-location', 'wpbroadbean' ),
		'menu_label'        => __( 'Locations', 'wpbroadbean' ),
		'hierarchical'      => true,
		'show_admin_column' => true,
		'show_on_frontend'  => true,
		'jobfeed_notes'    => array(
			'data_type'     => 'string',
			'input_type'    => 'text',
			'default_value' => '',
			'example_value' => 'London|Manchester',
			'notes'         => __( 'A pipe seperated string of job locations. It is better if this plugin has a defined list of locations to support, rather than using the standard Broadbean locations.', 'wpbroadbean' ),
		),
	);

	// add the job type taxonomy.
	$taxonomies['job_type'] = array(
		'taxonomy_name'     => 'wpbb_job_type',
		'xml_field'         => 'job_type',
		'plural'            => __( 'Job Types', 'wpbroadbean' ),
		'singular'          => __( 'Job Type', 'wpbroadbean' ),
		'slug'              => __( 'job-type', 'wpbroadbean' ),
		'menu_label'        => __( 'Types', 'wpbroadbean' ),
		'hierarchical'      => true,
		'show_admin_column' => true,
		'show_on_frontend'  => true,
		'jobfeed_notes'    => array(
			'data_type'     => 'string',
			'input_type'    => 'text',
			'default_value' => '',
			'example_value' => 'Permanent|Temporary|Contract',
			'notes'         => __( 'A pipe seperated string of job types.', 'wpbroadbean' ),
		),
	);

	// add the job skills taxonomy.
	$taxonomies['job_skill'] = array(
		'taxonomy_name'     => 'wpbb_job_skill',
		'xml_field'         => 'job_skills',
		'plural'            => __( 'Job Skills', 'wpbroadbean' ),
		'singular'          => __( 'Job Skill', 'wpbroadbean' ),
		'slug'              => __( 'job-skill', 'wpbroadbean' ),
		'menu_label'        => __( 'Skills', 'wpbroadbean' ),
		'hierarchical'      => false,
		'show_admin_column' => true,
		'show_on_frontend'  => true,
		'jobfeed_notes'    => array(
			'data_type'     => 'string',
			'input_type'    => 'text',
			'default_value' => '',
			'example_value' => 'PHP|Javascript',
			'notes'         => __( 'A pipe seperated string of job skills.', 'wpbroadbean' ),
		),
	);

	// return the modified taxonomies.
	return $taxonomies;

}

add_filter( 'wpbb_registered_taxonomies', 'wpbb_register_default_taxonomies', 10, 1 );

/**
 * Registers the added taxonomies with WordPress.
 */
function wpbb_register_taxonomies() {

	/* get the taxonomies that are registered with the plugin */
	$taxonomies = wpbb_get_registered_taxonomies();

	/* for each taxonomy returned, register it as a custom taxonomy */
	foreach ( $taxonomies as $taxonomy ) {

		register_taxonomy(
			$taxonomy['taxonomy_name'], // taxonomy name
			wpbb_job_post_type_name(), // post type for this taxonomy
			array(
				'labels'            => apply_filters( $taxonomy['taxonomy_name'] . '_labels',
					array(
						'name'              => $taxonomy['plural'],
						'singular_name'     => $taxonomy['singular'],
						'search_items'      => __( 'Search ', 'wpbroadbean' ) . $taxonomy['plural'],
						'all_items'         => __( 'All ', 'wpbroadbean' ) . $taxonomy['plural'],
						'parent_item'       => __( 'Parent ', 'wpbroadbean' ) . $taxonomy['singular'],
						'parent_item_colon' => __( 'Parent ', 'wpbroadbean' ) . $taxonomy['singular'] . ':',
						'edit_item'         => __( 'Edit ', 'wpbroadbean' ) . $taxonomy['singular'],
						'update_item'       => __( 'Update ', 'wpbroadbean' ) . $taxonomy['singular'],
						'add_new_item'      => __( 'Add New ', 'wpbroadbean' ) . $taxonomy['singular'],
						'new_item_name'     => __( 'New ', 'wpbroadbean' ) . $taxonomy['singular'] . ' Name',
						'menu_name'         => $taxonomy['plural'],
					)
				),
				'hierarchical'      => $taxonomy['hierarchical'],
				'sort'              => true,
				'rewrite'           => array(
					'slug' => $taxonomy['slug'],
				),
				'show_admin_column' => $taxonomy['show_admin_column'],
				'show_in_rest'      => true,
			)
		);

	}

}

add_action( 'init', 'wpbb_register_taxonomies', 10 );

/**
 * Add a submenu item of WP Broadbean menu page for each taxonomy registered.
 */
function wpbb_add_taxonomy_submenus() {

	// get all the registered taxonomies.
	$taxonomies = wpbb_get_registered_taxonomies();

	// if we have any taxonomies.
	if ( ! empty( $taxonomies ) ) {

		// loop through each registered taxonomy.
		foreach ( $taxonomies as $taxonomy ) {

			// add this taxonomy as as submenu of the WP Broadbean menu item.
			add_submenu_page(
				'wp_broadbean_home', // parent_slug,
				$taxonomy['plural'], // page_title,
				$taxonomy['plural'], // menu_title,
				apply_filters( 'wpbb_taxonomy_submenu_cap', 'edit_others_posts', $taxonomy ), // capability,
				'edit-tags.php?taxonomy=' . $taxonomy['taxonomy_name'] // menu slug,
			);

		}
	}

}

add_action( 'admin_menu', 'wpbb_add_taxonomy_submenus' );

/**
 * When viewing a taxonomy edit screen, keep the WP Broadbean top level menu open.
 *
 * @param  string $parent_file The current parent file set for this sub page.
 * @return string              The new parent file set for this sub page.
 */
function wpbb_tax_menu_correction( $parent_file ) {

	global $current_screen;

	/* get the taxonomy of the current screen */
	$current_taxonomy = $current_screen->taxonomy;
	$taxonomies = wpbb_get_registered_taxonomies();

	// loop through each registered taxonomy.
	foreach ( $taxonomies as $taxonomy ) {

		// if the current screen taxonomy is this taxonomy.
		if ( $current_taxonomy === $taxonomy['taxonomy_name'] ) {

			// set the parent file slug to the sen main page.
			$parent_file = 'wp_broadbean_home';

		}
	}

	// return the new parent file.
	return $parent_file;

}

add_action( 'parent_file', 'wpbb_tax_menu_correction' );
