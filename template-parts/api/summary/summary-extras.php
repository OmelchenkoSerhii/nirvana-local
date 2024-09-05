<?php
$order = $args['order'];
$api   = $args['api'];
$quote = $args['quote'];

if($order->getOrderType() != 'tour'):
	$extras = $order->getReservedExtras();
else:
	$extras = $order->getReservedPackageExtras();
endif;

if ( $extras ) :
	?>
	<div class="summary-extras mt-3">
		<h3 class="mb-1">Event Services</h3>
		<ul class="summary-extras__list">
			<?php foreach ( $extras as $item ) : ?>
				<?php
				get_template_part(
					'template-parts/api/summary/summary',
					'extra-card',
					array(
						'item'  => $item,
						'order' => $order,
					)
				);
				?>
			<?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>
