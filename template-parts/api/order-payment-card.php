<?php 
$order = $args['order'];
$api = $args['api'];

?>

<div class="order-payment-card">
    <?php 
    $client = $order->createClient($_POST);
    $payment = $order->getPaymentPrauth($_POST);
    ?>
    <?php if($payment): ?>
        <?php echo $payment; ?>
    <?php else: ?>
        <h3>There has been an error, please contact us 00441912571750 or by email <a style="text-decoration: underline; color: #f47920;" href="mailto:contactus@nirvanaeurope.com">contactus@nirvanaeurope.com</a></h3>
    <?php endif; ?>
</div>
