<?php
/*
Template Name: API Payment Card Page
*/
?>

<?php get_header('booking'); ?>

<?php 
$order = false;
$session_id = isset( $_COOKIE['my_session_id'] ) ? $_COOKIE['my_session_id'] : uniqid();
$order_token = get_transient( 'order_token_' . $session_id );
if ( $order_token ) {
    $api = new NiravanaAPI();
	  $order = new NiravanaOrder($order_token);
}
?>

  <div class="page-blocks">
      
    <section class="booking text--color--dark">
        <?php if($order): ?>
          <div class="booking__col-sidebar">
            <?php get_template_part('template-parts/api/order','sidebar' , array( 'event_code' => $order->getEventCode() , 'order' => $order )); ?>
          </div>
          <div class="booking__col-content">
            <?php do_action('order_content_header' , 'payment' , $order , $api); ?>
            <?php get_template_part('template-parts/api/order','payment-card',  array(
              'api' => $api,
              'order' => $order,
            )); ?>
          </div>
          <div class="booking__col-summary">
            <?php get_template_part('template-parts/api/order','sidebar-summary',  array(
              'api' => $api,
              'order' => $order,
            )); ?>
          </div>
        <?php else: ?>
          <?php get_template_part( 'template-parts/api/order', 'error'); ?>
        <?php endif; ?>
    </section>

  </div>
    
<?php get_footer(''); ?>
