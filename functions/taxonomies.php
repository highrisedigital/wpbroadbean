<?php
/***************************************************************
* Function wpbb_register_taxonomies()
* Register the necessary custom taxonomies for the plugin.
***************************************************************/
function wpbb_register_taxonomies() {
	
	/* register the job type taxonomy */
	register_taxonomy( 'wpbb_job_type', 'wpbb_job',
		array(
			'labels' => apply_filters( 'wpbb_job_type_labels',
				array(
					'name' => _x( 'Types', 'taxonomy general name' ),
					'singular_name' => _x( 'Job Type', 'taxonomy singular name' ),
					'search_items' =>  __( 'Search Job Types' ),
					'all_items' => __( 'All Job Types' ),
					'parent_item' => __( 'Parent Job Type' ),
					'parent_item_colon' => __( 'Parent Job Type:' ),
					'edit_item' => __( 'Edit Job Type' ), 
					'update_item' => __( 'Update Job Type' ),
					'add_new_item' => __( 'Add New Job Type' ),
					'new_item_name' => __( 'New Job Type Name' ),
					'menu_name' => __( 'Types' ),
				)
			),
			'hierarchical' => true,
			'sort' => true,
			'args' => array(
				'orderby' => 'term_order'
			),
			'rewrite' => array(
				'slug' => 'job-type'
			),
			'show_admin_column' => true,
		)
	);
	
	/* register the job category taxonomy */
	register_taxonomy( 'wpbb_job_category', 'wpbb_job',
		array(
			'labels' => apply_filters( 'wpbb_job_category_labels',
				array(
					'name' => _x( 'Categories', 'taxonomy general name' ),
					'singular_name' => _x( 'Category', 'taxonomy singular name' ),
					'search_items' =>  __( 'Search Categories' ),
					'all_items' => __( 'All Categories' ),
					'parent_item' => __( 'Parent Category' ),
					'parent_item_colon' => __( 'Parent Category:' ),
					'edit_item' => __( 'Edit Category' ), 
					'update_item' => __( 'Update Category' ),
					'add_new_item' => __( 'Add New Category' ),
					'new_item_name' => __( 'New Category Name' ),
					'menu_name' => __( 'Categories' ),
				)
			),
			'hierarchical' => true,
			'sort' => true,
			'args' => array(
				'orderby' => 'term_order'
			),
			'rewrite' => array(
				'slug' => 'job-category'
			),
			'show_admin_column' => true,
		)
	);
	
	/* register the job location taxonomy */
	register_taxonomy( 'wpbb_job_location', 'wpbb_job',
		array(
			'labels' => apply_filters( 'wpbb_job_location_labels',
				array(
					'name' => _x( 'Locations', 'taxonomy general name' ),
					'singular_name' => _x( 'Location', 'taxonomy singular name' ),
					'search_items' =>  __( 'Search Locations' ),
					'all_items' => __( 'All Locations' ),
					'parent_item' => __( 'Parent Location' ),
					'parent_item_colon' => __( 'Parent Location:' ),
					'edit_item' => __( 'Edit Location' ), 
					'update_item' => __( 'Update Location' ),
					'add_new_item' => __( 'Add New Location' ),
					'new_item_name' => __( 'New Location Name' ),
					'menu_name' => __( 'Locations' ),
				)
			),
			'hierarchical' => true,
			'sort' => true,
			'args' => array(
				'orderby' => 'term_order'
			),
			'rewrite' => array(
				'slug' => 'job-location'
			),
			'show_admin_column' => true,
		)
	);
	
	/* register the job location tag taxonomy */
	register_taxonomy( 'wpbb_job_location_tag', 'wpbb_job',
		array(
			'labels' => apply_filters( 'wpbb_job_location_tag_labels',
				array(
					'name' => _x( 'Location Tags', 'taxonomy general name' ),
					'singular_name' => _x( 'Location Tag', 'taxonomy singular name' ),
					'search_items' =>  __( 'Search Location Tags' ),
					'all_items' => __( 'All Location Tags' ),
					'parent_item' => __( 'Parent Location Tag' ),
					'parent_item_colon' => __( 'Parent Location Tag:' ),
					'edit_item' => __( 'Edit Location Tag' ), 
					'update_item' => __( 'Update Location Tag' ),
					'add_new_item' => __( 'Add New Location Tag' ),
					'new_item_name' => __( 'New Location Tag Name' ),
					'menu_name' => __( 'Location Tags' ),
				)
			),
			'hierarchical' => false,
			'sort' => true,
			'args' => array(
				'orderby' => 'term_order'
			),
			'rewrite' => array(
				'slug' => 'job-location-tags'
			),
			'show_admin_column' => false,
		)
	);
		
}

