<?php

/**
 * Template for displaying hotel room as card
 */

$debug = false;

$order         = $args['order'];
$room          = $args['room'];
$hotelPostID   = $args['hotelPostID'];
$hotelCode     = $args['hotelCode'];
$singleRoom    = $args['singleRoom'];
$room_id       = $args['room_id'];

$packageRooms = $order->getRooms();

$roomID          = $room->RoomStay->RoomType->RoomTypeCode;
$roomTitle       = $room->RoomStay->RoomType->RoomType;
$roomCode        = $room->RoomStay->RoomType->RoomTypeCode;
$roomComponentID = $room->RoomStay->ID;
$roomsQtt        = $room->TPA_Extensions->Available;
$selectionState  = $room->TPA_Extensions->SelectionState;
$roomStayNights  = $room->RoomStay->TimeSpan->Duration;
$roomImage       = false;
$roomDescription = false;
$roomFeatures    = false;
$roomSupplements = false;
$roomMinNights   = false;

$boardBasisValues = get_field('board_basis', 'option');
$hotelBoardBasis = get_field('board_basis', $hotelPostID);
$boardBasis = $room->RoomStay->RoomType->TPA_Extensions->BoardBasis;

$boardBasisName = '';

if ($boardBasisValues && $boardBasis) :
	foreach ($boardBasisValues as $feature) :
		if ($feature['name'] == $boardBasis) :
			$boardBasisName = $feature['description'];
		endif;
	endforeach;
endif;

if ($hotelBoardBasis) :
	$boardBasisName = $hotelBoardBasis;
endif;

$hotelPostRooms = get_field('hotel_rooms', $hotelPostID);
if ($hotelPostRooms) :
	foreach ($hotelPostRooms as $roomItem) :
		if ($roomItem['id'] == $roomID) :
			if ($roomItem['image']) :
				$roomImage = $roomItem['image']['url'];
			endif;
			$roomDescription = $roomItem['description'];
			break;
		endif;
	endforeach;
endif;

$api = new NiravanaAPI();
$supplements = $api->GetAccomSupplements($order->getEventCode() , $order->getCurrency() , $order->getPassengerTypeQuantity(), $roomCode , $order->getCheckinDateFormat('Y-m-d') , $order->getCheckoutDateFormat('Y-m-d'));
$price = 0;
if(isset($supplements->Supplements->Supplement)):
	if(is_array($supplements->Supplements->Supplement)):
		foreach($supplements->Supplements->Supplement as $supplement):
			$price += $supplement->SupplementPricingInfo->ItinTotalFare->TotalFare->Amount;
		endforeach;
	else:
		$price = $supplements->Supplements->Supplement->SupplementPricingInfo->ItinTotalFare->TotalFare->Amount;
	endif;
endif;
?>

	<li class="package-room <?php if ($selectionState == 2 || $singleRoom) echo 'active'; ?>" data-room="<?php echo $roomCode; ?>" data-roomid="<?php echo $roomComponentID; ?>" data-title="<?php echo $roomTitle; ?>" data-bb="<?php echo $boardBasisName;?>" data-state="<?php echo $selectionState;?>" data-price="<?php echo $price; 
	?>" data-duration="<?php echo $roomStayNights; ?>">
		<div class="package-room__inner">
			<?php if ($roomImage) : ?>
			<div class="package-room__image">
				<div class="package-room__image__img">
					<img src="<?php echo $roomImage; ?>" alt="">
					<?php if (1 === $roomsQtt) : ?>
						<span class="final-room"><?php echo esc_html_e('Final Room', 'nirvana'); ?></span>
					<?php endif; ?>
				</div>
			</div>
			<?php endif; ?>
			<div class="package-room__info">
				<div class="d-flex flex-column  h-100">
					<div class="d-flex flex-wrap flex-md-nowrap justify-content-between">
						<div>
							<h3 class="h4">
								<?php echo esc_html($roomTitle); ?>
								<?php
								if (false) :
								?>
									, <span class="text--opacity font--weight--400"><?php echo esc_html($room['type']); ?></span><?php endif; ?>
							</h3>
							<?php if($roomDescription): ?>
								<p class="text--opacity text--size--16 pt-2"><?php echo $roomDescription; ?></p>
							<?php endif; ?>
						</div>
						
						<?php if(!$singleRoom): ?>
							<div class="d-flex flex-column pt-2 pt-md-0 align-items-md-end">
								<h4><?php _e('View alternative room options','nirvana'); ?></h4>
								<a class="button button--sm button--orange mt-2 package-room__upgrade-btn js-open-packages-popup" data-popup="popup-rooms-<?php echo $hotelCode; ?>">View</a>
							</div>
						<?php endif; ?>
					</div>

					<?php if ($roomFeatures) : ?>
						<ul class="d-flex flex-column g-10 text--size--16 my-2">
							<?php foreach ($room['features'] as $feature) : ?>
								<li class="d-flex align-items-center g-10 text-capitalize">
									<?php the_svg_by_sprite('list-icon-grey-plus', 20, 20, 'flex-shrink-0'); ?>
									<span class="text--opacity"><?php echo esc_html($feature); ?></span>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

					<?php
					if ($boardBasisName) : ?>
						<ul class="d-flex flex-column g-10 text--size--16 my-2">
							<li class="d-flex align-items-center g-10 text-capitalize">
								<?php the_svg_by_sprite('list-icon-grey-plus', 20, 20, 'flex-shrink-0'); ?>
								<span class="text--opacity"><?php echo esc_html($boardBasisName); ?></span>
							</li>
						</ul>
					<?php endif; ?>


					<?php if ($roomSupplements) : ?>
						<div class="hotel-rooms-list__supplements">
							<span class="d-block text--size--12 font--weight--700"><?php echo esc_html_e('Room Supplements', 'nirvana'); ?></span>

							<?php foreach ($room['supplements'] as $supplement) : ?>
								<label class="d-flex g-10 text--size--16 mt-1">
									<div class="checkbox-wrapper">
										<input type="checkbox">
										<div class="checkbox-active"></div>
									</div>
									<div class="d-flex w-100 align-items-center justify-content-between">
										<span class="text--opacity"><?php echo esc_html($supplement['name']); ?></span>
										<span>+<?php echo $order->getCurrencySymbol(); ?><?php echo esc_html($supplement['price']); ?></span>
									</div>
								</label>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

					<div class="d-flex flex-wrap justify-content-end text-align-right mt-auto pt-2">
						<div class="hotel-rooms-list__meta-info">
							<?php if(true): ?>
							<span class="d-block h3 text-uppercase text-right">
								<?php if (0 === $roomsQtt) : ?>
									<?php echo esc_html_e('Sold Out', 'nirvana'); ?>
								<?php else : ?>
									<?php if($price == 0): ?>
										<?php //echo $order->getCurrencySymbol().'0'; ?>
									<?php else: ?>
										<?php echo $order->getCurrencySymbol(); ?><?php echo number_format($price, 2, '.', ','); ?>
									<?php endif;?>
								<?php endif; ?>
							</span>
							<?php endif; ?>

							<span class="d-block text--size--16 text--opacity mt-05">
								<?php
								printf(
									/* translators: 1: Nights 2: Adults */
									esc_html__('%1$s nights, %2$s', 'nirvana'),
									$roomStayNights,
									$order->getCustomPeopleQtt($packageRooms[$room_id]['adults_qtt'] , $packageRooms[$room_id]['childs_qtt']),
								);
								?>
							</span>
						</div>
					</div>


				</div>
			</div>
		</div>
	</li>

<?php
