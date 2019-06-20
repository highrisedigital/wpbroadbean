<?php
/**
 * Registers the post types associated with the plugin.
 *
 * @package WP_Broadbean
 */

/**
 * Registers the jobs post type with WordPress.
 */
function wpbb_register_job_post_type() {

	// register the jobs post type.
	register_post_type(
		wpbb_job_post_type_name(),
		array(
			'labels'        => array(
				'name'               => _x( 'Jobs', 'post type general name', 'wpbroadbean' ),
				'singular_name'      => _x( 'Job', 'post type singular name', 'wpbroadbean' ),
				'add_new'            => _x( 'Add New', 'Job', 'wpbroadbean' ),
				'add_new_item'       => __( 'Add New Job', 'wpbroadbean' ),
				'edit_item'          => __( 'Edit Job', 'wpbroadbean' ),
				'new_item'           => __( 'New Job', 'wpbroadbean' ),
				'view_item'          => __( 'View Job', 'wpbroadbean' ),
				'search_items'       => __( 'Search Jobs', 'wpbroadbean' ),
				'not_found'          => __( 'No Jobs found', 'wpbroadbean' ),
				'not_found_in_trash' => __( 'No Jobs found in Trash', 'wpbroadbean' ),
				'parent_item_colon'  => '',
				'menu_name'          => __( 'Jobs', 'wpbroadbean' ),
			),
			'public'        => true,
			'menu_position' => 95,
			'supports'      => array(
				'title',
				'editor',
				'excerpt',
				'author',
			),
			'query_var'     => true,
			'rewrite'       => array(
				'slug'       => 'jobs',
				'with_front' => false,
			),
			'has_archive'   => true,
			'show_in_menu'  => 'wp_broadbean_home', // shows the post type below wp broadbean home
			'show_in_rest'  => true,
		)
	);

	// register the application post type.
	register_post_type(
		'wpbb_application',
		array(
			'labels'        => array(
				'name'               => _x( 'Applications', 'post type general name', 'wpbroadbean' ),
				'singular_name'      => _x( 'Application', 'post type singular name', 'wpbroadbean' ),
				'add_new'            => _x( 'Add New', 'Application', 'wpbroadbean' ),
				'add_new_item'       => __( 'Add New Application', 'wpbroadbean' ),
				'edit_item'          => __( 'Edit Application', 'wpbroadbean' ),
				'new_item'           => __( 'New Application', 'wpbroadbean' ),
				'view_item'          => __( 'View Application', 'wpbroadbean' ),
				'search_items'       => __( 'Search Applications', 'wpbroadbean' ),
				'not_found'          => __( 'No Applications found', 'wpbroadbean' ),
				'not_found_in_trash' => __( 'No Applications found in Trash', 'wpbroadbean' ),
				'parent_item_colon'  => '',
				'menu_name'          => __( 'Applications', 'wpbroadbean' ),
			),
			'public'        => false,
			'has_archive'   => false,
		)
	);

}

add_action( 'init', 'wpbb_register_job_post_type' );
