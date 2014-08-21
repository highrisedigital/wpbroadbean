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
        'fields' => apply_filters(
        	'wpbb_job_metabox_fields',
        	array(
        		'reference' => array(
					'name' => 'Job Reference/ID',
					'desc' => 'This is the job reference or ID for the job applied for.',
					'id' => $wpbb_prefix . 'job_reference',
					'cols' => 4,
					'type' => 'text'
				),
				'email' => array(
					'name' => 'Email Address',
					'desc' => 'This is the email address of the person applying for this job.',
					'id' => $wpbb_prefix . 'applicant_email',
					'cols' => 4,
					'type' => 'text'
				),
				'tel' => array(
					'name' => 'Tel No.',
					'desc' => 'This is the telephone number of the person applying for this job.',
					'id' => $wpbb_prefix . 'applicant_telno',
					'cols' => 4,
					'type' => 'text'
				),
				'url' => array(
					'name' => 'Job URL',
					'desc' => 'This is the URL of the job applied for.',
					'id' => $wpbb_prefix . 'job_url',
					'type' => 'text'
				),
				'attachment' => array(
					'name' => 'Application Attachment',
					'desc' => 'Here are the files uploaded with this application.',
					'id' => $wpbb_prefix . 'application_uploads',
					'type' => 'file',
				),
        	)
        )
    );
    
    $meta_boxes[] = array(
        'title' => 'Application Information',
        'id' => 'wpbb_application_info',
        'pages' => array( 'wpbb_application' ),
        'context' => 'normal',
        'priority' => 'high',
        'fields' => apply_filters(
        	'wpbb_application_metabox_fields',
        	array(
	        	'reference' => array(
					'name' => 'Job Reference/ID',
					'desc' => 'This is the job reference or ID for the job applied for.',
					'id' => $wpbb_prefix . 'job_reference',
					'cols' => 4,
					'type' => 'text'
				),
				'email' => array(
					'name' => 'Email Address',
					'desc' => 'This is the email address of the person applying for this job.',
					'id' => $wpbb_prefix . 'applicant_email',
					'cols' => 4,
					'type' => 'text'
				),
				'tel' => array(
					'name' => 'Tel No.',
					'desc' => 'This is the telephone number of the person applying for this job.',
					'id' => $wpbb_prefix . 'applicant_telno',
					'cols' => 4,
					'type' => 'text'
				),
				'url' => array(
					'name' => 'Job URL',
					'desc' => 'This is the URL of the job applied for.',
					'id' => $wpbb_prefix . 'job_url',
					'type' => 'text'
				),
				'attachment' => array(
					'name' => 'Application Attachment',
					'desc' => 'Here are the files uploaded with this application.',
					'id' => $wpbb_prefix . 'application_uploads',
					'type' => 'file',
				),
			)
        )
    );

	return $meta_boxes;
}

add_filter( 'cmb_meta_boxes', 'wpbb_metaboxes' );