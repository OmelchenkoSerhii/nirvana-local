<?php

/**
 * Template for displaying hotel room as card
 */

$debug = false;

$order         = $args['order'];
$room          = $args['room'];
$hotelPostID   = $args['hotelPostID'];
$hotelCode     = $args['hotelCode'];
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

	<li class="package-room package-room--popup <?php if ($selectionState == 2) echo 'active'; ?>">
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

        <div class="p-2 bg--light d-flex flex-column">
            <h3 class="h4">
                <?php echo esc_html($roomTitle); ?>
            </h3>

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
            if($boardBasisName): ?>
                <ul class="d-flex flex-column g-10 text--size--16 my-2">			
                    <li class="d-flex align-items-center g-10 text-capitalize">
                        <?php the_svg_by_sprite( 'list-icon-grey-plus', 20, 20, 'flex-shrink-0' ); ?>
                        <span class="text--opacity"><?php echo esc_html( $boardBasisName ); ?></span>
                    </li>
                </ul>
            <?php endif; ?>

            

            <div class="d-flex justify-content-between mt-2">
                <div class="hotel-rooms-list__meta-info">
                    <span class="d-block h3 text-uppercase">
                        <?php echo $order->getCurrencySymbol(); ?><?php echo number_format($price, 2, '.', ','); ?>
                    </span>
                    
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

                <div>
                    <span class="button button--orange js-upgrade-room" data-room="<?php echo $roomCode; ?>" data-roomid="<?php echo $roomComponentID; ?>"><?php _e('Select' , 'nirvana'); ?></span>
                </div>
                

    
            </div>

        </div>
		<pre>
			<?php //print_r($room); ?>
		</pre>
		
	</li>

<?php
