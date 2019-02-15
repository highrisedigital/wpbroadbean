<?php
/**
 * Creates the outputs for messages to be displayed e.g. application form messages.
 *
 * @package WP_Broadbean
 */

?>

<div class="wpbb-messages">

	<?php

	// loop through each message.
	foreach ( $data as $message ) {
		echo wp_kses_post( $message );
	}

	?>

</div>
