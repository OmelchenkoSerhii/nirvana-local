<?php
$minNights = $args['nights'];
$order     = $args['order'];
$hotelCode = $args['hotelCode'];
?>

<div class="hotel-nights py-5">
	<span class="hotel-nights__notice d-block text-center h4 text--size--16 p-2 mb-2 ml-n5 mr-n4 mt-n1">
		Minimum of <?php echo $minNights; ?> nights needed to reserve this hotel room. You need to change your dates to reserve it.
	</span>
	<div class="hotel-nights-form p-2">
		<?php
			get_template_part(
				'template-parts/api/bedbanks/hotel',
				'change-dates',
				array(
					'order'     => $order,
					'hotelCode' => $hotelCode,
				)
			);
			?>
	</div>
</div>
