<?php
/*
* this file create the shortcode for the application form.
*/

/****************************************************************
* Function wpbb_generate_email_content()
* Creates the content of an email by adding the HTML tags at the
* begining and the end of the body etc with the content in the
* middle.
* @param post content to put in email
* @return complete HTML email to send
****************************************************************/
function wpbb_generate_email_content( $wpbb_full_post_content ) {
	
	$wpbb_email_top = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Latest Newsletter</title><style type="text/css">.alignleft {float: left;}.alignright {float: right;}.aligncenter {display: block;margin-left: auto;margin-right: auto;}img {max-width: 100%;}</style></head><body style="font-family: Arial, Helvetica, Verdana, sans-serif;"><table><tr><td style="max-width: 650px; width:650px; padding: 15px 20px 15px 20px; border: 1px solid #363636; margin-left: auto; margin-right: auto; font-family: Arial, Helvetica, Verdana, sans-serif; font-size:12px;">';
	
	$wpbb_email_bottom = '</tr></td</table></body></html>';
	
	$wpbb_complete_email = $wpbb_email_top . $wpbb_full_post_content . $wpbb_email_bottom;
	
	return $wpbb_complete_email;
	
}


/***************************************************************
* Function wpbb_application_form_shortcode()
* Creates the shortcode for adding the job application form.
***************************************************************/
function wpbb_application_form_shortcode() {

	/* set a global variable to show shortcode is running */
	global $wpbb_shortcode_page;
	$wpbb_shortcode_page = true;
	
	/* enqueue the style for forms when shortcode is added */
	wp_enqueue_style( 'wpbb_form_styles' );
	
	/* check for a job id and exit if not found */
	if( !empty( $_GET[ 'job_id' ] ) ) {
		
		/* if the form has not yet been submitted */
		if( !isset( $_POST[ 'wpbb_form_submitted' ] ) ) {
			
			/* get the job reference from the url query string */
			$wpbb_get_job_reference = $_GET[ 'job_id' ];
					
			/* setup some query args to query posts with this reference */
			$wpbb_posts_args = array(
				'post_type' => 'wpbb_job',
				'post_status'=> 'publish',
				'meta_key' => '_wpbb_job_reference',
				'meta_value' => $wpbb_get_job_reference,
				'relation' => 'AND'
			);
			
			/* get posts according to args above */
			$wpbb_posts = new WP_Query( $wpbb_posts_args );
			
			/* set an array to store the references of posts found */
			$wpbb_references = array();
			
			/* check there is a post with this job reference */
			if( $wpbb_posts->have_posts() ) {
				
				/* set the job reference now we know we have posts, to use in the form */
				$wpbb_job_reference = $_GET[ 'job_id' ];
				
				/* loop through each post returned */
				while( $wpbb_posts->have_posts() ) : $wpbb_posts->the_post();
				
					/* get the job title */
					$wpbb_job_title = get_the_title();
					
					/* get the job contact email address */
					$wpbb_job_contact_email = get_post_meta( get_the_ID(), 'wpbb_job_contact_email', true );
					
					/* get the bb job application email */
					$wpbb_job_application_email = get_post_meta( get_the_ID(), 'wpbb_job_application_email', true );
									
					/* get the url/permalink of the job */
					$wpbb_permalink = get_permalink();
					
					/* add this reference to the array */
					$wpbb_references[] = get_post_meta( get_the_ID(), 'wpbb_job_reference', true );
				
				/* end loop */
				endwhile;
				
			}
			
			/* only show the form if we have a query var for the job reference which exists in the posts returned */
			if( in_array( $_GET[ 'job_id' ], $wpbb_references ) ) {
			
				?>
					
				<form enctype="multipart/form-data" id="wpbb_application_form" method="post" action="">
		
					<div class="wpbb_input">
					
						<label for="wpbb_job_title" for="name">Job Title</label>
						
						<input class="wpbb_text_input" type="text" name="wpbb_job_title" id="wpbb_job_title" value="<?php echo esc_html( $wpbb_job_title ); ?>" tabindex="1">
					
					</div>
					
					<div class="wpbb_input">
					
						<label for="wpbb_job_reference">Job Reference/ID</label>
						
						<input class="wpbb_text_input" type="text" name="wpbb_job_reference" id="wpbb_job_reference" value="<?php echo $wpbb_job_reference; ?>" tabindex="2">
					
					</div>
					
					<div class="wpbb_input">
					
						<label for="wpbb_job_url">Job URL</label>
						
						<input class="wpbb_text_input" type="text" name="wpbb_job_url" id="wpbb_job_url" value="<?php echo $wpbb_permalink; ?>" tabindex="2">
					
					</div>
					
					<div class="wpbb_input">
					
						<label for="wpbb_name" class="require">Name</label>
						
						<input class="wpbb_text_input" type="text" name="wpbb_name" id="wpbb_name" value="" tabindex="3">
						
						<label class="error" for="wpbb_name">Please enter your name.</label>
					
					</div>
					
					<div class="wpbb_input">
					
						<label for="wpbb_email" class="require">Email</label>
						
						<input class="wpbb_text_input" type="text" name="wpbb_email" id="wpbb_email" value="" tabindex="4">
						
						<label class="error" for="wpbb_email">Please enter your email address.</label>
						
						<p class="wpbb_description">Please enter a valid email address as this will be used to contact you on.</p>
					
					</div>
					
					<div class="wpbb_input">
					
						<label for="wpbb_tel" class="require">Tel Number</label>
						
						<input class="wpbb_text_input" type="text" name="wpbb_tel" id="wpbb_tel" value="" tabindex="5">
						
						<label class="error" for="wpbb_tel">Please enter your contact telephone number.</label>
					
					</div>
					
					<div class="wpbb_input">				
					
						<label for="wpbb_file">Attach a CV</label>
						
						<input type="file" name="wpbb_upload" />
						
						<p class="wpbb_description">Please attach your CV on either .doc, .docx, .pdf, or .rtf file format.</p>
					
					</div>
					
					<input class="wpbb_hidden" type="hidden" name="wpbb_contact_email" id="wpbb_contact_email" value="<?php echo $wpbb_job_contact_email; ?>" tabindex="6">
					
					<input class="wpbb_hidden" type="hidden" name="wpbb_application_email" id="wpbb_contact_email" value="<?php echo $wpbb_job_application_email; ?>" tabindex="6">
					
					<input class="wpbb_hidden" type="hidden" name="wpbb_form_submitted" id="wpbb_form_submitted" value="1" tabindex="7">
					
					<div class="wpbb_submit">
						
						<input type="submit" value="Submit">
					
					</div>
				
				</form>
				
				<?php
			
			/* no job reference query var is present */
			} else {
				
				/* echo out an error message */
				echo apply_filters( 'wpbb_application_error_message', '<p class="message error">Oops! No job reference was detected or the job reference is not valid. Please go back to the job you are applying for and click the apply button.</p>' );
				
			} // end check we have job reference query var */
		
		/* form has been submitted */
		} else {
			
			/* check that the wp_handle_upload function is loaded */		
			if ( ! function_exists( 'wp_handle_upload' ) )
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
			
			/* get the uploaded file information */
			$pxjn_uploaded_file = $_FILES[ 'wpbb_upload' ];
			
			/* set overides to make it work */
			$pxjn_upload_overrides = array( 'test_form' => false );
			
			/* upload the file to wp uploads dir */
			$pxjn_moved_file = wp_handle_upload( $pxjn_uploaded_file, $pxjn_upload_overrides );
			
			/* get file type */
			$pxjn_filetype = wp_check_filetype( $pxjn_moved_file[ 'url' ], null );
			
			/* generate array of allowed mime types */
			$pxjn_allowed_mime_types = array(
				'application/msword',
				'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
				'application/pdf',
				'application/rtf',
				'text/plain'
			);
			
			/* check uploaded file is in allowed mime types array */
			if( ! in_array( $pxjn_filetype[ 'type' ], $pxjn_allowed_mime_types) )
				die( __( "Sorry, this file type is not alllowed. Please go back and try again." ) );
			
			/* wp upload directory */
			$pxjn_wp_upload_dir = wp_upload_dir();
			
			/* setup the attachment data */
			$pxjn_attachment = array(
			     'post_mime_type' => $pxjn_filetype[ 'type' ],
			     'post_title' => preg_replace('/\.[^.]+$/', '', $pxjn_uploaded_file[ 'name' ]),
			     'post_content' => '',
			     'guid' => $pxjn_wp_upload_dir[ 'url' ] . '/' . $pxjn_uploaded_file[ 'name' ],
			     'post_status' => 'inherit'
			);
				
			/* add filter below to allow / force mail to send as html */
			add_filter( 'wp_mail_content_type', create_function( '', 'return "text/html"; ' ) );
			
			/* create the content of the email by getting the posted values */
			$wpbb_posted_job_title = $_POST[ 'wpbb_job_title' ];
			$wpbb_posted_job_reference = $_POST[ 'wpbb_job_reference' ];
			$wpbb_posted_applicant_name = $_POST[ 'wpbb_name' ];
			$wpbb_posted_applicant_email = $_POST[ 'wpbb_email' ];
			$wpbb_posted_applicant_tel = $_POST[ 'wpbb_tel' ];
			$wpbb_posted_job_url = $_POST[ 'wpbb_job_url' ];
			$wpbb_posted_contact_email = $_POST[ 'wpbb_contact_email' ];
			$wpbb_posted_application_email = $_POST[ 'wpbb_application_email' ];
			
			/* setup the args to insert the job post */
			$wpbb_job_post_args = array(
				'post_type' => 'wpbb_application',
				'post_title' => wp_strip_all_tags( $wpbb_posted_applicant_name ),
				'post_status' => 'publish'
			);
			
			/* insert the application post */
			$wpbb_application_post_id = wp_insert_post( $wpbb_job_post_args );
			
			/* check the post has been added */
			if( $wpbb_application_post_id != 0 ) {
			
				/* set the post meta data (custom fields) */
				add_post_meta( $wpbb_application_post_id, '_wpbb_job_reference', wp_strip_all_tags( $wpbb_posted_job_reference ), true );
				add_post_meta( $wpbb_application_post_id, '_wpbb_job_reference', wp_strip_all_tags( $wpbb_posted_job_title ), true );
				add_post_meta( $wpbb_application_post_id, '_wpbb_job_url', wp_strip_all_tags( esc_url( $wpbb_posted_job_url ) ), true );
				add_post_meta( $wpbb_application_post_id, '_wpbb_applicant_email', wp_strip_all_tags( $wpbb_posted_applicant_email ), true );
				add_post_meta( $wpbb_application_post_id, '_wpbb_applicant_telno', wp_strip_all_tags( $wpbb_posted_applicant_tel ), true );
				
				/* add the attachment from the uploaded file */
				$pxjn_attach_id = wp_insert_attachment( $pxjn_attachment, $pxjn_filetype[ 'file' ], $wpbb_application_post_id );
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
				$pxjn_attach_data = wp_generate_attachment_metadata( $pxjn_attach_id, $pxjn_filetype[ 'file' ] );
				wp_update_attachment_metadata( $pxjn_attach_id, $pxjn_attach_data );
			
			} // end check post addded
			
			/* build the content of the email */
			$wpbb_email_content = '
			
				<p>' . $wpbb_posted_applicant_name . ' has completed an application for ' . $wpbb_posted_job_title . ' which has the job reference of ' . $wpbb_posted_job_reference . '. The applicants email address is ' . $wpbb_posted_applicant_email . ' and telephone numnber is ' . $wpbb_posted_applicant_tel . '. Below is a summary of their responses:</p>
				
				<ul>
					<li>Applicant Name: ' . $wpbb_posted_applicant_name . '</li>
					<li>Applicant Email Address: ' . $wpbb_posted_applicant_email . '</li>
					<li>Applicant Telephone Number: ' . $wpbb_posted_applicant_tel . '</li>
					<li>Job Title: ' . $wpbb_posted_job_title . '</li>
					<li>Job Reference: ' . $wpbb_posted_job_reference . '</li>
					<li>Job Permalink: <a href="' . $wpbb_posted_job_url . '">' . $wpbb_posted_job_url . '</a></li>
					<li><a href="' . get_edit_post_link( $wpbb_application_post_id ) . '">Application Edit Link</a></li>
					<li><a href="' . $pxjn_moved_file[ 'url' ] . '">CV Attachment Link</a></li>
				</ul>
				
				<p>Email sent by <a href="#">WP AdCourier WordPress plugin</a>.</p>
				
			';
			
			/* set up the mail variables */
			$wpbb_mail_subject = 'New Job Application Submitted - ' . $wpbb_posted_applicant_name;
			
			$wpbb_email_headers = 'From: ' . $wpbb_posted_applicant_name . ' <' . $wpbb_posted_applicant_email . '>';
			
			$wpbb_mail_content = wpbb_generate_email_content( $wpbb_email_content );
			
			$wpbb_mail_recipients = $wpbb_posted_application_email . ',' . $wpbb_posted_contact_email;
			
			/* send the mail */
			$wpbb_send_email = wp_mail( $wpbb_mail_recipients, $wpbb_mail_subject, $wpbb_mail_content, $wpbb_email_headers );
	
			/* check whether message was sent OK */
			if( $wpbb_send_email == 1 ) {
				
				echo '<p class="success">' . apply_filters( 'wpbb_application_sent_success_message', 'Your application has been sent successfully.' ) . '</p>';
			
			/* error occured in sending email */	
			} else {
				
				echo '<p class="error">' . apply_filters( 'wpbb_application_send_error_message', 'There was a problem sending your application. Please check you have entered a valid email address.' ) . '</p>';
				
			} // end check mail sent
			
		}
	
	/* no jon id detected */	
	} else {
		
		echo apply_filters( 'wpbb_no_job_reference_message', '<p class="message error">Oops! No job reference was detected.</p>' );
		
	} // end if has job id
	
}

add_shortcode( 'wpbb_applicationform', 'wpbb_application_form_shortcode' );