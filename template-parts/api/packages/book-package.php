<?php

/**
 * Template for displaying Book Accommodation Page
 */

$order = $args['order'];
$api   = $args['api'];


//print_r($_POST);
if ($_POST && $order->getRoomsSubmissionID() != $_POST['submission_key']) :
//$order->addPackage($_POST);
endif;

$clientID = '';
if (is_user_logged_in()) {
	$current_user = wp_get_current_user();
	$clientID     = get_user_meta($current_user->ID, 'api_client_id', true);
}

if (!$order->getQuoteID()) :
	$order->createQuote($clientID);
else :
	$order->updateQuote($clientID);
endif;

$packageData  = $order->getPackagesOrderData();
$packageAccom = $order->getPackageAccom();
$packageRooms = $order->getRooms();

$link = get_field('api_package_extras_page', 'option');
if ($packageData[0]['ExtrasExists']) :
	$link = get_field('api_package_extras_page', 'option');
else :
	if ($packageData[0]['TransfersExists']) :
		$link = get_field('api_package_transfers_page', 'option');
	else :
		$link = get_field('api_summary_page', 'option');
	endif;
endif;

if ($packageAccom) :
?>
	<form action="<?php echo $link; ?>" id="reserve-package-accom-form" class="book-package-accom__form book-package-accommodation mt-3 d-flex flex-column" data-order="<?php echo $order->getOrderNumber(); ?>">
		<div class="book-package-accommodation__container">
			<h2 class="h3"><?php esc_html_e('Your room', 'nirvana'); ?></h2>
			<input id="order-number" type="hidden" name="" value="<?php echo $order->getOrderNumber(); ?>">
			<div class="book-accommodation__form-loading" style="display: none;">
				<img src="<?php echo get_template_directory_uri(); ?>/assets/images/loader.svg" alt="">
			</div>
			<?php
			//reserve rooms loop
			$loopCounter = 0;
			if ($packageAccom) : //if package accoms exist
			?>
				<?php foreach ($packageRooms as $roomIndex => $packageRoom) : //loop through rooms 
				?>
					<div class="book-accommodation__form-item mt-3" data-index="<?php echo 0; ?>">
						<div class="p-2 bg--light mt-1">
							<?php /* hotels info block start */ ?>
							<?php foreach ($packageAccom as $packageHotel) : //loop through hotels 
								
								if($packageHotel['tour_id'] == $roomIndex):
									$hotelData   = false;
									$hotelPostID = $packageHotel['hotel_post_id'];
									?>

									<?php if ($hotelPostID) : ?>

										<?php
										$hotelPostRooms = get_field('hotel_rooms', $hotelPostID);

										$hotelID         = $packageHotel['hotel_id'];
										$roomID          = $packageHotel['room_id'];
										$roomComponentID = $packageHotel['accom_id'];
										$roomTitle       = $packageHotel['room_title'];
										$roomBoardBasis  = $packageHotel['room_bb'];
										$roomSelectionState  = $packageHotel['selection_state'];
										$roomPrice  = $packageHotel['room_price'];
										$roomDuration  = $packageHotel['room_duration'];
										$roomTourId = $packageHotel['tour_id'];

										$roomFeatures = false;
										$roomSupplements = false;
										$room = false;
										//find rooms in hotel options
										if ($hotelPostRooms) :
											foreach ($hotelPostRooms as $roomData) :
												if ($roomData['id'] == $roomID) :
													$room = $roomData;
												endif;
											endforeach;
										endif;

										/**
										 * $room - room data from hotel fields
										 * $selectedRoom - room data from API
										 */

										$roomImage = false;

										if ($room && $room['image']) :
											$roomImage = $room['image']['url'];
										endif;
										?>

										<div class="hotel-data-item">
											<?php
											/**
											 * Hidden data fields
											 */
											?>
											<input class="hotel_id" type="hidden" name="hotel_id" value="<?php echo $hotelID; ?>">
											<input class="room_id" type="hidden" name="room_id" value="<?php echo $roomID; ?>">
											<input class="room_component_id" type="hidden" name="room_component_id" value="<?php echo $roomComponentID; ?>">
											<input class="room_name" type="hidden" name="room_name" value="<?php echo $roomTitle; ?>">
											<input class="room_selection_state" type="hidden" name="room_selection_state" value="<?php echo $roomSelectionState; ?>">
											<input class="room_price" type="hidden" name="room_price" value="<?php echo $roomPrice; ?>">
											<input class="room_order_index" type="hidden" name="room_order_index" value="<?php echo $roomIndex; ?>">
											<input class="room_tour_index" type="hidden" name="room_tour_index" value="<?php echo $roomTourId; ?>">

											<?php /** End of hidden data fields */ ?>


											<div class="book-accommodation__content-info d-flex flex-wrap flex-md-nowrap">
												<?php if ($roomImage) : ?>
													<div class="image-wrapper">
														<div class="image-ratio" style="padding-bottom: 100%;">
															<img src="<?php echo $roomImage; ?>" alt="">
														</div>
													</div>
												<?php endif; ?>

												<div class="d-flex flex-column w-100 
													<?php
													if ($roomImage) {
														echo 'pl-md-2 pt-2 pt-md-0';
													}
													?>
													">
													<div class="d-flex align-items-center mb-1 header-booking__main__accom">
														<h3 class="h3"><?php echo get_the_title($hotelPostID); ?></h3>
														<?php the_rating(intval(get_field('hotel_rating', $hotelPostID)), 'ml-sm-1 mr-sm-1'); ?>
													</div>
													<div class="d-flex justify-content-between flex-wrap">
														<h4 class="h4">
															<?php echo esc_html($roomTitle); ?>
														</h4>
													</div>

													<?php if ($roomFeatures) : ?>
														<ul class="d-flex flex-column g-10 text--size--16 my-1">
															<?php foreach ($room['features'] as $feature) : ?>
																<li class="d-flex align-items-center g-10 text-capitalize">
																	<?php the_svg_by_sprite('list-icon-grey-plus', 20, 20, 'flex-shrink-0'); ?>
																	<span class="text--opacity"><?php echo esc_html($feature); ?></span>
																</li>
															<?php endforeach; ?>
														</ul>
													<?php endif; ?>

													<?php if ($roomBoardBasis) : ?>
														<ul class="d-flex flex-column g-10 text--size--16 my-2">
															<li class="d-flex align-items-center g-10 text-capitalize">
																<?php the_svg_by_sprite('list-icon-grey-plus', 20, 20, 'flex-shrink-0'); ?>
																<span class="text--opacity"><?php echo esc_html($roomBoardBasis); ?></span>
															</li>
														</ul>
													<?php endif; ?>


													<div class="d-flex justify-content-between mt-4">
														<?php if ($roomSupplements) : ?>
															<div class="book-accommodation__supplements">
																<span class="d-inline-flex text--size--12 font--weight--700 text-uppercase mr-2"><?php echo esc_html_e('Room Supplements', 'nirvana'); ?></span>

																<div class="d-inline-flex flex-column g-10">
																	<?php foreach ($room['supplements'] as $supplement) : ?>
																		<label class="d-flex g-10 text--size--16">
																			<div class="d-flex w-100 align-items-center justify-content-between">
																				<span class="text--opacity"><?php echo esc_html($supplement['name']); ?> <?php echo $order->getCurrencySymbol(); ?><?php echo esc_html($supplement['price']); ?></span>
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
															<span class="d-block text--size--16 text--opacity mt-05 text-right">
																<?php
																printf(
																	/* translators: 1: Nights 2: Adults */
																	esc_html__('%1$s nights, %2$s', 'nirvana'),
																	$roomDuration,
																	$order->getCustomPeopleQtt($packageRoom['adults_qtt'] , $packageRoom['childs_qtt']),
																);
																?>
															</span>
														</div>
													</div>

												</div>

											</div>

										</div>

										<hr class="mx-n2 mt-2 mb-3">
									<?php endif; ?>
								<?php endif; ?>
							<?php endforeach; //loop through hotels end 
							?>
							<?php /* hotels info block end */ ?>

							<?php /* passengers block */ ?>
							<div class="book-accommodation__content-adults">
								<h3 class="text--size--18 font--weight--700"><?php esc_html_e('Who will be staying in this room?', 'nirvana'); ?></h3>

								<?php
								$childrenIndex = 0;
								$passengers = $order->getPassengers();
								foreach ($passengers as $index => $passenger) : //loop through passengers
									if ($roomIndex !=  $passenger['room']) continue;
								?>

									<div class="book-accommodation__content-adults-item js-traveller">
										<div class="book-accommodation__content-adults-index"><?php echo $index + 1; ?></div>
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
										<?php
										if ($passenger['Age'] != 25 || $passenger['Age'] < 18 || (isset($passenger['AgeCode']) && $passenger['AgeCode'] != '10')) : ?>

											<div class="book-accommodation__content-adults-input book-accommodation__content-adults-input--age">
												<label>
													<?php _e('Child Age', 'nirvana'); ?>
													<input class="age" type="number" name="age" readonly="readonly" value="<?php echo $passenger['Age']; ?>">
													<input type="hidden" class="type" name="type" value="Child">
												</label>
											</div>
											<?php ++$childrenIndex; ?>


										<?php else : ?>
											<div class="book-accommodation__content-adults-input book-accommodation__content-adults-input--age d-none">
												<label>
													<?php _e('Age', 'nirvana'); ?>
													<input class="age" type="number" name="age" value="25">
												</label>
											</div>
											<?php if (is_celtic_active($order->getEventPostID())) : ?>
												<div class="book-accommodation__content-type-input d-none">
													<select class="type" name="type">
														<option value="Spectator" selected>Spectator</option>
													</select>
												</div>
											<?php else : ?>
												<div class="book-accommodation__content-type-input">
													<label>
														<span class="d-flex align-items-center">
															<?php _e('Traveller type', 'nirvana'); ?>
															<span class="info-tooltip ml-1">
																<span class="info-tooltip__icon">?</span>
																<span class="info-tooltip__content">
																	<h4><?php _e('Traveller type', 'nirvana'); ?></h4>
																	<p><?php the_field('traveller_type_instruction', 'option'); ?></p>
																</span>
															</span>
														</span>
														<select class="type" name="type">
															<option value="Athlete" selected>Athlete</option>
															<option value="Spectator">Spectator</option>
														</select>
													</label>
												</div>
											<?php endif;  ?>
										<?php endif; ?>

										<?php if (is_celtic_active($order->getEventPostID())) : ?>
											<div class="book-accommodation__content-adults-input">
												<label>
													<?php esc_html_e('Celtic Client Reference Number', 'nirvana'); ?>

													<input class="celtic-code" type="text" name="celtic_code" autocomplete="celtic-code">
													<input name="event_id" type="hidden" value="<?php echo esc_attr($order->getEventPostID()); ?>">
												</label>
											</div>
										<?php endif; ?>
										<input type="hidden" name="ref_number" value="<?php echo $passenger['ref_number']; ?>">
									</div>
								<?php endforeach; //loop through passengers end 
								?>
							</div>
							<?php /* passengers block end */ ?>
						</div>
					</div>
				<?php endforeach; //loop through rooms end 
				?>
			<?php endif; //if package accoms exist end 
			?>
		</div>
		<div class="order-form__errors text-center pt-2" style="display: none;"></div>
		<div class="mt-auto pt-4 mx-n2 ">
			<div class="package__buttons-bar d-flex p-2 ">
				<div class="package__buttons-bar__item">
					<a class="button button--dark-white" onclick="history.back()"><?php esc_html_e('Back to Hotel info', 'nirvana'); ?></a>
				</div>
				<div class="package__buttons-bar__item">
					<button type="submit" class="button button--orange w-100"><?php esc_html_e('Continue', 'nirvana'); ?></button>
				</div>
			</div>
		</div>
	</form>
