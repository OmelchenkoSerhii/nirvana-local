<?php

$hotels = $args['hotels'];
$order  = $args['order'];
$api    = $args['api'];

$orderEventId     = $order->getEventPostID();
$orderEventHotels = get_field( 'event_hotels', $orderEventId );
$enable_bedbanks_tab = get_field('enable_bedbanks_tab' , $orderEventId);
$nirvana_hotels_top_text = get_field('nirvana_hotels_top_text', $orderEventId);
$hide_accommodation_tab = get_field('hide_accommodation_tab', $orderEventId );
$availableHotels  = array();

?>

<div class="accommodations-nirvana">
	<?php if($enable_bedbanks_tab):
		?>
		<div class="content-block mb-4 position-relative">
			<?php if($nirvana_hotels_top_text): ?>
				<h4><?php _e('NIRVANA SUPPORTED HOTELS'); ?></h4>
				<?php echo $nirvana_hotels_top_text; ?>
			<?php else: ?>
				<h4><?php _e('NIRVANA SUPPORTED HOTELS'); ?></h4>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<ul class="accommodations__list js-accoms-list d-flex flex-column">
		<?php $hotelsCount = 0; ?>
		<?php if ( $hotels && !$hide_accommodation_tab ) : ?>
			<?php foreach ( $hotels as $roomHotels ) : ?>
				<?php if ( $roomHotels ) : ?>
					<?php if ( is_array( $roomHotels ) ) : ?>
						<?php foreach ( $roomHotels as $hotel ) : ?>
							<?php
							if ( ! in_array( $hotel->BasicPropertyInfo->HotelCode, $availableHotels ) ) :
								$fromPrice = 9999999;
								if ( gettype( $hotel->RoomRates->RoomRate ) == 'array' ) :
									foreach ( $hotel->RoomRates->RoomRate as $rate ) {
										$price = intval( $rate->Rates->Rate->Total->AmountAfterTax );
										if ( $price != 0 && $price < $fromPrice ) {
											$fromPrice = $price;
										}
									}
								else :
									foreach ( $hotel->RoomRates as $rate ) {
										$price = intval( $rate->Rates->Rate->Total->AmountAfterTax );
										if ( $price != 0 && $price < $fromPrice ) {
											$fromPrice = $price;
										}
									}
								endif;
								$hotelPrice = $fromPrice == 9999999 ? false : $fromPrice;
								if ( $hotelPrice ) :
									get_template_part(
										'template-parts/api/accommodation/hotel',
										'card',
										array(
											'hotel' => $hotel,
											'order' => $order,
											'api'   => $api,
										)
									);
									++$hotelsCount;
									array_push( $availableHotels, $hotel->BasicPropertyInfo->HotelCode );
									
								endif;
							endif;
							?>
						<?php endforeach; ?>
					<?php else : ?>
						
						<?php
						if ( ! in_array( $roomHotels->BasicPropertyInfo->HotelCode, $availableHotels ) ) :
							get_template_part(
								'template-parts/api/accommodation/hotel',
								'card',
								array(
									'hotel' => $hotels,
									'order' => $order,
									'api'   => $api,
								)
							);
							++$hotelsCount;
							array_push( $availableHotels, $roomHotels->BasicPropertyInfo->HotelCode );
						endif;
						?>
					<?php endif; ?>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php else : ?>
			<a class="button button--dark button--arrowback mt-2 d-none" onclick="history.back()">Go Back</a>
		<?php endif; ?>
		<?php if ( $hotels && ! $hotelsCount ) : ?>
			<a class="button button--dark button--arrowback mt-2 d-none" onclick="history.back()">Go Back</a>
		<?php endif; ?>

		<?php
		if ( $orderEventHotels ) :
			foreach ( $orderEventHotels as $hotel ) :
				$hotelCode = get_field( 'hotel_id', $hotel );
				if ( ! in_array( $hotelCode, $availableHotels ) ) :
					get_template_part(
						'template-parts/api/accommodation/hotel',
						'card-soldout',
						array(
							'hotelID'   => $hotel,
							'hotelCode' => $hotelCode,
							'order'     => $order,
						)
					);
				endif;
			endforeach;
		endif;
		?>
	</ul>
</div>
