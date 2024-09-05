<?php
/*
Template Name: API Confirmation Page
*/
?>

<?php get_header('booking'); ?>

<?php 
if(isset($_GET["order"]) && isset($_GET["transactionGUID"])):
    
    $api = new NiravanaAPI();
    $order = new NiravanaOrder($_GET["order"]);

    $booking_id = $order->getBookingID();
    $payment_type = $order->getPaymentType();
    if($booking_id && ($payment_type == 'due_payment' || $payment_type == 'custom_amount_due')):
        $order->setTransactionIDRemaining($_GET["transactionGUID"]);
    else:
        $order->setTransactionID($_GET["transactionGUID"]);
    endif;
    ?>
    <div class="page-blocks">
        
        <?php do_action('order_content_header' , 'confirmation' , $order , $api); ?>

        <section class="booking text--color--dark">

            <?php get_template_part('template-parts/api/order','payment-confirmation',  array(
                'api' => $api,
                'order' => $order,
            )); ?>
            
        </section>

    </div>
<?php else : ?>
    <div class="page-blocks">
        
        <section class="booking text--color--dark">
            <?php get_template_part( 'template-parts/api/order', 'error'); ?>
        </section>

    </div>
<?php endif; ?>
    
<?php get_footer(''); ?>
