<?php
$debug = false;

$order = $args['order'];
$api = $args['api'];


$hotelCode = $_GET['hotelid'];

$orderEventId = $order->getEventPostID();
$hide_accommodation_tab = get_field('hide_accommodation_tab', $orderEventId);

//Get rooms general info
$hotels = $order->searchBedBanks();
$hotelData = array();

if (sizeof($hotels) != 0):
	$roomCounter = 0;
	foreach ($hotels as $roomHotels):
		if ($roomHotels):
			if (is_array($roomHotels)):
				foreach ($roomHotels as $hotel):
					if ($hotel->BasicPropertyInfo->HotelCode == $hotelCode):
						$hotelData[$roomCounter] = $hotel;
					endif;
				endforeach;
			elseif ($roomHotels->BasicPropertyInfo->HotelCode == $hotelCode):
				$hotelData[$roomCounter] = $roomHotels;
			endif;

			//get minimum room price
			$fromPrice = false;
			foreach ($hotelData as $hotel):
				if (gettype($hotel->RoomRates->RoomRate) == 'array'):
					foreach ($hotel->RoomRates->RoomRate as $rate) {
						$price = intval($rate->Rates->Rate->Total->AmountAfterTax);
						if ($price < $fromPrice) {
							$fromPrice = $price;
						}
					}
				else:
					foreach ($hotel->RoomRates as $rate) {
						$price = intval($rate->Rates->Rate->Total->AmountAfterTax);
						if ($price < $fromPrice) {
							$fromPrice = $price;
						}
					}
				endif;
			endforeach;
			$hotelPrice = $fromPrice;
		endif;
		++$roomCounter;
	endforeach;
endif;


//get hotel data
//Get rooms detailed info

if (is_array($hotelData[0]->RoomRates->RoomRate)):
	$locator = $hotelData[0]->RoomRates->RoomRate[0]->Rates->Rate->TPA_Extensions->Locator;
else:
	$locator = $hotelData[0]->RoomRates->RoomRate->Rates->Rate->TPA_Extensions->Locator;
endif;
$hotelInfo = $api->GetBedBankHotelInfo($order->getEventCode(), $order->getPassengerTypeQuantity(), $locator, $order->getCheckinDate(), $order->getCheckoutDate());


//get acf hotel info
$hotelImage = false;
$hotelCityName = '';
$hotelDescritpion = '';
$hotelOverview = '';
$hotelRating = 0;
$accommodation_overview_title = '';
$customMinNights = false;
$video_tour_available = false;
$video_tour = false;
$show_miles_from_event = false;
$miles_from_event = false;
$hotelLat = false;
$hotelLng = false;

$hotelImage = $hotelInfo->MultimediaDescriptions->MultimediaDescription->ImageItems->ImageItem;
$hotelDescritpion = $hotelInfo->MultimediaDescriptions->MultimediaDescription->TextItems->TextItem->Description->_;

$hotel = $hotelData[0];
$hotelTitle = $hotel->BasicPropertyInfo->HotelName;

if (isset($hotel->BasicPropertyInfo->Award->Rating)):
	$hotelRating = $hotel->BasicPropertyInfo->Award->Rating;
endif;
if (isset($hotel->BasicPropertyInfo->Resort)):
	$hotelCityName = $hotel->BasicPropertyInfo->Resort;
endif;

if (isset($hotel->BasicPropertyInfo->Position)):
	$hotelPosition = $hotel->BasicPropertyInfo->Position;
	$eventId = $order->getEventPostID();
	$event_location = get_field('event_location', $eventId);
	if ($event_location):
		$show_miles_from_event = true;
		$hotelLat = $hotelPosition->Latitude;
		$hotelLng = $hotelPosition->Longitude;
		$miles_from_event = calculateMapDistance(
			$hotelPosition->Latitude,
			$hotelPosition->Longitude,
			$event_location['lat'],
			$event_location['lng']
		);
		$miles_from_event = number_format($miles_from_event, 1);
	endif;
endif;

$hotelMinNights = 1;
if ($customMinNights)
	$hotelMinNights = $customMinNights;
