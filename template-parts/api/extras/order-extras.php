<?php
$debug = false;
$order = $args['order'];
$api = $args['api'];

$orderType = $order->getOrderType();
$extras = $order->searchExtras();
$travellers = $order->getTravellers();
$childExist = false;
$infantExist = false;
foreach ($travellers as $passenger) :
	if (isset($passenger['AgeCode']) && ( $passenger['AgeCode'] == 'CHD' || $passenger['AgeCode'] == 8 )) $childExist = true;
	if (isset($passenger['AgeCode']) && ( $passenger['AgeCode'] == 'INF' || $passenger['AgeCode'] == 7 )) $infantExist = true;
endforeach;

$book_start_date = $order->getCheckinDate();
?>
<?php
if ($debug) :
?>
	<pre>
		<?php print_r($travellers); ?>
		<hr>
		<?php print_r($order->viewQuote()); ?>
		<hr>
		<?php print_r($extras); ?>
	</pre>
<?php
endif;

$link = get_field('api_summary_page', 'option');
?>

<?php 
$instruction = get_field('tailor_extras_instruction' , 'option');

$eventID = $order->getEventPostID();
$instruction_custom_change = get_field('change_text_on_event_services' , $eventID);
$event_services_content = get_field('event_services_content' , $eventID);
$instruction = ($instruction_custom_change && $event_services_content)?$event_services_content:$instruction;
if($instruction):
	?>
	<div class="content-block mb-4"><?php echo $instruction; ?></div>
<?php endif; ?>

