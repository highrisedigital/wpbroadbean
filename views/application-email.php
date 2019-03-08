<?php
/**
 * Builds the application email content.
 *
 * @package WP_Broadbean
 */

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php esc_html_e( 'New job application', 'wpbroadbean' ); ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<style>
		p { margin: 3px 0; }
	</style>
</head>

<body style="margin: 0; padding: 0;">
	
	<table class="wpbb-application-email-content" align="center" border="0" cellpadding="5" cellspacing="0" width="600" style="border-collapse: collapse;">

		<?php

		// loop through the application email content data.
		foreach ( $data as $application_data ) {

			?>
			<tr class="applicant-data">
				<td><p><span class="label"><?php echo esc_html( $application_data['label'] ); ?></span>: <span class="value"><?php echo esc_html( $application_data['value'] ); ?></span><p></td>
			</tr>
			<?php

		}

		?>

		<tr>
			<td>
				<p><a href="https://highrise.digital/broadbean-wordpress-integrations/"><?php esc_html_e( 'Broadbean integrated with WordPress by Highrise Digital', 'wpbroadbean' ); ?></a></p>
			</td>
		</tr>

	</table>
</body>

</html>
