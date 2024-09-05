<?php
$debug = false;

$order = $args['order'];
$api   = $args['api'];


$hotelCode   = $_GET['hotelid'];
$hotelPostId = $api->GetAccomHotelPostID( $hotelCode );

$orderEventId     = $order->getEventPostID();
$hide_accommodation_tab = get_field('hide_accommodation_tab', $orderEventId );

if ( empty( $hotelPostId ) || 'publish' !== get_post_status( $hotelPostId ) ) {
	return;
}

//Get rooms general info
$hotels    = $order->searchAccoms();
$hotelData = array();

if ( sizeof( $hotels ) != 0 ) :
	$roomCounter = 0;
	foreach ( $hotels as $roomHotels ) :
		if ( $roomHotels ) :
			if ( is_array( $roomHotels ) ) :
				foreach ( $roomHotels as $hotel ) :
					if ( $hotel->BasicPropertyInfo->HotelCode == $hotelCode ) :
						$hotelData[ $roomCounter ] = $hotel;
					endif;
				endforeach;
			elseif ( $roomHotels->BasicPropertyInfo->HotelCode == $hotelCode ) :
					$hotelData[ $roomCounter ] = $roomHotels;
			endif;

			//get minimum room price
			$fromPrice = false;
			foreach ( $hotelData as $hotel ) :
				if ( gettype( $hotel->RoomRates->RoomRate ) == 'array' ) :
					foreach ( $hotel->RoomRates->RoomRate as $rate ) {
						$price = intval( $rate->Rates->Rate->Total->AmountAfterTax );
						if ( $price < $fromPrice ) {
							$fromPrice = $price;
						}
					}
				else :
					foreach ( $hotel->RoomRates as $rate ) {
						$price = intval( $rate->Rates->Rate->Total->AmountAfterTax );
						if ( $price < $fromPrice ) {
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
if ( $hotelPostId ) :
	$hotelImage                   = get_the_post_thumbnail_url( $hotelPostId, 'full' );
	$hotelCityName                = get_field( 'hotel_location', $hotelPostId );
	$hotelDescritpion             = get_field( 'hotel_description', $hotelPostId );
	$hotelOverview                = get_field( 'location_overview', $hotelPostId );
	$hotelRating                  = get_field( 'hotel_rating', $hotelPostId );
	$accommodation_overview_title = get_field( 'accommodation_overview_title', $hotelPostId );
	$customMinNights = get_field('minimum_nights' , $hotelPostId);
	$video_tour_available = get_field('video_tour_available' , $hotelPostId);
	$video_tour = get_field('video_tour' , $hotelPostId);
endif;

if($customMinNights) $hotelMinNights = $customMinNights;
?>
<div class="accommodation pl-xl-3 pr-xl-2">
	<?php if ( $debug ) : ?>
		<pre style="color: #000; z-index: 4; position: relative">
			<?php
			$order->outputOrderData();
			?>
			<hr>
			<?php
			print_r( $hotels );
			?>
			<hr>
			<?php
			print_r( $hotelInfo );
			?>
			<hr>
		</pre>
	<?php endif; ?>
	

	<div class="bg--white position-absolute top-0 start-0 w-100 h-100"></div>


	<?php
	get_template_part(
		'template-parts/api/accommodation/hotel',
		'gallery',
		array(
			'hotelPostID' => $hotelPostId,
		)
	);
	?>

	<?php if ( $hotelDescritpion ) : ?>
	<div class="accommodation-content p2 mt-5 position-relative content-block">
		<?php echo $hotelDescritpion; ?>
	</div>
	<?php endif; ?>

	<?php
	$hotelAmenities = get_field( 'hotel_amenities', $hotelPostId );
	if ( $hotelAmenities ) :
		?>
		<div class="accommodation-amenities my-5 position-relative">
			<h3 class="p2 mb-1">Amenities</h3>
			<ul class="text-uppercase text--size--12 font--weight--700 mt-3">
				<?php foreach ( $hotelAmenities as $item ) : ?>
					<?php
					$icon = $item['icon'];
					$name = $item['name'];
					?>
					<li class="d-flex g-10 align-items-center">
						<?php if ( $icon ) : ?>
							<span class="accommodation-amenities__icon">
								<img src="<?php echo $icon['url']; ?>" alt="">
							</span>
						<?php else : ?>
							<?php the_svg_by_sprite( 'list-icon-grey-plus', 20, 20 ); ?>
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


<!-- IF ( ! TRAVELLERS DAYS TO STAY LESS THEN HOTEL MIN NIGHTS STAY) -->
<?php if($hide_accommodation_tab): ?>
	<div id="rooms">
	<?php
		get_template_part(
			'template-parts/api/accommodation/hotel',
			'rooms-list-static',
			array(
				'hotelPostID' => $hotelPostId,
			)
		);
		?>
		<style>.reserve-btn{display: none; }</style>
	</div>
<?php else: ?>
	<div id="rooms">
	<?php if ( $hotelMinNights && intval( $hotelMinNights ) > intval( $order->getNightsQtt() ) ) : ?>
		<?php
		get_template_part(
			'template-parts/api/accommodation/hotel',
			'min-nights',
			array(
				'nights'    => $hotelMinNights,
				'order'     => $order,
				'hotelCode' => $hotelCode,
			)
		);
		?>
		<style>.reserve-btn{display: none; }</style>
	<?php else: ?>
		<?php
			get_template_part(
				'template-parts/api/accommodation/hotel',
				'rooms-list',
				array(
					'rooms'       => $hotelData,
					'hotelPostID' => $hotelPostId,
					'hotelCode'   => $hotelCode,
					'order'       => $order,
					'hotelInfo'   => $hotelInfo,
					'hotelMinNights' =>  $hotelMinNights,
				)
			);
			?>
	<?php endif; ?>
	</div>
<?php endif; ?>		



<?php
$hotelLocation = get_field( 'hotel_location_map', $hotelPostId );
if ( $hotelLocation ) :
	?>
<section class="py-5 text--color--dark position-relative pl-xl-3 pr-xl-2">
	<h2 class="h3">
		<?php if ( $accommodation_overview_title ) : ?>
			<?php echo $accommodation_overview_title; ?>
		<?php else : ?>
			<?php esc_html_e( 'Location', 'nirvana' ); ?>
		<?php endif; ?>
	</h2>
	
	<div class="popup-hotel-location__subhead d-flex mt-1">
		<span class="text-uppercase text--color--light-orange text--size--12 font--weight--700"><?php echo esc_html(get_field( 'hotel_location', $hotelPostId )); ?></span>
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
	<?php if ( $hotelOverview ) : ?>
			<div class="text--opacity mt-3 content-block"><?php echo $hotelOverview; ?></div>
	<?php endif; ?>
	<div class="accommodation__location-map mt-2">
		<?php if ( $hotelLocation ) : ?>
			<div class="acf-map" data-zoom="16">
				<div class="marker" data-lat="<?php echo esc_attr( $hotelLocation['lat'] ); ?>" data-lng="<?php echo esc_attr( $hotelLocation['lng'] ); ?>"></div>
				<?php
				$eventId        = $order->getEventPostID();
				$event_location = get_field( 'event_location', $eventId );

				$add_event_startfinish = get_field('add_event_startfinish', $eventId);
				$event_start_location = get_field('event_start_location', $eventId);
				$event_finish_location = get_field('event_finish_location', $eventId);
				if ( $event_location ) :
					?>
					<div class="eventmarker" data-lat="<?php echo esc_attr( $event_location['lat'] ); ?>" data-lng="<?php echo esc_attr( $event_location['lng'] ); ?>"></div>
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
		<?php endif; ?>
	</div>
</section>
<?php endif; ?>

<?php 