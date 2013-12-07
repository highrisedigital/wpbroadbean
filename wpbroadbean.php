<?php
/*
Plugin Name: WP Broadbean
Plugin URI: 
Description: Integrates Broadbean Adcourier with WordPress.
Version: 0.1
Author: Mark Wilkinson
Author URI: http://markwilkinson.me
License: GPLv2 or later
*/

/* load custom post type & taxonomy functions */
require_once( dirname( __FILE__ ) . '/functions/posttypes-taxonomies.php' );

/* load the dashboard functions */
require_once( dirname( __FILE__ ) . '/functions/admin.php' );

/* load the metaboxes template file */
//require_once( dirname( __FILE__ ) . '/functions/metaboxes.php' );

/* load the shortcodes functions */
require_once( dirname( __FILE__ ) . '/functions/shortcodes.php' );

/***************************************************************
* Function wpbb_change_job_title_text()
* Changes the wordpress 'Enter title here' text for the job post
* type.
***************************************************************/
function wpbb_change_job_title_text( $title ){
     
	/* get the current screen we are viewing in the admin */
	$wpbb_screen = get_current_screen();
	
	/* if the current screen is our job post type */
	if( 'wpbb_job' == $wpbb_screen->post_type ) {
		
		/* set the new text for the title box */
		$title = 'Job Title';
		
	}
	
	/* return our new text */
	return $title;
}
 
add_filter( 'enter_title_here', 'wpbb_change_job_title_text' );

/***************************************************************
* Function wpbb_add_new_query_var()
* Adds a query var to wordpress so it knows what to do something
* when that query var is used.
***************************************************************/
function wpbb_add_new_query_var( $wpbb_public_query_vars ) {
    
    /* set a name for our new query var */
    $wpbb_public_query_vars[] = 'wpac';
    
    /* return the new name to the query_vars filter */
    return $wpbb_public_query_vars;
    
}

add_filter( 'query_vars', 'wpbb_add_new_query_var' );

/***************************************************************
* Function wpbb_adcourier_inbox_load()
* Loads the adcourier inbox template php file when the above
* query var is called. When a user navigates their browser to
* yoursite.com/?wpac=adcourier it loads page using the
* wp-adcourier-inbox.php template form this plugin folder.
***************************************************************/
function wpbb_adcourier_inbox_load() {
    
    /* get the query var we named above from the url */
    $wpbb_bb = get_query_var( 'wpac' );
    
    /* check that its value is equal to adcourier */
    if( $wpbb_bb == 'broadbean') {
        
        /* load the adcourier inbox file */
		require_once( dirname( __FILE__ ) . '/wpbb-inbox.php' );
        
        /* stop wordpress loading any further */
        exit;
    
    }
    
}

/* redirect the user to our adcourier inbox file when correct query var and value are inputted */
add_action( 'template_redirect', 'wpbb_adcourier_inbox_load' );

/***************************************************************
* Function wpbb_add_styles_scripts()
* Enqueues the scripts for the form validation only on posts
* were the shortcode has been added to the post content.
***************************************************************/
function wpbb_add_styles_scripts() {

	global $post;
	
	/* check the post variable is not empty */
	if( !empty( $post ) ) {
		
		/* check if we find our shortcode in the post content */
		if( stripos( $post->post_content, '[wpbb_applicationform') !== FALSE ) {
		
		/* register and enqueue the jwuery validate plugin */
		wp_enqueue_script( 'jquery' );
		wp_register_script( 'wpbb_jquery_validate' , plugins_url( '/scripts/jquery.validate.js', __FILE__ ), 'jquery' );  
		wp_register_script( 'wpbb_validate', plugins_url( '/scripts/wpbb_validate.js', __FILE__ ), 'wpbb_jquery_validate' );
		wp_enqueue_script( 'wpbb_jquery_validate' );
		wp_enqueue_script( 'wpbb_validate' );
		wp_register_style( 'wpbb_form_styles', plugins_url( '/css/form-style.css', __FILE__ ) );
		wp_enqueue_style( 'wpbb_form_styles' );
		
		} // if if shortcode detected
		
	} // end if have post varibale
				
}

add_action( 'wp_enqueue_scripts', 'wpbb_add_styles_scripts' );