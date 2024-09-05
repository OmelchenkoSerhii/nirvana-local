<?php
$debug = false;

$api   = $args['api'];


$hotelPostId = get_the_ID();
$hotelID = get_field('hotel_id');
$eventPost = $api->find_event_by_hotel_id($hotelPostId);
$eventPostID = false;
$eventPostLink = false;
if ($eventPost) {
    $eventPostID = $eventPost->ID;
    $orderEventID = get_field('event_id', $eventPostID);
    $orderEventCode = get_field('event_code', $eventPostID);
    $eventPostLink = get_field( 'api_accommodation_single_page', 'option' ).'?hotelid='.$hotelID.'&createorder=true';
}


//get acf hotel info
$hotelImage                   = false;
$hotelCityName                = '';
$hotelDescritpion             = '';
$hotelOverview                = '';
$hotelRating                  = 0;
$accommodation_overview_title = '';
$customMinNights = false;
$hotelRooms = false;
$video_tour_available = false;
$video_tour = false;
if ($hotelPostId) :
    $hotelImage                   = get_the_post_thumbnail_url($hotelPostId, 'full');
    $hotelCityName                = get_field('hotel_location', $hotelPostId);
    $hotelDescritpion             = get_field('hotel_description', $hotelPostId);
    $hotelOverview                = get_field('location_overview', $hotelPostId);
    $hotelRating                  = get_field('hotel_rating', $hotelPostId);
    $accommodation_overview_title = get_field('accommodation_overview_title', $hotelPostId);
    $customMinNights = get_field('minimum_nights', $hotelPostId);
    $hotelRooms  = get_field('hotel_rooms', $hotelPostId);
    $video_tour_available = get_field('video_tour_available' , $hotelPostId);
	$video_tour = get_field('video_tour' , $hotelPostId);
endif;

?>
<div class="booking-heading pl-xl-3 pr-xl-2">
    <div class="d-flex align-items-center header-booking__main__accom">
        <h1 class="h3"><?php echo get_the_title($hotelPostId); ?></h1>
        <?php the_rating(intval(get_field('hotel_rating', $hotelPostId)), 'ml-sm-1 mr-sm-1'); ?>

        <?php if($hotelRooms): ?>
            <a href="#book-rooms" class="button button--orange text-uppercase font--weight--700 text-center ml-auto reserve-btn"><?php echo esc_html_e('Book', 'nirvana'); ?></a>
        <?php endif; ?>
    </div>
</div>

<div class="accommodation pl-xl-3 pr-xl-2">

    <div class="bg--white position-absolute top-0 start-0 w-100 h-100"></div>


    <?php
    get_template_part(
        'template-parts/api/accommodation/hotel',
        'gallery',
        array(
            'hotelPostID' => $hotelPostId,
        )
    );
    ?>

    <?php if ($hotelDescritpion) : ?>
        <div class="accommodation-content p2 mt-5 mb-5 position-relative content-block">
            <?php echo $hotelDescritpion; ?>
        </div>
    <?php endif; ?>

    <?php
    $hotelAmenities = get_field('hotel_amenities', $hotelPostId);
    if ($hotelAmenities) :
    ?>
        <div class="accommodation-amenities my-5 position-relative">
            <h3 class="p2 mb-1">Amenities</h3>
            <ul class="text-uppercase text--size--12 font--weight--700 mt-3">
                <?php foreach ($hotelAmenities as $item) : ?>
                    <?php
                    $icon = $item['icon'];
                    $name = $item['name'];
                    ?>
                    <li class="d-flex g-10 align-items-center">
                        <?php if ($icon) : ?>
                            <span class="accommodation-amenities__icon">
                                <img src="<?php echo $icon['url']; ?>" alt="">
                            </span>
                        <?php else : ?>
                            <?php the_svg_by_sprite('list-icon-grey-plus', 20, 20); ?>
                        <?php endif; ?>
                        <span><?php echo $name; ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($video_tour_available &&  $video_tour) : ?>
		<div class="my-8">
			<div class="row justify-content-center">
				<div class="col-lg-6">
					<div class="videoBlock__video">
						<div class="video video--oembed">
							<?php echo $video_tour; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>

