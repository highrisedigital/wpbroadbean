<?php
/**
 * Output for the settings page.
 *
 * @package WP_Broadbean
 */

/**
 * Display the output markup for the WP Broadbean settings page.
 */
function wpbb_settings_page_output() {

	// get the slug of this plugin page.
	global $plugin_page;

	// strip the wpbb prefix.
	$settings_group = str_replace( 'wpbb_', '', $plugin_page );

	// allow the settings type to be filtered.
	$settings_group = apply_filters( 'wpbb_settings_page_output_settings_group', $settings_group, $plugin_page );

	?>

	<h1><?php printf( esc_html( 'WP Broadbean %s', 'wpbroadbean' ), esc_html( ucfirst( $settings_group ) ) ); ?></h1>

	<?php

		/**
		 * Fire an action before the settings page gets output.
		 *
		 * @hooked wpbb_add_settings_page_intro - 10
		 */
		do_action( 'wpbb_before_settings_page_form', $plugin_page );

		?>

		<form method="post" action="options.php" class="wpbb-settings-col">

			<?php

			// output settings field nonce action fields etc.
			settings_fields( $plugin_page );

			// get the registered settings for this settings page.
			$settings = wpbb_get_plugin_settings( $plugin_page );

			// check we have registered settings.
			if ( ! empty( $settings ) ) {

				// fores before the settings table output.
				do_action( 'wpbb_before_settings_output', $plugin_page );

				?>

				<table class="form-table">

					<tbody>

					<?php

					// loop through each registered setting.
					foreach ( $settings as $setting ) {

						// get the current value of this setting.
						$value = get_option( $setting['option_name'], '' );

						?>

						<tr valign="top" class="setting-type--<?php echo esc_attr( $setting['input_type'] ); ?>">

							<th scope="row">

								<?php

								// if this is a checkbox field.
								if ( 'checkbox' === $setting['input_type'] ) {

									// output the label.
									?>
									<span class="label"><?php echo esc_html( $setting['label'] ); ?></span>
									<?php

								} else { // setting type is not a checkbox.

									// output the field label.
									?>
									<label for="<?php echo esc_attr( $setting['option_name'] ); ?>"><?php echo esc_attr( $setting['label'] ); ?></label>
									<?php

								}

								?>
								
							</th>

							<td>

								<?php

								/**
								 * Fire a before setting action.
								 */
								do_action( 'wpbb_before_setting', $setting, $value );

								/**
								 * Create an action for this setting type
								 * functions for output can be hooked to it
								 */
								do_action( 'wpbb_setting_type_' . $setting['input_type'], $setting, $value );

								/**
								 * Fire a after setting action.
								 *
								 * @hooked wpbb_add_setting_description - 10
								 */
								do_action( 'wpbb_after_setting', $setting, $value );

								?>

							</td>

						</tr>

						<?php

					} // End foreach().

					?>

					</tbody>

				</table>

				<?php

			} // End if().

			?>

			<p class="submit">
				<input type="submit" name="wpbb_settings_submit" id="submit" class="button-primary" value="<?php echo esc_attr( 'Save Changes', 'wpbroadbean' ); ?>" />
			</p>

			<?php

			// Fires after the settings output table is closed.
			do_action( 'wpbb_after_settings_output', $plugin_page );

			?>

		</form>

		<?php

		/**
		 * Fires after the closing form element of the settings page.
		 */
		do_action( 'wpbb_after_settings_page_form', $plugin_page );

		?>

	<?php

}
