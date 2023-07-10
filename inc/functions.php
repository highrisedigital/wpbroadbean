<?php
/**
 * Plugin functions to be used in this plugin and in templates with function_exists().
 *
 * @package WP_Broadbean
 */

/**
 * Returns the name of the post type to be used for jobs.
 * @return string The name of the post type to be used for jobs.
 */
function wpbb_job_post_type_name() {
	return apply_filters( 'wpbb_job_post_type_name', 'wpbb_job' );
}

/**
 * Gets the feed username set on the settings page.
 *
 * @return string The username supplied.
 */
function wpbb_get_feed_username() {
	return esc_html( apply_filters( 'wpbb_feed_username', get_option( 'wpbb_username' ) ) );
}

/**
 * Gets the feed password set on the settings page.
 *
 * @return string The password supplied.
 */
function wpbb_get_feed_password() {
	return esc_html( apply_filters( 'wpbb_feed_password', get_option( 'wpbb_password' ) ) );
}

/**
 * Gets the status of whether to show the footer credit to the plugin authors.
 *
 * @return bool True if the footer credit is to be shown and false otherwise.
 */
function wpbb_show_plugin_credit() {
	return apply_filters( 'wpbb_plugin_credit_status', (bool) get_option( 'wpbb_plugin_credit', false ) );
}

/**
 * Gets the status of whether to hide the job data on for tax terms and meta.
 *
 * @return bool True if the data should be hidden and false otherwise.
 */
function wpbb_hide_job_data_output() {
	return apply_filters( 'wpbb_hide_job_data_output', (bool) get_option( 'wpbb_hide_job_data_output', false ) );
}

/**
 * Gets the application type from settings - defauly to form.
 *
 * @return string the type of application to use.
 */
function wpbb_get_job_application_type() {
	return apply_filters( 'wpbb_job_application_type', get_option( 'wpbb_job_application_type', 'form' ) );
}

/**
 * Gets an array of the registered plugin settings.
 *
 * @return array Registered plugins settings.
 */
function wpbb_get_plugin_settings( $settings_group = 'all' ) {

	// run the setting filter, where all the settings are added to.
	$settings = apply_filters( 'wpbb_plugin_settings', array() );

	// if we have settings.
	if ( ! empty( $settings ) ) {

		// if we are looking to return all settings.
		if ( 'all' !== $settings_group ) {

			// create an empty array to fill with the requested settings.
			$page_settings = array();

			// loop through each setting.
			foreach ( $settings as $setting ) {

				if ( $setting['settings_group'] === $settings_group ) {

					// add this setting to the page settings array.
					$page_settings[] = $setting;

				}
			}

			// set the new page settings to the settings array.
			$settings = $page_settings;

		}
	}

	// sort the settings based on the order parameter.
	uasort( $settings, 'wpbb_array_sort_by_order_key' );

	// return the settings.
	return $settings;

}

/**
 * Gets all of the registered settings groups.
 *
 * @return array Either an empty array if there are no groups or an array of groups.
 */
function wpbb_get_settings_groups() {

	// store the output as an array.
	$output = array();

	// get all the registered settings.
	$registered_settings = wpbb_get_plugin_settings();

	// if we have registered settings.
	if ( ! empty( $registered_settings ) ) {

		// loop through the registered settings.
		foreach ( $registered_settings as $registered_setting ) {

			// if this settings, setting group is not already in the output array.
			if ( ! isset( $output[ $registered_setting['settings_group'] ] ) ) {

				// add the setting group to the array.
				$output[ $registered_setting['settings_group'] ] = $registered_setting['settings_group'];

			}
		}
	}

	// return the ouput, filtered.
	return apply_filters( 'wpbb_settings_groups', $output );

}

/**
 * Get the tracking email address for a specified job.
 *
 * @param  integer $job_id the job post id to get the tracking email address from.
 * @return mixed          if the job has a tracking email address, returns a string of that address.
 *                        if the job does not have a tracking email address returns and empty string.
 */