<?php if($hotelRooms && $eventPostID ): ?>

    <?php 
    $passengers = array(array(
        'Code' => 10,
        'Age' => 25,
        'Quantity' => 1,
    ));
    $start_date = get_field('event_start_date', $eventPostID);
    $start_date_str = false;
    $start_date_form_str = false;
    if($start_date):
        $dateFormatStart = DateTime::createFromFormat('Ymd', $start_date);
        if($dateFormatStart):
            $start_date_str = $dateFormatStart->format('j F Y');
            $start_date_form_str = $dateFormatStart->format('F j, Y');
        endif;
    endif;
    $hotelInfo      = $api->GetHotelInfo( $orderEventCode, $passengers , $hotelID, $start_date_str, $start_date_str );
    $hotelMinNights = false;
    $hotelMaxNights = false;
    if ( $hotelInfo ) :
        $hotelInfoRooms = $hotelInfo->FacilityInfo->GuestRooms->GuestRoom;
        if ( gettype( $hotelInfoRooms ) == 'array' ) :
            foreach ( $hotelInfoRooms as $room ) :
                if ( isset( $room->Restrictions ) && $room->Restrictions->MinMaxStays ) :
                    if ( $hotelMinNights ) :
                        if ( $hotelMinNights > $room->Restrictions->MinMaxStays->MinMaxStay->MinStay ) :
                            $hotelMinNights = $room->Restrictions->MinMaxStays->MinMaxStay->MinStay;
                            $hotelMaxNights = $room->Restrictions->MinMaxStays->MinMaxStay->MaxStay;
                        endif;
                    else :
                        $hotelMinNights = $room->Restrictions->MinMaxStays->MinMaxStay->MinStay;
                        $hotelMaxNights = $room->Restrictions->MinMaxStays->MinMaxStay->MaxStay;
                    endif;
                endif;
            endforeach;
        elseif ( isset( $hotelInfoRooms->Restrictions ) && $hotelInfoRooms->Restrictions->MinMaxStays ) :
                $hotelMinNights = $hotelInfoRooms->Restrictions->MinMaxStays->MinMaxStay->MinStay;
                $hotelMaxNights = $hotelInfoRooms->Restrictions->MinMaxStays->MinMaxStay->MaxStay;
        endif;
    endif;    
    if($customMinNights) $hotelMinNights = $customMinNights;
    ?>
    <section id="book-rooms" class="hotel-rooms-list js-rooms-search py-8 px-1 px-xl-5">
        
        <h2 class="h3 ml-1"><?php esc_html_e( 'Rooms', 'nirvana' ); ?></h2>

        <?php if ( $hotelMinNights ) : ?>
            <div class="hotel-nights py-3">
                <span class="hotel-nights__notice d-block text-center h4 text--size--16 p-2  ml-n5 mr-n4">
                    Minimum of <?php echo $hotelMinNights; ?> nights needed to reserve this hotel room. You need to change your dates to reserve it.
                </span>
            </div>
        <?php endif; ?>

        <div class="hotel-nights-form p-2" style="color: #000c26!important;">
            <?php
                get_template_part(
                    'template-parts/api/accommodation/hotel',
                    'single-dates-form',
                    array(
                        'date'     => $start_date_form_str,
                        'date_format'     => $start_date_str,
                        'hotel_link' =>  $eventPostLink,
                        'event_id' => $eventPostID,
                    )
                );
                ?>
        </div>

        <div class="hotel-rooms-list__content mt-2 position-relative hotel-single-form">
            
            <div class="js-rooms-list">
                <ul class="hotel-rooms-list__list">
                    <?php 
                    foreach($hotelRooms as $room): 
                    ?>
                        <?php 
                        $roomImage = $room['image'];
                        $roomTitle = $room['room_type_name'];
                        $roomDescription = $room['description'];
                        ?>
                        <li class="hotel-rooms-list__item">
                            <div class="position-relative overflow-hidden hotel-rooms-list__item-image">
                                <?php if ( $roomImage ) : ?>
                                    <img src="<?php echo $roomImage['url']; ?>" alt="">
                                <?php endif; ?>
                            </div>

                            <div class="p-2 bg--light d-flex flex-column">
                                <h3 class="h4">
                                    <?php echo esc_html( $roomTitle ); ?>
                                    
                                </h3>

                                <?php if($roomDescription): ?>
                                    <p class="text--opacity text--size--16 pt-2"><?php echo $roomDescription; ?></p>
                                <?php endif; ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <div class="slider-arrows">
                    <button type="button" class="button--arrow button--arrow-left button--orange"></button>
                    <button type="button" class="button--arrow button--orange"></button>
                </div>
            </div>
        
        </div>
    </section>
