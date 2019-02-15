<?php
/**
 * Builds the application email content.
 *
 * @package WP_Broadbean
 */

?>
<div class="wpbb-application-email-content">

	<?php

	// loop through the application email content data.
	foreach ( $data as $application_data ) {

		?>
		<div class="applicant-data">
			<span class="label"><?php echo esc_html( $application_data['label'] ); ?></span>: <span class="value"><?php echo esc_html( $application_data['value'] ); ?></span>
		</div>
		<?php

	}

	?>

</div>

<p class="credit"><a href="https://highrise.digital"><?php __( 'Email sent by the WP Broadbean plugin from Highirse Digital', 'wpbroadbean' ); ?></a></p>
