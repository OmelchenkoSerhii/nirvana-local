<?php

/**
 * Template for displaying Book Accommodation Page
 */

$order = $args['order'];
$api   = $args['api'];



if ( $_POST && $order->getRoomsSubmissionID() != $_POST['submission_key'] ) :
	$order->addAccommodation( $_POST );
endif;

$clientID = '';
if ( is_user_logged_in() ) {
	$current_user = wp_get_current_user();
	$clientID     = get_user_meta( $current_user->ID, 'api_client_id', true );
}

if ( ! $order->getQuoteID() ) :
	$order->createQuote( $clientID );
endif;

$selectedRooms = $order->getAccommodations();

$link = get_field( 'api_extras_page', 'option' );
?>

<a class="d-flex align-items-center text--size--12 font--weight--700  text-uppercase book-accommodation__back-btn" onclick="history.back()">
	<?php the_svg_by_sprite( 'long-left-arrow', 14, 9, 'mr-1' ); ?>
	Back To Other Rooms
</a>

<div class="book-accommodation my-3">

	<div class="book-accommodation__container">
		<h2 class="h3 text-center"><?php esc_html_e( 'You have selected', 'nirvana' ); ?></h2>

		<form id="reserve-accom-form" class="book-accommodation__form" data-order="<?php echo $order->getOrderNumber(); ?>">
			<input id="order-number" type="hidden" name="" value="<?php echo $order->getOrderNumber(); ?>">
			
			<div class="book-accommodation__form-loading" style="display: none;">
				<img src="<?php echo get_template_directory_uri(); ?>/assets/images/loader.svg" alt="">
			</div>

			<div class="book-accommodation__form-norooms text-center" <?php if(sizeof( $selectedRooms )): ?>style="display: none;"<?php endif; ?>>
				<h4 class="py-5"><?php _e('Please select an accommodation.' , 'nirvana')?></h4>
			</div>

			<?php
			$roomIndex  = 1;
			$roomsTotal = sizeof( $selectedRooms );

			//reserve rooms loop
			$loopCounter = 0;
			foreach ( $selectedRooms as $selectedRoom ) :
				
				$hotelData   = false;
				$hotelPostID = $api->GetAccomHotelPostID( $selectedRoom['hotel_id'] );

				$room = false;
				if ( $hotelPostID ) :
					$hotelPostRooms = get_field( 'hotel_rooms', $hotelPostID );
					if ( $hotelPostRooms ) :
						foreach ( $hotelPostRooms as $roomData ) :
							if ( $roomData['id'] == $selectedRoom['room_id'] ) :
								$room = $roomData;
							endif;
						endforeach;
					endif;
				endif;

				$hotelID = $selectedRoom['hotel_id'];
				$hotelName = '';
				if(isset($selectedRoom['hotel_name'])) $hotelName = $selectedRoom['hotel_name'];
				$roomID  = $selectedRoom['room_id'];

				/**
				 * $room - room data from hotel fields
				 * $selectedRoom - room data from API
				 */

				$roomTitle        = $selectedRoom['room_title'];
				$roomLocator      = $selectedRoom['room_locator'];
				$roomPrice        = $selectedRoom['room_price'];
				$roomCurrency     = $selectedRoom['room_currency'];
				$roomAdults       = $selectedRoom['room_adults'];
				$roomChildren     = $selectedRoom['room_children'];
				$roomInfants     = $selectedRoom['room_infants'];
				$roomBoardBasis   = $selectedRoom['room_board'];
				$roomChildrenAges = $selectedRoom['room_children_ages'];
				$roomInfantsAges = $selectedRoom['room_infants_ages'];
				$roomImage        = false;
				$roomDescription  = false;
				$roomFeatures     = false;
				$roomSupplements  = false;
				$roomMinNights    = false;

				if ( $room && $room['image'] ) :
					$roomImage = $room['image']['url'];
				endif;
				if ( $room && $room['description'] ) :
					$roomDescription = $room['description'];
				endif;
				?>
				<div class="book-accommodation__form-item mt-3" data-index="<?php echo ( $roomIndex - 1 ); ?>">
					<?php
					/**
					 * Hidden data fields
					 */
					?>
					<input class="hotel_id" type="hidden" name="hotel_id" value="<?php echo $hotelID; ?>">
					<input class="hotel_name" type="hidden" name="hotel_name" value="<?php echo $hotelName; ?>">
					<input class="room_id" type="hidden" name="room_id" value="<?php echo $roomID; ?>">
					<input class="room_name" type="hidden" name="room_name" value="<?php echo $roomTitle; ?>">
					<input class="room_price" type="hidden" name="room_price" value="<?php echo $roomPrice; ?>">
					<span class="room_locator d-none"><?php echo $roomLocator; ?></span>
					<?php /** End of hidden data fields */ ?>

					<div class="d-flex justify-content-between">
						<span class="room-counter d-block text--size--16 font--weight--700">
							<?php
							printf(
								/* translators: 1: Room index 2: Number of rooms */
								esc_html__( 'Room %1$s of %2$s', 'nirvana' ),
								esc_html( $roomIndex ),
								$roomsTotal
							);
							?>
						</span>

						<button type="button" class="js-remove-room button--clean text--size--16 text--color--light-orange font--weight--700 d-block d-sm-none" data-index="<?php echo ( $roomIndex - 1 ); ?>"><?php esc_html_e( 'Delete room', 'nirvana' ); ?></button>
					</div>

					<div class="p-2 bg--light mt-1">
						<div class="book-accommodation__content-info d-flex">
							<?php if ( $roomImage ) : ?>
								<div class="image-wrapper">
									<div class="image-ratio" style="padding-bottom: 100%;">
										<img src="<?php echo $roomImage; ?>" alt="">
									</div>
								</div>
							<?php endif; ?>

							<div class="d-flex flex-column w-100 
							<?php
							if ( $roomImage ) {
								echo 'pl-sm-2';}
							?>
							">
								<div class="d-flex justify-content-between flex-wrap">
									<h3 class="h4">
										<?php echo esc_html( $roomTitle ); ?>
										<?php
										if ( false ) :
											?>
											, <span class="text--opacity font--weight--400"><?php echo esc_html( $room['type'] ); ?></span><?php endif; ?>
									</h3>
									<button type="button" class="js-remove-room button--clean text--size--16 text--color--light-orange font--weight--700 d-none d-sm-block" data-index="<?php echo ( $roomIndex - 1 ); ?>"><?php esc_html_e( 'Delete room', 'nirvana' ); ?></button>
								</div>

								<?php if($roomDescription): ?>
									<p class="text--opacity text--size--16 pt-2"><?php echo $roomDescription; ?></p>
								<?php endif; ?>


								<?php if ( $roomFeatures ) : ?>
									<ul class="d-flex flex-column g-10 text--size--16 my-1">
										<?php foreach ( $room['features'] as $feature ) : ?>
											<li class="d-flex align-items-center g-10 text-capitalize">
												<?php the_svg_by_sprite( 'list-icon-grey-plus', 20, 20, 'flex-shrink-0' ); ?>
												<span class="text--opacity"><?php echo esc_html( $feature ); ?></span>
											</li>
										<?php endforeach; ?>
									</ul>
								<?php endif; ?>

								<?php if ( $roomBoardBasis ) : ?>
									<ul class="d-flex flex-column g-10 text--size--16 my-2">
										<li class="d-flex align-items-center g-10 text-capitalize">
											<?php the_svg_by_sprite( 'list-icon-grey-plus', 20, 20, 'flex-shrink-0' ); ?>
											<span class="text--opacity"><?php echo esc_html( $roomBoardBasis ); ?></span>
										</li>	
									</ul>
								<?php endif; ?>


								<div class="d-flex justify-content-between mt-4">
									<?php if ( $roomSupplements ) : ?>
										<div class="book-accommodation__supplements">
											<span class="d-inline-flex text--size--12 font--weight--700 text-uppercase mr-2"><?php echo esc_html_e( 'Room Supplements', 'nirvana' ); ?></span>

											<div class="d-inline-flex flex-column g-10">
												<?php foreach ( $room['supplements'] as $supplement ) : ?>
													<label class="d-flex g-10 text--size--16">
														<div class="d-flex w-100 align-items-center justify-content-between">
															<span class="text--opacity"><?php echo esc_html( $supplement['name'] ); ?> <?php echo $order->getCurrencySymbol(); ?><?php echo esc_html( $supplement['price'] ); ?></span>
														</div>
														<div class="checkbox-wrapper">
															<input type="checkbox">
															<div class="checkbox-active"></div>
														</div>
													</label>
												<?php endforeach; ?>
											</div>
										</div>
									<?php endif; ?>

									<div class="ml-auto">
										<span class="d-block h3 text-uppercase text-right"><?php echo $order->getCurrencySymbolByCode( $roomCurrency ); ?><?php echo $roomPrice; ?></span>
										<span class="d-block text--size--16 text--opacity mt-05 text-right">
											<?php echo $order->getNightsQtt(); ?> <?php echo esc_html_e( 'nights', 'nirvana' ); ?>,
											<?php if ( $roomAdults != 0 ) : ?>
												<?php echo $roomAdults; ?> <?php echo $roomAdults <= 1 ? 'adult' : 'adults'; ?>
											<?php endif; ?>
											<?php if ( $roomChildren != 0 ) : ?>
												<?php echo $roomChildren; ?> <?php echo $roomChildren <= 1 ? 'child' : 'children'; ?>
											<?php endif; ?>
											<?php if ( $roomInfants != 0 ) : ?>
												<?php echo $roomInfants; ?> <?php echo $roomInfants <= 1 ? 'infant' : 'infants'; ?>
											<?php endif; ?>
										</span>
									</div>
								</div>
							</div>
						</div>

						<hr class="mx-n2 mt-2 mb-3">

						<div class="book-accommodation__content-adults">
							<h3 class="text--size--18 font--weight--700"><?php esc_html_e( 'Who will be staying in this room?', 'nirvana' ); ?></h3>

							<?php
							$childrenIndex = 0;
							$infantsIndex = 0;
							for ( $i = 1; $i <= ( $roomAdults + $roomChildren + $roomInfants ); $i++ ) :
								?>
								
								<div class="book-accommodation__content-adults-item js-traveller">
									<div class="book-accommodation__content-adults-index"><?php echo $i; ?></div>
									<div class="book-accommodation__content-adults-input">
										<label>
											<select class="title" name="title">
												<option value="Mr" selected>Mr</option>
												<option value="Mrs">Mrs</option>
												<option value="Miss">Miss</option>
												<option value="Ms">Ms</option>
												<option value="Dr">Dr</option>
												<option value="Mstr">Mstr</option>
											</select>
										</label>
									</div>
									<div class="book-accommodation__content-adults-input">
										<label>
											First Name
											<input class="name" type="text" name="name" autocomplete="given-name">
										</label>
									</div>
									<div class="book-accommodation__content-adults-input">
										<label>
											Last Name
											<input class="surname" type="text" name="surname" autocomplete="family-name">
										</label>
									</div>
									<?php if ( $i > $roomAdults &&  $i <= $roomAdults + $roomChildren) : ?>
										
										<div class="book-accommodation__content-adults-input book-accommodation__content-adults-input--age">
											<label>
												<?php _e( 'Child Age', 'nirvana' ); ?>
												<input class="age" type="number" name="age" readonly="readonly" value="<?php echo $roomChildrenAges[ $childrenIndex ]; ?>">
											</label>
										</div>
										<?php ++$childrenIndex; ?>
									<?php elseif($i > $roomAdults + $roomChildren): ?>
										<div class="book-accommodation__content-adults-input book-accommodation__content-adults-input--age">
											<label>
												<?php _e( 'Infant Age', 'nirvana' ); ?>
												<input class="age" type="number" name="age" readonly="readonly" value="<?php echo $roomInfantsAges[ $infantsIndex ]; ?>">
											</label>
										</div>
										<?php ++$infantsIndex; ?>
									<?php else : ?>
										<div class="book-accommodation__content-adults-input book-accommodation__content-adults-input--age d-none">
											<label>
												<?php _e( 'Age', 'nirvana' ); ?>
												<input class="age" type="number" name="age" value="25">
											</label>
										</div>
									<?php endif; ?>

									<?php if ( is_celtic_active( $order->getEventPostID() ) ) : ?>
										<div class="book-accommodation__content-adults-input">
											<label>
												<?php esc_html_e( 'Celtic Client Reference Number', 'nirvana' ); ?>

												<input class="celtic-code" type="text" name="celtic_code" autocomplete="celtic-code">
												<input name="event_id" type="hidden" value="<?php echo esc_attr( $order->getEventPostID() ); ?>">
											</label>
										</div>
									<?php endif; ?>
								</div>
							<?php endfor; ?>

						</div>
					</div>
				</div>
				<?php ++$roomIndex; ?>
			<?php endforeach; ?>


			<div class="primary-buttons d-flex justify-content-center mt-3 text-uppercase g-10">
				<a class="button button--dark-white" onclick="history.back()"><?php esc_html_e( 'Add Another Room', 'nirvana' ); ?></a>
				<?php if(sizeof( $selectedRooms )): ?>
					<button type="submit" class="button button--orange"><?php esc_html_e( 'Reserve', 'nirvana' ); ?></button>
				<?php endif; ?>
			</div>

			<div class="order-form__errors text-center pt-2" style="display: none;"></div>

			<div id="popup-accom-reserve" class="popup-block popup-accom-reserve">
				<div class="popup-block__background"></div>
				<div class="popup-block__inner">
					<div class="container popup-block__container position-relative">
						<div class="popup-block__content text-center text-color-light p-2">
							<span id="popupClose" class="popup-block__close"></span>
							<span class="popup-accom-reserve__icon mb-2">
								<img src="<?php echo get_template_directory_uri(); ?>/assets/images/accom-reserve-check.png" alt="">
							</span>
							<h4 class="mb-2">You have reserved:</h4>
							<ul class="popup-accom-reserve__list d-flex flex-column">
								<li class="popup-accom-reserve__item pb-1">
									<div class="popup-accom-reserve__item-inner p-2 d-flex flex-column">
										<span class="popup-accom-reserve__item-title">Classic Room, 1 King Bed</span>
										<span class="popup-accom-reserve__item-subtitle">(for 2 persons)</span>
									</div>
								</li>
								<li class="popup-accom-reserve__item pb-1">
									<div class="popup-accom-reserve__item-inner p-2 d-flex flex-column">
										<span class="popup-accom-reserve__item-title">Classic Room, 1 King Bed</span>
										<span class="popup-accom-reserve__item-subtitle">(for 2 persons)</span>
									</div>
								</li>
							</ul>
							<a class="button button--orange button--arrow mt-2 mb-3" href="<?php echo $link; ?>">Continue with event services</a>
							<div class="popup-block__footer mr-n2 ml-n2 mb-n2 p-2">
								<a class="button button--dark-white js-close-popup">Cancel</a>
								<a class="button button--dark-white" href="<?php echo get_field( 'api_accommodation_page', 'option' ); ?>">Reserve additional room</a>
							</div>
						</div>

					</div>
				</div>
			</div> <!-- #popup-hotel-location -->
		</form>
	</div>
</div>

<?php
