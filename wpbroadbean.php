<?php
/*
Plugin Name: WP Broadbean
Plugin URI: http://wpbroadbean.com
Description: Integrates Broadbean Adcourier with WordPress. This plugin allows jobs posted through Broadbean's Adcourier system to be sent to your WordPress website.
Version: 2.2.1
Author: Mark Wilkinson
Author URI: https://highrise.digital
License: GPLv2 or later
Text Domain: wpbroadbean
*/

/* exist if directly accessed */
if( ! defined( 'ABSPATH' ) ) exit;

/* define variable for path to this plugin file. */
define( 'WPBB_LOCATION', dirname( __FILE__ ) );

/* load required files & functions */
require_once( dirname( __FILE__ ) . '/functions/post-types.php' );
require_once( dirname( __FILE__ ) . '/functions/taxonomies.php' );
require_once( dirname( __FILE__ ) . '/functions/default-fields.php' );
require_once( dirname( __FILE__ ) . '/functions/email-functions.php' );
require_once( dirname( __FILE__ ) . '/functions/application-form.php' );
require_once( dirname( __FILE__ ) . '/functions/wpbb-functions.php' );
require_once( dirname( __FILE__ ) . '/functions/admin/admin-menus.php' );
require_once( dirname( __FILE__ ) . '/functions/admin/admin.php' );
require_once( dirname( __FILE__ ) . '/functions/admin/default-settings.php' );

/* check whether the metabox class already exists - and load it if not */
if( ! class_exists( 'CMB_Meta_Box' ) )
	require_once( dirname( __FILE__ ) . '/functions/metaboxes/custom-meta-boxes.php' );

/* load the metabox functions */
require_once( dirname( __FILE__ ) . '/functions/admin/metaboxes.php' );

/***************************************************************
* Function wpbb_add_new_query_var()
* Adds a query var to wordpress so it knows what to do something
* when that query var is used.
***************************************************************/
function wpbb_add_new_query_var( $wpbb_public_query_vars ) {
    
    /* set a name for our new query var */
    $wpbb_public_query_vars[] = 'wpbb';
    
    /* return the new name to the query_vars filter */
    return $wpbb_public_query_vars;
    
}

add_filter( 'query_vars', 'wpbb_add_new_query_var' );

/***************************************************************
* Function wpbb_adcourier_inbox_load()
* Loads the adcourier inbox template php file when the above
* query var is called. When a user navigates their browser to
* yoursite.com/?wpac=adcourier it loads page using the
* wpbb-inbox.php template form this plugin folder.
***************************************************************/
function wpbb_adcourier_inbox_load() {
    
    /* get the query var we named above from the url */
    $wpbb_bb = get_query_var( 'wpbb' );
    
    /* make the query var value filterable */
    $wpbb_query_var_value = apply_filters( 'wpbb_query_var_value', 'broadbean' );
    
    /* check that its value is equal to adcourier */
    if( $wpbb_bb == $wpbb_query_var_value ) {
    
    	/* check for a dashboard content file in the theme folder */
		if( file_exists( STYLESHEETPATH . '/wpbb/inbox.php' ) ) {

			/* load the dashboard content file from the theme folder */
			require_once STYLESHEETPATH . '/wpbb/inbox.php';

		} else {

			/* load the adcourier inbox file */
			require_once( dirname( __FILE__ ) . '/inbox.php' );

		}
        
        /* stop wordpress loading any further */
        exit;
    
    }
    
}

/* redirect the user to our adcourier inbox file when correct query var and value are inputted */
add_action( 'template_redirect', 'wpbb_adcourier_inbox_load' );

/**
 * Function wpbb_on_activation()
 * On plugin activation makes current user a wpbasis user and
 * sets an option to redirect the user to another page.
 */
function wpbb_on_activation() {
	
	/* set option to initialise the redirect */
	add_option( 'wpbb_activation_redirect', true );
	
}

register_activation_hook( __FILE__, 'wpbb_on_activation' );

/**
 * Function wpbb_activation_redirect()
 * Redirects user to the settings page for wp basis on plugin
 * activation.
 */
function wpbb_activation_redirect() {
	
	/* check whether we should redirect the user or not based on the option set on activation */
	if( true == get_option( 'wpbb_activation_redirect' ) ) {
		
		/* delete the redirect option */
		delete_option( 'wpbb_activation_redirect' );
		
		/* redirect the user to the wp basis settings page */
		wp_redirect( admin_url( 'admin.php?page=wpbb_broadbean_settings' ) );
		exit;
		
	}
	
}

add_action( 'admin_init', 'wpbb_activation_redirect' );

/***************************************************************
* Function wpbb_add_styles_scripts()
* Enqueues the scripts for the form validation only on posts
* were the shortcode has been added to the post content.
***************************************************************/
function wpbb_add_styles_scripts() {

	/* get the apply page from the settings */
	$apply_pageid = get_option( 'wpbb_apply_page_id' );
	
	/* check this is the apply page */
	if( is_page( $apply_pageid ) ) {
		
		/* enqueue the jquery validate plugin */
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'wpbb_jquery_validate', plugins_url( '/js/jquery.validate.js', __FILE__ ), 'jquery' );
		wp_enqueue_script( 'wpbb_validate', plugins_url( '/js/validate.js', __FILE__ ), 'wpbb_jquery_validate', array(), true ); 

		/* enqueue the application form styles */
		wp_enqueue_style( 'wpbb_form_styles', plugins_url( '/css/form-style.css', __FILE__ ) );
		
	} // end if have post varibale
				
}

add_action( 'wp_enqueue_scripts', 'wpbb_add_styles_scripts' );