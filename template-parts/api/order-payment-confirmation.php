<?php 
$order = $args['order'];
$api = $args['api'];


$booking_id = $order->getBookingID();
$payment_type = $order->getPaymentType();

if($booking_id && ($payment_type == 'due_payment' || $payment_type == 'custom_amount_due' )):
    $status = $order->getTransactionStatusRemaining();
else:
    $status = $order->getTransactionStatus();
endif;
?>

<div class="order-confirmation">
    
    <?php if($status && ($status == 'HELD' || $status == 'FULFILLED' )): ?>
            <?php 
            if(!$booking_id ):
                $booking = $order->createBooking();
            elseif($booking_id && $payment_type == 'due_payment'):
                $confirmation = $order->authorizePaymentBooking();
            elseif($booking_id && $payment_type == 'custom_amount_due'):
                $confirmation = $order->authorizePaymentBookingCustomDue();
            else:
                $confirmation = $order->authorizePaymentBookingDeposit();
            endif;
            
            if ( (!$booking_id && $booking) || ($booking_id && $confirmation)):
                ?>
                <div class="text-center">
                    <img class="order-confirmation__icon" src="<?php echo get_template_directory_uri(); ?>/assets/images/payment-sucessful.svg" alt="">
                    <h4 class="order-confirmation__title"><?php _e('Payment sucessful.'); ?></h4>
                    <h3 class="pt-2">Thank you!</h3>
                    <h3 class="pt-2">Order Number - <?php echo get_post_meta( $order->getOrderID() , 'booking_id', true);?></h3>
                    <p class="pt-2"><?php _e('Thank you for your booking. You will receive an email confirmation shortly.'); ?></p>
                    <p class="pt-2"><?php _e('If you have any special requirements on this booking, or need to speak to us about it, please do not hesitate to contact us'); ?> <a href="mailto:contactus@nirvanaeurope.com">contactus@nirvanaeurope.com</a>.</p>
                    <div class="pt-2">
                        <a class="button button--orange m-1" href="<?php echo get_home_url(); ?>"><?php _e('Back to Nirvana website'); ?></a>
                        <a class="button button--orange m-1" href="<?php echo get_home_url(); ?>/profile/"><?php _e('Account page'); ?></a>
                    </div>
                </div>
                <?php
            else:
                ?>
                <div class="text-center">
                    <img class="order-confirmation__icon" src="<?php echo get_template_directory_uri(); ?>/assets/images/payment-unsucessful.svg" alt="">
                    <h3 class="order-confirmation__title"><?php _e('Your payment has been made. However, there was an issue with your booking. Please contact us to resolve the matter.'); ?></h3>
                    <h3 class="mt-3">+44 191 2571750</h3>
                    <h3 class="mt-3"><a href="mailto:contactus@nirvanaeurope.com">contactus@nirvanaeurope.com</a></h3>
                    <?php if(false): ?>
                    <a class="button button--orange mt-3" href="<?php echo get_field('api_contact_page','option'); ?>"><?php _e('Contact Us'); ?></a>
                    <?php endif; ?>
                </div>
                <?php
            endif;
            ?>
      
    <?php else: ?>
        <div class="text-center">
            <img class="order-confirmation__icon" src="<?php echo get_template_directory_uri(); ?>/assets/images/payment-unsucessful.svg" alt="">
            <h3 class="order-confirmation__title"><?php _e('Your payment was unsuccessful. Please try again or contact support to resolve this issue.'); ?></h3>
            <a class="button button--orange mt-3" href="<?php echo get_field('api_payment_page','option'); ?>"><?php _e('Go Back'); ?></a>
        </div>
    <?php endif; ?>
   
</div>