function wpbb_get_job_applicant_tracking_email( $job_id = 0 ) {

	// return the tracking email from post meta.
	return apply_filters(
		'wpbb_job_applicant_tracking_email',
		get_post_meta( $job_id, '_wpbb_job_broadbean_application_email', true ),
		$job_id
	);

}

/**
 * Get the tracking or application url for a specified job.
 *
 * @param  integer $job_id the job post id to get the tracking url from.
 * @return mixed          if the job has a tracking url, returns a string of that address.
 *                        if the job does not have a tracking url returns and empty string.
 */
function wpbb_get_job_applicant_tracking_url( $job_id = 0 ) {

	// return the tracking url from post meta.
	return apply_filters(
		'wpbb_job_applicant_tracking_url',
		get_post_meta( $job_id, '_wpbb_job_broadbean_application_url', true ),
		$job_id
	);

}

/**
 * Prepare terms sent via the broadbean feed to adding to a job post once created.
 * returns an array of mixed term ids and terms names.
 * hierarchical taxonomies have term ids added whereas non hierarchical taxonomies have term name added.
 *
 * @param (string) $sent_terms      The terms sent via the broadbean feed for this taxonomy.
 * @param (sstring) $taxonomy       The taxomony name associated with the terms in $sent_terms.
 * @return (array)  $wpbb_tax_terms An array with all the taxonomies terms.
 */
function wpbb_prepare_terms( $sent_terms, $taxonomy ) {

	if ( empty( $sent_terms ) ) {
		return;
	}

	// turn category terms into arrays.
	$wpbb_tax = wp_strip_all_tags( (string) $sent_terms );
	$wpbb_taxonomy_terms = explode( '|', $wpbb_tax );

	// setup array to store the category term ids in.
	$wpbb_tax_terms = array();

	// loop through each term in array getting its id.
	foreach ( $wpbb_taxonomy_terms as $wpbb_taxonomy_term ) {

		/**
		 * check whether the term exists, and return its ID if it does.
		 * if it doesn't exist then create it either way add it to our array.
		 */

		// start by checking if the term exists.
		if ( $term_id = term_exists( $wpbb_taxonomy_term, $taxonomy['taxonomy_name'] ) ) {

			/* check if the taxonomy is hierarchical */
			if ( true === $taxonomy['hierarchical'] ) {

				/* add to term id to our terms array */
				$wpbb_tax_terms[] = $term_id['term_id'];

			} else {

				/* add the term name to our terms array */
				$wpbb_tax_terms[] = $wpbb_taxonomy_term;

			}
		} else {

			// check term we are adding is not an empty string.
			if ( '' !== $wpbb_taxonomy_term ) {

				$new_term = wp_insert_term(
					$wpbb_taxonomy_term, // term to insert
					$taxonomy['taxonomy_name'], // taxonomy to add the term to
					array(
						'slug' => sanitize_title( $wpbb_taxonomy_term ),
					)
				);

				// check if the taxonomy is hierarchical.
				if ( true === $taxonomy['hierarchical'] ) {

					// add to term id to our terms array.
					$wpbb_tax_terms[] = $new_term['term_id'];

				} else {

					// get the term name from its id.
					$new_term_obj = get_term_by(
						'id',
						$new_term['term_id'],
						$taxonomy['taxonomy_name']
					);

					/* add the term name to our terms array */
					$wpbb_tax_terms[] = $new_term_obj->name;

				}
			}
		} // End if().
	} // End foreach().

	return $wpbb_tax_terms;

}

/**
 * Gets the class to be added to a job fields wrapper div.
 *
 * @param  array $field an array of data about a job field - as used when it was registered.
 * @return string        classes to add to the class attr of the div.
 */
function wpbb_get_input_field_wrapper_class( $field ) {

	// array to fill with classes.
	$classes = array( 'wpbb-job-fields__field' );

	// default the type for this field to text if not supplied.
	$type = ! empty( $field['type'] ) ? $field['type'] : 'text';

	// add the type class to the array.
	$classes[] = 'wpbb-job-field-' . $type;

	// default the columns for this field to 12 if not supplied.
	$columns = ! empty( $field['cols'] ) ? $field['cols'] : '12';

	// add the columns to the classes array.
	$classes[] = 'wpbb-col-' . $columns;

	// return the column class string.
	return apply_filters( 'wpbb_input_field_wrapper_class', implode( ' ', $classes ), $field );

}

