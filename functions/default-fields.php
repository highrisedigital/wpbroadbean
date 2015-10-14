<?php
/**
 * function wpbb_add_default_fields()
 * adds the default job fields. This adds their meta box on the post edit screen as
 * well as setting them up to save the data from the feed.
 * @param (array) $fields is an array of current fields in the filter
 * @return (array) $fields is the newly modified array of fields
 *
 * each array passed here should contain the following:
 * 	array(
 * 		'name'		=> 'the label given to the field used in the admin',
 *		'id'		=> 'meta key used when saving the data as post meta',
 *		'type'		=> 'input type to use for outputting the metabox e.g. text, select, textarea etc.
 *		'options'	=> 'an array of value/name options for select box types
 *		'bb_field' 	=> 'the XML node which should be used from the Broadbean XML field to populate this field with data
 *		'cols'		=> 'used for layout in the admin meta box - 12 column grid
 * 		'desc'		=> 'a field description shown in the meta box under the field
 * 	)
 */
function wpbb_add_default_fields( $fields ) {
		
	/* add our default fields to the array */
	$fields[ 'reference' ] = array(
		'name'				=> 'Job Reference',
		'id'				=> '_wpbb_job_reference',
		'bb_field'			=> 'job_reference',
		'type'				=> 'text',
		'cols'				=> 12,
		'desc'				=> 'Enter the unique job reference/ID.',
		'show_on_frontend'	=> true
	);
	
	/**
	 * add salary fields
	 */
	 
	$fields[ 'salary' ] = array(
		'name'				=> 'Salary',
		'id'				=> '_wpbb_job_salary',
		'bb_field'			=> 'salary',
		'type'				=> 'text',
		'cols'				=> 6,
		'show_on_frontend'	=> true
	);
	
	$fields[ 'salary_benefits' ] = array(
		'name'				=> 'Salary Benefits',
		'id'				=> '_wpbb_job_salary_benefits',
		'bb_field'			=> 'salary_benefits',
		'type'				=> 'text',
		'cols'				=> 6,
		'show_on_frontend'	=> false
	);
	
	$fields[ 'salary_per' ] = array(
		'name'				=> 'Salary per',
		'id'				=> '_wpbb_job_salary_per',
		'bb_field'			=> 'salary_per',
		'type'				=> 'select',
		'options' 			=> array(
			'zero'	=> 'Select',
	        'hour'	=> 'Hour',
	        'week'	=> 'Week',
	        'month'	=> 'Month',
	        'annum'	=> 'Year',
	    ),
		'cols'				=> 6,
		'show_on_frontend'	=> true
	);
	
	$fields[ 'salary_currency' ] = array(
		'name'				=> 'Salary Currency',
		'id'				=> '_wpbb_job_salary_currency',
		'bb_field'			=> 'salary_currency',
		'type'				=> 'select',
		'options'			=> array(
			'zero'	=> 'Select',
	        'GBP'	=> 'GBP',
	        'EUR'	=> 'Euro',
	    ),
		'cols' 				=> 6,
		'show_on_frontend'	=> false
	);
	
	$fields[ 'salary_form' ] = array(
		'name'				=> 'Salary From',
		'id'				=> '_wpbb_job_salary_from',
		'bb_field'			=> 'salary_from',
		'type'				=> 'text',
		'cols'				=> 6,
		'show_on_frontend'	=> false
	);
	
	$fields[ 'salary_to' ] = array(
		'name'				=> 'Salary To',
		'id'				=> '_wpbb_job_salary_to',
		'bb_field'			=> 'salary_to',
		'type'				=> 'text',
		'cols'				=> 6,
		'show_on_frontend'	=> false
	);
	
	/**
	 * add contact fields
	 */
	
	$fields[ 'contact_name' ] = array(
		'name'				=> 'Contact Name',
		'id'				=> '_wpbb_job_contact_name',
		'bb_field'			=> 'contact_name',
		'desc'				=> 'Add the name of the person overseeing this job here.',
		'type'				=> 'text',
		'cols'				=> 6,
		'show_on_frontend'	=> false
	);
	
	$fields[ 'contact_email' ] = array(
		'name'				=> 'Contact Email',
		'id'				=> '_wpbb_job_contact_email',
		'bb_field'			=> 'contact_email',
		'desc'				=> 'Add the name of the person overseeing this job here.',
		'type'				=> 'text',
		'cols'				=> 6,
		'show_on_frontend'	=> false
	);
	
	$fields[ 'contact_telephone' ] = array(
		'name'				=> 'Contact Tel.',
		'id'				=> '_wpbb_job_contact_tel',
		'bb_field'			=> 'contact_telephone',
		'desc'				=> 'Add the tel no. of the person overseeing this job here.',
		'type'				=> 'text',
		'cols'				=> 6,
		'show_on_frontend'	=> false
	);
	
	$fields[ 'contact_url' ] = array(
		'name'				=> 'Contact URL',
		'id'				=> '_wpbb_job_contact_url',
		'bb_field'			=> 'contact_url',
		'desc'				=> 'Add the URL for contact about this job.',
		'type'				=> 'text',
		'cols'				=> 6,
		'show_on_frontend'	=> false
	);
	
	/**
	 * add application fields
	 */
	 
	$fields[ 'application_email' ] = array(
		'name'				=> 'Application Email',
		'id'				=> '_wpbb_job_broadbean_application_email',
		'bb_field'			=> 'application_email',
		'desc'				=> 'This is the tracking email used to track applications for this job.',
		'type'				=> 'text',
		'readonly'			=> true,
		'cols'				=> 6,
		'show_on_frontend'	=> false
	);
	
	$fields[ 'application_url' ] = array(
		'name'				=> 'Application URL',
		'id'				=> '_wpbb_job_broadbean_application_url',
		'bb_field'			=> 'application_url',
		'desc'				=> 'This is the application url, used only if clients should not apply on this site.',
		'type'				=> 'text',
		'readonly'			=> true,
		'cols'				=> 6,
		'show_on_frontend'	=> false
	);
	
	/**
	 * add misc job fields
	 */
	
	$fields[ 'days_to_advertise' ] = array(
		'name'				=> 'Days to Advertise',
		'id'				=> '_wpbb_job_days_to_advertise',
		'bb_field'			=> 'days_to_advertise',
		'type'				=> 'text',
		'cols'				=> 4,
		'show_on_frontend'	=> false
	);
	
	$fields[ 'job_duration' ] = array(
		'name'				=> 'Job Duration',
		'id'				=> '_wpbb_job_duration',
		'bb_field'			=> 'job_duration',
		'type'				=> 'text',
		'cols'				=> 4,
		'show_on_frontend'	=> true
	);
	
	$fields[ 'job_startdate' ] = array(
		'name'				=> 'Job Start Date',
		'id'				=> '_wpbb_job_start_date',
		'bb_field'			=> 'job_startdate',
		'type'				=> 'text',
		'cols'				=> 4,
		'show_on_frontend'	=> true
	);
	
	return $fields;
	
}

add_filter( 'wpbb_job_fields', 'wpbb_add_default_fields', 10 );