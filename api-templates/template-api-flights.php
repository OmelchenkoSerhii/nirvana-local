<?php
/*
 * Template Name: API Flights Page
 */
?>

<?php get_header( 'booking' ); ?>

<div class="page-blocks">

	<section class="booking text--color--dark">
		<div class="booking__col-sidebar">
			<?php get_template_part( 'template-parts/api/order', 'sidebar'  , array( 'event_code' => $order->getEventCode() )); ?>
		</div>
		<div class="booking__col-content">
			<?php do_action('order_content_header' , 'flights' , $order , $api); ?>
			<?php get_template_part( 'template-parts/api/flights/order', 'flights' ); ?>
		</div>
		<div class="booking__col-summary">
			<?php get_template_part( 'template-parts/api/order', 'sidebar-summary' ); ?>
		</div>
	</section>

</div>

<?php get_footer( 'booking' ); ?>
