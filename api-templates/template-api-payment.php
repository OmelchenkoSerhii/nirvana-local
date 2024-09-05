<?php
/*
Template Name: API Payment Page
*/
?>

<?php get_header('booking'); ?>

<?php
$api = new NiravanaAPI();
$order = false;
if (isset($_GET['order_token']) && $_GET['order_token']) :
  $order = new NiravanaOrder($_GET['order_token']);
  if (!$order->orderExists()) :
    $order = false;
  else :
    $order_token = $_GET['order_token'];
    // Get the session ID or create a new one
    $session_id = $_GET['order_token'];
    // Set a cookie with the session ID
    setcookie('my_session_id', $session_id, time() + 3600, '/');
    // Store the order token in a transient for 1 hour, associated with the session ID
    set_transient('order_token_' . $session_id, $order_token, 3600);
  endif;
elseif (isset($_GET['booking_id']) && $_GET['booking_id'] && isset($_GET['create_order'])) :
  
  $booking_id = $_GET['booking_id'];

  $bookingExists = false;
  $bookingOrderNumber = false;
  $args = array(
    'post_type' => 'booking',
    'fields' => 'ids',
    'meta_query' => array(
      array(
        'key' => 'booking_id',
        'value' => $booking_id,
        'compare' => '='
      )
    )
  );
  $query = new WP_Query($args);
  if ($query->have_posts()) {
    while ($query->have_posts()) {
      $query->the_post();
      $bookingExists = true;
      $bookingOrderNumber = get_post_meta(get_the_ID(), "order_token", true);
      break;
    }
    wp_reset_postdata();
  }

  if ($bookingExists && $bookingOrderNumber) :
    // Get the session ID or create a new one
    $session_id = $bookingOrderNumber;
    // Set a cookie with the session ID
    setcookie('my_session_id', $bookingOrderNumber, time() + 3600, '/');
    // Store the order token in a transient for 1 hour, associated with the session ID
    set_transient('order_token_' . $bookingOrderNumber, $bookingOrderNumber, 3600);
    $order = new NiravanaOrder($bookingOrderNumber);
  else:
    $order_token = wp_generate_uuid4();
    // Get the session ID or create a new one
    $session_id = $order_token;
    // Set a cookie with the session ID
    setcookie('my_session_id', $session_id, time() + 3600, '/');
    // Store the order token in a transient for 1 hour, associated with the session ID
    set_transient('order_token_' . $session_id, $order_token, 3600);
    $bookingData = $api->GetBookingData($booking_id);
    if ($bookingData) :
      $order = new NiravanaOrder($order_token);
      $order->createOrderPostBooking($bookingData);
    endif;
  endif;


else :
  $session_id = isset($_COOKIE['my_session_id']) ? $_COOKIE['my_session_id'] : uniqid();
  $order_token = get_transient('order_token_' . $session_id);
  if ($order_token) {
    $order = new NiravanaOrder($order_token);
  }
endif;
?>

<div class="page-blocks">

  <section class="booking text--color--dark">
    <?php if ($order) : ?>
      <div class="booking__col-sidebar">
        <?php get_template_part('template-parts/api/order', 'sidebar', array('event_code' => $order->getEventCode())); ?>
      </div>

      <div class="booking__col-content">
        <?php do_action('order_content_header', 'payment', $order, $api); ?>
        <?php get_template_part('template-parts/api/order', 'payment',  array(
          'api' => $api,
          'order' => $order,
        )); ?>
      </div>

      <div class="booking__col-summary">
        <?php get_template_part('template-parts/api/order', 'sidebar-summary',  array(
          'api' => $api,
          'order' => $order,
        )); ?>
      </div>
    <?php else : ?>
      <?php get_template_part('template-parts/api/order', 'error'); ?>
    <?php endif; ?>
  </section>

</div>

<?php get_footer(''); ?>