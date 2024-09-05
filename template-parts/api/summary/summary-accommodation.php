<?php
$order = $args['order'];
$api   = $args['api'];
$quote = $args['quote'];

$orderType = $order->getOrderType();
if($orderType == 'tour'):
	$hotelsData = $order->getPackageReservedAccom();
else:
	$hotelsData = $order->getReservedAccommodations();
endif;
?>


<?php if ( $hotelsData ) : ?>
	<div class="summary-accom mt-3">
		<h3 class="mb-1">Accommodation</h3>
		<ul class="summary-rooms-list__list">
			<?php
			//main loop
			$itemIndex = 0;
			foreach ( $hotelsData as $item ) :
				?>
				<?php
				$hotelPostID    = $api->GetAccomHotelPostID( $item['hotel'] );

				$hotelPostRooms = false;
				$hotelRating    = false;
				$hotelTitle     = false;
				if($hotelPostID):
					$hotelPostRooms = get_field( 'hotel_rooms', $hotelPostID );
					$hotelRating    = get_field( 'hotel_rating', $hotelPostID );
					$hotelTitle     = get_the_title( $hotelPostID );
				else:
					if(isset($item['hotel_name'])) $hotelTitle = $item['hotel_name'];
				endif;

				$roomTitle       = $item['name'];
				$roomPrice       = isset($item['price'])?$item['price']:0;
				$roomCurrency    = 'GBP';
				$roomImage       = false;
				$roomFeatures    = false;
				$roomSupplements = false;
				$roomMinNights   = false;
				$maxPeople       = 0;
				if ( $hotelPostRooms ) :
					foreach ( $hotelPostRooms as $roomItem ) :
						if ( $roomItem['id'] == $item['id'] ) :
							if ( $roomItem['image'] ) :
								$roomImage = $roomItem['image']['url'];
							endif;
							$maxPeople = $roomItem['max_adults'];
							break;
						endif;
					endforeach;
				endif;

				if ($quote && isset($quote->OTA_ViewQuoteResult->Hotels->Hotel)) :
					$removeHotelIDs = array();
					$roomStayID = false;
					$roomIndex = 0;
					$roomNotes = false;
					if (is_array($quote->OTA_ViewQuoteResult->Hotels->Hotel)) :
						foreach ($quote->OTA_ViewQuoteResult->Hotels->Hotel as $hotel) :
							if(is_array($hotel->RoomStays->RoomStay)):
								foreach($hotel->RoomStays->RoomStay as $room):
									if(($room->HUCode == $item['id'] || trim($room->RoomType) == $item['name']) || $roomIndex == $itemIndex):
										//print_r($room->RoomPricingInfo->ItinTotalFare->TotalFare);
										$roomPrice       = $room->RoomPricingInfo->ItinTotalFare->TotalFare->Amount;
										$roomStayID      = $room->ID;
										$roomCurrency    = $room->RoomPricingInfo->ItinTotalFare->TotalFare->CurrencyCode;
										$roomNotes 		 = $room->Notes;
										break;
									endif;
									$roomIndex++;
								endforeach;
							else:
								if(($hotel->RoomStays->RoomStay->HUCode == $item['id'] || trim($hotel->RoomStays->RoomStay->RoomType) == $item['name']) || $roomIndex == $itemIndex):
									//print_r($hotel->RoomStays->RoomStay->RoomPricingInfo->ItinTotalFare->TotalFare);
									$roomPrice       = $hotel->RoomStays->RoomStay->RoomPricingInfo->ItinTotalFare->TotalFare->Amount;
									$roomStayID      = $hotel->RoomStays->RoomStay->ID;
									$roomCurrency    = $hotel->RoomStays->RoomStay->RoomPricingInfo->ItinTotalFare->TotalFare->CurrencyCode;
									$roomNotes 		 = $hotel->RoomStays->RoomStay->Notes;
								endif;
								$roomIndex++;
							endif;
						endforeach;
					else :
						if(is_array($quote->OTA_ViewQuoteResult->Hotels->Hotel->RoomStays->RoomStay)):
							foreach($quote->OTA_ViewQuoteResult->Hotels->Hotel->RoomStays->RoomStay as $room):
								if(($room->HUCode == $item['id'] || trim($room->RoomType) == $item['name']) || $roomIndex == $itemIndex):
									//print_r($room->RoomPricingInfo->ItinTotalFare->TotalFare);
									$roomPrice       = $room->RoomPricingInfo->ItinTotalFare->TotalFare->Amount;
									$roomStayID      = $room->ID;
									$roomCurrency    = $room->RoomPricingInfo->ItinTotalFare->TotalFare->CurrencyCode;
									$roomNotes 		 = $room->Notes;
									break;
								endif;
								$roomIndex++;
							endforeach;
						else:
							
							if(($quote->OTA_ViewQuoteResult->Hotels->Hotel->RoomStays->RoomStay->HUCode == $item['id'] || trim($quote->OTA_ViewQuoteResult->Hotels->Hotel->RoomStays->RoomStay->RoomType) == $item['name']) || $roomIndex == $itemIndex):
								//print_r($quote->OTA_ViewQuoteResult->Hotels->Hotel->RoomStays->RoomStay->RoomPricingInfo->ItinTotalFare->TotalFare);
								$roomPrice       = $quote->OTA_ViewQuoteResult->Hotels->Hotel->RoomStays->RoomStay->RoomPricingInfo->ItinTotalFare->TotalFare->Amount;
								$roomStayID      = $quote->OTA_ViewQuoteResult->Hotels->Hotel->RoomStays->RoomStay->ID;
								$roomCurrency    = $quote->OTA_ViewQuoteResult->Hotels->Hotel->RoomStays->RoomStay->RoomPricingInfo->ItinTotalFare->TotalFare->CurrencyCode;
								$roomNotes 		 = $quote->OTA_ViewQuoteResult->Hotels->Hotel->RoomStays->RoomStay->Notes;
								$roomIndex++;
							endif;
						endif;
					endif;

					if($roomStayID && $roomPrice):
						if(is_array($quote->OTA_ViewQuoteResult->Extras->Extra)):
							foreach($quote->OTA_ViewQuoteResult->Extras->Extra as $item):
								if($item->ParentID == $roomStayID):
									$roomPrice += $item->ExtraPricingInfo->ItinTotalFare->TotalFare->Amount;
								endif; 
							endforeach;
						else:
							if($quote->OTA_ViewQuoteResult->Extras->Extra->ParentID == $roomStayID):
								$roomPrice += $quote->OTA_ViewQuoteResult->Extras->Extra->ExtraPricingInfo->ItinTotalFare->TotalFare->Amount;
							endif; 
						endif;
					endif;
				endif;
				?>
				<li class="summary-rooms-list__item mb-1">
					<div class="summary-rooms-list__item-inner  d-flex p-1">
						<?php if ( $roomImage ) : ?>
							<div class="summary-rooms-list__item__img mr-sm-2">
									<div class="image-ratio" style="padding-bottom: 100%;">
										<img src="<?php echo $roomImage; ?>" alt="">
									</div>
								
							</div>
						<?php endif; ?>
						<div class="summary-rooms-list__item__content d-flex flex-column">
							<div class="d-flex justify-content-between">
								<h4><?php echo $hotelTitle; ?></h4>
								<?php the_rating( intval( $hotelRating ), 'ml-1' ); ?>
							</div>
							<h4 class="text--size--16 mt-1"><?php echo esc_html( $roomTitle ); ?></h4>

							<?php if ( $roomFeatures ) : ?>
								<ul class="d-flex flex-wrap g-7 text--size--16 pt-1">
									<?php foreach ( $room['features'] as $feature ) : ?>
										<li class="d-flex align-items-center g-10 text-capitalize mr-3">
											<?php the_svg_by_sprite( 'list-icon-grey-plus', 20, 20, 'flex-shrink-0' ); ?>
											<span class="text--opacity"><?php echo esc_html( $feature ); ?></span>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>

							<div class="d-flex justify-content-between align-items-center mt-auto pt-3">
								<?php if ( $roomSupplements ) : ?>
									<div class="summary-rooms-list__item__supplements d-flex align-items-center">
										<h5 class="text--size--12 text-uppercase mr-9">Room supplements</h5>
										<?php foreach ( $room['supplements'] as $supplement ) : ?>
											<div class="d-flex g-10 text--size--16">
												<div class="d-flex w-100 align-items-center justify-content-between">
													<span class="text--opacity"><?php echo esc_html( $supplement['name'] ); ?> &nbsp;</span>
													<span> <?php echo $order->getCurrencySymbol(); ?><?php echo esc_html( $supplement['price'] ); ?></span>
												</div>
											</div>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>
								<?php if($roomPrice!=0 && $roomPrice>0): ?>
									<div class="h3 ml-auto"><?php echo $order->getCurrencySymbolByCode($roomCurrency); ?><?php echo $roomPrice; ?></div>
								<?php endif; ?>
							</div>
							<?php if($roomNotes): ?>
								<p class="text--size--14 mt-2"><?php echo $roomNotes; ?></p>
							<?php endif; ?>
						</div>
					</div>
				</li>
				<?php $itemIndex++; ?>
			<?php endforeach; ?>
		</ul>
	</div>

<?php endif; ?>
