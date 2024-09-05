<?php
/**
 * Template part for displaying hotel as card in list
 */

$hotel = $args['hotel'];
$order = $args['order'];
$api   = $args['api'];


$link = get_field( 'api_bedbanks_single_page', 'option' );

if ( is_array( $hotel ) ) :
	$hotel = $hotel[0];
endif;

$hotelCode   = $hotel->BasicPropertyInfo->HotelCode;


//get hotel data
$hotelTitle = $hotel->BasicPropertyInfo->HotelName;
$hotelLink  = $link . '?hotelid=' . $hotelCode;

//get from price
$fromPrice = 9999999;
$currency = 'GPB';
if ( gettype( $hotel->RoomRates->RoomRate ) == 'array' ) :
	foreach ( $hotel->RoomRates->RoomRate as $rate ) {
		$price = intval( $rate->Rates->Rate->Total->AmountAfterTax );
		$currency = $rate->Rates->Rate->Total->CurrencyCode;
		if ( $price != 0 && $price < $fromPrice ) {
			$fromPrice = $price;
		}
	}
else :
	foreach ( $hotel->RoomRates as $rate ) {
		$price = intval( $rate->Rates->Rate->Total->AmountAfterTax );
		$currency = $rate->Rates->Rate->Total->CurrencyCode;
		if ( $price != 0 && $price < $fromPrice ) {
			$fromPrice = $price;
		}
	}
endif;
$hotelPrice = $fromPrice == 9999999 ? false : $fromPrice/$order->getNightsQtt();
$hotelPrice = number_format((float)$hotelPrice, 2, '.', '');

//get hotel post data
$hotelImage       = false;
$hotelGallery     = false;
$hotelCityName    = '';
$hotelDescritpion = '';
$hotelPosition = false;
$showCardLabel    = false;
$cardLabels       = false;
$hotelRating      = 0;
$hotelMap         = false;
$hidePriority     = false;
$changePriority = false;
$changePriorityLabel = false;
$customMinNights = false;
$show_miles_from_event = false;
$miles_from_event = false;

$VendorMessages = $hotel->BasicPropertyInfo->VendorMessages->VendorMessage->SubSection->Paragraph;
if($VendorMessages):
	foreach($VendorMessages as $VendorMessage):
		if(isset($VendorMessage->Name) && $VendorMessage->Name == 'Hotel image'):
			$hotelImage = $VendorMessage->Image;
		endif;
		if(isset($VendorMessage->Name) && $VendorMessage->Name == 'Hotel summary'):
			$hotelDescritpion = excerpt_str($VendorMessage->Text->_ , 30);
		endif;
	endforeach;
endif;

if(isset($hotel->BasicPropertyInfo->Award->Rating)):
	$hotelRating = $hotel->BasicPropertyInfo->Award->Rating;
endif;
if(isset($hotel->BasicPropertyInfo->Resort)):
	$hotelCityName = $hotel->BasicPropertyInfo->Resort;
endif;

if(isset($hotel->BasicPropertyInfo->Position)):
	$hotelPosition = $hotel->BasicPropertyInfo->Position;
	$eventId = $order->getEventPostID();
	$event_location = get_field('event_location', $eventId);
	if($event_location):
		$show_miles_from_event = true;
		$miles_from_event = calculateMapDistance(
			$hotelPosition->Latitude,
			$hotelPosition->Longitude,
			$event_location['lat'],
			$event_location['lng']
		);
		$miles_from_event = number_format($miles_from_event, 1);
	endif;
endif;

$priorityHotel = $hotel->BasicPropertyInfo->PriorityHotel;

