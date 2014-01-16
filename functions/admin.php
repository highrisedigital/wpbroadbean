<?php
/***************************************************************
* Function wpbb_change_job_title_text()
* Changes the wordpress 'Enter title here' text for the job post
* type.
***************************************************************/
function wpbb_add_admin_menu() {

	/* add the main page for SEN info */
	add_menu_page(
		'WP Broadbean', // page_title,
		'WP Broadbean', // menu_title,
		'edit_posts', // capability,
		'wp_broadbean_home', // menu_slug,
		'__return_false', // function,
		'div', // icon url
		'90' // position
	);
	
	/* add the sub page for the wpbb_job_type taxonomy */
	add_submenu_page(
		'wp_broadbean_home', // parent_slug,
		'Job Type', // page_title,
		'Job Type', // menu_title,
		'edit_others_posts', // capability,
		'edit-tags.php?taxonomy=wpbb_job_type' // menu_slug
	);
	
	/* add the sub page for the wpbb_job_category taxonomy */
	add_submenu_page(
		'wp_broadbean_home', // parent_slug,
		'Job Category', // page_title,
		'Job Category', // menu_title,
		'edit_others_posts', // capability,
		'edit-tags.php?taxonomy=wpbb_job_category' // menu_slug
	);
	
	/* add the sub page for the wpbb_job_location taxonomy */
	add_submenu_page(
		'wp_broadbean_home', // parent_slug,
		'Job Location', // page_title,
		'Job Location', // menu_title,
		'edit_others_posts', // capability,
		'edit-tags.php?taxonomy=wpbb_job_location' // menu_slug
	);
	
	/* add the sub page for the wpbb_job_location_tag taxonomy */
	add_submenu_page(
		'wp_broadbean_home', // parent_slug,
		'Job Location Tag', // page_title,
		'Job Location Tag', // menu_title,
		'edit_others_posts', // capability,
		'edit-tags.php?taxonomy=wpbb_job_location_tag' // menu_slug
	);
	
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
	
	/* if the current screen taxonomy is a SEN taxonomy */
	if( $wpbb_taxonomy == 'wpbb_job_location_tag' || $wpbb_taxonomy == 'wpbb_job_location' || $wpbb_taxonomy == 'wpbb_job_category' || $wpbb_taxonomy == 'wpbb_job_type') {
		
		/* set the parent file slug to the sen main page */
		$parent_file = 'wp_broadbean_home';
		
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
function wpbb_register_settings() {
	
	/* register a setting for the username */
	register_setting( 'wpbb_settings', 'wpbb_username' );
	
	/* register a setting for the password */
	register_setting( 'wpbb_settings', 'wpbb_password' );
	
}

add_action( 'admin_init', 'wpbb_register_settings' );

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
		
		?>
		
		<form method="post" action="options.php">
		
			<?php settings_fields( 'wpbb_settings' ); ?>
			
			<table class="form-table">
			
				<tbody>
					<tr valign="top">
						<th scope="row">
							<label for="wpbb_username">Username</label>
						</th>
						<td>
							<input type="text" name="wpbb_username" id="wpbb_username" value="<?php echo get_option( 'wpbb_username' ) ?>">
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="wpbb_password">Password</label>
						</th>
						<td>
							<input type="text" name="wpbb_password" id="wpbb_password" value="<?php echo get_option( 'wpbb_password' ) ?>">
						</td>
					</tr>
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