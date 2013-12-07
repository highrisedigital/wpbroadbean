<?php
/***************************************************************
* Function wpbb_metaboxes()
* Defines the additional metaboxes for the post edit screens.
***************************************************************/
function wpbb_metaboxes( $meta_boxes ) {
	
	/* sets a prefix for all metaboxes */
	$prefix = 'wpbb_';
	
	/* repeat this for each metabox you require */
	$meta_boxes[] = array(
		'id' => 'job-information',
		'title' => 'Job Information',
		'pages' => array( 'wpbb_job' ), // post type
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => 'Job Reference/ID',
				'desc' => 'Enter the job reference or ID of this job.',
				'id' => $prefix . 'job_reference',
				'type' => 'text'
			),
			array(
				'name' => 'Salary From',
				'desc' => 'Enter the lowest amount of salary for this job.',
				'id'   => $prefix . 'job_salary_from',
				'type' => 'text_small',
			),
			array(
				'name' => 'Salary To',
				'desc' => 'Enter the highest amount of salary for this job.',
				'id'   => $prefix . 'job_salary_to',
				'type' => 'text_small',
			),
			array(
				'name'    => 'Salary Per',
				'desc'    => 'field description (optional)',
				'id'      => $prefix . 'job_salary_per',
				'type'    => 'select',
				'options' => array(
					array( 'name' => '-- Select Salary Per --', 'value' => '0', ),
					array( 'name' => 'Hour', 'value' => 'hour', ),
					array( 'name' => 'Week', 'value' => 'week', ),
					array( 'name' => 'Month', 'value' => 'month', ),
					array( 'name' => 'Year', 'value' => 'annum', ),
				),
			),
			array(
				'name'    => 'Salary Currency',
				'desc'    => 'field description (optional)',
				'id'      => $prefix . 'job_salary_currency',
				'type'    => 'select',
				'options' => array(
					array( 'name' => 'GBP', 'value' => 'gbp', ),
					array( 'name' => 'Euro', 'value' => 'euro', ),
				),
			),
			array(
				'name' => 'Job Start Date',
				'desc' => 'State when the job starts.',
				'id' => $prefix . 'job_start_date',
				'type' => 'text'
			),
			array(
				'name' => 'Job Duration',
				'desc' => 'Enter the duration of this job e.g. 6 months.',
				'id' => $prefix . 'job_duration',
				'type' => 'text'
			),
			array(
				'name' => 'Application Email',
				'desc' => 'Enter the Aplitrak email address for this job.',
				'id' => $prefix . 'job_application_email',
				'type' => 'text'
			),
			array(
				'name' => 'Contact Email',
				'desc' => 'Enter the internal contact email address for this job.',
				'id' => $prefix . 'job_contact_email',
				'type' => 'text'
			),
			array(
				'name' => 'Featured Job',
				'desc' => 'Tick to make this job a featured job.',
				'id'   => $prefix . 'featured_job',
				'type' => 'checkbox',
			),
		),
	);
	
	/* repeat this for each metabox you require */
	$meta_boxes[] = array(
		'id' => 'application-information',
		'title' => 'Application Information',
		'pages' => array( 'wpbb_application' ), // post type
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => 'Job Reference/ID',
				'desc' => 'This is the job reference or ID for the job applied for.',
				'id' => $prefix . 'job_reference',
				'type' => 'text'
			),
			array(
				'name' => 'Job URL',
				'desc' => 'This is the URL of the job applied for.',
				'id' => $prefix . 'job_url',
				'type' => 'text'
			),
			array(
				'name' => 'Email Address',
				'desc' => 'This is the email address of the person applying for this job.',
				'id' => $prefix . 'applicant_email',
				'type' => 'text'
			),
			array(
				'name' => 'Tel No.',
				'desc' => 'This is the telephone number of the person applying for this job.',
				'id' => $prefix . 'applicant_telno',
				'type' => 'text'
			),
			array(
				'name' => 'Application Attachment',
				'desc' => 'Here are the files uploaded with this application.',
				'id' => $prefix . 'application_uploads',
				'type' => 'file_list',
			),
		),
	);

	return $meta_boxes;
}

add_filter( 'wpac_meta_boxes', 'wpbb_metaboxes' );