<form id="api-extra-form" class="extras" action="<?php echo $link; ?>">
	<input id="order-number" type="hidden" name="" value="<?php echo $order->getOrderNumber() ; ?>">
	<div class="book-accommodation__form-loading" style="display: none;">
		<img src="<?php echo get_template_directory_uri(); ?>/assets/images/loader.svg" alt="">
	</div>
	<ul class="extras-list">
		<?php
		$index = 0;
		$reservedExtra = $order->getReservedExtras();
		foreach ($extras as $extraID => $extraItem) :
			$show = false;
			
			if ($extraItem->StayDateRange) :
				$start = $extraItem->StayDateRange->Start;
				$end = $extraItem->StayDateRange->End;
				if ($start && $end) :
					$start = DateTime::createFromFormat('Y-m-d', $start);
					$end = DateTime::createFromFormat('Y-m-d', $end);
					if($book_start_date == $start->format('j F Y')) $show = true;
				endif;
			endif;
			
			$extraType = $extraItem->Type;
			if($show && $extraType):
				if($orderType == 'bedbanks'):
					if (stripos($extraType, 'bedbanks') == false): $show = false; endif;
				else:	
					if (stripos($extraType, 'bedbanks') !== false): $show = false; endif;
				endif;
			endif;

			if($show):
			?>
				<li class="mb-1">
					<div class="extras-card p-1">

						<span class="extra-locator d-none"><?php echo $extraItem->TPA_Extensions->Locator; ?></span>
						<input type="hidden" class="js-extra-title" name="extra_name" value="<?php echo $extraItem->Description; ?>">
						<input type="hidden" class="extra-id" name="extra_id" value="<?php echo $extraID; ?>">

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
										<h3><?php echo $extraItem->Description; ?></h3>
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
										<span class="extras-card__qtt js-extra-qtt mr-2" style="display:none; ">Qty 1</span>
										<?php if($childExist || $infantExist): ?>
											<?php 
											if (is_array($extraItem->ExtraPricingInfo->PTC_FareBreakdowns->PTC_FareBreakdown)) :
												$adultPrice = false;
												$childPrice = false;
												$infantPrice = false;
												foreach ($extraItem->ExtraPricingInfo->PTC_FareBreakdowns->PTC_FareBreakdown as $breakdown) :
													if ($breakdown->PassengerTypeQuantity->Code == 10) :
														$adultPrice = $breakdown->PassengerFare->TotalFare->Amount;
													endif;
													if ($breakdown->PassengerTypeQuantity->Code == 8) :
														$childPrice= $breakdown->PassengerFare->TotalFare->Amount;
													endif;
													if ($breakdown->PassengerTypeQuantity->Code == 7) :
														$infantPrice= $breakdown->PassengerFare->TotalFare->Amount;
													endif;
												endforeach;
											endif;	
											?>
											<?php if (isset($extraItem->ExtraPricingInfo->PTC_FareBreakdowns->PTC_FareBreakdown) && is_array($extraItem->ExtraPricingInfo->PTC_FareBreakdowns->PTC_FareBreakdown)) : ?>
												<span class="extras-card__price mr-0">
													<?php if($childPrice || $infantPrice): ?>
														<?php echo $order->getCurrencySymbol(); ?><?php echo number_format($adultPrice, 2, '.', ','); ?><span>/pa</span>
													<?php else: ?>
														<?php echo $order->getCurrencySymbol(); ?><?php echo number_format($extraItem->ExtraPricingInfo->PTC_FareBreakdowns->PTC_FareBreakdown[0]->PassengerFare->TotalFare->Amount, 2, '.', ','); ?><span>/pp</span>
													<?php endif; ?>
													<?php if($childExist && $childPrice): ?>
														&nbsp;<?php echo $order->getCurrencySymbol(); ?><?php echo number_format($childPrice, 2, '.', ','); ?><span>/pc</span>
													<?php endif; ?>
													<?php if($infantExist && $infantPrice): ?>
														&nbsp;<?php echo $order->getCurrencySymbol(); ?><?php echo number_format($infantPrice, 2, '.', ','); ?><span>/pi</span>
													<?php endif; ?>
												</span>
											<?php else : ?>
												<span class="extras-card__price mr-0"><?php echo $order->getCurrencySymbol(); ?><?php echo number_format($extraItem->ExtraPricingInfo->PTC_FareBreakdowns->PTC_FareBreakdown->PassengerFare->TotalFare->Amount, 2, '.', ','); ?><span>/pp</span></span>
											<?php endif; ?>
										<?php else: ?>
											<?php if (isset($extraItem->ExtraPricingInfo->PTC_FareBreakdowns->PTC_FareBreakdown) && is_array($extraItem->ExtraPricingInfo->PTC_FareBreakdowns->PTC_FareBreakdown)) : ?>
												<span class="extras-card__price mr-0"><?php echo $order->getCurrencySymbol(); ?><?php echo number_format($extraItem->ExtraPricingInfo->PTC_FareBreakdowns->PTC_FareBreakdown[0]->PassengerFare->TotalFare->Amount, 2, '.', ','); ?><span>/pp</span></span>
											<?php else : ?>
												<span class="extras-card__price mr-0"><?php echo $order->getCurrencySymbol(); ?><?php echo number_format($extraItem->ExtraPricingInfo->PTC_FareBreakdowns->PTC_FareBreakdown->PassengerFare->TotalFare->Amount, 2, '.', ','); ?><span>/pp</span></span>
											<?php endif; ?>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
						<div class="extras-card__dropdown">
							<?php if ($extraItem->Comment || $extraItem->LongComment) : ?>
								<div class="content-block">
									<h4>Description</h4>
									<?php if($extraItem->Comment): ?>
										<p><?php echo $extraItem->Comment; ?></p>
									<?php endif; ?>
									<?php if($extraItem->LongComment): ?>
										<p><?php echo $extraItem->LongComment; ?></p>
									<?php endif; ?>
								</div>
							<?php endif; ?>
							<?php if ($travellers) : ?>
								<div class="extras-card__travellers mt-3">
									<div class="extras-card__travellers__header d-flex justify-content-between">
										<h4>Assign to travellers <span class="js-person-selected" style="display: none;">(<span class="js-person-selected-val">1</span> selected)</span></h4>
										<div class="extras-card__travellers__nav font--weight--600">
											<span class="js-select-all"><?php _e('Select all','nirvana'); ?></span>
											|
											<span class="js-clear-all"><?php _e('Clear all','nirvana'); ?></span>
										</div>
									</div>
									<ul class="extras-card__travellers-list">
										<?php
										$indexInner = 1;
										foreach ($travellers as $passenger) :
											$passengerAgeCode = 10;
											if (isset($passenger['AgeCode']) && ( $passenger['AgeCode'] == 'CHD' || $passenger['AgeCode'] == 8 )) $passengerAgeCode = 8;
											if (isset($passenger['AgeCode']) && ( $passenger['AgeCode'] == 'INF' || $passenger['AgeCode'] == 7 )) $passengerAgeCode = 7;

											$passengerRefNumber = $passenger['ref_number'];
											$price = 0;
											$showFlag = false;

											if (is_array($extraItem->ExtraPricingInfo->PTC_FareBreakdowns->PTC_FareBreakdown)) :
												foreach ($extraItem->ExtraPricingInfo->PTC_FareBreakdowns->PTC_FareBreakdown as $breakdown) :
													if ($breakdown->PassengerTypeQuantity->Code == $passengerAgeCode) :
														$showFlag = true;
														$price = $breakdown->PassengerFare->TotalFare->Amount;
													endif;
												endforeach;
											else :
												if ($extraItem->ExtraPricingInfo->PTC_FareBreakdowns->PTC_FareBreakdown->PassengerTypeQuantity->Code == $passengerAgeCode) :
													$showFlag = true;
													$price = $extraItem->ExtraPricingInfo->PTC_FareBreakdowns->PTC_FareBreakdown->PassengerFare->TotalFare->Amount;
												endif;
											endif;

											if ($showFlag) :
												//check if extra is reserved
												$checked = false;
												if($reservedExtra):
													foreach($reservedExtra as $reservedExtraItem):
														if($reservedExtraItem['id'] == $extraID){
															foreach($reservedExtraItem['travellers'] as $traveller){
																if($traveller['ref_number'] == $passengerRefNumber && $traveller['selected'] == 1){
																	$checked = true;
																}
															}
														}
													endforeach;
												endif;
												?>
												<li class="extras-card__travellers-item">
													<label class="extras-card__travellers-item__label d-flex align-items-center">
														<span class="checkbox-style mr-1">
															<input class="person" type="checkbox" name="person_<?php echo $index; ?>_<?php echo $indexInner; ?>" value="<?php echo $passenger['ref_number']; ?>" <?php if($checked) echo 'checked="true"'; ?>>
															<span class="checkbox-style__pseudo"></span>
														</span>
														<span><?php echo $passenger['full-name']; ?></span>
													</label>
													<input type="hidden" name="price" class="price" value="<?php echo $price;  ?>">
													<input type="hidden" name="type" class="type" value="<?php echo $passengerAgeCode; ?>">
												</li>
											<?php endif;
											$indexInner++; ?>
										<?php endforeach; ?>
									</ul>
								</div>
							<?php endif; ?>
							<?php if( strpos(strtolower($extraItem->Description), "race fee cover") !== false || strpos(strtolower($extraItem->Description), "race entry fee") !== false): ?>
								<div class="extras-card__insurance">
									<h4><?php _e('Insurance declaration','nirvana') ?></h4>

									<div class="content-block">
										<?php the_field('insurance_description','option'); ?>
									</div>

									<?php 
									$termsFile = get_field('insurance_terms_pdf','option');
									?>

									<label class="extras-card__insurance-label d-flex align-items-center">
										<span class="checkbox-style mr-1">
											<input class="insurance-checkbox" type="checkbox" name="insurance" <?php if($checked) echo 'checked="true"'; ?>>
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

	<div class="d-flex justify-content-end mt-3">
		<button type="submit" class="button button--orange"><?php _e('Continue' , 'nirvana'); ?></button>
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