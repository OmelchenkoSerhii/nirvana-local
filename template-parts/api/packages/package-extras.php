<?php
$debug = false;
$order = $args['order'];
$api = $args['api'];

$packageData = $order->getPackagesOrderData();
$extras = $order->searchPackageExtras();
$travellers = $order->getTravellers();
$book_start_date = $order->getCheckinDate();

if(!is_array($extras)):
	$extras = array($extras);
endif;
?>


<?php
if ($debug) :
?>
	<pre>
		<?php //print_r($travellers); 
		?>
		<?php print_r($extras); ?>
		<hr>
		<?php //print_r($order->viewQuote()); ?>
		<hr>
		
	</pre>
<?php
endif;

$link = get_field('api_package_transfers_page', 'option');
if($packageData[0]['TransfersExists']):
	$link = get_field('api_package_transfers_page', 'option');
else:
	$link = get_field('api_summary_page', 'option');
endif;
?>

<?php
$instruction = get_field('package_extras_instruction', 'option');

$eventID = $order->getEventPostID();
$instruction_custom_change = get_field('change_text_on_event_services' , $eventID);
$event_services_content = get_field('event_services_content' , $eventID);
$instruction = ($instruction_custom_change && $event_services_content)?$event_services_content:$instruction;
if ($instruction) :
?>
	<div class="content-block mb-4"><?php echo $instruction; ?></div>
<?php endif; ?>

