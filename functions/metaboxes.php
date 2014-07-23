<?php
/***************************************************************
* Function wpbb_metaboxes()
* Defines the additional metaboxes for the post edit screens.
***************************************************************/
function wpbb_metaboxes( $meta_boxes ) {
	
	/* sets a prefix for all metaboxes */
	$wpbb_prefix = '_wpbb_';
	
	$meta_boxes[] = array(
        'title' => 'Job Information',
        'id' => 'wpbb_job_info',
        'pages' => array( 'wpbb_job' ),
        'context' => 'normal',
        'priority' => 'high',
        'fields' => array(
        	array(
				'name' => 'Unique Job Reference/ID',
				'id'   => $wpbb_prefix . 'job_reference',
				'desc' => 'Enter the unique job reference/ID.',
				'type' => 'text',
				'cols' => 12
			),
			array(
				'name' => 'Salary',
				'id'   => $wpbb_prefix . 'job_salary',
				'type' => 'text',
				'cols' => 4
			),
			array(
				'name' => 'Salary Per',
				'id'   => $wpbb_prefix . 'job_salary_per',
				'type' => 'select',
				'options' => array(
					'zero' => 'Select',
			        'hour' => 'Hour',
			        'week' => 'Week',
			        'month' => 'Month',
			        'year' => 'Year',
			    ),
				'cols' => 4
			),
			array(
				'name' => 'Salary Currency',
				'id'   => $wpbb_prefix . 'job_salary_currency',
				'type' => 'select',
				'options' => array(
					'zero' => 'Select',
			        'gbp' => 'GBP',
			        'eur' => 'Euro',
			    ),
				'cols' => 4
			),
			array(
				'name' => 'Job Start Date',
				'id'   => $wpbb_prefix . 'job_start_date',
				'type' => 'text',
				'cols' => 6
			),
			array(
				'name' => 'Job Duration',
				'id'   => $wpbb_prefix . 'job_duration',
				'type' => 'text',
				'cols' => 6
			),
			array(
				'name' => 'Broadbean Application Email',
				'desc' => 'Enter the Broadbean Aplitrak email address.',
				'id'   => $wpbb_prefix . 'job_broadbean_application_email',
				'type' => 'text',
				'cols' => 6
			),
			array(
				'name' => 'Contact Email',
				'desc' => 'Enter the internal email address for this job.',
				'id'   => $wpbb_prefix . 'job_contact_email',
				'type' => 'text',
				'cols' => 6
			),
			array(
				'name' => 'Allow LinkedIn Applications?',
				'id'   => $wpbb_prefix . 'allow_linkedin_applications',
				'desc' => 'Tick to allow applications via LinkedIn.',
				'type' => 'checkbox',
				'cols' => 6
			),
			array(
				'name' => 'Feature Job',
				'id'   => $wpbb_prefix . 'featured_job',
				'desc' => 'Tick to make this a featured job.',
				'type' => 'checkbox',
				'cols' => 6
			),
        )
    );
    
    $meta_boxes[] = array(
        'title' => 'Application Information',
        'id' => 'wpbb_application_info',
        'pages' => array( 'wpbb_application' ),
        'context' => 'normal',
        'priority' => 'high',
        'fields' => array(
        	array(
				'name' => 'Job Reference/ID',
				'desc' => 'This is the job reference or ID for the job applied for.',
				'id' => $wpbb_prefix . 'job_reference',
				'cols' => 4,
				'type' => 'text'
			),
			array(
				'name' => 'Email Address',
				'desc' => 'This is the email address of the person applying for this job.',
				'id' => $wpbb_prefix . 'applicant_email',
				'cols' => 4,
				'type' => 'text'
			),
			array(
				'name' => 'Tel No.',
				'desc' => 'This is the telephone number of the person applying for this job.',
				'id' => $wpbb_prefix . 'applicant_telno',
				'cols' => 4,
				'type' => 'text'
			),
			array(
				'name' => 'Job URL',
				'desc' => 'This is the URL of the job applied for.',
				'id' => $wpbb_prefix . 'job_url',
				'type' => 'text'
			),
			array(
				'name' => 'Application Attachment',
				'desc' => 'Here are the files uploaded with this application.',
				'id' => $wpbb_prefix . 'application_uploads',
				'type' => 'file',
			),
        )
    );

	return $meta_boxes;
}

add_filter( 'cmb_meta_boxes', 'wpbb_metaboxes' );