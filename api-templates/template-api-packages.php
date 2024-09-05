<?php
/**
 * Template Name: API Packages
 */
?>

<?php get_header( 'booking' ); ?>

<?php
$order = false;

if(isset($_GET['order_token']) && $_GET['order_token']):
	// Generate a unique order token
	$order_token = $_GET['order_token'];
	// Get the session ID or create a new one
	$session_id = $_GET['order_token'];
	// Set a cookie with the session ID
	setcookie( 'my_session_id', $session_id, time() + 43200, '/' );
	// Store the order token in a transient for 1 hour, associated with the session ID
	set_transient( 'order_token_' . $session_id, $order_token, 43200 );
	$order = new NiravanaOrder($_GET['order_token']);
	$order->setOrderType('tour');
elseif(isset( $_COOKIE['my_session_id'] )):
	$session_id = $_COOKIE['my_session_id'];
	$order_token = get_transient( 'order_token_' . $session_id );
	$order = new NiravanaOrder($order_token);
	$order->setOrderType('tour');
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
				<?php do_action('order_content_header' , 'packages' , $order , $api); ?>
				<?php get_template_part( 'template-parts/api/packages/order', 'packages',  array(
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