/**
 * Gets the class that will be added to a job field input.
 *
 * @param  array $field an array of data about the job field - as used when it was registered.
 * @return string        classes to add to the class attr of the input.
 */
function wpbb_get_input_field_class( $field ) {

	// if the field has no type - default to text.
	if ( ! isset( $field['type'] ) ) {
		$field['type'] = 'text';
	}

	// build an array to fill with classes which includes the class for input type.
	$classes = array( 'wpbb-input' );

	// if this field has a class.
	if ( isset( $field['classes'] ) ) {

		// if the classes are an array.
		if ( is_array( $field['classes'] ) ) {

			// loop through each class.
			foreach ( $field['classes'] as $class ) {

				// add to classes array.
				$classes[] = $class;

			}
		}
	}

	return apply_filters( 'wpbb_input_field_class', implode( ' ', $classes ), $field );

}

/**
 * Gets the input name for a given job field.
 *
 * @param  array $field an array of data about the job field - as used when it was registered.
 * @return string        the input name to use that would go in the input name attr.
 */
function wpbb_get_input_field_name( $field ) {

	// if the field has no name - use its id.
	if ( ! isset( $field['name'] ) ) {
		$field['name'] = $field['id'];
	}

	return apply_filters( 'wpbb_input_field_name', $field['name'], $field );

}

/**
 * Gets the input placeholder for a given job fields.
 *
 * @param  array $field an array of data about the job field - as used when it was registered.
 * @return string        the input placeholder to use that would go in the input placeholder attr.
 */
function wpbb_get_input_field_placeholder( $field ) {

	// if the field has no placeholder - use its id.
	if ( ! isset( $field['placeholder'] ) ) {
		$field['placeholder'] = '';
	}

	return apply_filters( 'wpbb_input_field_placeholder', $field['placeholder'], $field );

}

/**
 * Sorts array of 2 levels on the second level key of order.
 *
 * @param  mixed $a The first value to compare.
 * @param  mixed $b The second value to compare.
 * @return mixed    Either 0 if the values match or 1 or -1 if they are different.
 */
function wpbb_array_sort_by_second_level_order_key( $a, $b ) {

	// if first array does not have an order parameter.
	if ( empty( $a['order'] ) ) {

		// set order to default to 10.
		$a['order'] = 10;

	}

	// if second array does not have an order parameter.
	if ( empty( $b['order'] ) ) {

		// set order to default to 10.
		$b['order'] = 10;

	}

	// if the first array element is the same as the next.
	if ( $a['order'] === $b['order'] ) {
		return 0;
	}

	// return -1 is the first array element is less than the second, otherwise return 1.
	return ( $a['order'] < $b['order'] ) ? -1 : 1;

}

/**
 * Gets the job post id for the specified job reference.
 *
 * @param  string $job_ref the job reference to find the job post id of.
 * @return mixed           null if no job found with that reference.
 *                         post id of the job found if a job is found with that reference.
 */
function wpbb_get_job_by_reference( $job_ref = 0 ) {

	// run a WP_Query to find the job with this reference.
	$jobs = new WP_Query(
		apply_filters(
			'wpbb_get_job_by_reference_args',
			array(
				'post_type'      => wpbb_job_post_type_name(),
				'post_status'    => 'publish',
				'posts_per_page' => 750,
				'meta_key'       => '_wpbb_job_reference',
				'meta_value'     => $job_ref,
				'fields'         => 'ids',
				'no_found_rows'  => true,
			),
			$job_ref
		)
	);

	// reset the query.
	wp_reset_postdata();

	// return the job id.
	return apply_filters( 'wpbb_get_job_by_reference', array_shift( $jobs->posts ), $job_ref );

}

/**
 * Loads a view from the views folder in the plugin to output something.
 *
 * @param  string $path this is the path or filename inside the views folder.
 * @param  array  $data an array of data that is available inside the view file via $data.
 * @return mixed        the content of the file.
 */
