<?php

/**
 * Template part for displaying hotel as card in list
 */

$hotelPostId       = $args['hotelID'];

if (empty($hotelPostId) || 'publish' !== get_post_status($hotelPostId)) {
	return;
}

$hotelCode         = $args['hotelCode'];
$order             = $args['order'];

$api = new NiravanaAPI();

//get hotel data
$hotelTitle = get_the_title($hotelPostId);
$link = get_field('api_accommodation_single_page', 'option');
$hotelLink = $link . '?hotelid=' . $hotelCode;

$orderEventId     = $order->getEventPostID();
$hide_accommodation_tab = get_field('hide_accommodation_tab', $orderEventId);


//get hotel post data
$hotelImage = false;
$hotelCityName = '';
$hotelDescritpion = '';
$hotelRating = 0;
$hotelMap = false;
$showCardLabel = false;
$cardLabels = false;
$hidePriority = false;
$customMinNights = false;

$show_miles_from_event = get_field('show_miles_from_event', $hotelPostId);
$distance_format = get_field('distance_format', $hotelPostId);
$miles_from_event      = get_field('miles_from_event', $hotelPostId);

if ($hotelPostId) :
	$hotelImage = get_the_post_thumbnail_url($hotelPostId, 'full');
	$hotelGallery     = get_field( 'hotel_gallery', $hotelPostId );
	$hotelCityName = get_field('hotel_location', $hotelPostId);
	$hotelDescritpion = get_field('hotel_synopsis', $hotelPostId);
	$hotelRating = get_field('hotel_rating', $hotelPostId);
	$hotelMap = get_field('hotel_location_map', $hotelPostId);
	$showCardLabel = get_field('add_card_label', $hotelPostId);
	$cardLabels = get_field('card_label_tags', $hotelPostId);
	$hidePriority = get_field('hide_priority', $hotelPostId);
	$customMinNights = get_field('minimum_nights', $hotelPostId);
endif;

$hotelInfo = $api->GetHotelInfo($order->getEventCode(), $order->getPassengerTypeQuantity(), $hotelCode, $order->getCheckinDate(), $order->getCheckoutDate());
$hotelMinNightsFlag = false;
$hotelMinNights = false;
$hotelMaxNights = false;
if ($hotelInfo) :
	$hotelInfoRooms = $hotelInfo->FacilityInfo->GuestRooms->GuestRoom;
	if (gettype($hotelInfoRooms) == 'array') :
		foreach ($hotelInfoRooms as $room) :
			if (isset($room->Restrictions) && $room->Restrictions->MinMaxStays) :
				if ($hotelMinNights) :
					if ($hotelMinNights > $room->Restrictions->MinMaxStays->MinMaxStay->MinStay) :
						$hotelMinNights = $room->Restrictions->MinMaxStays->MinMaxStay->MinStay;
						$hotelMaxNights = $room->Restrictions->MinMaxStays->MinMaxStay->MaxStay;
					endif;
				else :
					$hotelMinNights = $room->Restrictions->MinMaxStays->MinMaxStay->MinStay;
					$hotelMaxNights = $room->Restrictions->MinMaxStays->MinMaxStay->MaxStay;
				endif;
			endif;
		endforeach;
	else :
		if (isset($hotelInfoRooms->Restrictions) && $hotelInfoRooms->Restrictions->MinMaxStays) :
			$hotelMinNights = $hotelInfoRooms->Restrictions->MinMaxStays->MinMaxStay->MinStay;
			$hotelMaxNights = $hotelInfoRooms->Restrictions->MinMaxStays->MinMaxStay->MaxStay;
		endif;
	endif;
