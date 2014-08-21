<?php
/****************************************************************
* Function wpbb_generate_email_header()
* Generate the header HTML for emails
* Must match the closing tags in wpbb_generate_email_header()
* Plugable functions which can be overidden
****************************************************************/
if( ! function_exists( 'wpbb_generate_email_header' ) ) {
	function wpbb_generate_email_header() {
	
		ob_start();
		?>
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html>
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
					<title>Latest Newsletter</title>
					<style type="text/css">
						.alignleft {
							float: left;
						}
						.alignright {
							float: right;
						}
						.aligncenter {
							display: block;
							margin-left: auto;
							margin-right: auto;
						}
						img {
							max-width: 100%;
						}
						</style>
					</head>
				<body style="font-family: Arial, Helvetica, Verdana, sans-serif;">
					<table>
						<tr>
							<td style="max-width: 650px; width:650px; padding: 15px 20px 15px 20px; border: 1px solid #363636; margin-left: auto; margin-right: auto; font-family: Arial, Helvetica, Verdana, sans-serif; font-size:12px;">
		
		<?php
		$wpbb_email_header = ob_get_clean();
		
		return $wpbb_email_header;
		
	}
}

/****************************************************************
* Function wpbb_generate_email_footer()
* Generate the footer HTML for emails
* Must match the opened tags in wpbb_generate_email_header()
* Plugable functions which can be overidden
****************************************************************/
if( ! function_exists( 'wpbb_generate_email_footer' ) ) {
	function wpbb_generate_email_footer() {
	
		ob_start();
		?>
							</td>
						</tr>
					</table>
				</body>
			</html>
		
		<?php
		$wpbb_email_footer = ob_get_clean();
		
		return $wpbb_email_footer;
	
	}
}

/****************************************************************
* Function wpbb_generate_email_content()
* Creates the content of an email by adding the HTML tags at the
* begining and the end of the body etc with the content in the
* middle.
* @param post content to put in email
* @return complete HTML email to send
****************************************************************/
function wpbb_generate_email_content( $wpbb_email_content ) {
	
	/* build email header */
	$wpbb_email_header = wpbb_generate_email_header();
	
	/* build email footer */
	$wpbb_email_footer = wpbb_generate_email_footer();
	
	/* build compltete email */
	$wpbb_complete_email = $wpbb_email_header . $wpbb_email_content . $wpbb_email_footer;
	
	return $wpbb_complete_email;
	
}