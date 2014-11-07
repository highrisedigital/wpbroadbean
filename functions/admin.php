<?php
/***************************************************************
* Function wpbb_change_job_title_text()
* Changes the wordpress 'Enter title here' text for the job post
* type.
***************************************************************/
function wpbb_add_admin_menu() {

	/* add the main page for wpbroadbean info */
	add_menu_page(
		'WP Broadbean', // page_title,
		'WP Broadbean', // menu_title,
		'edit_posts', // capability,
		'wp_broadbean_home', // menu_slug,
		'__return_false', // function,
		'dashicons-businessman', // icon url
		'90' // position
	);
	
	/**
	 * Register Admin pages for all Taxonomies
	 */
	$taxonomies = wpbb_get_registered_taxonomies();
	foreach ($taxonomies as $taxonomy) {
		/* add the sub page for the wpbb_job_category taxonomy */
		add_submenu_page(
			'wp_broadbean_home', // parent_slug,
			str_replace( 'Job ', '', $taxonomy['plural'] ), // page_title,
			str_replace( 'Job ', '', $taxonomy['singular'] ), // menu_title,
			'edit_others_posts', // capability,
			'edit-tags.php?taxonomy=' . $taxonomy['taxonomy_name'] // menu_slug
		);
	}
			
	/* add the settings page sub menu item */
	add_submenu_page(
		'wp_broadbean_home',
		'Settings',
		'Settings',
		'manage_options',
		'wp_broadbean_settings',
		'wpbb_setings_page_content'
	);
	
}

add_action( 'admin_menu', 'wpbb_add_admin_menu' );

/*****************************************************************
* function wpbb_tax_menu_correction()
* Sets the correct parent item for the sen custom taxonomies
*****************************************************************/
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

/***************************************************************
* Function wpbb_register_settings()
* Register the settings for this plugin. Just a username and a
* password for authenticating.
***************************************************************/
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

/***************************************************************
* Function wpbb_admin_page_content()
* Builds the content for the admin settings page.
***************************************************************/
function wpbb_setings_page_content() {

	?>
	
	<div class="wrap">
		
		<?php screen_icon( 'options-general' ); ?>
		<h2>WP Broadbean Settings</h2>
		
		<?php
		
			/* do before settings page action */
			do_action( 'wpbb_before_settings_page' );
		
			/* build filterable opening text */
			$wpbb_admin_paragraph = '<p>Welcome to the Broadbean (AdCourier) settings page. To find out more about this advert distribution tool please <a href="http://www.broadbean.com/multiposting.html">click here</a>.</p>';
			
			$wpbb_admin_paragraph .= '<h3>Steps to Get Started</h3>';
			
			$wpbb_admin_paragraph .= '
				<ol>
					<li>Setup your <a href="' . admin_url( 'edit-tags.php?taxonomy=wpbb_job_type' ) .'">job type terms here</a></li>
					<li>Add your <a href="' . admin_url( 'edit-tags.php?taxonomy=wpbb_job_category' ) .'">job categories here</a></li>
					<li>Input a list of your <a href="' . admin_url( 'edit-tags.php?taxonomy=wpbb_job_location' ) .'">job locations here</a></li>
					<li>Set a username and password below, to use for the integration with Broadbean, making sure you save the changes.</li>
				</ol>
			';
			
			echo apply_filters( 'wpbb_admin_paragraph', $wpbb_admin_paragraph );
			
			/* setup an array of settings */
			$wpbb_settings = apply_filters( 'wpbb_settings_output', array() );
		
		?>
		
		<form method="post" action="options.php">
		
			<?php settings_fields( 'wpbb_settings' ); ?>
			
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
			
		</form>
		
	</div><!- // wrap -->
	
	<?php
	
	/* do after settings page action */
	do_action( 'wpbb_after_settings_page' );
	
}

/***************************************************************
* Function wpbb_change_title_text()
* Changes the wordpress 'Enter title here' text for the job post
* type.
***************************************************************/
function wpbb_change_title_text( $title ){
     
	/* get the current screen we are viewing in the admin */
	$wpbb_screen = get_current_screen();

	/* if the current screen is our job post type */
	if( 'wpbb_job' == $wpbb_screen->post_type ) {
		
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

/***************************************************************
* Function wpbb_job_post_editor_content()
* Pre-fills the post editor on jobs with instructional text.
***************************************************************/
function wpbb_job_post_editor_content( $content ) {
		
	/* check we are on the job post type */
	if( 'wpbb_job' != wpbb_get_current_post_type() )
		return;
	
	$content = "Replace this text with the long description of the job.";

	return apply_filters( 'wpbb_post_editor_text', $content );
}

add_filter( 'default_content', 'wpbb_job_post_editor_content' );

/***************************************************************
* Function wpbb_get_current_admin_post_type()
* Returns the post type of a post in the admin.
***************************************************************/
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

/***************************************************************
* Function wpbb_job_short_description_meta_box()
* Change html output of the excerpt box, removing the paragraph
* of instruction text.
***************************************************************/
function wpbb_job_short_description_meta_box( $post ) {
	
	?>
	<label class="screen-reader-text" for="excerpt"><?php _e('Excerpt') ?></label>
	<textarea rows="1" cols="40" name="excerpt" id="excerpt"><?php echo $post->post_excerpt; // textarea_escaped ?></textarea>
	<?php
	
}

/***************************************************************
* Function wpbb_excerpt_box_title()
* Change title of the excerpt metabox to short description.
***************************************************************/
function wpbb_excerpt_box_title() {
	
	/* check this is a job post */
	if( 'wpbb_job' != wpbb_get_current_admin_post_type() )
		return;
		
	/* remove the excerpt metabox */
	remove_meta_box( 'postexcerpt', 'my_custom_post_type', 'side' );
	
	/* add the metabox back with a different title */
	add_meta_box( 'postexcerpt', __( 'Short Description' ), 'wpbb_job_short_description_meta_box', 'wpbb_job', 'normal', 'high' );
	
}

add_action( 'do_meta_boxes',  'wpbb_excerpt_box_title', 99 );