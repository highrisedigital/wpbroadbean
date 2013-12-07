<?php
/***************************************************************
* Function wpbb_change_job_title_text()
* Changes the wordpress 'Enter title here' text for the job post
* type.
***************************************************************/
function wpbb_add_admin_menu() {
	add_submenu_page(
		'options-general.php',
		'WP Adcourier',
		'WP Adcourier',
		'manage_options',
		'wp-adcourier',
		'wpbb_admin_page_content'
	);
}

add_action( 'admin_menu', 'wpbb_add_admin_menu' );

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
function wpbb_admin_page_content() {

	?>
	
	<div class="wrap">
		
		<?php screen_icon( 'options-general' ); ?>
		<h2>WP AdCourier Settings</h2>
		
		<p>Welcome to the Broadbean (AdCourier) settings page. To find out more about this advert distribution tool please <a href="http://www.broadbean.com/multiposting.html">click here</a>.</p>
		
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
	
}