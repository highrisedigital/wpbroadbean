<?php


/***************************************************************
* Function wpbb_create_default_taxonomies()
* Create the default taxonomies by filtering the settings that are
* registered.
***************************************************************/
function wpbb_get_registered_taxonomies() {
	
	$taxonomies = array(
		array(
			'taxonomy_name' => 'wpbb_job_type',
			'broadbean_field' => 'job_type',
			'plural' => 'Job Types',
			'singular' => 'Job Type',
			'slug' => 'job-type'
		),
		array(
			'taxonomy_name' => 'wpbb_job_location',
			'broadbean_field' => 'job_location',
			'plural' => 'Job Locations',
			'singular' => 'Job Location',
			'slug' => 'job-location'
		),
		array(
			'taxonomy_name' => 'wpbb_job_industry',
			'broadbean_field' => 'job_industry',
			'plural' => 'Job Industries',
			'singular' => 'Job Industry',
			'slug' => 'job-industry'
		),
		array(
			'taxonomy_name' => 'wpbb_job_category',
			'broadbean_field' => 'job_category',
			'plural' => 'Job Categories',
			'singular' => 'Job Category',
			'slug' => 'job-category'
		),
	);

	// Allow developers to add additional custom taxonomies
	return apply_filters('wpbb_registered_taxonomies', $taxonomies);

}





/***************************************************************
* Function wpbb_register_taxonomies()
* Register the necessary custom taxonomies for the plugin.
***************************************************************/
function wpbb_register_taxonomies() {


	$taxonomies = wpbb_get_registered_taxonomies();


	// Register each taxonomy
	foreach ($taxonomies as $taxonomy) {

		register_taxonomy( $taxonomy['taxonomy_name'], 'wpbb_job',
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
				'hierarchical' => true,
				'sort' => true,
				'args' => array(
					'orderby' => 'term_order'
				),
				'rewrite' => array(
					'slug' => $taxonomy['slug']
				),
				'show_admin_column' => true,
			)
		);


		
	} // end foreach
	
	
	

}
	
add_action( 'init', 'wpbb_register_taxonomies', 10 );

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