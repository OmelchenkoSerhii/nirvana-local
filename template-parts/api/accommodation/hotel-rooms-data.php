<?php
$rooms         = $args['rooms'];
$order         = $args['order'];
$hotelPostID   = $args['hotel_post_id'];
$selectedRooms = $args['selected_rooms'];
$hotelInfo     = $args['hotelInfo'];

$roomSets = $order->getRooms();

$roomsArray = array();
foreach ( $rooms as $key => $roomsCombo ) :
	if ( is_array( $roomsCombo->RoomRates->RoomRate ) ) :
		foreach ( $roomsCombo->RoomRates->RoomRate as $room ) :
			$room->Combo = $key;
			array_push( $roomsArray, $room );
		endforeach;
	else :
		$roomsCombo->RoomRates->RoomRate->Combo = $key;
		array_push( $roomsArray, $roomsCombo->RoomRates->RoomRate );
	endif;
endforeach;


//sort by price
usort(
	$roomsArray,
	function ( $a, $b ) {
		return $a->Rates->Rate->Total->AmountAfterTax <=> $b->Rates->Rate->Total->AmountAfterTax;
	}
);
?>

<ul class="hotel-rooms-list__list">
	<?php foreach ( $roomsArray as $room ) : ?>
		<?php
		get_template_part(
			'template-parts/api/accommodation/hotel',
			'room-card',
			array(
				'room'           => $room,
				'order'          => $order,
				'hotelPostID'    => $hotelPostID,
				'selected_rooms' => $selectedRooms,
				'rooms_sets'     => $roomSets,
				'hotel_info'     => $hotelInfo,
			)
		);
		?>
	<?php endforeach; ?>
	
</ul>

<div class="slider-arrows">
	<button type="button" class="button--arrow button--arrow-left button--orange"></button>
	<button type="button" class="button--arrow button--orange"></button>
</div>