?>
<div class="accommodation pl-xl-3 pr-xl-2">
	<?php if ($debug): ?>
		<pre style="color: #000; z-index: 4; position: relative">
				<?php
				$order->outputOrderData();
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
				<?php
				print_r($hotelInfo);
				?>
				<hr>
			</pre>
	<?php endif; ?>


	<div class="bg--white position-absolute top-0 start-0 w-100 h-100"></div>

	<div class="d-flex align-items-center header-booking__main__accom position-relative mb-5">
		<a class="button button-back button--dark-transparent mr-sm-3"
			href="<?php echo get_field('api_accommodation_page', 'option'); ?>">
			<svg width="15" height="9" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg">
				<rect x="14.3286" y="5" width="12" height="1" transform="rotate(-180 14.3286 5)" fill="#000C26"
					stroke="#000C26" stroke-width="0.75" />
				<rect x="4.53564" y="8.03564" width="5" height="1" transform="rotate(-135 4.53564 8.03564)"
					fill="#000C26" stroke="#000C26" stroke-width="0.75" />
				<rect x="5.24268" y="1.67139" width="5" height="1" transform="rotate(135 5.24268 1.67139)"
					fill="#000C26" stroke="#000C26" stroke-width="0.75" />
			</svg>
		</a>
		<h1 class="h3"><?php echo $hotelTitle; ?></h1>
		<?php if ($hotelRating): ?>
			<?php the_rating(intval($hotelRating), 'ml-sm-1 mr-sm-1'); ?>
		<?php endif; ?>

		<a href="#rooms"
			class="button button--orange text-uppercase font--weight--700 text-center ml-auto reserve-btn"><?php echo esc_html_e('Reserve', 'nirvana'); ?></a>
	</div>

	<?php
	get_template_part(
		'template-parts/api/bedbanks/hotel',
		'gallery',
		array(
			'images' => $hotelImage,
		)
	);
	?>

	<?php if ($hotelDescritpion): ?>
		<div class="accommodation-content p2 my-5 position-relative content-block">
			<p><?php echo $hotelDescritpion; ?></p>
		</div>
	<?php endif; ?>

	<?php
	$hotelAmenities = get_field('hotel_amenities', $hotelPostId);
	if ($hotelAmenities):
		?>
		<div class="accommodation-amenities my-5 position-relative">
			<h3 class="p2 mb-1">Amenities</h3>
			<ul class="text-uppercase text--size--12 font--weight--700 mt-3">
				<?php foreach ($hotelAmenities as $item): ?>
					<?php
					$icon = $item['icon'];
					$name = $item['name'];
					?>
					<li class="d-flex g-10 align-items-center">
						<?php if ($icon): ?>
							<span class="accommodation-amenities__icon">
								<img src="<?php echo $icon['url']; ?>" alt="">
							</span>
						<?php else: ?>
							<?php the_svg_by_sprite('list-icon-grey-plus', 20, 20); ?>
						<?php endif; ?>
						<span><?php echo $name; ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>

	<?php if ($video_tour_available && $video_tour): ?>
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


<!-- IF ( ! TRAVELLERS DAYS TO STAY LESS THEN HOTEL MIN NIGHTS STAY) -->
<?php if ($hide_accommodation_tab): ?>
	<div id="rooms">
		<?php
		get_template_part(
			'template-parts/api/bedbanks/hotel',
			'rooms-list-static',
			array(
				'hotelPostID' => $hotelPostId,
			)
		);
		?>
		<style>
			.reserve-btn {
				display: none;
			}
		</style>
	</div>
