<?php
/**
 * Registers a new endpoint with WordPress to have the jobs sent to.
 *
 * @package WP_Broadbean
 */

/**
 * Adds a new endpoint in WordPress for hd-job-integrator.
 */
function wpbb_add_inbox_endpoint() {

	add_rewrite_endpoint(
		'wpbb/jobfeed', // this is the endpoint part of the url.
		EP_ROOT,
		'wpbb' // this is var that is set when the endpoint is reached.
	);

}

add_action( 'init', 'wpbb_add_inbox_endpoint' );

/**
 * Makes sure that the endpoint variable has a true value when set.
 *
 * @param array $vars The current query vars.
 */
function wpbb_fix_inbox_endpoint_requests( $vars ) {

	// if the endpoint var is set.
	if ( isset( $vars['wpbb'] ) ) {

		// make sure it is always equal to true.
		$vars['wpbb'] = true;

	} else { // if the endpoint var is not set.

		// make sure it always is unset completely and not empty.
		unset( $vars['wpbb'] );

	}

	// return the modified vars.
	return $vars;

}

add_filter( 'request', 'wpbb_fix_inbox_endpoint_requests' );

/**
 * When the endpoint for hd-job-integrator is visited load the correct template file.
 *
 * @param  string $template The current template WordPress will load from the theme.
 * @return string           The modified tempalte string WordPress will load.
 */
function wpbb_load_inbox_endpoint_template( $template ) {

	// check the endpoint var is set to true - if not pass back original template.
	if ( true !== get_query_var( 'wpbb' ) ) {
		return $template;
	}

	// check for a app push template file in the theme folder.
	if ( file_exists( STYLESHEETPATH . '/wpbb/inbox.php' ) ) {

		// load the file from the theme folder.
		return STYLESHEETPATH . '/wpbb/inbox.php';

	} else { // file not in theme folder.

		// load the timetables file from the plugin.
		return WPBB_LOCATION . '/templates/inbox.php';

	}

	return $template;

}

add_filter( 'template_include', 'wpbb_load_inbox_endpoint_template' );
