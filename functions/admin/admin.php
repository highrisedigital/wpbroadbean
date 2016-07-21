<?php
/**
 * function wpbb_admin_styles()
 * outputs css for the admin pages
 */
function wpbb_admin_styles() {
	?>
	<style>
		.wpbb-postbox-inner { padding: 6px 10px 16px; }
		.wpbb-postbox-title { border-bottom:1px solid #eee }
		.plugin-info { line-height: 2; }
	</style>
	<?php
}

add_action( 'admin_head', 'wpbb_admin_styles' );

/**
 * Function wpbb_add_admin_menu()
 * adds the wpbroadbean admin menus under a parent menu
 */
function wpbb_add_admin_menu() {

	/* add the main page for wpbroadbean info */
	add_menu_page(
		apply_filters( 'wpbb_admin_page_title', 'WP Broadbean' ), // page_title,
		apply_filters( 'wpbb_admin_page_title', 'WP Broadbean' ), // menu_title,
		'edit_posts', // capability,
		'wp_broadbean_home', // menu_slug,
		'__return_false', // function,
		'dashicons-businessman', // icon url
		'90' // position
	);

}

add_action( 'admin_menu', 'wpbb_add_admin_menu' );

/**
 * function wpbb_add_admin_sub_menus()
 * adds the plugins sub menus under the wpbroadbean main admin menu item
 * filterable by devs using the wpbb_admin_sub_menus filter
 */
function wpbb_add_admin_sub_menus() {
	
	/* filterable array of menus to add */
	$wpbb_admin_sub_menus = apply_filters(
		'wpbb_admin_sub_menus',
		array()
	);
	
	/* check we have sub menus to add */
	if( ! empty( $wpbb_admin_sub_menus ) ) {

		/* loop through each sub menu to add */
		foreach( $wpbb_admin_sub_menus as $submenu ) {
			
			/* check whether a callback is passed */
			if( empty( $submenu[ 'callback' ] ) )
				$submenu[ 'callback' ] = false;
			
			/* add the sub page for the wpbb_job_category taxonomy */
			add_submenu_page(
				'wp_broadbean_home', // parent_slug,
				$submenu[ 'label' ], // page_title,
				$submenu[ 'label' ], // menu_title,
				$submenu[ 'cap' ], // capability,
				$submenu[ 'slug' ], // menu slug,
				$submenu[ 'callback' ] // callback function for the pages content
			);
			
		} // end loop through menus to add
		
	} // end if have sub menus to add */
		
}

add_action( 'admin_menu', 'wpbb_add_admin_sub_menus' );

/**
 * function wpbb_tax_menu_correction()
 * Sets the correct parent item for the sen custom taxonomies
 */
function wpbb_tax_menu_correction( $parent_file ) {
	
	global $current_screen;
	
	/* get the taxonomy of the current screen */
	$wpbb_taxonomy = $current_screen->taxonomy;

	$taxonomies = wpbb_get_registered_taxonomies();

	foreach ($taxonomies as $taxonomy) {
		/* if the current screen taxonomy is a SEN taxonomy */
		if( $wpbb_taxonomy == $taxonomy['taxonomy_name'] ) {
			
			/* set the parent file slug to the sen main page */
			$parent_file = 'wp_broadbean_home';
			
		}
	}
		
	/* return the new parent file */	
	return $parent_file;
	
}
add_action( 'parent_file', 'wpbb_tax_menu_correction' );

/**
 * Function wpbb_register_settings()
 * Register the settings for this plugin. Just a username and a
 * password for authenticating.
 */
function wpbb_register_default_settings() {

	/* build array of setttings to register */
	$wpbb_registered_settings = apply_filters( 'wpbb_registered_settings', array() );
	
	/* loop through registered settings array */
	foreach( $wpbb_registered_settings as $wpbb_registered_setting ) {
		
		/* register a setting for the username */
		register_setting( 'wpbb_settings', $wpbb_registered_setting );
		
	}
		
}

add_action( 'admin_init', 'wpbb_register_default_settings' );

/**
 * Function wpbb_admin_page_content()
 * Builds the content for the admin settings page.
 */
function wpbb_settings_page_content() {

	?>
	
	<div class="wrap">
		
		<?php screen_icon( 'options-general' ); ?>
		<h2><?php echo apply_filters( 'wpbb_admin_settings_page_title', 'WP Broadbean Settings' ); ?></h2>
		
		<form method="post" action="options.php">
			
			<div id="poststuff">
				
				<div id="post-body" class="metabox-holder columns-2">
					
					<div class="right-column postbox-container" id="postbox-container-1">
						
						<div class="column-inner">
							
							<?php
							
								/* do before settings page action */
								do_action( 'wpbb_settings_page_right_column' );
							
							?>
							
						</div><!-- // column-inner -->
						
					</div><!-- // postbox-contaniner -->
					
					<div class="left-column postbox-container" id="postbox-container-2">
						
						<div class="column-inner">
							
							<?php
								
								/* output settings field nonce action fields etc. */
								settings_fields( 'wpbb_settings' );
		
								/* do before settings page action */
								do_action( 'wpbb_before_settings_page' );
										
								/* setup an array of settings */
								$wpbb_settings = apply_filters(
									'wpbb_settings_output', 
									array()
								);
							
							?>
			
							<table class="form-table">
							
								<tbody>
								
									<?php
									
										/* loop through the settings array */
										foreach( $wpbb_settings as $wpbb_setting ) {
											
											?>
											
											<tr valign="top">
												<th scope="row">
													<label for="wpbb_username"><?php echo $wpbb_setting[ 'label' ]; ?></label>
												</th>
												<td>
													<?php
														switch( $wpbb_setting[ 'type' ] ) {
														    										    
														    /* if the setting is a select input */
														    case 'select' :
														        
														        ?>
														    	<select name="<?php echo $wpbb_setting[ 'name' ]; ?>" id="<?php echo $wpbb_setting[ 'name' ]; ?>">
														    	
														    	<?php
				
														    	/* get the setting options */
														    	$wpbb_setting_options = $wpbb_setting[ 'options' ];
				
														        /* loop through each option */
														        foreach( $wpbb_setting_options as $wpbb_setting_option ) {
				
															        ?>
															        <option value="<?php echo esc_attr( $wpbb_setting_option[ 'value' ] ); ?>" <?php selected( get_option( $wpbb_setting[ 'name' ] ), $wpbb_setting_option[ 'value' ] ); ?>><?php echo $wpbb_setting_option[ 'name' ]; ?></option>
																	<?php
				
														        }
				
														        ?>
														    	</select>
														        <?php
														        if( $wpbb_setting[ 'description' ] != '' ) {
																	?>
																	<p class="description"><?php echo $wpbb_setting[ 'description' ]; ?></p>
																	<?php
																}
														        
														        /* break out of the switch statement */
														        break;
														    
														    /* if the setting is a wysiwyg input */
														    case 'wysiwyg' :
														    	
														    	/* set some settings args for the editor */
														    	$wpbb_editor_settings = array(
														    		'textarea_rows' => $wpbb_setting[ 'textarea_rows' ],
														    		'media_buttons' => $wpbb_setting[ 'media_buttons' ],
														    	);
				
														    	/* get current content for the wysiwyg */
														    	$wpbb_wysiwyg_content = get_option( $wpbb_setting[ 'name' ] );
																
														    	/* display the wysiwyg editor */
														    	wp_editor( $wpbb_wysiwyg_content, $wpbb_setting[ 'name' ], $wpbb_editor_settings );
														    	
														    	/* break out of the switch statement */
														    	break;
														        
														    default :
														       
														       ?>
																<input type="text" name="<?php echo $wpbb_setting[ 'name' ]; ?>" id="<?php echo $wpbb_setting[ 'name' ]; ?>" class="regular-text" value="<?php echo get_option( $wpbb_setting[ 'name' ] ) ?>" />
																<?php
																if( $wpbb_setting[ 'description' ] != '' ) {
																	?>
																	<p class="description"><?php echo $wpbb_setting[ 'description' ]; ?></p>
																	<?php
																}
																
														} // end switch statement
														
													?>
													
												</td>
				
											</tr>
											
											<?php
										}
									
									?>
									
								</tbody>
								
							</table>
							
							<p class="submit">
								<input type="submit" name="submit" id="submit" class="button-primary" value="Save Changes">
							</p>
							
						</div><!-- // column-inner -->
						
					</div><!-- // postbox-container -->
					
				</div><!-- // post-body -->
				
			</div><!-- // poststuff -->
			
		</form>
		
	</div><!-- // wrap -->
	
	<?php
	
	/* do after settings page action */
	do_action( 'wpbb_after_settings_page' );
	
}

/**
 * function wpbb_settings_page_cta()
 * adds intro text on the settings page
 */
function wpbb_settings_page_cta() {
	
	?>
	
	<div class="wpbb-cta">
		
		<p>Edit the WP Broadbean plugin settings below:</p>
		
	</div>
	
	<?php
	
}

add_action( 'wpbb_before_settings_page', 'wpbb_settings_page_cta', 10 );

/**
 *
 */
function wpbb_settings_page_ctas() {
	
	/* get this plugins data - such as version, author etc. */
	$data = get_plugin_data(
		WPBB_LOCATION . '/wpbroadbean.php',
		false // no markup in return
	);

	?>
	
	<div class="postbox">
		
		<h3 class="wpbb-postbox-title"><?php echo esc_html( $data[ 'Name' ] ); ?></h3>
		
		<div class="wpbb-postbox-inner">
			<p class="plugin-info">
				Version: <?php echo esc_html( $data[ 'Version' ] ); ?><br />
				Written by: <a href="<?php echo esc_url( $data[ 'AuthorURI' ] ); ?>"><?php echo esc_html( $data[ 'AuthorName' ] ); ?></a><br />
				Website: <a href="http://wpbroadbean.com">WP Broadbean Plugin</a>
			</p>
			<p>
				If you find WP Broadbean useful then please <a href="https://wordpress.org/support/view/plugin-reviews/wpbroadbean">rate it on the plugin repository</a>.
			</p>
		</div>
		
	</div>
	
	<div class="postbox">
		
		<h3 class="wpbb-postbox-title">WP Broadbean Assist</h3>
		
		<div class="wpbb-postbox-inner">
			
			<p>WP Broadbean Assist provides you with a fully managed Broadbean integration with WordPress starting from &pound;750.</p>
			<a class="button button-primary button-large" href="http://wpbroadbean.com/assist/">Integrate Broadbean Now</a>
			
		</div>
		
	</div>
	
	<?php
		
}

add_action( 'wpbb_settings_page_right_column', 'wpbb_settings_page_ctas' );

/**
 * Function wpbb_change_title_text()
 * Changes the wordpress 'Enter title here' text for the job post
 * type.
 */
function wpbb_change_title_text( $title ){
     
	/* get the current screen we are viewing in the admin */
	$wpbb_screen = get_current_screen();

	/* if the current screen is our job post type */
	if( wpbb_job_post_type_name() == $wpbb_screen->post_type ) {
		
		/* set the new text for the title box */
		$title = 'Job Title';
	
	/* if the current screen is our job application post type */
	} elseif( 'wpbb_application' == $wpbb_screen->post_type ) {
		
		/* set the new text for the title box */
		$title = 'Applicant Name';

	}
	
	/* return our new text */
	return apply_filters( 'wpbb_post_title_text', $title, $wpbb_screen );
	
}
 
add_filter( 'enter_title_here', 'wpbb_change_title_text' );

/**
 * Function wpbb_job_post_editor_content()
 * Pre-fills the post editor on jobs with instructional text.
 */
function wpbb_job_post_editor_content( $content ) {
		
	/* check we are on the job post type */
	if( wpbb_job_post_type_name() != wpbb_get_current_post_type() )
		return;
	
	$content = "Replace this text with the long description of the job.";

	return apply_filters( 'wpbb_post_editor_text', $content );
}

add_filter( 'default_content', 'wpbb_job_post_editor_content' );

/**
 * Function wpbb_get_current_admin_post_type()
 * Returns the post type of a post in the admin.
 */
function wpbb_get_current_admin_post_type() {
  
	global $post, $typenow, $current_screen;
	
	/* we have a post so we can just get the post type from that */
	if ( $post && $post->post_type )
		return $post->post_type;
	
	/* check the global $typenow - set in admin.php */
	elseif( $typenow )
		return $typenow;
	
	/* check the global $current_screen object - set in sceen.php */
	elseif( $current_screen && $current_screen->post_type )
		return $current_screen->post_type;
	
	/* lastly check the post_type querystring */
	elseif( isset( $_REQUEST[ 'post_type' ] ) )
		return sanitize_key( $_REQUEST[ 'post_type' ] );
	
	/* we do not know the post type! */
	return null;
  
}

/**
 * Function wpbb_job_short_description_meta_box()
 * Change html output of the excerpt box, removing the paragraph
 * of instruction text.
 */
function wpbb_job_short_description_meta_box( $post ) {
	
	?>
	<label class="screen-reader-text" for="excerpt"><?php _e('Excerpt') ?></label>
	<textarea rows="1" cols="40" name="excerpt" id="excerpt"><?php echo $post->post_excerpt; // textarea_escaped ?></textarea>
	<?php
	
}

/**
 * Function wpbb_excerpt_box_title()
 * Change title of the excerpt metabox to short description.
 */
function wpbb_excerpt_box_title() {
	
	/* check this is a job post */
	if( wpbb_job_post_type_name() != wpbb_get_current_admin_post_type() )
		return;
		
	/* remove the excerpt metabox */
	remove_meta_box( 'postexcerpt', wpbb_job_post_type_name(), 'side' );
	
	/* add the metabox back with a different title */
	add_meta_box( 'postexcerpt', __( 'Short Description' ), 'wpbb_job_short_description_meta_box', wpbb_job_post_type_name(), 'normal', 'high' );
	
}

add_action( 'do_meta_boxes',  'wpbb_excerpt_box_title', 99 );
