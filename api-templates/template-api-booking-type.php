<?php
/**
 * Template Name: API Booking Type
 */
?>

<?php get_header( 'booking' ); ?>

<?php
$order = false;

//Get event ID
$eventPostID = false;
if(isset($_GET['eventid']) && $_GET['eventid']){
    $eventCode = $_GET['eventid'];
    $events = get_posts(array(
        'numberposts'   => 1,
        'post_type'     => 'event',
        'meta_key'      => 'event_code',
        'meta_value'    => $eventCode
    ));

    if($events):
        $eventPostID = $events[0]->ID;
    endif;
}

if($eventPostID): //If event code passed - create new order
    $order_token = wp_generate_uuid4(); //generate order token
    $session_id = $order_token;
    //Find client ID
    $clientID = 0;
    if ( is_user_logged_in() ) {
        $current_user = wp_get_current_user();
        $clientID = get_user_meta( $current_user->ID, 'api_client_id', true );
    }
	// Set a cookie with the session ID
	setcookie( 'my_session_id', $session_id, time() + 43200, '/' );
	// Store the order token in a transient for 1 hour, associated with the session ID
	set_transient( 'order_token_' . $session_id, $order_token, 43200 );

    $order = new NiravanaOrder($order_token);
    if (!$order->orderExists()) :
        $start_date = get_field('event_start_date', $eventPostID);
        $end_date = get_field('event_end_date', $eventPostID);
        $currency = get_field('event_default_currency', $eventPostID)?get_field('event_default_currency', $eventPostID):'GBP';
        
        if($start_date):
            $dateFormat = DateTime::createFromFormat('Ymd', $start_date);
            if($dateFormat):
                $start_date = $dateFormat->format('j F Y');
            endif;
        endif;

        $rooms = array(
            array(
                'adults_qtt' => 1,
                'childs_qtt' => 0,
                'infants_qtt' => 0,
                'ages' => array(),
                'infant_ages' => array(),
            ),
        );

        $order->createOrderPost($eventPostID, $start_date, $start_date, $currency, $rooms, $clientID);
    else :
        $order->UpdateOrderPost($data);
        $order->setupOrderData();
    endif;
else:
    if(isset( $_COOKIE['my_session_id'] )):
        $session_id = $_COOKIE['my_session_id'];
        $order_token = get_transient( 'order_token_' . $session_id );
        $order = new NiravanaOrder($order_token);
        $order->setOrderType('tour');
    else:

    endif;
endif;

$api = new NiravanaAPI();
?>
<div class="page-blocks">
	<section class="booking text--color--dark">
		<?php if($order): ?>
			<?php redirect_if_booking($order); ?>
			<div class="booking__col-sidebar">
				<?php get_template_part('template-parts/api/order','sidebar' , array( 'event_code' => $order->getEventCode() , 'order' => $order )); ?>
			</div>
			<div class="booking__col-content px-2">
				<?php do_action('order_content_header' , 'booking-type' , $order , $api); ?>
				<?php get_template_part( 'template-parts/api/order', 'booking-type',  array(
					'api' => $api,
					'order' => $order,
				)); ?>
			</div>
			<div class="booking__col-summary">
				<?php get_template_part( 'template-parts/api/order', 'sidebar-summary', array( 
					'api' => $api,
					'order' => $order,
				) ); ?>
			</div>
		<?php else: ?>
			<?php get_template_part( 'template-parts/api/order', 'error'); ?>
		<?php endif; ?>
	</section>
</div>

<?php
get_footer( '' );
