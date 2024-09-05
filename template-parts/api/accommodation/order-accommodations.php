<?php

/**
 * Template part for displaying accommodations list
 */
$debug = false;

$order = $args['order'];
$api = $args['api'];
$hotels = $order->searchAccoms();
?>
<?php if($debug): ?>
<pre style="color: #000; z-index: 2; position: relative; ">
	<?php print_r($hotels); ?>
</pre>
<?php endif; ?>

<div class="accommodations js-accoms-search" data-order="<?php echo $order->getOrderNumber(); ?>">
	
	<?php if(true): ?>
	<div class="pb-4">
		<?php
		get_template_part(
			'template-parts/api/order',
			'dates-select',
			array(
				'order' => $order,
				'api' => $api,
			)
		);
		?>
	</div>
	<?php endif; ?>
	
	<div class="accommodations-filters pb-2 d-flex flex-wrap g-20 align-items-center justify-content-between">
		<div class="accommodations-sort sort-dropdown">
			<div class="sort-dropdown__header">
				<span class="mr-1">Sort by:</span>
				<span class="sort-dropdown__button d-flex align-items-center">
					<span class="sort-dropdown__selected font--weight--600 mr-1">Distance to event</span>
					<span class="sort-dropdown__arrow"><img src="<?php echo get_template_directory_uri();?>/assets/images/icon-arrow-orange.svg" alt=""></span>
				</span>
			</div>
			<ul class="sort-dropdown__content p-1" style="display:none;">
				<li data-sort="distance" data-type="asc" class="active">Distance to event</li>
				<li data-sort="price" data-type="desc">Cost – High to low</li>
				<li data-sort="price" data-type="asc">Cost – Low to high</li>
				<li data-sort="rating" data-type="desc">Star Rating – High to low</li>
				<li data-sort="rating" data-type="asc">Star Rating – Low to high</li>
			</ul>
		</div>
		<div class="accommodations-map-btn p-1">
			<a class="button button--dark" href="#popup-map">
				<span>Show on map</span>
				<img src="<?php echo get_template_directory_uri(); ?>/assets/images/map-pin.svg" alt="">
			</a>
		</div>
	</div>

	<!-- #popup-hotel-location -->
	<div id="popup-map" class="popup-block popup-hotel-location">
			<div class="popup-block__background"></div>
			<div class="popup-block__inner">
				<div class="container popup-block__container position-relative">
					<div class="popup-block__content text-color-light p-2">
						<span class="popup-block__close"></span>
						<div class="row">
							<?php 
							$eventId = $order->getEventPostID();
							$event_location = get_field('event_location' , $eventId);

							$add_event_startfinish = get_field('add_event_startfinish', $eventId);
							$event_start_location = get_field('event_start_location', $eventId);
							$event_finish_location = get_field('event_finish_location', $eventId);
							?>
							<div class="col-12">
								<div class="acf-hotels-map" data-zoom="16"></div>
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
							<?php 
							
							if($event_location): ?>
								<span class="d-none js-event-location" data-lat="<?php echo esc_attr($event_location['lat']); ?>" data-lng="<?php echo esc_attr($event_location['lng']); ?>"></span>
							<?php endif; ?>
							<?php if($add_event_startfinish): ?>
								<?php if($event_start_location): ?>
									<div class="d-none js-event-start-marker" data-lat="<?php echo esc_attr($event_start_location['lat']); ?>" data-lng="<?php echo esc_attr($event_start_location['lng']); ?>"></div>
								<?php endif; ?>
								<?php if($event_finish_location): ?>
									<div class="d-none js-event-finish-marker" data-lat="<?php echo esc_attr($event_finish_location['lat']); ?>" data-lng="<?php echo esc_attr($event_finish_location['lng']); ?>"></div>
								<?php endif; ?>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div> <!-- #popup-hotel-locations -->
	
	<div class="book-accommodation__form-loading" style="display: none;">
		<img src="<?php echo get_template_directory_uri(); ?>/assets/images/loader.svg" alt="">
	</div>

	<?php 
	$orderEventId     = $order->getEventPostID();
	$enable_bedbanks_tab = get_field('enable_bedbanks_tab' , $orderEventId);
	$nirvana_hotels_top_text = get_field('nirvana_hotels_top_text');
	?>
	<div class="js-accoms-result <?php echo $enable_bedbanks_tab?'with-bedbanks':''; ?>">
		<?php 
		get_template_part(
			'template-parts/api/accommodation/order-accoms',
			'data',
			array(
				'order' => $order,
				'api' => $api,
				'hotels' => $hotels,
			)
		);
		?>

		<?php 
		if($enable_bedbanks_tab):
			$bedbanks = $order->searchBedBanks();
			if($bedbanks && $bedbanks[0]):
				get_template_part(
					'template-parts/api/bedbanks/order-bedbanks',
					'data',
					array(
						'order' => $order,
						'api' => $api,
						'bedbanks' => $bedbanks,
					)
				);
			endif;
		endif;
		?>
	</div>

	
</div>
