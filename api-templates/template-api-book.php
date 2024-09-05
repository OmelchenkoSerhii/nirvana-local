<?php
/*
Template Name: API Book Event Page
*/
?>

<?php get_header('booking'); ?>

  <?php 
  $event = false;

  if(isset($_GET['eventid']) && $_GET['eventid']){
    $eventCode = $_GET['eventid'];
    
    $events = get_posts(array(
      'numberposts'   => 1,
      'post_type'     => 'event',
      'meta_key'      => 'event_code',
      'meta_value'    => $eventCode
    ));
    
    if($events):
        $event = $events[0]->ID;
    endif;
  }
  ?>

  <div class="page-blocks">
      
    <section class="booking text--color--dark">
        <div class="booking__col-sidebar">
          <?php get_template_part('template-parts/api/order','sidebar' , array( 'event_code' => $eventCode )); ?>
        </div>
        <div class="booking__col-content">
          <?php do_action('order_content_header' , 'book' , false , false); ?>
          <?php get_template_part('template-parts/api/order','dates', array( 'event_id' => $event )); ?>
        </div>
        <div class="booking__col-summary">
          <?php get_template_part( 'template-parts/api/order', 'sidebar-summary', array( 
            'api' => false,
            'order' => false,
            'event_id' => $event,
          ) ); ?>
        </div>
    </section>

  </div>
    
<?php get_footer(''); ?>
