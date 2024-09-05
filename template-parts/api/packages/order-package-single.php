<?php
$debug = false;

$order = $args['order'];
$api   = $args['api'];


$toursData = $order->getReservedMultiplePackagesData();
$hotelsData = array();
foreach($toursData as $tourData):
	array_push($hotelsData , $tourData->TourComponents->AccommodationItems->AccommodationComponent);
endforeach;

$hotelCodes = array();
if (is_array($hotelsData[0])):
	foreach ($hotelsData[0] as $hotel) :
		if(!in_array($hotel->RoomStay->BasicPropertyInfo->HCode , $hotelCodes)) array_push($hotelCodes, $hotel->RoomStay->BasicPropertyInfo->HCode);
	endforeach;
else :
	array_push($hotelCodes, $hotelsData[0]->RoomStay->BasicPropertyInfo->HCode);
endif;
?>

<h1 class="h3 mb-4"><?php echo $tourData->TourDetails->TourName; ?></h1>
<div class="book-accommodation__form-loading" style="display: none;">
	<img src="<?php echo get_template_directory_uri(); ?>/assets/images/loader.svg" alt="">
</div>
<?php
foreach ($hotelCodes as $hotelIndex => $hotelCode) :
?>
	<div class="package-hotel-item package-hotel-item--<?php echo $hotelIndex; ?>" data-index="<?php echo $hotelIndex; ?>">
		<?php
		$hotelPostId = $api->GetAccomHotelPostID($hotelCode);

		if (empty($hotelPostId) || 'publish' !== get_post_status($hotelPostId)) {
			return;
		}

		//get acf hotel info
		$hotelImage                   = false;
		$hotelCityName                = '';
		$hotelDescritpion             = '';
		$hotelOverview                = '';
		$hotelRating                  = 0;
		$accommodation_overview_title = '';
		$customMinNights = false;
		$video_tour_available = false;
		$video_tour = false;
		if ($hotelPostId) :
			$hotelImage                   = get_the_post_thumbnail_url($hotelPostId, 'full');
			$hotelCityName                = get_field('hotel_location', $hotelPostId);
			$hotelDescritpion             = get_field('hotel_description', $hotelPostId);
			$hotelOverview                = get_field('location_overview', $hotelPostId);
			$hotelRating                  = get_field('hotel_rating', $hotelPostId);
			$accommodation_overview_title = get_field('accommodation_overview_title', $hotelPostId);
			$customMinNights = get_field('minimum_nights', $hotelPostId);
			$video_tour_available = get_field('video_tour_available', $hotelPostId);
			$video_tour = get_field('video_tour', $hotelPostId);
		endif;
		?>

		<div class="package-main-wrapper">

			<div class="package-main-info px-2 py-3">

				<?php if ($debug) : ?>
					<pre style="color: #000; z-index: 4; position: relative">
						<?php
						//$order->outputOrderData();
						?>
						<hr>
						<?php //print_r($tourData); 
						?>
						<hr>
						<?php
						//print_r( $hotels );
						?>
						<hr>
						<?php
						print_r($hotelData);
						?>
						<hr>
					</pre>
				<?php endif; ?>


				<div class="bg--white position-absolute top-0 start-0 w-100 h-100 d-none"></div>

				<div class="d-flex align-items-center mb-3 header-booking__main__accom">
					<h3 class="h3"><?php echo get_the_title($hotelPostId); ?></h3>
					<?php the_rating(intval(get_field('hotel_rating', $hotelPostId)), 'ml-sm-1 mr-sm-1'); ?>
				</div>

				<?php
				get_template_part(
					'template-parts/api/accommodation/hotel',
					'gallery',
					array(
						'hotelPostID' => $hotelPostId,
					)
				);
				?>

				<?php if ($hotelDescritpion) : ?>
					<div class="accommodation-content p2 text--opacity mt-5 position-relative content-block">
						<?php echo $hotelDescritpion; ?>
					</div>
				<?php endif; ?>

				<?php
				$hotelAmenities = get_field('hotel_amenities', $hotelPostId);
				if ($hotelAmenities) :
				?>
					<div class="accommodation-amenities my-5 position-relative">
						<h3 class="p2 mb-1">Amenities</h3>
						<ul class="text-uppercase text--size--12 font--weight--700 mt-3">
							<?php foreach ($hotelAmenities as $item) : ?>
								<?php
								$icon = $item['icon'];
								$name = $item['name'];
								?>
								<li class="d-flex g-10 align-items-center">
									<?php if ($icon) : ?>
										<span class="accommodation-amenities__icon">
											<img src="<?php echo $icon['url']; ?>" alt="">
										</span>
									<?php else : ?>
										<?php the_svg_by_sprite('list-icon-grey-plus', 20, 20); ?>
									<?php endif; ?>
									<span><?php echo $name; ?></span>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>

				<?php if ($video_tour_available &&  $video_tour) : ?>
					<div class="my-8">
						<div class="row justify-content-center">
							<div class="col-lg-6">
								<div class="videoBlock__video">
									<div class="video video--oembed">
										<?php echo $video_tour; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>
			</div>

			
			<?php if(sizeof($hotelsData) == 1): ?>
				<h2 class="h3 mt-5 mb-3"><?php esc_html_e( 'The following room is included in your Event Package', 'nirvana' ); ?></h2>
			<?php else: ?>
				<h2 class="h3 mt-5 mb-3"><?php esc_html_e( 'The following rooms are included in your Event Package', 'nirvana' ); ?></h2>
			<?php endif; ?>

			<?php 
			foreach($hotelsData as $tourIndex => $hotelsDataTour):
				?>
				<div class="js-packages-tour-rooms">
					<?php
					$hotelData = array();
					if (is_array($hotelsDataTour)) :
						foreach ($hotelsDataTour as $hotel) :
							if ($hotelCode == $hotel->RoomStay->BasicPropertyInfo->HCode) :
								array_push($hotelData, $hotel);
							endif;
						endforeach;
					else :
						array_push($hotelData, $hotelsDataTour);
					endif;

					get_template_part(
						'template-parts/api/packages/package',
						'rooms-list',
						array(
							'rooms'       => $hotelData,
							'hotelPostID' => $hotelPostId,
							'hotelCode'   => $hotelCode,
							'order'       => $order,
							'room_id'     => $tourIndex,
						)
					);
					?>
					<form class="js-package-hotel-data d-none">
						<input type="hidden" id="hotel-id" class="hotel-id" name="hotel_id" value="<?php echo $hotelCode; ?>">
						<input type="hidden" id="hotel-post-id" class="hotel-post-id" name="hotel_post_id" value="<?php echo $hotelPostId; ?>">
						<input type="hidden" id="room-id" class="room-id" name="room_id" value="">
						<input type="hidden" id="room-title" class="room-title" name="room_title" value="">
						<input type="hidden" id="room-bb" class="room-bb" name="room_bb" value="">
						<input type="hidden" id="room-price" class="room-price" name="room_price" value="">
						<input type="hidden" id="room-duration" class="room-duration" name="room_duration" value="">
						<input type="hidden" id="room-occupancy" class="room-occupancy" name="room_occupancy" value="">
						<input type="hidden" id="accom-id" class="accom-id" name="AccomId" value="">
						<input type="hidden" id="tour-id" class="tour-id" name="TourIndex" value="<?php echo $tourIndex; ?>">
						<input type="hidden" id="accom-selection-state" class="accom-selection-state" name="SelectionState" value="">
					</form>
				</div>
				<?php
			endforeach;
			?>
			

			<div class="package__buttons-bar d-flex p-2 mt-2 mb-5 mx-n2">
				<?php if($hotelIndex == 0 ): ?>
					<div class="package__buttons-bar__item">
						<a href="<?php the_field('api_packages_page', 'option'); ?>" class="button button--dark-white">Back to Event Packages</a>
					</div>
				<?php else: ?>
					<div class="package__buttons-bar__item">
						<button class="button button--dark-white w-100 js-prev-hotel">Previous Hotel</button>
					</div>
				<?php endif; ?>
				<?php if($hotelIndex == (sizeof($hotelCodes)-1) ): ?>
					<form method="POST" action="<?php the_field('api_package_order_page' , 'option'); ?>" id="package-book-form" class="package__buttons-bar__item" data-order="<?php echo $order->getOrderNumber(); ?>">
						<button class="button button--orange w-100" type="submit">Continue</button>
					</form>
				<?php else: ?>
					<div class="package__buttons-bar__item">
						<button class="button button--orange w-100 js-next-hotel">Next Hotel</button>
					</div>
				<?php endif; ?>	
			</div>

			<?php
			$hotelLocation = get_field('hotel_location_map', $hotelPostId);
			if ($hotelLocation) :
			?>
				<section class="package-main-info px-2 py-3 text--color--dark position-relative ">
					<h2 class="h3">
						<?php if ($accommodation_overview_title) : ?>
							<?php echo $accommodation_overview_title; ?>
						<?php else : ?>
							<?php esc_html_e('Location', 'nirvana'); ?>
						<?php endif; ?>
					</h2>
					<div class="popup-hotel-location__subhead d-flex mt-1">
						<span class="text-uppercase text--color--light-orange text--size--12 font--weight--700"><?php echo esc_html(get_field('hotel_location', $hotelPostId)); ?></span>
						<?php
						$show_miles_from_event = get_field('show_miles_from_event', $hotelPostId);
						$distance_format = get_field('distance_format', $hotelPostId);
						$miles_from_event      = get_field('miles_from_event', $hotelPostId);
						if ($show_miles_from_event && $miles_from_event) :
						?>
							<span class="text-uppercase text--opacity text--size--12 font--weight--700 ml-1">
								<?php
								printf(
									$distance_format == 'km' ? esc_html__('%s KM from event', 'nirvana') : esc_html__('%s Miles from event', 'nirvana'),
									$miles_from_event
								);
								?>
							</span>
						<?php endif; ?>
					</div>
					<?php if ($hotelOverview) : ?>
						<div class="text--opacity mt-3 content-block"><?php echo $hotelOverview; ?></div>
					<?php endif; ?>
					<div class="accommodation__location-map mt-3">
						<?php if ($hotelLocation) : ?>
							<div class="acf-map" data-zoom="16">
								<div class="marker" data-lat="<?php echo esc_attr($hotelLocation['lat']); ?>" data-lng="<?php echo esc_attr($hotelLocation['lng']); ?>"></div>
								<?php
								$eventId        = $order->getEventPostID();
								$event_location = get_field('event_location', $eventId);
								if ($event_location) :
								?>
									<div class="eventmarker" data-lat="<?php echo esc_attr($event_location['lat']); ?>" data-lng="<?php echo esc_attr($event_location['lng']); ?>"></div>
								<?php endif; ?>
							</div>
							<div class="map-notice text--size--14 font--weight--600">
								<span>
									<img src="<?php echo get_template_directory_uri(); ?>/assets/images/map/event-icon.png"> - Event
								</span>
								<span>
									<img src="<?php echo get_template_directory_uri(); ?>/assets/images/map/hotel-icon.png"> - Hotel
								</span>
							</div>

						<?php endif; ?>
					</div>
				</section>
			<?php endif; ?>
		</div>
	</div>
<?php
endforeach;