function wpbb_load_view( $path = '', $data = array() ) {

	// build a filterable path for the view.
	$view_path = apply_filters( 'wpbb_load_view_path', WPBB_LOCATION . '/views/' . $path . '.php', $path, $data );

	/* start output buffering */
	ob_start();

	/* grab the file asked for */
	include( $view_path );

	/* get the content of the buffer - the file asked for and clean up */
	$content = ob_get_clean();

	/* return the content */
	return apply_filters( 'wpbb_load_view_content', $content, $path, $data );

}

/**
 * Gets the currency symbol for the job post id requested.
 *
 * @param  integer $post_id the post if of the job to get the currency symbol of.
 * @return string           html entity of the currency symbol if supported or the default is not.
 */
function wpbb_get_job_currency_symbol( $post_id = 0 ) {

	// set a filterable default currency.
	$default_currency_symbol = apply_filters( 'wpbb_default_currency_symbol', '&pound;', $post_id );

	// get the currency code for this job.
	$currency_code = get_post_meta( $post_id, wpbb_get_job_field_prefix() . 'salary_currency', true );

	// if we have no currency code - return a default.
	if ( '' === $currency_code ) {
		$currency_symbol = $default_currency_symbol;
	}

	// create an array of currency code supported.
	$supported_currency_codes = apply_filters(
		'wpbb_supported_currency_codes',
		array(
			'GBP' => array(
				'name'   => 'United Kingdom Pound',
				'symbol' => '&pound;',
			),
			'USD' => array(
				'name'   => 'United States Dollar',
				'symbol' => '&dollar;',
			),
			'EUR' => array(
				'name'   => 'Euro Member Countries',
				'symbol' => '&euro;',
			),
		)
	);

	// if the currency code for this job does not exist in the supported codes.
	if ( ! isset( $supported_currency_codes[ $currency_code ] ) ) {

		// use the default symbol.
		$currency_symbol = $default_currency_symbol;

	} else { // currency code for this job is supported.

		// find the symbol in the support list.
		$currency_symbol = $supported_currency_codes[ $currency_code ]['symbol'];

	}

	// return the currency symbol.
	return apply_filters( 'wpbb_get_job_currency_symbol', $currency_symbol, $post_id );

}

/**
 * Gets the jobs data (fields and terms) associated with a job.
 *
 * @param  array $args An array of args in terms of what to show. See $defaults.
 * @return mixed       false if there is no data or a string of html if there is.
 */
