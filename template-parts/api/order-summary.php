<?php 
$debug = false;
$order = $args['order'];
$api = $args['api'];

if(isset($_POST['comment']) && $_POST['comment'] != ''):
    $comment = $order->addComment($_POST['comment']);
endif; 

$quote = $order->getQuoteFromRes();
?>

<?php if($debug): ?>
<pre>
    <?php //$order->addPackageToQuote(); ?>
    <?php //print_r($comment) ;?>
    <?php print_r($order->getQuoteFromRes()); ?>
</pre>
<?php endif; ?>


<div class="order-summary">
    <?php
    get_template_part(
        'template-parts/api/summary/summary',
        'dates',
        array(
			'order' => $order,
			'api' => $api
		)
    );
    ?>

    <?php
    get_template_part(
        'template-parts/api/summary/summary',
        'tours',
        array(
			'order' => $order,
			'api' => $api,
            'quote' => $quote
		)
    );
    ?>

    <?php
    get_template_part(
        'template-parts/api/summary/summary',
        'accommodation',
        array(
			'order' => $order,
			'api' => $api,
            'quote' => $quote
		)
    );
    ?>
    
    <?php
    get_template_part(
        'template-parts/api/summary/summary',
        'extras',
        array(
			'order' => $order,
			'api' => $api,
            'quote' => $quote
		)
    );
    ?>

    <?php
    get_template_part(
        'template-parts/api/summary/summary',
        'transfers',
        array(
			'order' => $order,
			'api' => $api,
            'quote' => $quote
		)
    );
    ?>

    <?php 
    $link = get_field('api_payment_page','option');
    ?>
    <div class="package__buttons-bar d-flex p-2 mt-2 mx-n2 mb-n3">
		<div class="package__buttons-bar__item">
			<a  onclick="history.back()" class="button button--dark-white">Go Back</a>
		</div>
        <div class="package__buttons-bar__item">
            <a href="<?php echo $link; ?>" class="button button--orange">Proceed to payment</a>   
		</div>
	</div>
</div>