<?php
/***************************************************************
* Function wpbb_metaboxes()
* Defines the additional metaboxes for the post edit screens.
***************************************************************/
function wpbb_metaboxes( $meta_boxes ) {
	
	$meta_boxes[ 'job_information' ] = array(
        'title' => 'Job Information',
        'id' => 'wpbb_job_info',
        'pages' => array(
        	wpbb_job_post_type_name()
        ),
        'context' => 'normal',
        'priority' => 'high',
        'fields' => wpbb_get_job_fields()
    );
    
    $meta_boxes[ 'application_information' ] = array(
        'title' => 'Application Information',
        'id' => 'wpbb_application_info',
        'pages' => array( 'wpbb_application' ),
        'context' => 'normal',
        'priority' => 'high',
        'fields' => apply_filters(
        	'wpbb_application_metabox_fields',
        	array(
	        	'reference' => array(
					'name' => 'Job Reference/ID',
					'desc' => 'This is the job reference or ID for the job applied for.',
					'id' => '_wpbb_job_reference',
					'cols' => 6,
					'type' => 'text'
				),
				'email' => array(
					'name' => 'Email Address',
					'desc' => 'This is the email address of the person applying for this job.',
					'id' => '_wpbb_applicant_email',
					'cols' => 6,
					'type' => 'text'
				),
			)
        )
    );

	return $meta_boxes;
}

add_filter( 'cmb_meta_boxes', 'wpbb_metaboxes' );

/**
 * 
 */
function wpbb_maybe_add_application_attachments( $fields ) {

	// if we are not removing application attachments
	if( false === wpbb_maybe_remove_application_attachments() ) {

		// add application attachments field
		$fields[ 'attachments' ] = array(
			'name' => 'Application Attachments',
			'desc' => 'Attachments to this Application are listed below.',
			'id' => '_wpbb_applicant_attachments',
			'cols' => 12,
			'type' => 'attachments'
		);

	}

	return $fields;

}

add_filter( 'wpbb_application_metabox_fields', 'wpbb_maybe_add_application_attachments' );

/**
 * create our own custom meta box field for showing attachments
 */
class Attachments_Field extends CMB_Field {

	public function html() {
		
		/* get the post ID from the url as we are editing a post */
		if( isset( $_GET[ 'post' ] ) ) {
			$post_id = absint( $_GET[ 'post' ] );
		}
		
		/* check we have a post id */
		if( ! empty( $post_id ) ) {
			
			/* get the attachment posts for this post */
			$attachments = new WP_Query(
				array(
					'post_type'			=> 'attachment',
					'post_parent'		=> $post_id,
					'post_status'		=> 'inherit',
					'posts_per_page'	=> -1
				)
			);
			
			/* check we have attachments */
			if( $attachments->have_posts() ) {
				
				?>
				
				<div class="attachments">
					
					<ul class="attachment-list">
					
					<?php
						
						/* loop through attachments */
						while( $attachments->have_posts() ) : $attachments->the_post();
						
							/* get the attachment URL */
							$url = wp_get_attachment_url( get_the_ID() );
						
							?>
							
							<li <?php post_class(); ?>><a href="<?php echo esc_url( $url ); ?>"><?php the_title(); ?></a></li>
							
							<?php
						
						/* end loop */
						endwhile;	
						
					?>
					
					</ul>
					
				</div>
				
				<?php
				
			} // end if have attachments
			
			/* reset query */
			wp_reset_query();
			
		}
		
	}

}

/* registers our field type with cmb */
function wpbb_show_attachments( $cmb_field_types ) {
	
	/* add our new field type to the filed types array */
	$cmb_field_types[ 'attachments' ] = 'Attachments_Field';
	
	return $cmb_field_types;
	
}

add_filter( 'cmb_field_types', 'wpbb_show_attachments' );