function wpbb_get_job_meta_data( $args = array() ) {

	// string to hold meta data in.
	$meta_output = '';

	// string to hold taxonomy data in.
	$tax_output = '';

	// entire output string.
	$output = '';

	global $post;

	// set some defaults for the args.
	$defaults = array(
		'post_id'    => $post->ID,
		'taxonomies' => wpbb_get_registered_taxonomies(),
		'meta'       => wpbb_get_job_fields(),
		'show_title' => true,
	);

	// merge defaults with args.
	$args = wp_parse_args( $args, $defaults );

	// add the wrapper start.
	$output .= '<div class="wpbb-job-data__wrapper">';

	// if we should output the title.
	if ( true === $args['show_title'] ) {

		// add the title next.
		$output .= '<h3 class="wpbb-job-data__title">' . esc_html__( 'Job Information', 'wproadbean' ) . '</h3>';

	}

	// add the job data div.
	$output .= '<div class="wpbb-job-data">';

	// handle the job fields first if we have any to handle.
	if ( ! empty( $args['meta'] ) ) {

		// loop through each meta.
		foreach ( $args['meta'] as $field ) {

			// get the value of this field.
			$field_value = get_post_meta( $args['post_id'], $field['id'], true );

			// make sure we have a show_on_frontend set.
			if ( ! isset( $field['show_on_frontend'] ) ) {

				// set to show on both archive and single views.
				$field['show_on_frontend'] = array( 'single', 'archive' );

			}

			// if we are not showing this data on the front end.
			if ( false === $field['show_on_frontend'] ) {
				continue;
			}

			// if this is the salary from or salary to field.
			if ( wpbb_get_job_field_prefix() . 'salary_from' === $field['id'] || wpbb_get_job_field_prefix() . 'salary_to' === $field['id'] ) {
				$value_prefix = wpbb_get_job_currency_symbol( $args['post_id'] );
			} else {
				$value_prefix = '';
			}

			// add this field to the output string.
			$meta_output .= '<div class="wpbb-job-data__field wpbb-job-data__field--' . esc_attr( str_replace( '_', '-', $field['xml_field'] ) ) . '" data-type="meta"><span class="label">' . esc_html( $field['name'] ) . ':</span> <span class="value">' .  $value_prefix . $field_value . '</span></div>';

		} // End foreach().
	} // End if().

	// add the meta output to the total output if we have meta output.
	if ( '' !== $meta_output ) {
		$output .= $meta_output;
	}

	// handle the job taxonomy terms first checking we have any.
	if ( ! empty( $args['taxonomies'] ) ) {

		// loop through each taxonomy.
		foreach ( $args['taxonomies'] as $tax ) {

			// if we are not showing on the front end.
			if ( false === $tax['show_on_frontend'] ) {
				continue;
			}

			// get the terms of this post.
			$terms = wp_strip_all_tags(
				get_the_term_list(
					$args['post_id'],
					$tax['taxonomy_name'],
					'',
					', ',
					''
				)
			);

			// if we have terms to display.
			if ( '' !== $terms ) {

				// add this to the tax output.
				$tax_output .= '<div class="wpbb-job-data__field wpbb-job-data__field--' . esc_attr( str_replace( '_', '-', $tax['slug'] ) ) . '" data-type="term"><span class="label">' . esc_html( $tax['plural'] ) . ':</span> <span class="value">' .  $terms . '</span></div>';

			}
		} // End foreach().
	} // End if().

	// add the meta output to the total output if we have meta output.
	if ( '' !== $tax_output ) {
		$output .= $tax_output;
	}

	// if we have output.
	if ( '' !== $output ) {

		// return output filtered.
		return apply_filters( 'wpbb_get_job_meta_data', $output . '</div></div>', $args );

	}

	return '';

}

/**
 * Gets the application url for a specified job post id.
 *
 * @param  integer $job_id the post id of the job to get the url for.
 * @return mixed          if the job has a url a string is returned of the url.
 *                        if the job has now application url returns an empty string.
 */
function wpbb_get_job_application_url( $job_id = 0 ) {

	// return the tracking email from post meta.
	return apply_filters(
		'wpbb_job_application_url',
		get_post_meta( $job_id, wpbb_get_job_field_prefix() . 'application_url', true ),
		$job_id
	);

}

/**
 * Outputs a required string if the field passed in is a required field.
 *
 * @param array $field An array of the registered application field.
 */
function wpbb_maybe_application_field_required( $field = array() ) {

	$required_output = '';

	// if this field is a required field.
	if ( true === $field['required'] ) {

		// set the required output.
		$required_output = ' required';

	}

	// echo the required string.
	echo esc_attr( $required_output );

}

/**
 * Actions a file upload.
 *
 * @param  integer $application_id the id of the application post produced.
 * @param  array   $field  an array of field data for the file upload field.
 * @return mixed           in success the attachment id of the upload
 *                         on failure returns false.
 */
