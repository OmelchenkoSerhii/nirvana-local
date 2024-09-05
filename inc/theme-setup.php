<?php
/*
=====================
	Theme Setup Function
=====================
*/

function theme_setup(){
	load_theme_textdomain( 'nirvana', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'menus' );
	add_theme_support( 'woocommerce' );

	global $content_width;
	if ( ! isset( $content_width ) ) $content_width = 640;

	//main menu
	register_nav_menus(
		array( 
			'main-menu' => __( 'Main Menu', 'nirvana' ),
			'footer-menu-1' => __( 'Footer Menu 1', 'nirvana'),
			'footer-menu-2' => __( 'Footer Menu 2', 'nirvana'),
            'footer-bottom-menu' => __( 'Footer Bottom Menu', 'nirvana'),  
		)
    );
    
}

add_action( 'after_setup_theme', 'theme_setup' );


add_action( 'admin_enqueue_scripts', 'load_admin_style' );
function load_admin_style() {
    wp_enqueue_style( 'admin_css', get_template_directory_uri() . '/inc/admin/admin-style.css', false, '1.0.0' );
}

function login_enqueue_scripts() {
	wp_enqueue_style( 'login_css', get_template_directory_uri() . '/inc/admin/login-style.css', false, '1.0.0' );
}
add_action('login_head', 'login_enqueue_scripts');

add_filter( 'login_headerurl', 'my_custom_login_url' );
function my_custom_login_url($url) {
    return 'https://rocket-saas.io/';
}

/**
 * Change emails
 */

function wp_sender_email( $original_email_address ) {
	return 'contactus@nirvanaeurope.com';
}

// Function to change sender name
function wp_sender_name( $original_email_from ) {
	return 'Nirvana';
}

// Add our functions to WordPress filters 
add_filter( 'wp_mail_from', 'wp_sender_email' );
add_filter( 'wp_mail_from_name', 'wp_sender_name' );

add_filter( 'retrieve_password_message', 'my_retrieve_password_message', 10, 4 );
function my_retrieve_password_message( $message, $key, $user_login, $user_data ) {

    // Start with the default content.
    $site_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
    $message = __( 'Dear Customer,' ) . "\r\n\r\n";
	$message .= __( 'Thank you for requesting a password reset for your Nirvana Europe website account.' ) . "\r\n\r\n";
	$message .= __( 'To reset your password please follow the link below.' ) . "\r\n\r\n";
	$message .= __( 'If you did not request a password reset, please ignore this email.' ) . "\r\n\r\n";
    /* translators: %s: site name */
    //$message .= sprintf( __( 'Site Name: %s' ), $site_name ) . "\r\n\r\n";
    /* translators: %s: user login */
    //$message .= sprintf( __( 'Username: %s' ), $user_login ) . "\r\n\r\n";
    //$message .= __( 'If this was a mistake, just ignore this email and nothing will happen.' ) . "\r\n\r\n";
    $message .= __( 'To reset your password, visit the following address:' ) . "\r\n\r\n";
    $message .= '<' . network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . ">\r\n\r\n";
	$message .= __( 'Nirvana Europe' ) . "\r\n\r\n";

    /*
     * If the problem persists with this filter, remove
     * the last line above and use the line below by
     * removing "//" (which comments it out) and hard
     * coding the domain to your site, thus avoiding
     * the network_site_url() function.
     */
    // $message .= '<http://yoursite.com/wp-login.php?action=rp&key=' . $key . '&login=' . rawurlencode( $user_login ) . ">\r\n";

    // Return the filtered message.
    return $message;

}


/**
 * Add options for bookings filters
 */

 // Add custom meta field filter to admin posts list
function add_custom_meta_field_filter() {
	global $typenow;
	
	if ( 'booking' === $typenow ) {
		$meta_value = isset( $_GET['booking_filter'] ) ? sanitize_text_field( $_GET['booking_filter'] ) : '';
		
		
		
        echo '<select name="booking_filter">';
        echo '<option value="">' . esc_html__( 'Booking Stage', 'text-domain' ) . '</option>';
        
        $options = array(
            'all' => 'All',
            'quote_created' => 'Created Quote',
            'booking_created' => 'Created Booking',  
            'failed_booking' => 'Failed Final Step',
            'failed_payment' => 'Failed Payment',
            'created_from_account' => 'Bookings Created from My Account',
        );
        foreach ( $options as $key => $name ) {
            $selected = selected( $key, $meta_value, false );
            echo '<option value="' . esc_attr( $key ) . '" ' . $selected . '>' . esc_html( $name ) . '</option>';
        }
        
        echo '</select>';
	}
}
add_action( 'restrict_manage_posts', 'add_custom_meta_field_filter' );

