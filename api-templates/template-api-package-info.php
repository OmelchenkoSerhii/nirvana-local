<?php
/**
 * Template Name: API Package Single Info
 */
?>

<?php 

$api = new NiravanaAPI();
$order = false;
$session_id = isset( $_COOKIE['my_session_id'] ) ? $_COOKIE['my_session_id'] : uniqid();
$order_token = get_transient( 'order_token_' . $session_id );
if ( $order_token ) {
    $order = new NiravanaOrder($order_token);
	if (isset($_POST['date-checkin'])):
		$order->UpdateOrderPost($_POST);
	endif;
}

?>
<?php get_header( 'booking' ); ?>


<div class="page-blocks">
	<section class="booking text--color--dark">
		<?php if($order): ?>
			<?php redirect_if_booking($order); ?>
			<div class="booking__col-sidebar">
				<?php get_template_part('template-parts/api/order','sidebar' , array( 'event_code' => $order->getEventCode() , 'order' => $order )); ?>
			</div>
			<div class="booking__col-content px-2">
				<?php do_action('order_content_header' , 'package' , $order , $api , array(
					//'title' => $tourTitle
				)); ?>
				<?php get_template_part( 'template-parts/api/packages/order', 'package-single-info',  array(
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
get_footer( '' );
