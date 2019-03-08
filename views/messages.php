<?php
/**
 * Creates the outputs for messages to be displayed e.g. application form messages.
 *
 * @package WP_Broadbean
 */

?>

<div class="wpbb-messages" id="wpbb-application-messages-<?php echo esc_attr( $data['post_id'] ); ?>">

	<?php

	// remove the post id from the data array.
	unset( $data['post_id'] );

	// loop through each message.
	foreach ( $data as $message ) {
		echo wp_kses_post( $message );
	}

	?>

</div>