add_action( 'init', 'wpbb_register_taxonomies' );

/***************************************************************
* Function wpbb_insert_taxonomy_terms()
* Inserts the default terms of the created taxonomies on plugin
* activation. These can be overidden/added to on the UI.
* Checks to see if they have already been added using an option
* value, before adding them. Skips those already added.
***************************************************************/
function wpbb_insert_taxonomy_terms() {

	/* check whether terms already inserted i.e. this function has already run */
	$wpbb_terms_inserted = get_option( 'wpbb_terms_inserted' );
	
	/* if terms already inserted, do nothing, i.e. this has run before */
	if( $wpbb_terms_inserted == 1 )
		return;
	
	/* create an array of terms to insert */
	$wpbb_terms = array();
	
	/******************************************************
	* sets up terms for the job type category adding each
	* to the term array.
	******************************************************/
	$wpbb_terms[] = array(
		'term' => 'Contract',
		'taxonomy' => 'wpbb_job_type',
		'args' => array(
			'description' => 'Jobs which are contract jobs, for a specified length of time, neither temporary or permanent.',
			'slug' => 'contract'
		)
	);

	$wpbb_terms[] = array(
		'term' => 'Permanent',
		'taxonomy' => 'wpbb_job_type',
		'args' => array(
			'description' => 'Jobs which are on a permeant basis.',
			'slug' => 'permanent'
		)
	);
		
	$wpbb_terms[] = array(
		'term' => 'Temporary',
		'taxonomy' => 'wpbb_job_type',
		'args' => array(
			'description' => 'Jobs which are on a temporary basis.',
			'slug' => 'temporary'
		)
	);
	
	/******************************************************
	* sets up terms for the location category adding each
	* to the term array.
	******************************************************/
	$wpbb_terms[] = array(
		'term' => 'London',
		'taxonomy' => 'wpbb_job_location',
		'args' => array(
			'slug' => 'london'
		)
	);
	
	$wpbb_terms[] = array(
		'term' => 'Midlands of England',
		'taxonomy' => 'wpbb_job_location',
		'args' => array(
			'slug' => 'england-midlands'
		)
	);
	
	$wpbb_terms[] = array(
		'term' => 'Northern England',
		'taxonomy' => 'wpbb_job_location',
		'args' => array(
			'slug' => 'northern-england'
		)
	);
	$wpbb_terms[] = array(
		'term' => 'Northern Ireland',
		'taxonomy' => 'wpbb_job_location',
		'args' => array(
			'slug' => 'northern-ireland'
		)
	);
	
	$wpbb_terms[] = array(
		'term' => 'Republic of Ireland',
		'taxonomy' => 'wpbb_job_location',
		'args' => array(
			'slug' => 'republic-of-ireland'
		)
	);
	
	$wpbb_terms[] = array(
		'term' => 'Scotland',
		'taxonomy' => 'wpbb_job_location',
		'args' => array(
			'slug' => 'scotland'
		)
	);
	
	$wpbb_terms[] = array(
		'term' => 'South East England',
		'taxonomy' => 'wpbb_job_location',
		'args' => array(
			'slug' => 'south-east-england'
		)
	);
	
	$wpbb_terms[] = array(
		'term' => 'South West England',
		'taxonomy' => 'wpbb_job_location',
		'args' => array(
			'slug' => 'south-west-england'
		)
	);
	
	$wpbb_terms[] = array(
		'term' => 'Wales',
		'taxonomy' => 'wpbb_job_location',
		'args' => array(
			'slug' => 'wales'
		)
	);
	
	/* loop through and insert each term from the above array */
	foreach( $wpbb_terms as $wpbb_term ) {
		
		/* check whether term exists already */
		$wpbb_term_exist = term_exists( $wpbb_term[ 'term' ], $wpbb_term[ 'taxonomy' ] );
		
		/* check the term does not already exist */
		if( $wpbb_term_exist == 0 ) {
			
			/* insert term for each item in array */
			wp_insert_term(
				$wpbb_term[ 'term' ], // term to insert
				$wpbb_term[ 'taxonomy' ], // taxonomy to add the term to
				array(
					'description'=> $wpbb_term['args']['description'],
					'slug' => $wpbb_term['args']['slug']
				)
			);
			
		} // end checking whether term exists or not
				
	} // end loop through each term to insert
	
	/* add/update option to say terms inserted */
	update_option( 'wpbb_terms_inserted', 1 );
	
}

add_action( 'init', 'wpbb_insert_taxonomy_terms' );