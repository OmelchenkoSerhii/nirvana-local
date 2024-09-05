<?php
/**
 * Handles AJAX requests to use referral codes.
 *
 * The function first validates the AJAX request and retrieves the referral code
 * and event ID from the request data. It then checks the code's validity and
 * if it is related to the correct event and has not exceeded its maximum uses.
 * If all checks pass, the function increments the use count of the code and
 * sends a success response.
 *
 */
function ajax_celtic_check_code() {
	validate_ajax_request();

    // phpcs:ignore
	$code     = sanitize_text_field( $_POST['data']['code'] );
    // phpcs:ignore
	$event_id = intval( $_POST['data']['event_id'] );

	// $code is changing to an object in the function
	validate_referral_code( $code, $event_id );
	send_success();
}

/**
 * Validates the AJAX request.
 *
 * This function checks the AJAX referer to prevent CSRF attacks.
 * If the validation fails, it sends an error response and terminates the request.
 *
 */
function validate_ajax_request() {
	if ( false === check_ajax_referer( 'secure_nonce_name', 'ajax_nonce', false ) ) {
		wp_send_json_error( 'Invalid nonce' );
		die;
	}
}

/**
 * Validates the referral code.
 *
 * This function checks whether the code exists, is active,
 * is related to the correct event and has not exceeded its maximum uses.
 * If any check fails, it sends an error response.
 *
 * @param   string  $code  The referral code.
 * @param   int     $event_id  The event ID.
 */
function validate_referral_code( &$code, $event_id ) {
	$celtic_db = new Celtic_Model();
	$code_row      = $celtic_db->get_record( $code, $event_id );

	if ( empty( $code ) || $code_row->event_id !== (string) $event_id ) {
		send_error( 'Your client reference number '.$code.' does not match the list of qualifying refrence numbers that have been provided to us by the club and we are unable to continue with your booking.  If you believe this to be an error please contact the Celtic FC ticket office for further information.' );
	}

	if ( ! $code_row->is_active ) {
		send_error( 'Your Season Ticket number does not match the list of qualifying season tickets that have been provided to us by the Club and we are unable to continue with your booking.  Please contact the Celtic FC ticket office for further information.' );
	}

	if ( $code_row->used_times >= $code_row->max_uses ) {
		send_error( 'The client reference number '.$code_row->code.' has already been used for this game.' );
	}
}

/**
 * Sends an error response.
 *
 * This function prepares an error response and sends it.
 *
 * @param   string  $message  The error message.
 */
function send_error( $message ) {
	$response = array(
		'message' => $message,
	);

	wp_send_json_error( $response, 400 );
	die;
}

/**
 * Updates the use count of the referral code and sends a success response.
 *
 * This function increments the use count of the code in the database.
 * If the update succeeds, it sends a success response. If not, it sends an error response.
 *
 * @param   object  $code  The referral code object.
 */
function send_success() {
	$response = array(
		'message' => 'Code has been activated.',
	);

	wp_send_json_success( $response, 200 );
	die;
}

add_action( 'wp_ajax_celtic_check_code', 'ajax_celtic_check_code' );
add_action( 'wp_ajax_nopriv_celtic_check_code', 'ajax_celtic_check_code' );