<?php else : ?>

	<form action="<?php echo $link; ?>" id="reserve-package-passengers-form" class="book-package-accom__form book-package-accommodation mt-3 d-flex flex-column" data-order="<?php echo $order->getOrderNumber(); ?>">

		<div class="book-package-accommodation__container">
			<h2 class="h3"><?php esc_html_e('Passengers', 'nirvana'); ?></h2>

			<input id="order-number" type="hidden" name="" value="<?php echo $order->getOrderNumber(); ?>">

			<div class="book-accommodation__form-loading" style="display: none;">
				<img src="<?php echo get_template_directory_uri(); ?>/assets/images/loader.svg" alt="">
			</div>

			<?php


			//reserve rooms loop
			$passengers = $order->getPassengers();
			?>
			<div class="book-accommodation__form-item mt-3" data-index="<?php echo 0; ?>">


				<div class="p-2 bg--light pt-0">


					<div class="book-accommodation__content-adults js-travellers-list">
						<?php
						$childrenIndex = 0;
						foreach ($passengers as $index => $passenger) :
						?>

							<div class="book-accommodation__content-adults-item js-traveller">
								<div class="book-accommodation__content-adults-index"><?php echo $index + 1; ?></div>
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
								<?php
								if ($passenger['Age'] != 25 || $passenger['Age'] < 18 || (isset($passenger['AgeCode']) && $passenger['AgeCode'] != '10')) : ?>

									<div class="book-accommodation__content-adults-input book-accommodation__content-adults-input--age">
										<label>
											<?php _e('Child Age', 'nirvana'); ?>
											<input class="age" type="number" name="age" readonly="readonly" value="<?php echo $passenger['Age']; ?>">
											<input type="hidden" class="type" name="type" value="Child">
										</label>
									</div>
									<?php ++$childrenIndex; ?>

								<?php else : ?>
									<div class="book-accommodation__content-adults-input book-accommodation__content-adults-input--age d-none">
										<label>
											<?php _e('Age', 'nirvana'); ?>
											<input class="age" type="number" name="age" value="25">
										</label>
									</div>
									<?php if (is_celtic_active($order->getEventPostID())) : ?>
										<div class="book-accommodation__content-type-input d-none">
											<select class="type" name="type">
												<option value="Spectator" selected>Spectator</option>
											</select>
										</div>
									<?php else : ?>
										<div class="book-accommodation__content-type-input">
											<label>
												<span class="d-flex align-items-center">
													<?php _e('Traveller type', 'nirvana'); ?>
													<span class="info-tooltip ml-1">
														<span class="info-tooltip__icon">?</span>
														<span class="info-tooltip__content">
															<h4><?php _e('Traveller type', 'nirvana'); ?></h4>
															<p><?php the_field('traveller_type_instruction', 'option'); ?></p>
														</span>
													</span>
												</span>
												<select class="type" name="type">
													<option value="Athlete" selected>Athlete</option>
													<option value="Spectator">Spectator</option>
												</select>
											</label>
										</div>
									<?php endif;  ?>
								<?php endif; ?>

								<?php if (is_celtic_active($order->getEventPostID())) : ?>
									<div class="book-accommodation__content-adults-input">
										<label>
											<?php esc_html_e('Celtic Client Reference Number', 'nirvana'); ?>

											<input class="celtic-code" type="text" name="celtic_code" autocomplete="celtic-code">
											<input name="event_id" type="hidden" value="<?php echo esc_attr($order->getEventPostID()); ?>">
										</label>
									</div>
								<?php endif; ?>

								<input type="hidden" name="ref_number" value="<?php echo $passenger['ref_number']; ?>">
							</div>
						<?php endforeach; ?>

					</div>
				</div>
			</div>


			<div class="order-form__errors text-center pt-2" style="display: none;"></div>
		</div>
		<div class="mt-auto pt-4 mx-n2 ">
			<div class="package__buttons-bar d-flex p-2 ">
				<div class="package__buttons-bar__item">
					<a class="button button--dark-white" onclick="history.back()"><?php esc_html_e('Back to Package', 'nirvana'); ?></a>
				</div>
				<div class="package__buttons-bar__item">
					<button type="submit" class="button button--orange w-100"><?php esc_html_e('Continue', 'nirvana'); ?></button>
				</div>
			</div>
		</div>

	</form>

<?php
endif;