$hotelInfo      = $api->GetHotelInfo( $order->getEventCode(), $order->getPassengerTypeQuantity(), $hotelCode, $order->getCheckinDate(), $order->getCheckoutDate() );
$hotelMinNights = false;
$hotelMaxNights = false;
if ( $hotelInfo ) :
	$hotelInfoRooms = $hotelInfo->FacilityInfo->GuestRooms->GuestRoom;
	if ( gettype( $hotelInfoRooms ) == 'array' ) :
		foreach ( $hotelInfoRooms as $room ) :
			if ( isset( $room->Restrictions ) && $room->Restrictions->MinMaxStays ) :
				if ( $hotelMinNights ) :
					if ( $hotelMinNights > $room->Restrictions->MinMaxStays->MinMaxStay->MinStay ) :
						$hotelMinNights = $room->Restrictions->MinMaxStays->MinMaxStay->MinStay;
						$hotelMaxNights = $room->Restrictions->MinMaxStays->MinMaxStay->MaxStay;
					endif;
				else :
					$hotelMinNights = $room->Restrictions->MinMaxStays->MinMaxStay->MinStay;
					$hotelMaxNights = $room->Restrictions->MinMaxStays->MinMaxStay->MaxStay;
				endif;
			endif;
		endforeach;
	elseif ( isset( $hotelInfoRooms->Restrictions ) && $hotelInfoRooms->Restrictions->MinMaxStays ) :
			$hotelMinNights = $hotelInfoRooms->Restrictions->MinMaxStays->MinMaxStay->MinStay;
			$hotelMaxNights = $hotelInfoRooms->Restrictions->MinMaxStays->MinMaxStay->MaxStay;
	endif;
endif;