<?php else: ?>
	<div id="rooms">
		<?php if ($hotelMinNights && intval($hotelMinNights) > intval($order->getNightsQtt())): ?>
			<?php
			get_template_part(
				'template-parts/api/bedbanks/hotel',
				'min-nights',
				array(
					'nights' => $hotelMinNights,
					'order' => $order,
					'hotelCode' => $hotelCode,
				)
			);
			?>
			<style>
				.reserve-btn {
					display: none;
				}
			</style>
		<?php else: ?>
			<?php
			get_template_part(
				'template-parts/api/bedbanks/hotel',
				'rooms-list',
				array(
					'rooms' => $hotelData,
					'hotelPostID' => $hotelPostId,
					'hotelCode' => $hotelCode,
					'order' => $order,
					'hotelInfo' => $hotelInfo,
					'hotelMinNights' => $hotelMinNights,
				)
			);
			?>
		<?php endif; ?>
	</div>
<?php endif; ?>



<?php
if ($hotelLat && $hotelLng):
	?>
	<section class="py-5 text--color--dark position-relative pl-xl-3 pr-xl-2">
		<h2 class="h3">
			<?php if ($accommodation_overview_title): ?>
				<?php echo $accommodation_overview_title; ?>
			<?php else: ?>
				<?php esc_html_e('Location', 'nirvana'); ?>
			<?php endif; ?>
		</h2>

		<div class="popup-hotel-location__subhead d-flex mt-1">
			<span
				class="text-uppercase text--color--light-orange text--size--12 font--weight--700"><?php echo $hotelCityName; ?></span>
			<?php
			if ($show_miles_from_event && $miles_from_event):
				?>
				<span class="text-uppercase text--opacity text--size--12 font--weight--700 ml-1">
					<?php
					printf(
						esc_html__('%s KM from event', 'nirvana'),
						$miles_from_event
					);
					?>
				</span>
			<?php endif; ?>
		</div>
		<?php if ($hotelOverview): ?>
			<div class="text--opacity mt-3 content-block"><?php echo $hotelOverview; ?></div>
		<?php endif; ?>
		<div class="accommodation__location-map mt-2">
			<?php if ($hotelLat && $hotelLng): ?>
				<div class="acf-map" data-zoom="16">
					<div class="marker" data-lat="<?php echo esc_attr($hotelLat); ?>"
						data-lng="<?php echo esc_attr($hotelLng); ?>"></div>
					<?php
					$eventId = $order->getEventPostID();
					$event_location = get_field('event_location', $eventId);

					$add_event_startfinish = get_field('add_event_startfinish', $eventId);
					$event_start_location = get_field('event_start_location', $eventId);
					$event_finish_location = get_field('event_finish_location', $eventId);
					if ($event_location):
						?>
						<div class="eventmarker" data-lat="<?php echo esc_attr($event_location['lat']); ?>"
							data-lng="<?php echo esc_attr($event_location['lng']); ?>"></div>
					<?php endif; ?>
					<?php if ($add_event_startfinish): ?>
						<?php if ($event_start_location): ?>
							<div class="event-start-marker" data-lat="<?php echo esc_attr($event_start_location['lat']); ?>"
								data-lng="<?php echo esc_attr($event_start_location['lng']); ?>"></div>
						<?php endif; ?>
						<?php if ($event_finish_location): ?>
							<div class="event-finish-marker" data-lat="<?php echo esc_attr($event_finish_location['lat']); ?>"
								data-lng="<?php echo esc_attr($event_finish_location['lng']); ?>"></div>
						<?php endif; ?>
					<?php endif; ?>
				</div>
				<div class="map-notice text--size--14 font--weight--600">
					<?php if ($event_location): ?>
						<span>
							<img src="<?php echo get_template_directory_uri(); ?>/assets/images/map/event-icon.png"> - Event
						</span>
					<?php endif; ?>
					<span>
						<img src="<?php echo get_template_directory_uri(); ?>/assets/images/map/hotel-icon.png"> - Hotel
					</span>
					<?php if ($add_event_startfinish): ?>
						<?php if ($event_start_location): ?>
							<span>
								<img src="<?php echo get_template_directory_uri(); ?>/assets/images/map/start-icon.png"> - Event Start
							</span>
						<?php endif; ?>
						<?php if ($event_finish_location): ?>
							<span>
								<img src="<?php echo get_template_directory_uri(); ?>/assets/images/map/finish-icon.png"> - Event Finish
							</span>
						<?php endif; ?>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
<?php endif; ?>