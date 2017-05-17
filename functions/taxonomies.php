<?php
/***************************************************************
* Function wpbb_create_default_taxonomies()
* Create the default taxonomies by filtering the settings that are
* registered.
* broadbean_field is the name of the field for this taxonomy in the
* broadbean XML feed e.g. <job_type>
***************************************************************/
function wpbb_get_registered_taxonomies() {
	
	$taxonomies = array(
		'job_type' => array(
			'taxonomy_name'		=> 'wpbb_job_type',
			'bb_field'			=> 'job_type',
			'plural'			=> 'Job Types',
			'singular'			=> 'Job Type',
			'slug'				=> 'job-type',
			'menu_label'		=> 'Types',
			'hierarchical'		=> true,
			'show_admin_column' => true,
			'show_on_frontend'	=> true
		),
		'job_location' => array(
			'taxonomy_name'		=> 'wpbb_job_location',
			'bb_field'			=> 'job_location',
			'plural'			=> 'Job Locations',
			'singular'			=> 'Job Location',
			'slug'				=> 'job-location',
			'menu_label'		=> 'Locations',
			'hierarchical'		=> true,
			'show_admin_column' => true,
			'show_on_frontend'	=> true
		),
		'job_industry' => array(
			'taxonomy_name'		=> 'wpbb_job_industry',
			'bb_field'			=> 'job_industry',
			'plural'			=> 'Job Industries',
			'singular'			=> 'Job Industry',
			'slug'				=> 'job-industry',
			'menu_label'		=> 'Industries',
			'hierarchical'		=> true,
			'show_admin_column' => true,
			'show_on_frontend'	=> true
		),
		'job_skill' => array(
			'taxonomy_name'		=> 'wpbb_job_skill',
			'bb_field'			=> 'job_skills',
			'plural'			=> 'Job Skills',
			'singular'			=> 'Job Skill',
			'slug'				=> 'job-skill',
			'menu_label'		=> 'Skills',
			'hierarchical'		=> false,
			'show_admin_column'	=> true,
			'show_on_frontend'	=> true
		),
		
	);

	/* allow developers to add additional custom taxonomies */
	return apply_filters( 'wpbb_registered_taxonomies', $taxonomies );

}

/***************************************************************
* Function wpbb_register_taxonomies()
* Register the necessary custom taxonomies for the plugin.
***************************************************************/
function wpbb_register_taxonomies() {
	
	/* get the taxonomies that are registered with the plugin */
	$taxonomies = wpbb_get_registered_taxonomies();

	/* for each taxonomy returned, register it as a custom taxonomy */
	foreach ($taxonomies as $taxonomy) {

		register_taxonomy(
			$taxonomy['taxonomy_name'], // taxonomy name
			wpbb_job_post_type_name(), // post type for this taxonomy
			array(
				'labels' => apply_filters( $taxonomy['taxonomy_name'] . '_labels',
					array(
						'name' => _x( $taxonomy['plural'], 'taxonomy general name' ),
						'singular_name' => _x( $taxonomy['singular'], 'taxonomy singular name' ),
						'search_items' =>  __( 'Search ' . $taxonomy['plural'] ),
						'all_items' => __( 'All ' . $taxonomy['plural'] ),
						'parent_item' => __( 'Parent ' . $taxonomy['singular'] ),
						'parent_item_colon' => __( 'Parent ' . $taxonomy['singular'] . ':' ),
						'edit_item' => __( 'Edit ' . $taxonomy['singular'] ), 
						'update_item' => __( 'Update ' . $taxonomy['singular'] ),
						'add_new_item' => __( 'Add New ' . $taxonomy['singular'] ),
						'new_item_name' => __( 'New ' . $taxonomy['singular'] . ' Name' ),
						'menu_name' => __(  $taxonomy['plural'] ),
					)
				),
				'hierarchical' => $taxonomy['hierarchical'],
				'sort' => true,
				'rewrite' => array(
					'slug' => $taxonomy['slug']
				),
				'show_admin_column' => $taxonomy['show_admin_column'],
			)
		);
		
	} // end foreach

}
	
add_action( 'init', 'wpbb_register_taxonomies', 10 );