if($customMinNights) $hotelMinNights = $customMinNights;
?>
<?php if ( $hotelPrice ) : ?>
	<li class="accommodations__item bg--light mb-2 d-flex g-20" data-rating="<?php echo intval( $hotelRating ); ?>" data-distance="<?php echo $miles_from_event?$miles_from_event:99999; ?>" data-price="<?php echo intval( $fromPrice ); ?>" data-priority="<?php echo $priorityHotel?$priorityHotel:0; ?>">
		<?php
		if ( $hotelImage || $hotelGallery ) :
			?>
			<div class="accommodations__item-image-wrapper" href="">
				<?php if ( $hotelGallery ) : ?>
					<ul class="accommodations__item-image-gallery">
						<?php foreach ( $hotelGallery as $index => $image ) : ?>
							<li class="accommodations__item-image-gallery__item">
								<div class="image-ratio" style="padding-bottom: 100%;">
									<img src="<?php echo $image['url']; ?>" alt="">
								</div>
							</li>
							<?php if($index >= 5) break; ?>
						<?php endforeach; ?>
					</ul>
				<?php else : ?>
					<div class="image-ratio" style="padding-bottom: 100%;">
						<img src="<?php echo $hotelImage; ?>" alt="">
					</div>
				<?php endif; ?>
				</div>
		<?php endif; ?>

		<div class="accommodations__item-content p-2 d-flex flex-column align-items-start">
			<a href="<?php echo $hotelLink; ?>" class="h3"><?php echo esc_html( $hotelTitle ); ?></a>

			<div class="d-flex g-15 align-items-center mt-1 mb-2">
				<span class="link--style--2"><?php echo esc_html( $hotelCityName ); ?></span>
				<?php

				if ( $show_miles_from_event && $miles_from_event ) :
					$distance_format = 'km';
					?>
					<span class="text--size--12 font--weight--700 text-uppercase text--opacity">
						<?php
						printf(
							$distance_format=='km'?esc_html__( '%s KM from event', 'nirvana' ):esc_html__( '%s Miles from event', 'nirvana' ),
							$miles_from_event
						);
						?>
					</span>
				<?php endif; ?>
			</div>

			<?php if ( $hotelDescritpion ) : ?>
				<div class="text--opacity text--size--16 pb-2 accommodations__item-content-description">
					<?php echo $hotelDescritpion; ?>
				</div>
			<?php endif; ?>
			
			<a class="button button--sm button--orange mt-1 mb-1" href="<?php echo $hotelLink; ?>">View</a>

			<?php the_rating( intval( $hotelRating ), 'mt-auto' ); ?>
		</div>

		<div class="accommodations__item-additional-content d-flex flex-column pb-2 pr-2 ml-auto">
			
			<div class="icon-bookmark-list d-flex g-15">
				<?php if ( $hotelMinNights ) : ?>
					<?php if ( intval( $hotelMinNights ) > intval( $order->getNightsQtt() ) ) : ?>
						<?php the_icon_bookmark_min_nights( intval( $hotelMinNights ) ); ?>
					<?php endif; ?>
				<?php endif; ?>

				<?php
				if ( $priorityHotel == 1 && ! $hidePriority ) :
					if($changePriority && $changePriorityLabel):
						the_icon_bookmark_primary($changePriorityLabel);
					else:
						the_icon_bookmark_primary();
					endif;?>
				<?php endif; ?>

				<?php if ( $showCardLabel && $cardLabels ) : ?>
					<?php foreach ( $cardLabels as $item ) : ?>
						<?php $label = $item['label']; ?>
						<?php if ( $label ) : ?>
							<?php the_icon_bookmark_primary( $label ); ?>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>

			<?php if ( true ) : ?>
				<?php if ( ! $hotelPrice ) : ?>
					<span class="h3 mt-auto text-nowrap text-right text-uppercase"><?php esc_html_e( 'Sold Out', 'nirvana' ); ?></span>
				<?php else : ?>
					<span class="h3 mt-auto text-nowrap text-right">
						<?php
						printf(
							/* translators: 1: Currency 2: Room price */
							esc_html__( 'From %1$s%2$s', 'nirvana' ),
							$order->getCurrencySymbolByCode($currency),
							esc_html( $hotelPrice )
						);
						?>
						<span class="text--size--14"><?php _e('per night' , 'nirvana'); ?></span>
					</span>
				<?php endif; ?>

				<span class="text--size--16 text--opacity mt-05 text-nowrap text-right">
					<?php
					printf(
						/* translators: 1: Nights 2: Adults */
						esc_html__( '%1$s nights, %2$s', 'nirvana' ),
						$order->getNightsQtt(),
						$order->getPeopleQtt(),
					);
					?>
				</span>
			<?php endif; ?>
		</div>

		<?php if ( /*0 === $room['count']*/ false ) : ?>
			<div class="accommodations__item-bg-sold-out"></div>
		<?php endif; ?>

		<?php if ( $hotelPosition ) : ?>
			<span class="d-none js-hotel-location" data-lat="<?php echo esc_attr( $hotelPosition->Latitude ); ?>" data-lng="<?php echo esc_attr( $hotelPosition->Longitude ); ?>" data-hotel="<?php echo $hotelCode; ?>"></span>
			<!-- #popup-hotel-location -->
			<div id="popup-hotel-location-<?php echo $hotelCode; ?>" class="popup-block popup-hotel-location">
				<div class="popup-block__background"></div>
				<div class="popup-block__inner">
					<div class="container popup-block__container position-relative">
						<div class="popup-block__content text-color-light p-2">
							<span class="popup-block__close"></span>
							<div class="row">
								<div class="col-md-4 d-flex flex-column text--color--dark">
									<?php if ( $hotelImage ) : ?>
									<div class="popup-hotel-location__image-wrapper">
										<img class="w-100 h-auto" src="<?php echo $hotelImage; ?>" alt="">
										<?php if ( $hotelMinNights ) : ?>
											<?php if ( intval( $hotelMinNights ) > intval( $order->getNightsQtt() ) ) : ?>
												<?php the_icon_bookmark_min_nights( intval( $hotelMinNights ) ); ?>
											<?php endif; ?>
										<?php endif; ?>

										<?php
										if ( $priorityHotel == 1 && ! $hidePriority ) :
											if($changePriority && $changePriorityLabel):
												the_icon_bookmark_primary($changePriorityLabel);
											else:
												the_icon_bookmark_primary();
											endif;
										endif; ?>
									</div>
									<?php endif; ?>

									

									<div class="popup-hotel-location__subhead mt-2 d-flex justify-content-between">
										<span class="text-uppercase text--color--light-orange text--size--12 font--weight--700"><?php echo esc_html( $hotelCityName ); ?></span>
										<?php
										if ( $show_miles_from_event && $miles_from_event ) :
											$distance_format = 'km';
											?>
											<span class="text-uppercase text--opacity text--size--12 font--weight--700">
												<?php
												printf(
													$distance_format=='km'?esc_html__( '%s KM from event', 'nirvana' ):esc_html__( '%s Miles from event', 'nirvana' ),
													$miles_from_event
												);
												?>
											</span>
										<?php endif; ?>
									</div>

									<span class="d-block h3 mt-2"><?php echo esc_html( $hotelTitle ); ?></span>
									
									<?php the_rating( intval( $hotelRating ), 'mt-1' ); ?>

									<?php if ( $hotelDescritpion ) : ?>
										<p class="text--opacity mt-2"><?php echo excerpt_str( $hotelDescritpion, 40 ); ?></p>
									<?php endif; ?>

									<div class="d-flex align-items-center justify-content-between mt-auto pt-2">
										<div>
											<span class="d-block h3">
												<?php
												printf(
													/* translators: 1: Currency 2: Room price */
													esc_html__( 'From %1$s%2$s', 'nirvana' ),
													$order->getCurrencySymbol(),
													esc_html( $hotelPrice )
												);
												?>
											</span>
											<span class="d-block text--opacity mt-05">
												<?php
												printf(
													/* translators: 1: Nights 2: Adults */
													esc_html__( '%1$s nights, %2$s', 'nirvana' ),
													$order->getNightsQtt(),
													$order->getPeopleQtt(),
												);
												?>
											</span>
										</div>
										<a href="<?php echo $hotelLink; ?>" class="button button--orange"><?php _e('Reserve' , 'nirvana'); ?></a>
									</div>
								</div>
								<div class="col-md-8">
									<div class="acf-map" data-zoom="16">
										<div class="marker" data-lat="<?php echo esc_attr($hotelPosition->Latitude); ?>" data-lng="<?php echo esc_attr($hotelPosition->Longitude); ?>"></div>
										<?php
										$eventId        = $order->getEventPostID();
										$event_location = get_field('event_location', $eventId);

										$add_event_startfinish = get_field('add_event_startfinish', $eventId);
										$event_start_location = get_field('event_start_location', $eventId);
										$event_finish_location = get_field('event_finish_location', $eventId);
										if ($event_location) :
										?>
											<div class="eventmarker" data-lat="<?php echo esc_attr($event_location['lat']); ?>" data-lng="<?php echo esc_attr($event_location['lng']); ?>"></div>
										<?php endif; ?>
										<?php if($add_event_startfinish): ?>
											<?php if($event_start_location): ?>
												<div class="event-start-marker" data-lat="<?php echo esc_attr($event_start_location['lat']); ?>" data-lng="<?php echo esc_attr($event_start_location['lng']); ?>"></div>
											<?php endif; ?>
											<?php if($event_finish_location): ?>
												<div class="event-finish-marker" data-lat="<?php echo esc_attr($event_finish_location['lat']); ?>" data-lng="<?php echo esc_attr($event_finish_location['lng']); ?>"></div>
											<?php endif; ?>
										<?php endif; ?>
									</div>
									<div class="map-notice text--size--14 font--weight--600">
										<?php if ( $event_location ) : ?>
											<span>
												<img src="<?php echo get_template_directory_uri(); ?>/assets/images/map/event-icon.png"> - Event
											</span>
										<?php endif; ?>
										<span>
											<img src="<?php echo get_template_directory_uri(); ?>/assets/images/map/hotel-icon.png"> - Hotel
										</span>
										<?php if($add_event_startfinish): ?>
											<?php if($event_start_location): ?>
												<span>
													<img src="<?php echo get_template_directory_uri(); ?>/assets/images/map/start-icon.png"> - Event Start
												</span>
											<?php endif; ?>
											<?php if($event_finish_location): ?>
												<span>
													<img src="<?php echo get_template_directory_uri(); ?>/assets/images/map/finish-icon.png"> - Event Finish
												</span>
											<?php endif; ?>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div> <!-- #popup-hotel-location -->
			<?php endif; ?>
	</li>
<?php endif; ?>