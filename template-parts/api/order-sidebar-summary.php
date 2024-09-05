<?php
/**
 * Template for displaying summary sidebar
 */
?>

<?php
$order = $args['order'];
$api   = $args['api'];

$orderType = $order->getOrderType();
?>

<button type="button" class="button button--dark button--booking-summary h4 mb-5"><?php esc_html_e( 'Summary', 'nirvana' ); ?></button>

<div class="booking-summary text--color--default">
	<h2 class="h4 mb-5"><?php esc_html_e( 'Summary', 'nirvana' ); ?></h2>

	<div class="booking-summary__inner">
		<div class="booking-summary__trip">
			<h3><?php esc_html_e( 'Your trip', 'nirvana' ); ?></h3>
			<?php if ( $order ) : ?>
				<div class="d-flex g-10 align-items-center">
					
					<?php
					$event_cats     = get_the_terms( $order->getEventPostID(), 'event_category' );
					$event_cat      = false;
					$event_cat_logo = false;
					if($event_cats):
						foreach ( $event_cats as $cat ) {
							$event_cat = $cat;
						}
					endif;
					if ( $event_cat ) :
						$event_cat_logo = get_field( 'booking_logo', $event_cat );
					endif;
					if ( $event_cat_logo ) :
						?>
						<div class="img-block booking-summary__logo">
							<img src="<?php echo $event_cat_logo['url']; ?>" alt="<?php echo $event_cat_logo['alt']; ?>">
						</div>
					<?php endif; ?>

					<div class="d-block text--size--16">
						<h4 class="text--size--16"><?php echo get_the_title( $order->getEventPostID() ); ?></h4>
						<?php
						$event_start_date = get_field( 'event_start_date', $order->getEventPostID() );
						if ( $event_start_date ) :
							$dateFormat = DateTime::createFromFormat( 'Ymd', $event_start_date );
							if ( $dateFormat ) :
								?>
							<time><?php echo $dateFormat->format( 'F j, Y' ); ?></time>
							<?php endif; ?>
						<?php endif; ?>
					</div>
				</div>
			<?php else : ?>
				<?php $event_id = $args['event_id']; ?>
				<div class="d-flex g-10 align-items-center">
					<?php
						$event_cats     = get_the_terms( $event_id, 'event_category' );
						$event_cat      = false;
						$event_cat_logo = false;
					foreach ( $event_cats as $cat ) {
						$event_cat = $cat;
					}
					if ( $event_cat ) :
						$event_cat_logo = get_field( 'booking_logo', $event_cat );
						endif;
					if ( $event_cat_logo ) :
						?>
						<div class="img-block booking-summary__logo">
							<img src="<?php echo $event_cat_logo['url']; ?>" alt="<?php echo $event_cat_logo['alt']; ?>">
						</div>
					<?php endif; ?>
					<div class="d-block text--size--16">
							<h4 class="text--size--16"><?php echo get_the_title( $event_id ); ?></h4>
							<?php
							$event_start_date = get_field( 'event_start_date', $event_id );
							if ( $event_start_date ) :
								$dateFormat = DateTime::createFromFormat( 'Ymd', $event_start_date );
								if ( $dateFormat ) :
									?>
								<time><?php echo $dateFormat->format( 'F j, Y' ); ?></time>
								<?php endif; ?>
							<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>

		<hr class="booking-summary__divider">

		<div class="booking-summary__loading" style="display: none;">
			<img src="<?php echo get_template_directory_uri(); ?>/assets/images/loader.svg" alt="">
		</div>

		<?php if ( $order ) : ?>
			<input type="hidden" name="order-number" value=""/>

			<div class="booking-summary__dates">
				<h3><?php esc_html_e( 'Dates & Travellers', 'nirvana' ); ?></h3>

				<span class="d-block text--size--16"><?php echo $order->getDateRange(); ?></span>
				<span class="d-block text--size--16 mt-1"><?php echo $order->getPeopleQtt(); ?></span>
			</div>

			<hr class="booking-summary__divider">
		<?php endif; ?>

		<?php 
		if($order):
			$packageData = $order->getPackagesOrderData();
			$orderType = $order->getOrderType();
			if($packageData && $orderType == 'tour'):
				$orderType = $order->getOrderType();
				?>
				<div class="booking-summary__hotel-rooms">
					<h3 style="opacity: 1;"><?php echo $packageData[0]['TourName']; ?></h3>
				</div>
				<?php
			endif;
		endif;
		?>

		<?php
		if ( $order ) :
			if($orderType == 'tour'):
				$packageAccoms = $order->getPackageReservedAccom();
				if ( $packageAccoms ) :
					?>
					<div class="booking-summary__hotel-rooms">
						<h3><?php esc_html_e( 'Hotel & Rooms', 'nirvana' ); ?></h3>
						
						<ul class="text--size--16">
							<?php foreach ( $packageAccoms as $key => $room ) : ?>
							<li class="d-flex align-items-start mt-2">
								<span class="js-basket-remove d-none" data-type="accom" data-hotel="<?php echo $room['hotel']; ?>" data-id="<?php echo $room['id']; ?>" data-index="<?php echo $key; ?>" data-name="<?php echo $room['name']; ?>" data-order="<?php echo $order->getOrderNumber(); ?>"> <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icon-remove.svg" alt=""></span>
								<div>
									<?php 
									$hotelID = $api->getHotelIDbyCode( $room['hotel'] );
									if($hotelID): ?>
										<h4 class="d-block text--size--16"><?php echo get_the_title( $hotelID ); ?></h4>
									<?php else: ?>
										<?php if(isset($room['hotel_name'])): ?>
											<h4 class="d-block text--size--16"><?php echo $room['hotel_name']; ?></h4>
                                    	<?php endif; ?>
									<?php endif; ?>
									<h5 class="d-block mt-1 text--size--16 font--weight--400"><?php echo $room['name']; ?></h5>
									<ul>
										<?php foreach ( $room['passengers'] as $person ) : ?>
											<?php if ( $person['name'] && $person['name'] != '' ) : ?>
												<li class="d-flex align-items-center mt-1">
													<?php the_svg_by_sprite( 'user-summary', 13, 17, '', '#ffffff' ); ?>
													<span class="text--opacity ml-1"><?php echo $person['name']; ?> <?php echo $person['surname']; ?></span>
												</li>
											<?php endif; ?>
										<?php endforeach; ?>
										
									</ul>
								</div>
							</li>
							<?php endforeach; ?>
							
						</ul>
					</div>

					<hr class="booking-summary__divider">
				<?php endif; 
			else:
				$accoms = $order->getReservedAccommodations();
				if ( $accoms ) :
					?>
					<div class="booking-summary__hotel-rooms">
						<h3><?php esc_html_e( 'Hotel & Rooms', 'nirvana' ); ?></h3>
						
						<ul class="text--size--16">
							<?php foreach ( $accoms as $key => $room ) : ?>
							<li class="pb-3 d-flex align-items-start">
								<span class="js-basket-remove" data-type="accom" data-hotel="<?php echo $room['hotel']; ?>" data-id="<?php echo $room['id']; ?>" data-index="<?php echo $key; ?>"  data-name="<?php echo $room['name']; ?>" data-order="<?php echo $order->getOrderNumber(); ?>"> <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icon-remove.svg" alt=""></span>
								<div>
									<?php 
									$hotelID = $api->getHotelIDbyCode( $room['hotel'] );
									if($hotelID): ?>
										<h4 class="d-block text--size--16"><?php echo get_the_title( $hotelID ); ?></h4>
									<?php else: ?>
										<?php if(isset($room['hotel_name'])): ?>
											<h4 class="d-block text--size--16"><?php echo $room['hotel_name']; ?></h4>
                                    	<?php endif; ?>
									<?php endif; ?>
									<h5 class="d-block mt-2 text--size--16 font--weight--400"><?php echo $room['name']; ?></h5>
									<ul>
										<?php foreach ( $room['passengers'] as $person ) : ?>
											<?php if ( $person['name'] && $person['name'] != '' ) : ?>
												<li class="d-flex align-items-center mt-1">
													<?php the_svg_by_sprite( 'user-summary', 13, 17, '', '#ffffff' ); ?>
													<span class="text--opacity ml-1"><?php echo $person['name']; ?> <?php echo $person['surname']; ?></span>
												</li>
											<?php endif; ?>
										<?php endforeach; ?>
										
									</ul>
								</div>
							</li>
							<?php endforeach; ?>
							
						</ul>
					</div>

					<hr class="booking-summary__divider">
				<?php endif; ?>
			<?php endif; ?>
			
		<?php endif; ?>

		<?php
		if ( $order ) :
			if($orderType == 'tour'):
				$packageExtras = $order->getReservedPackageExtras();
				if ( $packageExtras ) :
					$extrasGrouped = array();
					?>
					<div class="booking-summary__extras mb-3">
						<h3><?php esc_html_e( 'Event Services', 'nirvana' ); ?></h3>

						<ul class="text--size--16 d-flex flex-column g-10">
							<?php foreach ( $packageExtras as $item ) : ?>
								<li class="d-flex align-items-start">
									<span class="js-basket-remove d-none" data-type="extra" data-id="<?php echo $item['id']; ?>" data-order="<?php echo $order->getOrderNumber(); ?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/icon-remove.svg" alt=""></span>
									<?php echo $item['Name']; ?> <span class="text--opacity">(<?php echo sizeof( $item['travellers'] ); ?>)</span>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif;
			else:
				$extras = $order->getReservedExtras();
				if ( $extras ) :
					$extrasGrouped = array();
					?>
					<div class="booking-summary__extras mb-3">
						<h3><?php esc_html_e( 'Event Services', 'nirvana' ); ?></h3>

						<ul class="text--size--16 d-flex flex-column g-10">
							<?php foreach ( $extras as $item ) : ?>
								<li class="d-flex align-items-start">
									<span class="js-basket-remove" data-type="extra" data-id="<?php echo $item['id']; ?>" data-order="<?php echo $order->getOrderNumber(); ?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/icon-remove.svg" alt=""></span>
									<?php echo $item['name']; ?> <span class="text--opacity">(<?php echo sizeof( $item['travellers'] ); ?>)</span>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
			<?php endif; ?>
			
		<?php endif; ?>
	</div>

	<div class="booking-summary__cost bg--light-orange mt-auto">
		<div class="d-flex justify-content-between align-items-center text-uppercase font--weight--700">
			<span class="text--size--12"><?php _e('Price' , 'nirvana'); ?></span>
			<?php if ( $order ) : ?>
				<?php
				$price = $order->getPriceFromQuote();
				$orderType = $order->getOrderType();
				if($orderType == 'tour'):
					if($price):
						?>
						<span class="text--size--20"><?php echo $order->getCurrencySymbol(); ?><?php echo $price ? number_format($price,2) : 0; ?></span>
						<?php
					else:
						$packageData = $order->getPackagesOrderData();
						if($packageData):
							$price = 0;
							foreach($packageData as $single_package):
								$price += isset($single_package['price'])?$single_package['price']:0;
							endforeach;
							if($packageExtras):
								foreach($packageExtras as $item):
									foreach($item['travellers'] as $traveller):
										$price += $traveller['price'];
									endforeach;
								endforeach;
							endif;
							if($packageAccoms):
								foreach($packageAccoms as $item):
									if(isset($item['price'])):
										$price += floatval($item['price']);
									endif;
								endforeach;
								
							endif;
							?>
							<span class="text--size--20"><?php echo $order->getCurrencySymbol(); ?><?php echo number_format($price,2); ?></span>
						<?php
						else:
						?>
							<span class="text--size--20"><?php echo $order->getCurrencySymbol(); ?><?php echo 0; ?></span>
						<?php
						endif;
					endif;
				else:
				?>
					<span class="text--size--20"><?php echo $order->getCurrencySymbol(); ?><?php echo $price ? number_format($price,2) : 0; ?></span>
				<?php endif; ?>
			<?php else : ?>
				<span class="text--size--20">£0</span>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php