<?php elseif($hotelRooms && !$eventPostID): ?>

    <section id="book-rooms" class="hotel-rooms-list js-rooms-search py-8 px-1 px-xl-5">
        
        <h2 class="h3 ml-1"><?php esc_html_e( 'Rooms', 'nirvana' ); ?></h2>

        <div class="hotel-rooms-list__content mt-2 position-relative hotel-single-form">
            
            <div class="js-rooms-list">
                <ul class="hotel-rooms-list__list">
                    <?php 
                    foreach($hotelRooms as $room): 
                    ?>
                        <?php 
                        $roomImage = $room['image'];
                        $roomTitle = $room['room_type_name'];
                        $roomDescription = $room['description'];
                        ?>
                        <li class="hotel-rooms-list__item">
                            <div class="position-relative overflow-hidden hotel-rooms-list__item-image">
                                <?php if ( $roomImage ) : ?>
                                    <img src="<?php echo $roomImage['url']; ?>" alt="">
                                <?php endif; ?>
                            </div>

                            <div class="p-2 bg--light d-flex flex-column">
                                <h3 class="h4">
                                    <?php echo esc_html( $roomTitle ); ?>
                                    
                                </h3>

                                <?php if($roomDescription): ?>
                                    <p class="text--opacity text--size--16 pt-2"><?php echo $roomDescription; ?></p>
                                <?php endif; ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <div class="slider-arrows">
                    <button type="button" class="button--arrow button--arrow-left button--orange"></button>
                    <button type="button" class="button--arrow button--orange"></button>
                </div>
            </div>
        
        </div>
    </section>

<?php else: ?>
    <?php if($eventPostLink): ?>
        <div class="hotel-rooms-list py-8 px-1 px-xl-5">
            <div class="container text-center">
                <a class="button button--orange text-uppercase font--weight--700 text-center ml-auto" href="<?php echo $eventPostLink; ?>"><?php _e('Book Now'); ?></a>
            </div>    
        </div>
    <?php endif; ?>
<?php endif; ?>


<?php
$hotelLocation = get_field('hotel_location_map', $hotelPostId);
if ($hotelLocation) :
?>
    <section class="py-5 text--color--dark position-relative pl-xl-3 pr-xl-2">
        <h2 class="h3">
            <?php if ($accommodation_overview_title) : ?>
                <?php echo $accommodation_overview_title; ?>
            <?php else : ?>
                <?php esc_html_e('Location', 'nirvana'); ?>
            <?php endif; ?>
        </h2>
        <div class="popup-hotel-location__subhead d-flex mt-1">
            <span class="text-uppercase text--color--light-orange text--size--12 font--weight--700"><?php echo esc_html(get_field( 'hotel_location', $hotelPostId )); ?></span>
            <?php
            $show_miles_from_event = get_field('show_miles_from_event', $hotelPostId);
            $distance_format = get_field('distance_format', $hotelPostId);
            $miles_from_event      = get_field('miles_from_event', $hotelPostId);
            if ($show_miles_from_event && $miles_from_event) :
            ?>
                <span class="text-uppercase text--opacity text--size--12 font--weight--700 ml-1">
                    <?php
                    printf(
                        $distance_format == 'km' ? esc_html__('%s KM from event', 'nirvana') : esc_html__('%s Miles from event', 'nirvana'),
                        $miles_from_event
                    );
                    ?>
                </span>
            <?php endif; ?>
        </div>
        <?php if ($hotelOverview) : ?>
            <div class="text--opacity mt-3 content-block"><?php echo $hotelOverview; ?></div>
        <?php endif; ?>
        <div class="accommodation__location-map mt-3">
            <?php if ($hotelLocation) : ?>
                <div class="acf-map" data-zoom="16">
                    <div class="marker" data-lat="<?php echo esc_attr($hotelLocation['lat']); ?>" data-lng="<?php echo esc_attr($hotelLocation['lng']); ?>"></div>

                </div>
                <div class="map-notice text--size--14 font--weight--600">

                    <span>
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/map/hotel-icon.png"> - Hotel
                    </span>
                </div>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>

<?php
