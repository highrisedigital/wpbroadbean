<?php
/***************************************************************
* Function wpbb_metaboxes()
* Defines the additional metaboxes for the post edit screens.
***************************************************************/
function wpbb_metaboxes( $meta_boxes ) {
	
	$meta_boxes[] = array(
        'title' => 'Job Information',
        'id' => 'wpbb_job_info',
        'pages' => array(
        	wpbb_job_post_type_name()
        ),
        'context' => 'normal',
        'priority' => 'high',
        'fields' => wpbb_get_job_fields()
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
					'id' => '_wpbb_job_reference',
					'cols' => 6,
					'type' => 'text'
				),
				'email' => array(
					'name' => 'Email Address',
					'desc' => 'This is the email address of the person applying for this job.',
					'id' => '_wpbb_applicant_email',
					'cols' => 6,
					'type' => 'text'
				),
			)
        )
    );

	return $meta_boxes;
}

add_filter( 'cmb_meta_boxes', 'wpbb_metaboxes' );