<?php

$bedbanks = $args['bedbanks'];
$order  = $args['order'];
$api    = $args['api'];

$orderEventId     = $order->getEventPostID();
$orderEventHotels = get_field( 'event_hotels', $orderEventId );
$orderEventHotelTitles = array();
$availableHotels  = array();

if($orderEventHotels):
	foreach($orderEventHotels as $postHotel):
		$title = get_the_title($postHotel);
		array_push($orderEventHotelTitles , $title);
	endforeach;
endif;

$bedbanks_top_text = get_field('bedbanks_top_text' , $orderEventId);
?>
<div class="accommodations-bedbanks mt-4">
	<?php if($bedbanks_top_text): ?>
		<div class="content-block mb-4">
			<h4><?php _e('NONE SUPPORTED NIRVANA HOTELS'); ?></h4>
			<?php echo $bedbanks_top_text; ?>
		</div>
	<?php else: ?>
		<div class="content-block mb-4">
			<h4><?php _e('NONE SUPPORTED NIRVANA HOTELS'); ?></h4>
		</div>
	<?php endif; ?>
	<ul class="accommodations__list js-bedbanks-list d-flex flex-column">
		<?php $hotelsCount = 0; ?>
		<?php if ( $bedbanks ) : ?>
			<?php foreach ( $bedbanks as $roomHotels ) : ?>
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
								if ( $hotelPrice && !in_array( $hotel->BasicPropertyInfo->HotelName , $orderEventHotelTitles) ) :
									get_template_part(
										'template-parts/api/bedbanks/hotel',
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
								'template-parts/api/bedbanks/hotel',
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
		<?php if ( $bedbanks && ! $hotelsCount ) : ?>
			<a class="button button--dark button--arrowback mt-2 d-none" onclick="history.back()">Go Back</a>
		<?php endif; ?>
	</ul>
</div>