<?php
/**
 * Template for displaying hotel rooms as row list
 */

$rooms = $args['rooms'];
$hotelPostID = $args['hotelPostID'];
$hotelCode = $args['hotelCode'];
$order =  $args['order'];
$room_id = $args['room_id'];

$link = get_field('api_package_order_page','option');
?>
<section class="package-rooms-wrapper js-packages-rooms pt-1 pb-1">
	<ul class="package-rooms-list">
		<?php 
		if($rooms):
			if(is_array($rooms)):
				foreach($rooms as $room):
					get_template_part(
						'template-parts/api/packages/package',
						'room-card',
						array(
							'room'           => $room,
							'order'          => $order,
							'hotelPostID'    => $hotelPostID,
							'hotelCode'      => $hotelCode,
							'singleRoom'     => !(sizeof($rooms) > 1),
							'room_id'        => $room_id,
						)
					);
				endforeach;
			else:
				get_template_part(
					'template-parts/api/packages/package',
					'room-card',
					array(
						'room'           => $rooms,
						'order'          => $order,
						'hotelPostID'    => $hotelPostID,
						'hotelCode'      => $hotelCode,
						'singleRoom'      => true,
					)
				);
			endif;
		endif; 
		?>
		
	</ul>

	<?php if(sizeof($rooms) > 1): ?>
		<div id="popup-rooms-<?php echo $hotelCode; ?>" class="popup-block package-rooms-popup">
			<div class="popup-block__background"></div>
			<div class="popup-block__inner">
				<div class="container popup-block__container position-relative">
					<div class="popup-block__content text-color-light p-2">
						<span id="popupClose" class="popup-block__close"></span>
						<h4 class="mb-5 mt-3 text-center">Select your room</h4>
						<div class="position-relative">
							<ul class="package-popup-rooms-list">
								<?php 
								if($rooms):
									foreach($rooms as $room):
										if($room->RoomStay->BasicPropertyInfo->HCode == $hotelCode):
											get_template_part(
												'template-parts/api/packages/package-popup',
												'room-card',
												array(
													'room'           => $room,
													'order'          => $order,
													'hotelPostID'    => $hotelPostID,
													'hotelCode'      => $hotelCode,
													'singleRoom'     => false,
													'room_id'        => $room_id,
												)
											);
										endif;
									endforeach;
								endif; 
								?>
							</ul>
							<div class="slider-arrows">
								<button class="button--arrow button--arrow-left button--orange"></button>
								<button class="button--arrow button--orange"></button>
							</div>
						</div>

					</div>

				</div>
			</div>
		</div> <!-- #popup-hotel-location -->
	<?php endif; ?>
</section>

<?php
