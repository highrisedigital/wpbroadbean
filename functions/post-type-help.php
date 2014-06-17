<?php
/***************************************************************
* Function wpbb_job_post_type_help()
* Add help tab with information for jobs post type.
***************************************************************/
function wpbb_job_post_type_help() {

	/* check this is a job post */
	if( 'wpbb_job' != wpbb_get_current_admin_post_type() )
		return; 
	
	/* get the current screen information */
	$wpbb_screen = get_current_screen();
	
	/* save our adding a job content in a variable */
	$wpbb_adding_job_content = '<h3>Adding a Job</h3>';
	
	$wpbb_screen->add_help_tab(
		array(
			'id' => 'wpbb_job_adding_jobs',
			'title' => 'Adding a Job',
			'content' => $wpbb_adding_job_content,
		)
	);

}

add_action( 'admin_head', 'wpbb_job_post_type_help' );