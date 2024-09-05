<?php
/**
 * Template for displaying hotel room as card
 */

$debug = false;

$order         = $args['order'];
$room          = $args['room'];
$hotelPostID   = $args['hotelPostID'];
$selectedRooms = $args['selected_rooms'];
$roomSets      = $args['rooms_sets'];
$hotelInfo     = $args['hotel_info'];

$roomCombo       = $room->Combo;
$roomObject      = $room->Rates->Rate;
$roomID          = $room->RoomTypeCode;
$roomRatePlanCode = $room->RatePlanCode;
$roomTitle       = $roomObject->TPA_Extensions->RoomType->RoomType;
$roomCurrency    = $roomObject->Total->CurrencyCode;
$roomPrice       = $roomObject->Total->AmountAfterTax;
$roomLocator     = $roomObject->TPA_Extensions->Locator;
$roomRef         = $roomObject->TPA_Extensions->Nonrefundable?$roomObject->TPA_Extensions->Nonrefundable:0;
$roomDescription = false;
$roomImage       = false;
$roomFeatures    = false;
$roomSupplements = false;
$roomMinNights   = false;
$roomsQtt        = $room->NumberOfUnits;

$boardBasisValues = get_field('board_basis' , 'option');
$hotelBoardBasis = get_field('board_basis' , $hotelPostID);
$boardBasis = $roomObject->TPA_Extensions->BoardBasis;

$boardBasisName = '';

if($boardBasisValues && $boardBasis):
	foreach ( $boardBasisValues as $feature ) :
		if($feature['name'] == $boardBasis):
			$boardBasisName = $feature['description'];
		endif;
	endforeach;
endif;

if($hotelBoardBasis):
	$boardBasisName = $hotelBoardBasis;
endif;

$hotelPostRooms = get_field( 'hotel_rooms', $hotelPostID );
if ( $hotelPostRooms ) :
	foreach ( $hotelPostRooms as $roomItem ) :
		if ( $roomItem['id'] == $roomID ) :
			if ( $roomItem['image'] ) :
				$roomImage = $roomItem['image']['url'];
			endif;
			$roomDescription = $roomItem['description'];
			break;
		endif;
	endforeach;
endif;

//get travellers occupancy
$roomAdults = $roomSets[ $roomCombo ]['adults_qtt'];
$roomChilds = $roomSets[ $roomCombo ]['childs_qtt'];
$roomInfants = $roomSets[ $roomCombo ]['infants_qtt'];


$selectedQtt = 0;
if ( $selectedRooms ) :
	foreach ( $selectedRooms as $room ) :
		if ( $room['room_id'] == $roomID && $roomAdults == $room['room_adults'] && $roomChilds == $room['room_children'] && $roomInfants == $room['room_infants'] ) :
			++$selectedQtt;
		endif;
	endforeach;
endif;

if ( $hotelInfo ) :
	$hotelInfoRooms = $hotelInfo->FacilityInfo->GuestRooms->GuestRoom;
	if ( gettype( $hotelInfoRooms ) == 'array' ) :
		foreach ( $hotelInfoRooms as $room ) :
			if ( $roomID == $room->ID ) :
				if ( isset( $room->Restrictions ) && $room->Restrictions->MinMaxStays ) :
					$roomMinNights = $room->Restrictions->MinMaxStays->MinMaxStay->MinStay;
				endif;
				break;
			endif;
		endforeach;
	else:
		if( $roomID == $hotelInfoRooms->ID ):
			if ( isset( $hotelInfoRooms->Restrictions ) && $hotelInfoRooms->Restrictions->MinMaxStays ) :
				$roomMinNights = $hotelInfoRooms->Restrictions->MinMaxStays->MinMaxStay->MinStay;
			endif;
		endif;
	endif;
endif;
?>