endif;
if ($customMinNights && $customMinNights != 0) $hotelMinNights = $customMinNights;
?>
<li class="accommodations__item bg--light mb-2 d-flex g-20" style="order: <?php echo $hotelMinNights ? '999999996' : '999999999'; ?>;">
	<?php
	if ( $hotelImage || $hotelGallery ) :
		?>
		<div class="accommodations__item-image-wrapper">
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
		<a href="<?php echo $hotelLink; ?>" class="h3"><?php echo esc_html($hotelTitle); ?></a>
		<div class="d-flex g-15 align-items-center mt-1 mb-2">
			<span class="link--style--2"><?php echo esc_html($hotelCityName); ?></span>

			<?php if ( $hotelMap ) : ?>
				<a href="#popup-hotel-location-<?php echo $hotelPostId; ?>" class="link--style--1"><?php esc_html_e( 'Show on map', 'nirvana' ); ?></a>
			<?php endif; ?>


			<?php

			if ($show_miles_from_event && $miles_from_event) : ?>
				<span class="text--size--12 font--weight--700 text-uppercase text--opacity">
					<?php
					printf(
						$distance_format == 'km' ? esc_html__('%s KM from event', 'nirvana') : esc_html__('%s Miles from event', 'nirvana'),
						$miles_from_event
					);
					?>
				</span>
			<?php endif; ?>
		</div>

		<?php if ($hotelDescritpion) : ?>
			<div class="text--opacity text--size--16 pb-1 accommodations__item-content-description">
				<?php echo $hotelDescritpion; ?>
			</div>
		<?php endif; ?>

		<a class="button button--sm button--orange mt-1 mb-1" href="<?php echo $hotelLink; ?>">View</a>

		<?php the_rating(intval($hotelRating), 'mt-auto'); ?>
	</div>

	<div class="accommodations__item-additional-content d-flex flex-column pb-2 pr-2 ml-auto">

		<div class="icon-bookmark-list d-flex g-15">
			<?php if ($hotelMinNights && !$hide_accommodation_tab) : ?>
				<?php if (intval($hotelMinNights) > intval($order->getNightsQtt())) : ?>
					<?php the_icon_bookmark_min_nights(intval($hotelMinNights)); ?>
				<?php endif; ?>
			<?php endif; ?>



			<?php if ($showCardLabel && $cardLabels) : ?>
				<?php foreach ($cardLabels as $item) : ?>
					<?php $label = $item['label']; ?>
					<?php if ($label) : ?>
						<?php the_icon_bookmark_primary($label); ?>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>

		<span class="h3 mt-auto text-nowrap text-right text-uppercase accommodations__item-contact">
			<?php 
			//$orderEventId
			$contact_us_link = get_field('api_contact_page', 'option');
			$contact_us_link = get_field('api_contact_page', 'option'); // Retrieve the base URL from ACF options

			// Retrieve the event title
			$event_title = get_the_title($orderEventId);
			$event_title_encoded = urlencode($event_title); // URL encode the title to safely include it in a URL

			// Retrieve the event series or category
			$terms = wp_get_post_terms($orderEventId, 'event_category'); // Adjust 'event_category' if the taxonomy is different
			$event_series = '';
			if (!is_wp_error($terms) && !empty($terms)) {
				$event_series = $terms[0]->name; // Get the name of the first term
			}
			$event_series_encoded = urlencode($event_series); // URL encode the series name

			// Construct the URL with query parameters
			$contact_us_link_full = $contact_us_link . '?event_title=' . $event_title_encoded . '&event_series=' . $event_series_encoded;
			?>
			<?php if ($hide_accommodation_tab) : ?>
				<?php esc_html_e('COMING SOON - ', 'nirvana'); ?>
				<a class="text--color--light-orange text--underline" href="<?php echo $contact_us_link_full; ?>" target="_blank"><?php _e('CONTACT US', 'nirvana'); ?></a>
			<?php else : ?>
				<?php if ($hotelMinNights && $hotelMinNights != 0 && intval($hotelMinNights) > intval($order->getNightsQtt())) : ?>
					<?php esc_html_e('PLEASE ADJUST YOUR DATES', 'nirvana'); ?>
				<?php else : ?>
					<?php esc_html_e('SOLD OUT - ', 'nirvana'); ?>
					<a class="text--color--light-orange text--underline" href="<?php echo $contact_us_link_full; ?>" target="_blank"><?php _e('CONTACT US', 'nirvana'); ?></a>
				<?php endif; ?>
			<?php endif; ?>
		</span>


		<span class="text--size--16 text--opacity mt-05 text-nowrap text-right">
			<?php
			printf(
				/* translators: 1: Nights 2: Adults */
				esc_html__('%1$s nights, %2$s', 'nirvana'),
				$order->getNightsQtt(),
				$order->getPeopleQtt(),
			);
			?>
		</span>
	</div>

	<?php if (false && !$hotelMinNightsFlag) : ?>
		<div class="accommodations__item-bg-sold-out"></div>
	<?php endif; ?>

	<?php if ($hotelMap) : ?>
		<span class="d-none js-hotel-location" data-lat="<?php echo esc_attr($hotelMap['lat']); ?>" data-lng="<?php echo esc_attr($hotelMap['lng']); ?>" data-hotel="<?php echo $hotelPostId; ?>"></span>
		<!-- #popup-hotel-location -->
		<div id="popup-hotel-location-<?php echo $hotelPostId; ?>" class="popup-block popup-hotel-location">
			<div class="popup-block__background"></div>
			<div class="popup-block__inner">
				<div class="container popup-block__container position-relative">
					<div class="popup-block__content text-color-light p-2">
						<span class="popup-block__close"></span>
						<div class="row">
							<div class="col-md-4 d-flex flex-column text--color--dark">
								<?php if ($hotelImage) : ?>
									<div class="popup-hotel-location__image-wrapper">
										<img class="w-100 h-auto" src="<?php echo $hotelImage; ?>" alt="">
										<?php if ($hotelMinNights) : ?>
											<?php if (intval($hotelMinNights) > intval($order->getNightsQtt())) : ?>
												<?php the_icon_bookmark_min_nights(intval($hotelMinNights)); ?>
											<?php endif; ?>
										<?php endif; ?>

										<?php
										//if ($priorityHotel == 1 && !$hidePriority) :
										if (false) :
											if ($changePriority && $changePriorityLabel) :
												the_icon_bookmark_primary($changePriorityLabel);
											else :
												the_icon_bookmark_primary();
											endif;
										endif; ?>

										<?php if ($showCardLabel && $cardLabels) : ?>
											<?php foreach ($cardLabels as $item) : ?>
												<?php $label = $item['label']; ?>
												<?php if ($label) : ?>
													<?php the_icon_bookmark_primary($label); ?>
												<?php endif; ?>
											<?php endforeach; ?>
										<?php endif; ?>
									</div>
								<?php endif; ?>



								<div class="popup-hotel-location__subhead mt-2 d-flex justify-content-between">
									<span class="text-uppercase text--color--light-orange text--size--12 font--weight--700"><?php echo esc_html($hotelCityName); ?></span>
									<?php
									$show_miles_from_event = get_field('show_miles_from_event', $hotelPostId);
									$distance_format = get_field('distance_format', $hotelPostId);
									$miles_from_event      = get_field('miles_from_event', $hotelPostId);
									if ($show_miles_from_event && $miles_from_event) :
									?>
										<span class="text-uppercase text--opacity text--size--12 font--weight--700">
											<?php
											printf(
												$distance_format == 'km' ? esc_html__('%s KM from event', 'nirvana') : esc_html__('%s Miles from event', 'nirvana'),
												$miles_from_event
											);
											?>
										</span>
									<?php endif; ?>
								</div>

								<span class="d-block h3 mt-2"><?php echo esc_html($hotelTitle); ?></span>

								<?php the_rating(intval($hotelRating), 'mt-1'); ?>

								<?php if ($hotelDescritpion) : ?>
									<p class="text--opacity mt-2"><?php echo excerpt_str($hotelDescritpion, 40); ?></p>
								<?php endif; ?>

								<div class="d-flex align-items-center justify-content-between mt-auto pt-2">
									<div>
										
										
									</div>
									<a href="<?php echo $hotelLink; ?>" class="button button--orange"><?php _e('View', 'nirvana'); ?></a>
								</div>
							</div>
							<div class="col-md-8">
								<?php
								if ($hotelMap) :
								?>
									<div class="acf-map" data-zoom="16">
										<div class="marker" data-lat="<?php echo esc_attr($hotelMap['lat']); ?>" data-lng="<?php echo esc_attr($hotelMap['lng']); ?>"></div>
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
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div> <!-- #popup-hotel-location -->
	<?php endif; ?>
</li>