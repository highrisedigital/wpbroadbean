<?php
/**
 * Functions hooked into various parts of the plugin.
 *
 * @package WP_Broadbean
 */

/**
 * Adds the description paragraph below each of the settings.
 *
 * @param  array $setting The array of settings args.
 * @param  string $value   The current value of this setting.
 */
function wpbb_add_setting_description( $setting, $value ) {

	// if this setting has a description to output.
	if ( isset( $setting['description'] ) && '' !== $setting['description'] ) {

		// if this is a checkbox field.
		if ( 'checkbox' !== $setting['input_type'] ) {

			// output the description, below the input.
			?>
			<p class="description"><?php echo wp_kses_post( $setting['description'] ); ?></p>
			<?php

		}
	}

}

add_action( 'wpbb_after_setting', 'wpbb_add_setting_description', 10, 2 );

/**
 * Adds introductory text before the plugin settings page gets output.
 */
function wpbb_add_settings_page_intro( $settings_group ) {

	// if this is not the settings setting group.
	if ( 'settings' !== $settings_group ) {
		return;
	}

	?>
	<p><?php esc_html_e( 'This is the plugin settings screen. You should complete these settings first.', 'wpbroadbean' ); ?></p>
	<?php

}

add_action( 'wpbb_before_settings_page_form', 'wpbb_add_settings_page_intro', 10, 1 );

/**
 * Display a warning to the user in the WP admin if they are not running over SSL.
 */
function wpbb_show_ssl_warning() {

	// if this page is not being served over ssl.
	if ( ! is_ssl() ) {

		// output a warning to the user.
		?>
		<div style="margin-left: 0;" class="message error">
			<p><?php esc_html_e( 'We have detected that your site is not being served using SSL (https). As the plugin allows candidates to submit personal data in the application form, we highly recommend your site uses SSL.', 'wpbroadbean' ); ?></p>
		</div>
		<?php

	}

}

add_action( 'wpbb_before_settings_page_form', 'wpbb_show_ssl_warning' );

/**
 * Adds the plugins calls to action on the settings page.
 */
function wpbb_add_settings_page_ctas( $settings_group ) {

	// if this is not the settings setting group.
	if ( 'wpbb_settings' !== $settings_group ) {
		return;
	}

	// get the cta view file.
	echo wpbb_load_view( 'settings-ctas' );

}

add_action( 'wpbb_after_settings_page_form', 'wpbb_add_settings_page_ctas', 10, 1 );

/**
 * If the user has selected in the settings - add the footer credit link.
 */
function wpbb_maybe_add_plugin_credit( $content ) {

	// if we are showing the footer credit.
	if ( true === wpbb_show_plugin_credit() ) {

		// if this is a job archive or job single page.
		if ( is_singular( wpbb_job_post_type_name() ) ) {

			$content = $content . '<p class="wpbb-plugin-credit"><small>' . sprintf( __( 'Broadbean integration by %s', 'wpbroadbean' ), '<a href="https://highrise.digital/?source=wpbb-plugin-credit">Highrise Digital</a>' ) . '</small></p>';

		}
	}

	return $content;

}

add_filter( 'the_content', 'wpbb_maybe_add_plugin_credit', 99 );

/**
 * If application tracking by url is active, append an apply link to the job post content.
 *
 * @param  string $content The current job post content.
 * @return string          The modified job post content.
 */
function wpbb_add_job_application_url( $content ) {

	// if the job application type is not a form.
	if ( 'url' !== wpbb_get_job_application_type() ) {
		return $content;
	}

	// if this is not a single job view.
	if ( ! is_singular( wpbb_job_post_type_name() ) ) {
		return $content;
	}

	// get the job application url.
	global $post;
	$application_url = wpbb_get_job_applicant_tracking_url( $post->ID );

	// return the content with application url appended.
	return $content . '<a target="_blank" class="wpbb-application-url" href="' . esc_url( $application_url ) . '">' . esc_html__( 'Apply Now', 'wpbroadbean' ) . '</a>';

}

add_filter( 'the_content', 'wpbb_add_job_application_url', 20, 1 );

/**
 * Adds the job field descriptions under the fields.
 *
 * @param  array $field The field array.
 */
function wpbb_add_job_field_descriptions( $field ) {

	?>
	<p class="wpbb-field__description"><?php echo esc_html( $field['desc'] ); ?></p>
	<?php

}

add_action( 'wpbb_after_field_input', 'wpbb_add_job_field_descriptions' );

/**
 * Prevents attachment pages from showing a link to a an attachment URL for application attachments.
 *
 * @param  string $attachment_link The attachment link to append to the posts content.
 * @return string                  The modified attachment link.
 */
function wpbb_remove_application_attachment_content_links( $attachment_link ) {

	global $post;

	// get the attachment post object.
	$attachment = get_post( $post->ID );

	// if this attachment does not have a parent.
	if ( 0 === $attachment->post_parent ) {
		return $attachment_link;
	}

	// if the parent post type if not an application.
	if ( 'wpbb_application' !== get_post_type( $attachment->post_parent ) ) {
		return $attachment_link;
	}

	// return the attachment link.
	return '';

}

add_filter( 'prepend_attachment', 'wpbb_remove_application_attachment_content_links', 10, 1 );

/**
 * Outputs the job data - meta and terms.
 *
 * @param  string $content The current job post content.
 * @return string          The modified job post content.
 */
function wpbb_ouput_job_meta_data( $content ) {

	// if this is a not a singular job post.
	if ( ! is_singular( wpbb_job_post_type_name() ) ) {
		return $content;
	}

	// if we should not be showing this then just return the unmodifed content.
	if ( true === wpbb_hide_job_data_output() ) {
		return $content;
	}

	global $post;

	// get the job meta data.
	$data = wpbb_get_job_meta_data(
		array(
			'post_id' => $post->ID,
		)
	);

	// return the post content along with the meta data.
	return $content . $data;

}

add_filter( 'the_content', 'wpbb_ouput_job_meta_data', 10, 1 );

/**
 * Alters the post enter title here text for jobs.
 *
 * @param  string $title The current title placeholder text.
 * @return string        The new title placeholder text.
 */
function wpbb_change_job_post_title_text( $title ) {

	// get the current screen we are viewing in the admin.
	$screen = get_current_screen();

	// if the current screen is our job post type.
	if ( wpbb_job_post_type_name() === $screen->post_type ) {

		// set the new text for the title box.
		$title = __( 'Job Title', 'wpbroadbean' );

	}

	/* return our new text */
	return apply_filters( 'wpbb_post_title_text', $title, $screen );

}

add_filter( 'enter_title_here', 'wpbb_change_job_post_title_text' );
