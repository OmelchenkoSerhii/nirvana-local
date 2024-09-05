<?php
$order = $args['order'];
?>
<div class="summary-dates">
	<h3 class="mb-1">Dates</h3>

	<div class="d-flex summary-dates__list">
		<div class="summary-dates__item">
			<span class="summary-dates__item-inner">
				<span class="summary-dates__item-label">Check-in</span>
				<span class="summary-dates__item-value"><?php echo $order->getCheckinDate(); ?></span>
			</span>
		</div>
		<div class="summary-dates__item">
			<span class="summary-dates__item-inner">
				<span class="summary-dates__item-label">Check-out</span>
				<span class="summary-dates__item-value"><?php echo $order->getCheckoutDate(); ?></span>
			</span>
		</div>
		<div class="summary-dates__item">
			<span class="summary-dates__item-inner">
				<?php
				$adultsQtt    = $order->getAdultsQtt();
				$childsQtt    = $order->getChildQtt();
				$adults_tag   = $adultsQtt <= 1 ? 'adult' : 'adults';
				$children_tag = $childsQtt == 1 ? 'child' : 'children';
				?>
				<span class="summary-dates__item-value"><?php echo $adultsQtt; ?> <?php echo $adults_tag; ?></span>
				<span class="summary-dates__item-value"><?php echo $childsQtt; ?> <?php echo $children_tag; ?></span>
			</span>
		</div>
	</div>
</div>
