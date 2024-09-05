<?php
/**
 * Template Name: API Accommodation
 */
?>

<?php get_header( 'booking' ); ?>

<?php 

$api = new NiravanaAPI();
$order = false;

//check if params for creating new order exist
$create_order = isset($_GET['createorder']);
$create_order_token = isset($_GET['order_token'])?$_GET['order_token']:false;

if($create_order && $create_order_token):
	$order_token = $create_order_token; //generate order token
    $session_id = $create_order_token;
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
	$order->setOrderType('tailor');

else:
	$session_id = isset( $_COOKIE['my_session_id'] ) ? $_COOKIE['my_session_id'] : uniqid();
	$order_token = get_transient( 'order_token_' . $session_id );
	if ( $order_token ) {
		$order = new NiravanaOrder($order_token);
		$order->setOrderType('tailor');
		if (isset($_POST['date-checkin'])):
			$order->UpdateOrderPost($_POST);
		endif;
	}
endif;
?>

<div class="page-blocks">
	<section class="booking text--color--dark">
		<?php if($order): ?>
			<?php redirect_if_booking($order); ?>
			<div class="booking__col-content px-2">
				<?php do_action('order_content_header' , 'accomomodation' , $order , $api); ?>
				<?php get_template_part( 'template-parts/api/accommodation/order', 'accommodation',  array(
					'api' => $api,
					'order' => $order,
				)); ?>
			</div>
			<div class="booking__col-summary">
				<?php get_template_part( 'template-parts/api/order', 'sidebar-summary',  array(
					'api' => $api,
					'order' => $order,
				)); ?>
			</div>
		<?php else: ?>
			<?php get_template_part( 'template-parts/api/order', 'error'); ?>
		<?php endif; ?>
	</section>
</div>

<?php 
if(($create_order && $create_order_token) || isset($_GET['reserve'])):
?>
	<script>
		jQuery(document).ready(function() {
			jQuery('html, body').animate({ scrollTop: jQuery('#rooms').offset().top - 100 }, 1000);
		});
	</script>
<?php
endif;
?>

<?php
get_footer( '' );