function wpbb_action_file_upload_field( $application_id = 0, $field = array() ) {

	/* check that the wp_handle_upload function is loaded */
	if ( ! function_exists( 'wp_handle_upload' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
	}

	/* get the uploaded file information */
	$uploaded_file = $_FILES[ esc_attr( $field['id'] ) ];

	/* check that the $_FILES var is an array */
	if ( ! is_array( $uploaded_file ) ) {

		/* add an error message */
		$wpbb_messages['attachment_failed'] = array(
			'type'    => 'error',
			'message' => __( 'Error: File attachment failed.', 'hd-job-integrator' ),
		);

		/* go no further as file type not allowed */
		return false;
	}

	/* sanitize the uploaded file name */
	$uploaded_file_name = sanitize_text_field( $uploaded_file['name'] );

	/* check we have a file to upload */
	if ( '' !== $uploaded_file_name ) {

		/* set overides to make it work */
		$upload_overrides = array(
			'test_form' => false,
		);

		/* upload the file to wp uploads dir */
		$moved_file = wp_handle_upload( $uploaded_file, $upload_overrides );

		/* get file type of the uploaded file */
		$filetype = wp_check_filetype( $moved_file['url'], null );

		/* generate array of allowed mime types */
		$allowed_mime_types = apply_filters(
			'wpbb_application_allowed_file_types',
			array(
				'pdf'       => 'application/pdf',
				'word'      => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
				'word_old'  => 'application/msword',
				'pages'     => 'application/vnd.apple.pages',
				'open_text' => 'application/vnd.oasis.opendocument.text',
				'rich_text' => 'application/rtf',
				'text'      => 'text/plain',
			)
		);

		/* check uploaded file is in allowed mime types array */
		if ( ! in_array( $filetype['type'], $allowed_mime_types, true ) ) {

			/* add an error message */
			$wpbb_messages['cv_type_failed'] = array(
				'type'    => 'error',
				'message' => __( 'Error: CV is not an allowed file type.', 'hd-job-integrator' ),
			);

		} else { /* excellent we have a file to upload */

			/* get the wp upload directory */
			$wp_upload_dir = wp_upload_dir();

			/* setup the attachment data */
			$attachment = array(
				'post_mime_type' => $filetype['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', $application_id . '-' . time() ), // use a post title not related to the application.
				'post_content'   => '',
				'guid'           => $wp_upload_dir['url'] . '/' . basename( $moved_file['file'] ),
				'post_status'    => 'inherit',
			);

			/* check the application post has been added */
			if ( 0 !== $application_id ) {

				/* check we have a file to attach */
				if ( '' !== $uploaded_file_name ) {

					/* add the attachment from the uploaded file */
					$attachment_id = wp_insert_attachment( $attachment, $moved_file['file'], $application_id );
					require_once( ABSPATH . 'wp-admin/includes/image.php' );
					$attachment_data = wp_generate_attachment_metadata( $attachment_id, $moved_file['file'] );
					wp_update_attachment_metadata( $attachment_id, $attachment_data );

				}
			}
		} // End if().

		/* return the attachment id of the added file */
		return absint( $attachment_id );

	} // End if().

	return false;

}

/**
 * Sets the email type in WordPress to html.
 *
 * @return string the email type to use
 */
function wpbb_text_html_email_type() {
	return 'text/html';
}

/**
 * Gets the applicant candidate name for a given application.
 *
 * @param  integer $application_id The post ID of the application to get the name of.
 * @return string                  The name of the applicant or an empty string.
 */
function wpbb_get_application_applicant_name( $application_id = 0 ) {
	return apply_filters(
		'wpbb_application_applicant_name',
		get_post_meta( $application_id, 'candidate_name', true ),
		$application_id
	);
}

/**
 * Gets the applicant candidate email address for a given application.
 *
 * @param  integer $application_id The post ID of the application to get the name of.
 * @return string                  The email of the applicant or an empty string.
 */
function wpbb_get_application_applicant_email( $application_id = 0 ) {
	return apply_filters(
		'wpbb_application_applicant_name',
		get_post_meta( $application_id, 'candidate_email', true ),
		$application_id
	);
}

/**
 * Sorts array of 2 levels on the second level key of order.
 *
 * @param  mixed $a The first value to compare.
 * @param  mixed $b The second value to compare.
 * @return mixed    Either 0 if the values match or 1 or -1 if they are different.
 */
function wpbb_array_sort_by_order_key( $a, $b ) {

	// if no order paramter is provided.
	if ( ! isset( $a['order'] ) ) {

		// set the order to 10.
		$a['order'] = 10;

	}

	// if no order paramter is provided.
	if ( ! isset( $b['order'] ) ) {

		// set the order to 10.
		$b['order'] = 10;

	}

	// if the first array element is the same as the next.
	if ( $a['order'] === $b['order'] ) {
		return 0;
	}

	// return -1 is the first array element is less than the second, otherwise return 1.
	return ( $a['order'] < $b['order'] ) ? -1 : 1;

}
