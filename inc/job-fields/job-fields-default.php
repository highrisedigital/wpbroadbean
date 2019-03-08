<?php
/**
 * Functions associated with job custom fields.
 *
 * @package WP_Broadbean
 */

/**
 * Gets an array of all the registed fields for the job post type.
 *
 * @return an array of registered job fields.
 */
function wpbb_get_job_fields() {

	// make all the fields filterable.
	$fields = apply_filters(
		'wpbb_job_fields',
		array()
	);

	// sort the fields based on their order parameter.
	uasort( $fields, 'wpbb_array_sort_by_second_level_order_key' );

	// return the sorted fields array.
	return $fields;

}

/**
 * Gets the prefix string for the of the job fields.
 *
 * @return string the prefix for job fields keys
 */
function wpbb_get_job_field_prefix() {
	return apply_filters(
		'wpbb_job_field_prefix',
		'_wpbb_job_'
	);
}

/**
 * Sets up the default fields for the jobs post type.
 *
 * @param  array $fields the current array of registered fields for jobs.
 * @return array         the newly modified array of registered fields for jobs.
 */
function wpbb_add_default_job_fields( $fields ) {

	/* set a prefix for all default meta */
	$prefix = wpbb_get_job_field_prefix();

	/* add the job reference field */
	$fields['short_description'] = array(
		'id'               => $prefix . 'short_description',
		'name'             => __( 'Short Description', 'wpbroadbean' ),
		'type'             => 'textarea',
		'xml_field'        => 'short_description',
		'desc'             => __( 'Enter a short description for the job.', 'wpbroadbean' ),
		'show_on_frontend' => false,
		'cols'             => 12,
		'order'            => 5,
	);

	/* add the job reference field */
	$fields['job_reference'] = array(
		'id'               => $prefix . 'reference',
		'name'             => __( 'Job Reference', 'wpbroadbean' ),
		'type'             => 'text',
		'xml_field'        => 'job_reference',
		'desc'             => __( 'Unique to each job.', 'wpbroadbean' ),
		'show_on_frontend' => true,
		'cols'             => 4,
		'order'            => 10,
	);

	/* add the application tracking email field */
	$fields['application_email'] = array(
		'id'               => $prefix . 'broadbean_application_email',
		'name'             => __( 'Application Email', 'wpbroadbean' ),
		'type'             => 'email',
		'xml_field'        => 'application_email',
		'desc'             => __( 'For applicant tracking.', 'wpbroadbean' ),
		'show_on_frontend' => false,
		'cols'             => 4,
		'order'            => 20,
	);

	/* add the application tracking url field */
	$fields['application_url'] = array(
		'id'               => $prefix . 'broadbean_application_url',
		'name'             => __( 'Application URL', 'wpbroadbean' ),
		'type'             => 'text',
		'xml_field'        => 'application_url',
		'desc'             => __( 'External apply URL.', 'wpbroadbean' ),
		'show_on_frontend' => false,
		'cols'             => 4,
		'order'            => 30,
	);

	/* add the salary display field */
	$fields['salary_display'] = array(
		'id'               => $prefix . 'salary_display',
		'name'             => __( 'Salary', 'wpbroadbean' ),
		'type'             => 'text',
		'desc'             => __( 'Salary display field to show on front end.', 'wpbroadbean' ),
		'xml_field'        => 'salary_display',
		'show_on_frontend' => true,
		'cols'             => 3,
		'order'            => 40,
	);

	/* add the salary field */
	$fields['salary'] = array(
		'id'               => $prefix . 'salary',
		'name'             => __( 'Salary Amount', 'wpbroadbean' ),
		'type'             => 'number',
		'desc'             => __( 'Salary amount.', 'wpbroadbean' ),
		'xml_field'        => 'salary',
		'show_on_frontend' => false,
		'cols'             => 2,
		'order'            => 50,
	);

	/* add the salary from field */
	$fields['salary_from'] = array(
		'id'               => $prefix . 'salary_from',
		'name'             => __( 'Salary From', 'wpbroadbean' ),
		'type'             => 'number',
		'xml_field'        => 'salary_from',
		'desc'             => __( 'Salary from number.', 'wpbroadbean' ),
		'show_on_frontend' => true,
		'cols'             => 2,
		'order'            => 60,
	);

	/* add the salary from field */
	$fields['salary_to'] = array(
		'id'               => $prefix . 'salary_to',
		'name'             => __( 'Salary To', 'wpbroadbean' ),
		'type'             => 'number',
		'xml_field'        => 'salary_to',
		'desc'             => __( 'Salary to number.', 'wpbroadbean' ),
		'show_on_frontend' => true,
		'cols'             => 2,
		'order'            => 70,
	);

	/* add the job reference field */
	$fields['currency'] = array(
		'id'               => $prefix . 'salary_currency',
		'name'             => __( 'Currency', 'wpbroadbean' ),
		'type'             => 'text',
		'xml_field'        => 'currency',
		'desc'             => __( 'Currency code.', 'wpbroadbean' ),
		'show_on_frontend' => false,
		'cols'             => 3,
		'order'            => 80,
	);

	/* add the job reference field */
	$fields['days_to_advertise'] = array(
		'id'               => $prefix . 'days_to_advertise',
		'name'             => __( 'Days to Advertise', 'wpbroadbean' ),
		'type'             => 'number',
		'xml_field'        => 'days_to_advertise',
		'desc'             => __( 'Numbers of days to advertise job. This number is not actioned by this plugin.', 'wpbroadbean' ),
		'show_on_frontend' => false,
		'cols'             => 6,
		'order'            => 90,
	);

	// add the application tracking url field.
	$fields['consultant_email'] = array(
		'id'               => $prefix . 'consultant_email',
		'name'             => __( 'Consultant Email', 'wpbroadbean' ),
		'type'             => 'text',
		'xml_field'        => 'consultant_email',
		'desc'             => __( 'Add the email of the consultant posting this job.', 'wpbroadbean' ),
		'show_on_frontend' => false,
		'cols'             => 6,
		'order'            => 100,
	);

	/* return the modified fields array */
	return $fields;

}

add_filter( 'wpbb_job_fields', 'wpbb_add_default_job_fields', 10, 1 );
