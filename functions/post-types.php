<?php
/***************************************************************
* Function wpbb_register_post_types()
* Register the necessary post types for the plugin.
***************************************************************/
function wpbb_register_post_types() {
	
	/* register the jobs post type */
	register_post_type(
		'wpbb_job', array(
			'labels' => array(
				'name' => _x( 'Jobs', 'post type general name' ),
				'singular_name' => _x( 'Job', 'post type singular name' ),
				'add_new' => _x( 'Add New', 'Job' ),
			    'add_new_item' => __( 'Add New Job' ),
			    'edit_item' => __( 'Edit Job' ),
			    'new_item' => __( 'New Job' ),
			    'view_item' => __( 'View Job' ),
			    'search_items' => __( 'Search Jobs' ),
			    'not_found' =>  __( 'No Jobs found' ),
			    'not_found_in_trash' => __( 'No Jobs found in Trash' ), 
			    'parent_item_colon' => '',
			    'menu_name' => 'Jobs'
			),
			'public' => true,
			'menu_position' => 95,
			'supports' => array(
				'title',
				'editor',
				'excerpt'
			),
			'query_var' => true,
			'rewrite' => array(
				'slug' => 'jobs',
				'with_front' => false
			),
			'has_archive' => true,
			'show_in_menu' => 'wp_broadbean_home' // shows the post type below wp broadbean home
		)
	);
	
	/* registers the application post type */
	register_post_type(
		'wpbb_application',array(
			'labels' => array(
				'name' => _x( 'Applications', 'post type general name' ),
				'singular_name' => _x( 'Application', 'post type singular name' ),
				'add_new' => _x( 'Add New', 'Application' ),
			    'add_new_item' => __( 'Add New Application' ),
			    'edit_item' => __( 'Edit Application' ),
			    'new_item' => __( 'New Application' ),
			    'view_item' => __( 'View Application' ),
			    'search_items' => __( 'Search Applications' ),
			    'not_found' =>  __( 'No Applications found' ),
			    'not_found_in_trash' => __( 'No Applications found in Trash' ), 
			    'parent_item_colon' => '',
			    'menu_name' => 'Applications'
			),
			'public' => false,
			'show_ui' => true,
			'exclude_from_search' => true,
			'menu_position' => 99,
			'supports' => array(
				'title',
				'editor'
			),
			'query_var' => true,
			'rewrite' => array(
				'slug' => 'applications',
				'with_front' => false
			),
			'has_archive' => true,
			'show_in_menu' => 'wp_broadbean_home' // shows the post type below wp broadbean home
		)
	);
	
}

add_action( 'init', 'wpbb_register_post_types', 5 );