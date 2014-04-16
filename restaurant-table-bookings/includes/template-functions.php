<?php
/**
 * Template functions for rendering booking forms, etc.
 */

/**
 * Create a shortcode to render the booking form
 * @since 0.0.1
 */
if ( !function_exists( 'rtb_booking_form_shortcode' ) ) {
function rtb_booking_form_shortcode() {
	return rtb_print_booking_form();
}
add_shortcode( 'booking-form', 'rtb_booking_form_shortcode' );
} // endif;

/**
 * Print the booking form's HTML code, including error handling and confirmation
 * notices.
 * @since 0.0.1
 */
if ( !function_exists( 'rtb_print_booking_form' ) ) {
function rtb_print_booking_form() {

	// Enqueue assets for the form
	rtb_enqueue_assets();

	// Allow themes and plugins to override the booking form's HTML output.
	$output = apply_filters( 'rtb_booking_form_html_pre', '' );

	if ( !empty( $output ) ) {
		return $output;
	}

	ob_start();

	?>

<div class="rtb-booking-form">
	<form method="POST" action="">
		<input type="hidden" name="action" value="booking_request">
		<fieldset class="reservation">
			<legend>
				<?php _e( 'Book a table', RTB_TEXTDOMAIN ); ?>
			</legend>
			<div class="date">
				<label for="rtb-date">
					<?php _e( 'Date', RTB_TEXTDOMAIN ); ?>
				</label>
				<input type="text" name="date" id="rtb-date">
			</div>
			<div class="time">
				<label for="rtb-time">
					<?php _e( 'Time', RTB_TEXTDOMAIN ); ?>
				</label>
				<input type="text" name="time" id="rtb-time">
			</div>
			<div class="party">
				<label for="rtb-party">
					<?php _e( 'Party', RTB_TEXTDOMAIN ); ?>
				</label>
				<input type="text" name="party" id="rtb-party">
			</div>
		</fieldset>
		<fieldset class="contact">
			<legend>
				<?php _e( 'Contact Details', RTB_TEXTDOMAIN ); ?>
			</legend>
			<div class="name">
				<label for="rtb-name">
					<?php _e( 'Name', RTB_TEXTDOMAIN ); ?>
				</label>
				<input type="text" name="name" id="rtb-name" placeholder="Your name">
			</div>
			<div class="email">
				<label for="rtb-email">
					<?php _e( 'Email', RTB_TEXTDOMAIN ); ?>
				</label>
				<input type="text" name="email" id="rtb-email" placeholder="your@email.com">
			</div>
			<div class="phone">
				<label for="rtb-phone">
					<?php _e( 'Phone', RTB_TEXTDOMAIN ); ?>
				</label>
				<input type="text" id="phone" name="rtb-phone" placeholder="Your phone number">
			</div>
			<div class="add-message">
				<a href="#">
					<?php _e( 'Add a Message', RTB_TEXTDOMAIN ); ?>
				</a>
			</div>
			<div class="message">
				<label for="rtb-message">
					<?php _e( 'Message', RTB_TEXTDOMAIN ); ?>
				</label>
				<textarea name="message" id="rtb-message"></textarea>
			</div>
		</fieldset>
		<button type="submit"><?php _e( 'Request Booking', RTB_TEXTDOMAIN ); ?></button>
	</form>
</div>

	<?php

	$output = ob_get_clean();

	$output = apply_filters( 'rtb_booking_form_html_post', $output );

	return $output;
}
} // endif;

/**
 * Enqueue the front-end CSS and Javascript for the booking form
 * @since 0.0.1
 */
if ( !function_exists( 'rtb_enqueue_assets' ) ) {
function rtb_enqueue_assets() {

	wp_enqueue_style( 'rtb-booking-form' );

	wp_enqueue_style( 'pickadate-default', RTB_PLUGIN_URL . '/lib/simple-admin-pages/lib/pickadate/themes/default.css' );
	wp_enqueue_style( 'pickadate-date', RTB_PLUGIN_URL . '/lib/simple-admin-pages/lib/pickadate/themes/default.date.css' );
	wp_enqueue_style( 'pickadate-time', RTB_PLUGIN_URL . '/lib/simple-admin-pages/lib/pickadate/themes/default.time.css' );
	wp_enqueue_script( 'pickadate', RTB_PLUGIN_URL . '/lib/simple-admin-pages/lib/pickadate/picker.js', array( 'jquery' ), '', true );
	wp_enqueue_script( 'pickadate-date', RTB_PLUGIN_URL . '/lib/simple-admin-pages/lib/pickadate/picker.date.js', array( 'jquery' ), '', true );
	wp_enqueue_script( 'pickadate-time', RTB_PLUGIN_URL . '/lib/simple-admin-pages/lib/pickadate/picker.time.js', array( 'jquery' ), '', true );
	wp_enqueue_script( 'pickadate-legacy', RTB_PLUGIN_URL . '/lib/simple-admin-pages/lib/pickadate/legacy.js', array( 'jquery' ), '', true );
	// @todo is there some way I can enqueue this for RTL languages
	// wp_enqueue_style( 'pickadate-rtl', RTB_PLUGIN_URL . '/lib/simple-admin-pages/lib/pickadate/themes/rtl.css' );
	
	wp_enqueue_script( 'rtb-booking-form' );
	
	// Pass date and time format settings to the pickadate controls
	global $rtb_controller;
	wp_localize_script(
		'rtb-booking-form',
		'rtb_pickadate',
		array(
			'date_format' => $rtb_controller->settings->get_setting( 'date-format' ),
			'time_format'  => $rtb_controller->settings->get_setting( 'time-format' ),
			'schedule_open' => $rtb_controller->settings->get_setting( 'schedule-open' ),
			'schedule_closed' => $rtb_controller->settings->get_setting( 'schedule-closed' ),
			'early_bookings' => $rtb_controller->settings->get_setting( 'early-bookings' ),
			'late_bookings' => $rtb_controller->settings->get_setting( 'late-bookings' ),
		)
	);

}
} // endif;
