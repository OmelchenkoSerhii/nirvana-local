<?php
/**
 * Template part for displaying hotel as card in list
 */

$hotel = $args['hotel'];
$order = $args['order'];
$api   = $args['api'];


$link = get_field( 'api_accommodation_single_page', 'option' );

$hotelCode   = $hotel->BasicPropertyInfo->HotelCode;
$hotelPostId = $api->GetAccomHotelPostID( $hotelCode );

if ( empty( $hotelPostId ) || 'publish' !== get_post_status( $hotelPostId ) ) {
	return;
}

//get hotel data
$hotelTitle    = $hotel->BasicPropertyInfo->HotelName;
$hotelLink     = $link . '?hotelid=' . $hotelCode;
$priorityHotel = $hotel->BasicPropertyInfo->PriorityHotel;

//get from price
$fromPrice = 9999999;
$currency = 'GPB';
if ( gettype( $hotel->RoomRates->RoomRate ) == 'array' ) :
	foreach ( $hotel->RoomRates->RoomRate as $rate ) {
		$price = intval( $rate->Rates->Rate->Total->AmountAfterTax );
		if ( $price < $fromPrice ) {
			$fromPrice = $price;
		}
	}
else :
	foreach ( $hotel->RoomRates as $rate ) {
		$price = intval( $rate->Rates->Rate->Total->AmountAfterTax );
		if ( $price < $fromPrice ) {
			$fromPrice = $price;
		}
	}
endif;
$hotelPrice = $fromPrice == 9999999 ? false : $fromPrice;

//get hotel post data
$hotelImage       = false;
$hotelCityName    = '';
$hotelDescritpion = '';
$hotelRating      = 0;
$hotelMap         = false;
$hotelMap         = false;
$hidePriority     = false;
$changePriority = false;
$changePriorityLabel = false;

$show_miles_from_event = get_field( 'show_miles_from_event', $hotelPostId );
$distance_format = get_field( 'distance_format', $hotelPostId );
$miles_from_event      = get_field( 'miles_from_event', $hotelPostId );

if ( $hotelPostId ) :
	$hotelImage       = get_the_post_thumbnail_url( $hotelPostId, 'full' );
	$hotelCityName    = get_field( 'hotel_location', $hotelPostId );
	$hotelDescritpion = get_field( 'hotel_description', $hotelPostId );
	$hotelRating      = get_field( 'hotel_rating', $hotelPostId );
	$hotelMap         = get_field( 'hotel_location_map', $hotelPostId );

	$showCardLabel    = get_field( 'add_card_label', $hotelPostId );
	$cardLabels       = get_field( 'card_label_tags', $hotelPostId );
	$hidePriority     = get_field( 'hide_priority', $hotelPostId );
	$changePriority   = get_field( 'change_priority', $hotelPostId );
	$changePriorityLabel = get_field( 'priority_label_text', $hotelPostId );
endif;

if ( $hotelCode != $_GET['hotelid'] ) :
	?>
<li class="hotel-similar-list__item">
	<?php print_r($hotel); ?>
	<a class="accommodations__item-image-wrapper" href="<?php echo $hotelLink; ?>">
		
			<div class="image-ratio" style="padding-bottom: 60%;">
				<?php
				if ( $hotelImage ) :
					?>
					<img src="<?php echo $hotelImage; ?>" alt="">
				<?php endif; ?>
			</div>
		
	</a>

	<div class="p-2 bg--light">
		<h3 class="h4"><?php echo esc_html( $hotelTitle ); ?></h3>
		<a href="#" class="link--style--1"><?php echo esc_html( $hotelCityName ); ?></a>
	   
		<?php if ( ! $hotelPrice ) : ?>
			<span class="h3 d-block mt-1 text-uppercase"><?php esc_html_e( 'Sold Out', 'nirvana' ); ?></span>
		<?php else : ?>
			<span class="h3 d-block mt-1">
				<?php
				printf(
					/* translators: 1: Currency 2: Room price */
					esc_html__( 'From %1$s%2$s', 'nirvana' ),
					$order->getCurrencySymbolByCode($currency),
					esc_html( $hotelPrice )
				);
				?>
			</span>
		<?php endif; ?>
		

		<div class="d-flex align-items-center justify-content-between mt-1">
			<span class="text--size--16 text--opacity">
				<?php
				printf(
					/* translators: 1: Nights 2: Adults */
					esc_html__( '%1$s nights, %2$s', 'nirvana' ),
					$order->getNightsQtt(),
					$order->getPeopleQtt(),
				);
				?>
			</span>
			<?php the_rating( intval( $hotelRating ), 'mt-1' ); ?>
		</div>
	</div>
</li>
<?php endif; ?>
