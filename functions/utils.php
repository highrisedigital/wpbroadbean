<?php

function wpbb_convert_cat_terms_to_ids($tax_broadbean_field, $wpbb_params, $taxonomy) {

	if ( empty( $wpbb_params[ $tax_broadbean_field ] ) ) {
		return;
	}
	
	/* turn category terms into arrays */
	$wpbb_category = wp_strip_all_tags( $wpbb_params[ $tax_broadbean_field ] );
	$wpbb_category_terms = explode( ',', $wpbb_category );

	/* setup array to store the category term ids in */
	$wpbb_category_term_ids = array();

	/* loop through each term in array getting its id */
	foreach( $wpbb_category_terms as $wpbb_category_term ) {
		
		/* 	check whether the term exists, and return its ID if it does, 
			if it doesn't exist then create it 
			either way add it to our array 
		*/
		if ( $term_id = term_exists( $wpbb_category_term ) ) {
			$wpbb_category_term_ids[] = $term_id;
		} else {
			$new_term = wp_insert_term(
				$wpbb_category_term, // term to insert
				$taxonomy['taxonomy_name'], // taxonomy to add the term to
				array(
					'slug' => sanitize_title($wpbb_category_term)
				)
			);
			$wpbb_category_term_ids[] = $new_term['term_id'];
		}
		
		
	} // end loop through each term

	return $wpbb_category_term_ids;
}