<form action="<?php echo $link; ?>" id="api-package-extra-form" class="extras">
	<input id="order-number" type="hidden" name="" value="<?php echo $order->getOrderNumber(); ?>">
	<div class="book-accommodation__form-loading" style="display: none;">
		<img src="<?php echo get_template_directory_uri(); ?>/assets/images/loader.svg" alt="">
	</div>

	<?php
	/**
	 * Check Extras Types
	 */

	$includesExists = false;
	$raceFeeCoverExists = false;
	$upgradesExists = false;

	foreach ($extras as $extraItem) :
		$show = true;
		$extraID = $extraItem->ID;
		$selectionState = $extraItem->SelectionState;

		$preselected = false;
		$speactatorFlag = false;
		$speactatorShow = false;

		//check if preselected
		foreach ($travellers as $passenger) :
			$passengerAgeCode = $passenger['AgeCode'];
			$passengerType = $passenger['Type'];
			$price = 0;
			$extraType = $extraItem->Type;
			$selectedClass = '';
			if ($passengerAgeCode == 10) :
				$showFlag = true;
				$price = floatval($extraItem->AdultPrice);
			elseif ($passengerAgeCode == 8) :
				$showFlag = true;
				$price = floatval($extraItem->ChildPrice);
			endif;

			if ($price == 0) :
				if ($passengerType == 'Athlete') :
					if (!(strpos(strtolower($extraItem->Name), "race fee cover") !== false || strpos(strtolower($extraItem->Name), "race entry fee") !== false)) :
						$includesExists = true;
					else:
						$raceFeeCoverExists = true;
					endif;						
				endif;
			else:
				$upgradesExists = true;
			endif;
			
			if($selectionState == 1) $includesExists = true;
		endforeach;
	endforeach;
	?>

	<ul class="extras-list d-flex flex-column">
		<?php if($includesExists): ?>
			<li class="my-3" style="order: 1;">
				<h4><?php _e('Included in your Event Package', 'nirvana'); ?></h4>
			</li>
		<?php endif; ?>
		<?php if($raceFeeCoverExists): ?>
			<li class="my-3" style="order: 3;">
				<h4><?php _e('Don\'t forget to opt in to your complimentary insurance', 'nirvana'); ?></h4>
			</li>
		<?php endif; ?>
		<?php if($upgradesExists || ($includesExists && !$upgradesExists)): ?>
			<li class="my-3" style="order: 5;">
				<h4><?php _e('Upgrade your Event Package', 'nirvana'); ?></h4>
			</li>
		<?php endif; ?>

		<?php
		$index = 0;
		$reservedExtra = $order->getReservedPackageExtras();
		$preaddExtras = array();
		foreach ($extras as $extraItem) :
			$show = true;
			$extraID = $extraItem->ID;
			$selectionState = $extraItem->SelectionState;
			$Quantity =  $extraItem->Quantity;
			if ($extraItem->StayDateRange) :
				$start = $extraItem->StayDateRange->Start;
				$end = $extraItem->StayDateRange->End;
				if ($start && $end) :
					$start = DateTime::createFromFormat('Y-m-d', $start);
					$end = DateTime::createFromFormat('Y-m-d', $end);
					if ($book_start_date == $start->format('j F Y')) {
						$show = true;
					}

				endif;
			endif;

			if($Quantity == 0){
				$show = false;
			}

			$preselected = false;
			$speactatorFlag = false;
			$speactatorShow = false;

			if($selectionState != 1):
				//check if preselected
				foreach ($travellers as $passenger) :
					$passengerAgeCode = $passenger['AgeCode'];
					$passengerType = $passenger['Type'];
					$price = 0;
					$extraType = $extraItem->Type;
					$selectedClass = '';
					if ($passengerAgeCode == 10) :
						$showFlag = true;
						$price = floatval($extraItem->AdultPrice);
					elseif ($passengerAgeCode == 8) :
						$showFlag = true;
						$price = floatval($extraItem->ChildPrice);
					endif;
					if ($price == 0) :
						if ($passengerType == 'Athlete') :
							if (!(strpos(strtolower($extraItem->Name), "race fee cover") !== false || strpos(strtolower($extraItem->Name), "race entry fee") !== false)) :
								$preselected = true;
							endif;
						else :
							$selectedClass = 'disabled';
						endif;
					endif;
					//if ($extraType == 'SPECTATOR DISCOUNT') :
						if ($price < 0) :
							$speactatorFlag = true;
							if ($passengerType == 'Athlete') :

							else :
								$speactatorShow = true;
							endif;
						else :

						endif;
					//endif;
				endforeach;
			else:
				$preselected = true;
			endif;

			$cssOrder = 2;
			if(strpos(strtolower($extraItem->Name), "race fee cover") !== false || strpos(strtolower($extraItem->Name), "race entry fee") !== false):
				$cssOrder = 4;
			else:	
				if($preselected):
					$cssOrder = 2;
				else:
					$cssOrder = 6;
				endif;
			endif;

			if ($show && (!$speactatorFlag || ($speactatorFlag && $speactatorShow))) :
			?>
				<li class="mb-1" style="order:<?php echo $cssOrder; ?>;">
					
					<div class="extras-card p-1">

						<input type="hidden" class="js-extra-title" name="extra_name" value="<?php echo $extraItem->Name; ?>">
						<input type="hidden" class="extra-id" name="extra_id" value="<?php echo $extraItem->ID; ?>">
						<input type="hidden" class="extra-nbr-code" name="extra_nbr_code" value="<?php echo $extraItem->NBRCode; ?>">

						<div class="extras-card__header d-flex align-items-center">
							<?php if (false) : ?>
								<div class="extras-card__image">
									<div class="image-ratio" style="padding-bottom: 100%;">
										<img src="<?php echo get_template_directory_uri() . '/assets/images/temp--kam-idris.jpg'; ?>" alt="">
									</div>
								</div>
							<?php endif; ?>
							<div class="extras-card__content">
								<div class="extras-card__content-inner d-flex justify-content-between align-items-center">
									<div class="extras-card__content-title">
										<h3><?php echo $extraItem->Name; ?></h3>
										<?php if ($extraItem->Type == 'VIP Athlete Services') : ?>
											<span class="extras-card__vip-label">VIP ACCESS</span>
										<?php endif; ?>

										<span class="extras-card__dropdown-btn">
											<span class="show">Show more</span>
											<span class="hide">Show less</span>
											<span class="extras-card__dropdown-btn__icon">
												<svg xmlns="http://www.w3.org/2000/svg" width="7" height="7" viewBox="0 0 7 7">
													<rect id="Rectangle" width="7" height="2.333" transform="translate(0 4.667)" fill="#f47920" />
													<rect id="Rectangle_Copy_11" data-name="Rectangle Copy 11" width="7" height="2.333" transform="translate(7) rotate(90)" fill="#f47920" />
												</svg>
											</span>
										</span>
									</div>
									<div class="extras-card__content-right">
										<span class="extras-card__qtt js-extra-qtt" style="display:none; ">Qty 1</span>
										<?php if (isset($extraItem->AdultPrice)) : ?>
											<span class="extras-card__price"><?php echo $order->getCurrencySymbol(); ?><?php echo number_format($extraItem->AdultPrice, 2, '.', ','); ?><span>/pp</span></span>
										<?php else : ?>
											<span class="extras-card__price"><?php echo $order->getCurrencySymbol(); ?><?php echo number_format($extraItem->AdultPrice, 2, '.', ','); ?><span>/pp</span></span>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
						<div class="extras-card__dropdown">

							<?php if ($travellers) : ?>
								<div class="extras-card__travellers mt-3">
									<div class="extras-card__travellers__header d-flex justify-content-between">
										<h4>Assign to travellers <span class="js-person-selected" style="display: none;">(<span class="js-person-selected-val">1</span> selected)</span></h4>
										<div class="extras-card__travellers__nav font--weight--600">
											<span class="js-select-all"><?php _e('Select all', 'nirvana'); ?></span>
											|
											<span class="js-clear-all"><?php _e('Clear all', 'nirvana'); ?></span>
										</div>
									</div>
									<ul class="extras-card__travellers-list">
										<?php
										$indexInner = 1;
										foreach ($travellers as $passenger) :
											$passengerAgeCode = $passenger['AgeCode'];
											$passengerRefNumber = $passenger['ref_number'];
											$passengerType = $passenger['Type'];
											$passengerTourId = $passenger['room'];
											$price = 0;
											$showFlag = false;
											$extraType = $extraItem->Type;

											if ($passengerAgeCode == 10) :
												$showFlag = true;
												$price = floatval($extraItem->AdultPrice);
											elseif ($passengerAgeCode == 8) :
												$showFlag = true;
												$price = floatval($extraItem->ChildPrice);
											endif;

											//check if extra is reserved
											$checked = false;
											if ($reservedExtra) :
												foreach ($reservedExtra as $reservedExtraItem) :
													if ($reservedExtraItem['id'] == $extraID) {
														foreach ($reservedExtraItem['travellers'] as $traveller) {
															if ($traveller['ref_number'] == $passengerRefNumber && (isset($traveller['selected']) && $traveller['selected'] == 1)) {
																$checked = true;
															}
															if ($traveller['ref_number'] == $passengerRefNumber && (isset($traveller['selected']) && $traveller['selected'] == 0)) {
																$checked = false;
															}
														}
													}
												endforeach;
											endif;

											$selectedClass = '';
											if($selectionState != 1):
												if(!$reservedExtra):
													switch ($extraType):
														case 'BTF EVENTS':
															if ($price == 0) :
																if ($passengerType == 'Athlete') :
																	if (!(strpos(strtolower($extraItem->Name), "race fee cover") !== false || strpos(strtolower($extraItem->Name), "race entry fee") !== false)) :
																		$checked = true;
																	endif;
																else :
																	$selectedClass = 'disabled';
																	$checked = false;
																endif;
															else :

															endif;
															break;
														case 'Quotation Requests':

															break;
														case 'SPECTATOR DISCOUNT':
															if ($price < 0) :
																if ($passengerType == 'Athlete') :
																	$selectedClass = 'disabled';
																	$checked = false;
																else :
																	$selectedClass = 'mandatory';
																	$checked = true;
																endif;
															else :

															endif;
															break;
														default:
															if($selectionState == 2 && !(strpos(strtolower($extraItem->Name), "race fee cover") !== false || strpos(strtolower($extraItem->Name), "race entry fee") !== false)) $checked = true;
															break;
													endswitch;
													
												endif;
											else:
												$selectedClass = 'mandatory';
												$checked = true;
											endif;

											

											if ($showFlag) :

										?>
												<li class="extras-card__travellers-item">
													<label class="extras-card__travellers-item__label d-flex align-items-center <?php echo $selectedClass; ?>">
														<span class="checkbox-style mr-1">
															<input class="person" type="checkbox" name="person_<?php echo $index; ?>_<?php echo $indexInner; ?>" value="<?php echo $passenger['ref_number']; ?>" <?php if ($checked) {
																																																						echo 'checked="true"';
																																																					}
																																																					?>>
															<span class="checkbox-style__pseudo"></span>
														</span>
														<span><?php echo $passenger['full-name']; ?> (<?php echo $passengerType; ?>)</span>
													</label>
													<input type="hidden" name="price" class="price" value="<?php echo $price; ?>">
													<input type="hidden" name="type" class="type" value="<?php echo $passengerType; ?>">
													<input type="hidden" name="age_code" class="agecode" value="<?php echo $passengerAgeCode; ?>">
													<input type="hidden" name="tour_id" class="tour_id" value="<?php echo $passengerTourId; ?>">
												</li>
											<?php endif;
											$indexInner++; ?>
										<?php endforeach; ?>
									</ul>
									
								</div>
							<?php endif; ?>
							<?php if (strpos(strtolower($extraItem->Name), "race fee cover") !== false || strpos(strtolower($extraItem->Name), "race entry fee") !== false) : ?>
								<div class="extras-card__insurance">
									<h4><?php _e('Insurance declaration', 'nirvana') ?></h4>

									<div class="content-block">
										<?php the_field('insurance_description', 'option'); ?>
									</div>

									<?php
									$termsFile = get_field('insurance_terms_pdf', 'option');
									?>

									<label class="extras-card__insurance-label d-flex align-items-center">
										<span class="checkbox-style mr-1">
											<input class="insurance-checkbox" type="checkbox" name="insurance" <?php if ($checked) {
																													echo 'checked="true"';
																												}
																												?>>
											<span class="checkbox-style__pseudo"></span>
										</span>
										<span>I have read and understood the Terms and Conditions.</span>
									</label>

								</div>
							<?php endif; ?>
						</div>
					</div>
				</li>
				<?php $index++; ?>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>


	<?php
	//preadd extras to order
	//$order->preaddPackageExtras($preaddExtras);
	?>

	<div class="mt-auto pt-4 mx-n2 mb-n3 ">
		<div class="package__buttons-bar d-flex p-2 ">
			<div class="package__buttons-bar__item">
				<a class="button button--dark-white" onclick="history.back()"><?php esc_html_e('Go back', 'nirvana'); ?></a>
			</div>
			<div class="package__buttons-bar__item">
				<button type="submit" class="button button--orange w-100"><?php esc_html_e('Continue', 'nirvana'); ?></button>
			</div>
		</div>
	</div>

	<div id="popup-extra-added" class="popup-block popup-extra">
		<div class="popup-block__background"></div>
		<div class="popup-block__inner">
			<div class="container popup-block__container position-relative">
				<div class="popup-block__content text-center text-color-light px-2 py-10">
					<span id="popupClose" class="popup-block__close"></span>
					<span class=" popup-extra__icon mb-2">
						<img src="<?php echo get_template_directory_uri(); ?>/assets/images/icon-checkbox-circle.svg" alt="">
					</span>
					<h4 class="mb-2">
						<span class="js-extra-name"></span>
					</h4>
				</div>
			</div>
		</div>
	</div>

	<div id="popup-extra-removed" class="popup-block popup-extra">
		<div class="popup-block__background"></div>
		<div class="popup-block__inner">
			<div class="container popup-block__container position-relative">
				<div class="popup-block__content text-center text-color-light px-2 py-10">
					<span id="popupClose" class="popup-block__close"></span>
					<span class=" popup-extra__icon mb-2">
						<img src="<?php echo get_template_directory_uri(); ?>/assets/images/icon-cross-red.svg" alt="">
					</span>
					<h4 class="mb-2">
						<span class="js-extra-name"></span>
						<?php _e(' has been removed from your extras.'); ?>
					</h4>
				</div>
			</div>
		</div>
	</div>

	<div id="popup-extra-insurance" class="popup-block popup-extra popup-extra--insurance">
		<div class="popup-block__background"></div>
		<div class="popup-block__inner">
			<div class="container popup-block__container position-relative">
				<div class="popup-block__content text-center text-color-light px-2 pt-5 pb-2">
					<span id="popupClose" class="popup-block__close"></span>
					<span class=" popup-extra__icon mb-2">
						<img src="<?php echo get_template_directory_uri(); ?>/assets/images/icon-warning.svg" alt="">
					</span>
					<h4 class="mb-2">
						<?php _e(' You haven’t approved the insurance declaration.'); ?>
					</h4>
					<p><?php _e('You have selected 1 race entry cancellation insurance, but haven’t approved the insurance declaration. Please read and approve the declaration to continue with your booking.', 'nirvana'); ?></p>
					<div class="d-flex popup-extra__buttons pt-5">
						<span class="w-100">
							<a class="button button--orange js-close-popup">GO BACK TO APPROVE</a>
						</span>
						
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="popup-extra-insurance-empty" class="popup-block popup-extra popup-extra--insurance">
		<div class="popup-block__background"></div>
		<div class="popup-block__inner">
			<div class="container popup-block__container position-relative">
				<div class="popup-block__content text-center text-color-light px-2 pt-5 pb-2">
					<span id="popupClose" class="popup-block__close"></span>
					<span class=" popup-extra__icon mb-2">
						<img src="<?php echo get_template_directory_uri(); ?>/assets/images/icon-warning.svg" alt="">
					</span>
					<?php
					$termsFile = get_field('insurance_terms_pdf', 'option');
					?>
					<p><?php _e('As part of your booking with Nirvana, athletes are eligible to receive a complimentary insurance policy that covers part of the fee you paid to enter the event, should an unexpected circumstance prevent you from reasonably taking part.', 'nirvana'); ?><br> Read full <a class="text--color--light-orange" href="<?php echo $termsFile['url']; ?>" target="_blank">T&C’s here</a>.</p>
					<div class="d-flex popup-extra__buttons pt-5">
						<span>
							<a class="button button--dark-white js-close-popup">NO - BACK TO EVENT SERVICES</a>
						</span>
						<span>
							<button type="submit" class="button button--orange w-100">Continue</button>
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>