<li class="hotel-rooms-list__item hotel-rooms-list__item--bedbank">
	<?php if ( $debug ) : ?>
		<pre style="color: black;position: relative;z-index: 2;">
			<?php print_r($roomObject); ?>
			<hr>
			<?php print_r($room); ?>
			<hr>
			<?php print_r($hotelInfo); ?>
		</pre>
	<?php endif; ?>
	<?php if(false): ?>
		<div class="position-relative overflow-hidden hotel-rooms-list__item-image">
			<?php if ( $roomImage ) : ?>
				<img src="<?php echo $roomImage; ?>" alt="">
			<?php endif; ?>

			<?php if ( $roomMinNights && false ) : ?>
				<?php the_icon_bookmark_min_nights( $roomMinNights ); ?>
			<?php endif; ?>

			<?php if ( 1 === $roomsQtt ) : ?>
				<span class="final-room"><?php echo esc_html_e( 'Final Room', 'nirvana' ); ?></span>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div class="p-2 bg--light d-flex flex-column hotel-rooms-list__item-inner">
		<?php if ( 1 === $roomsQtt ) : ?>
			<span class="final-room-badge mb-2"><?php echo esc_html_e( 'Final Room', 'nirvana' ); ?></span>
		<?php endif; ?>
		<h3 class="h4">
			<?php echo esc_html( $roomTitle ); ?>
			<?php
			if ( false ) :
				?>
				, <span class="text--opacity font--weight--400"><?php echo esc_html( $room['type'] ); ?></span><?php endif; ?>
		</h3>

		<?php if($roomDescription): ?>
			<p class="text--opacity text--size--16 pt-2"><?php echo $roomDescription; ?></p>
		<?php endif; ?>

		<?php if ( $roomFeatures ) : ?>
			<ul class="d-flex flex-column g-10 text--size--16 my-2">
				<?php foreach ( $room['features'] as $feature ) : ?>
					<li class="d-flex align-items-center g-10 text-capitalize">
						<?php the_svg_by_sprite( 'list-icon-grey-plus', 20, 20, 'flex-shrink-0' ); ?>
						<span class="text--opacity"><?php echo esc_html( $feature ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php 
		if($roomRatePlanCode || $roomRef): ?>
			<ul class="d-flex flex-column g-10 text--size--16 my-2">
				<?php if($roomRatePlanCode): ?>		
					<li class="d-flex align-items-center g-10 text-capitalize">
						<?php the_svg_by_sprite( 'list-icon-grey-plus', 20, 20, 'flex-shrink-0' ); ?>
						<span class="text--opacity"><?php echo esc_html( $order->getRatePlanNameNyCode($roomRatePlanCode) ); ?></span>
					</li>
				<?php endif; ?>
				<?php if($roomRef && $roomRef == 1): ?>		
					<li class="d-flex align-items-center g-10 text-capitalize">
						<?php the_svg_by_sprite( 'list-icon-grey-plus', 20, 20, 'flex-shrink-0' ); ?>
						<span class="text--opacity"><?php echo __('Non Refundable','nirvana'); ?></span>
					</li>
				<?php endif; ?>
			</ul>
		<?php endif; ?>

		<?php if ( false && $dates_travellers['nights'] < $room['min_nights'] ) : //If min nights > booked nights ?>
			<a href="#" class="button button--dark-transparent text-center w-100 mt-auto"><?php echo esc_html_e( 'More Info', 'nirvana' ); ?></a>
		<?php else : ?>
			<?php if ( $roomSupplements ) : ?>
				<div class="hotel-rooms-list__supplements">
					<span class="d-block text--size--12 font--weight--700"><?php echo esc_html_e( 'Room Supplements', 'nirvana' ); ?></span>

					<?php foreach ( $room['supplements'] as $supplement ) : ?>
						<label class="d-flex g-10 text--size--16 mt-1">
							<div class="checkbox-wrapper">
								<input type="checkbox">
								<div class="checkbox-active"></div>
							</div>
							<div class="d-flex w-100 align-items-center justify-content-between">
								<span class="text--opacity"><?php echo esc_html( $supplement['name'] ); ?></span>
								<span>+<?php echo $order->getCurrencySymbol(); ?><?php echo esc_html( $supplement['price'] ); ?></span>
							</div>
						</label>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<div class="d-flex flex-wrap justify-content-between mt-auto pt-2">
				<div class="hotel-rooms-list__meta-info">
					<span class="d-block h3 text-uppercase">
						<?php if ( 0 === $roomsQtt || $roomPrice == 0 ) : ?>
							<?php echo esc_html_e( 'Sold Out', 'nirvana' ); ?>
						<?php else : ?>
							<?php echo $order->getCurrencySymbolByCode($roomCurrency); ?><?php echo $roomPrice; ?>
						<?php endif; ?>
					</span>
					
					<span class="d-block text--size--16 text--opacity mt-05">
						<?php echo $order->getNightsQtt(); ?> <?php echo esc_html_e( 'nights', 'nirvana' ); ?>, 
						<?php if ( $roomAdults != 0 ) : ?>
							<?php echo $roomAdults; ?> <?php echo $roomAdults <= 1 ? 'adult' : 'adults'; ?>
						<?php endif; ?>
						<?php if ( $roomChilds != 0 ) : ?>
							<?php echo $roomChilds; ?> <?php echo $roomChilds <= 1 ? 'child' : 'children'; ?>
						<?php endif; ?>
						<?php if ( $roomInfants != 0 ) : ?>
							<?php echo $roomInfants; ?> <?php echo $roomInfants <= 1 ? 'infant' : 'infants'; ?>
						<?php endif; ?>
					</span>
				</div>

				<div class="hotel-rooms-qtt-select">
					<?php if ( 0 === $roomsQtt ) : ?>
						<span class="hotel-rooms-qtt-select__header--sold-out button--disabled"><?php echo esc_html_e( 'Select', 'nirvana' ); ?></span>
					<?php else : ?>
						<?php if ( $selectedQtt == 0 ) : ?>
							<span class="hotel-rooms-qtt-select__header"><?php echo esc_html_e( 'Select', 'nirvana' ); ?></span>
						<?php else : ?>
							<span class="hotel-rooms-qtt-select__header"><?php echo esc_html_e( 'Selected', 'nirvana' ); ?>(<?php echo $selectedQtt; ?>)</span>
						<?php endif; ?>
						
						<ul class="hotel-rooms-qtt-select__dropdown">
							<li data-key="0">
								0
							</li>
							<?php for ( $i = 1; $i <= $roomsQtt; $i++ ) : ?>
								<li data-key="<?php echo $i; ?>">
									<?php echo esc_html( $i ); ?>
								</li>
							<?php endfor; ?>
						</ul>
						<input type="hidden" class="hotel_rooms" name="hotel_rooms[<?php echo $roomID . '_' . $roomCombo; ?>]" value="<?php echo $selectedQtt; ?>">
						<input type="hidden" class="hotel_rooms_id" name="hotel_rooms_id[<?php echo $roomID . '_' . $roomCombo; ?>]" value="<?php echo $roomID; ?>">
						<input type="hidden" class="hotel_rooms_title" name="hotel_rooms_title[<?php echo $roomID . '_' . $roomCombo; ?>]" value="<?php echo $roomTitle; ?>">
						<input type="hidden" class="hotel_rooms_price" name="hotel_rooms_price[<?php echo $roomID . '_' . $roomCombo; ?>]" value="<?php echo $roomPrice; ?>">
						<input type="hidden" class="hotel_rooms_board" name="hotel_rooms_board[<?php echo $roomID . '_' . $roomCombo; ?>]" value="<?php echo $boardBasisName; ?>">
						<input type="hidden" class="hotel_rooms_ref" name="hotel_rooms_ref[<?php echo $roomID . '_' . $roomCombo; ?>]" value="<?php echo $roomRef; ?>">
						<input type="hidden" class="hotel_rooms_currencies" name="hotel_rooms_currency[<?php echo $roomID . '_' . $roomCombo; ?>]" value="<?php echo $roomCurrency; ?>">
						<input type="hidden" class="hotel_rooms_locator" name="hotel_rooms_locator[<?php echo $roomID . '_' . $roomCombo; ?>]" value="<?php echo esc_html( $roomLocator ); ?>">
						<input type="hidden" class="hotel_rooms_combo" name="hotel_rooms_combo[<?php echo $roomID . '_' . $roomCombo; ?>]" value="<?php echo $roomCombo; ?>">
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( 0 === $roomsQtt || $roomPrice == 0 ) : ?>
			<div class="bg--sold-out"></div>
		<?php endif; ?>
	</div>
</li>

<?php
