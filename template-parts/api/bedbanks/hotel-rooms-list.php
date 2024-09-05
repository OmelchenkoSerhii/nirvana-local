<?php
/**
 * Template for displaying hotel rooms as row list
 */

$rooms = $args['rooms'];
$hotelPostID = $args['hotelPostID'];
$hotelCode = $args['hotelCode'];
$order =  $args['order'];
$hotelInfo = $args['hotelInfo'];
$hotelMinNights = $args['hotelMinNights'];
$selected_rooms =  $order->getAccommodations();

$hotelName = false;
if(isset($hotelInfo->HotelInfo->HotelName->_) && $hotelInfo->HotelInfo->HotelName->_) $hotelName = $hotelInfo->HotelInfo->HotelName->_;

$link = get_field('api_accommodation_order_page','option');
?>
<section id="rooms" class="hotel-rooms-list js-bedbanks-rooms-search py-8 px-1 px-xl-5" data-hotel="<?php echo $hotelPostID; ?>" data-hotelcode="<?php echo $hotelCode; ?>" data-order="<?php echo $order->getOrderNumber(); ?>">
	<h2 class="h3 ml-1"><?php esc_html_e( 'Rooms', 'nirvana' ); ?></h2>

	<div class="pt-3 ml-1">
		<?php 
		get_template_part(
			'template-parts/api/order',
			'dates-select',
			array(
				'order' => $order
			)
		);
		?>
	</div>

	<div class="book-accommodation__form-loading" style="display: none;">
		<img src="<?php echo get_template_directory_uri(); ?>/assets/images/loader.svg" alt="">
	</div>

	<form id="rooms-reserve-form" method="POST" action="<?php echo $link; ?>" class="hotel-rooms-list__content mt-2 position-relative hotel-single-form">
		
		<div class="js-bedbanks-rooms-list" data-nights="<?php echo $hotelMinNights; ?>">
			<?php if($rooms): ?>
				<?php 
				get_template_part(
					'template-parts/api/bedbanks/hotel-rooms',
					'data',
					array(
						'order' => $order,
						'rooms' => $rooms,
						'hotel_post_id' => $hotelPostID,
						'selected_rooms' => $selected_rooms,
						'hotelInfo' => $hotelInfo,
					)
				);
				?>
			<?php else: ?>
				<h3 class="p-1"><?php _e('No available rooms found for selected dates.' , 'nirvana'); ?></h3>
			<?php endif; ?>
		</div>
	
		<input type="hidden" name="hotelid" value="<?php echo $_GET['hotelid']; ?>">
		<input type="hidden" name="hotel_name" value="<?php echo $hotelName; ?>">
		<input type="hidden" name="submission_key" value="<?php echo wp_generate_uuid4(); ?>">

		<div class="d-flex justify-content-center w-100">
			<button type="submit" class="button button--orange text-uppercase font--weight--700 text-center mt-5"><?php echo esc_html_e( 'Continue', 'nirvana' ); ?></button>
		</div>
		<div class="order-form__errors text-center pt-2" style="display: none;"></div>
	</form>
</section>

<?php