// Filter posts by custom meta field value
function filter_posts_by_custom_meta_field( $query ) {
    global $pagenow, $typenow;
	
	if ( 'edit.php' !== $pagenow ) {
		return;
	}
	
    if ( $pagenow == 'edit.php' && $typenow == 'booking' && $query->is_search && $query->query_vars['s'] ) {       
        $meta_query_args = array(
            'relation' => 'OR',
            array(
                'key' => 'booking_id',
                'value' => $query->query_vars['s'],
                'compare' => 'LIKE'
            ),
            array(
                'key' => 'quote_id',
                'value' => $query->query_vars['s'],
                'compare' => 'LIKE'
            ),
            array(
                'key' => 'client_id',
                'value' => $query->query_vars['s'],
                'compare' => 'LIKE'
            ),
            array(
                'key' => 'transaction_id',
                'value' => $query->query_vars['s'],
                'compare' => 'LIKE'
            ),
            array(
                'key' => 'rest_transaction_id',
                'value' => $query->query_vars['s'],
                'compare' => 'LIKE'
            ),
           
        );

        $query->set( 'meta_query', $meta_query_args );
        $query->set( 's' , '');
    }

	$meta_value = isset( $_GET['booking_filter'] ) ? sanitize_text_field( $_GET['booking_filter'] ) : '';
	if ( !empty( $meta_value ) && !$query->query_vars['s']) {
        switch($meta_value):
            case 'quote_created':
                $query->query_vars['meta_key'] = 'quote_id';
		        $query->query_vars['meta_compare'] = 'EXISTS';
                break;
            case 'booking_created':
                $query->query_vars['meta_key'] = 'booking_id';
		        $query->query_vars['meta_compare'] = 'EXISTS';
                break;
            case 'failed_payment':
                $meta_query_args = array(
                    'relation' => 'OR',
                    array(
                        'relation' => 'AND',
                        array(
                            'key' => 'payment_type',
                            'value' => 'due_payment',
                            'compare' => '!='
                        ),
                        array(
                            'key' => 'billing_info',
                            'compare' => 'EXISTS'
                        ),
                        array(
                            'key' => 'transaction_id',
                            'compare' => 'NOT EXISTS'
                        )
                    ),
                    array(
                        'relation' => 'AND',
                        array(
                            'key' => 'payment_type',
                            'value' => 'due_payment',
                            'compare' => '='
                        ),
                        array(
                            'key' => 'rest_billing_info',
                            'compare' => 'EXISTS'
                        ),
                        array(
                            'key' => 'rest_transaction_id',
                            'value' => 'NOT EXISTS',
                            'compare' => '='
                        )
                    )
                );
                $query->set('meta_query', $meta_query_args);
                break;
            case 'failed_booking':
                $meta_query_args = array(
                    'relation' => 'OR',
                    array(
                        'relation' => 'AND',
                        array(
                            'key' => 'payment_type',
                            'value' => 'due_payment',
                            'compare' => '!='
                        ),
                        array(
                            'key' => 'transaction_id',
                            'compare' => 'EXISTS'
                        ),
                        array(
                            'key' => 'booking_id',
                            'compare' => 'NOT EXISTS'
                        )
                    ),
                    array(
                        'relation' => 'AND',
                        array(
                            'key' => 'payment_type',
                            'value' => 'due_payment',
                            'compare' => '='
                        ),
                        array(
                            'key' => 'rest_transaction_id',
                            'compare' => 'EXISTS'
                        ),
                        array(
                            'key' => 'booking_id',
                            'compare' => 'NOT EXISTS'
                        )
                    )
                );
                $query->set('meta_query', $meta_query_args);
                break;
            case 'created_from_account':
                $meta_query_args = array(
                    'relation' => 'AND',
                    array(
                        'key' => 'rooms',
                        'compare' => 'NOT EXISTS'
                    ),
                    array(
                        'key' => 'booking_id',
                        'compare' => 'EXISTS'
                    )                
                );
                $query->set('meta_query', $meta_query_args);
                break;
        endswitch;
	}
    
}
add_action( 'pre_get_posts', 'filter_posts_by_custom_meta_field' );



function add_theme_caps() {
    // gets the administrator role
    $admins = get_role( 'administrator' );

    $admins->add_cap( 'edit_hotel' ); 
    $admins->add_cap( 'read_hotel' ); 
    $admins->add_cap( 'delete_hotel' );
    $admins->add_cap( 'edit_hotels' ); 
    $admins->add_cap( 'edit_other_hotels' ); 
    $admins->add_cap( 'edit_published_hotels' ); 
    $admins->add_cap( 'edit_private_hotels' ); 
    $admins->add_cap( 'publish_hotels' );
    $admins->add_cap( 'read_private_hotels' ); 
    $admins->add_cap( 'delete_hotels' ); 
    $admins->add_cap( 'delete_others_hotels' ); 
    $admins->add_cap( 'delete_published_hotels' ); 
    $admins->add_cap( 'delete_private_hotels' ); 

    $admins->add_cap( 'edit_event' ); 
    $admins->add_cap( 'read_event' ); 
    $admins->add_cap( 'delete_event' );
    $admins->add_cap( 'edit_events' ); 
    $admins->add_cap( 'edit_other_events' ); 
    $admins->add_cap( 'edit_published_events' ); 
    $admins->add_cap( 'edit_private_events' ); 
    $admins->add_cap( 'publish_events' );
    $admins->add_cap( 'read_private_events' ); 
    $admins->add_cap( 'delete_events' ); 
    $admins->add_cap( 'delete_others_events' ); 
    $admins->add_cap( 'delete_published_events' ); 
    $admins->add_cap( 'delete_private_events' ); 
}
add_action( 'admin_init', 'add_theme_caps');