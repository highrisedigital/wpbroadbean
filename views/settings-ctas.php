<?php
/**
 * Outputs the settings page ctas.
 *
 * @package WP_Broadbean
 */

?>

<div class="wpbb-cta-col" id="poststuff">
		
	<div class="wpbb-plugin-info postbox">
	
		<h2 class="hndle ui-sortable-handle wpbb-postbox-title"><?php esc_html_e( 'WP Broadbean information', 'wpbroadbean' ); ?></h3>
		<div class="inside">
			<p class="plugin-info">
				Version: <?php echo esc_html( get_option( 'wpbroadbean_version' ) ); ?>, written by <a href="https://highrise.digital"><?php esc_html_e( 'Highrise Digital', 'wpbroadbean' ); ?></a><br />
			</p>
			<p><?php printf( esc_html__( 'Your sites endpoint URL is: %s', 'wpbroadbean' ), '<code>' . esc_url( home_url( '/wpbb/jobfeed/' ) ) . '</code>' ); ?></p>
			<p><?php printf( esc_html__( 'Sample XML files can be found in the %s folder in the plugin root.', 'wpbroadbean' ), '<code>sample-xml</code>' ); ?></p>
		</div>
		
	</div>

	<div class="wpbb-cta-mailchimp postbox">

		<h2 class="hndle ui-sortable-handle wpbb-postbox-title"><?php esc_html_e( 'Subscribe for plugin updates', 'wpbroadbean' ); ?></h2>

		<div class="inside">

			<p><?php esc_html_e( 'Signup below to recieve plugin news, updates and security information.', 'wpbroadbean' ); ?></p>

			<!-- Begin Mailchimp Signup Form -->
			<div id="mc_embed_signup">
				<form action="https://digital.us17.list-manage.com/subscribe/post?u=8f84734f114d7816de2addb0d&amp;id=eb5dafa0ef" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
					<div id="mc_embed_signup_scroll">
						<div class="mc-field-group">
							<label for="mce-EMAIL">Email Address  <span class="asterisk">*</span></label>
							<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL" placeholder="<?php echo esc_attr( get_option( 'admin_email' ) ); ?>">
						</div>
						<p><a href="https://us17.campaign-archive.com/home/?u=8f84734f114d7816de2addb0d&id=eb5dafa0ef"><?php esc_html_e( 'View previous security emails sent to users', 'wpbroadbean' ); ?></a></p>
						<div id="mce-responses" class="clear">
							<div class="response" id="mce-error-response" style="display:none"></div>
							<div class="response" id="mce-success-response" style="display:none"></div>
						</div><!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
						<div style="position: absolute; left: -5000px;" aria-hidden="true">
							<input type="text" name="b_8f84734f114d7816de2addb0d_eb5dafa0ef" tabindex="-1" value="">
						</div>
						<p><small class="subscribe-terms"><?php esc_html_e( 'We only store the email address you enter below, will only use it to send you updates and news about this plugin. You can unsubscribe at any time simply by following the links in an email sent to you. Clicking subscribe will take you to our email marketing platform Mailchimp for conformation.', 'wpbroadbean' ); ?></small></p>
						<div class="clear">
							<input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button-primary">
						</div>
					</div>
				</form>
			</div>
			<!--End mc_embed_signup-->
		</div>

	</div>

	<div class="wpbb-cta-help postbox">

		<h2 class="hndle ui-sortable-handle wpbb-postbox-title"><?php esc_html_e( 'Need some help?', 'wpbroadbean' ); ?></h2>
		<div class="inside">
			<p><?php esc_html_e( 'If you are finding things a little tricky to setup, fear not. We are experienced at integrating WordPress sites with Broadbean and provide a fully managed integration service.', 'wpbroadbean' ); ?></p>
			<a class="button-primary" href="https://highrise.digital/services/integrate-broadbean-wordpress/"><?php esc_html_e( 'Find out more', 'wpbroadbean' ); ?></a>
		</div>

	</div>

	<div class="wpbb-cta-addons postbox">

		<h2 class="hndle ui-sortable-handle wpbb-postbox-title"><?php esc_html_e( 'WP Broadbean add-ons', 'wpbroadbean' ); ?></h2>
		<div class="inside">
			<a class="button-primary" href="https://highrise.digital/products/wpbroadbean-wordpress-plugin/"><?php esc_html_e( 'Find out about our available add-ons', 'wpbroadbean' ); ?></a>
		</div>

	</div>

</div>
