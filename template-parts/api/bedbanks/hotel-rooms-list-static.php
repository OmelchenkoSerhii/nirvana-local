<?php
/**
 * Template for displaying hotel rooms as row list
 */

$hotelPostID = $args['hotelPostID'];
$hotelRooms = get_field('hotel_rooms' , $hotelPostID);

if($hotelRooms):
?>
<section id="rooms" class="hotel-rooms-list js-rooms-search py-8 px-1 px-xl-5">
	<h2 class="h3 ml-1"><?php esc_html_e( 'Rooms', 'nirvana' ); ?></h2>

	

	<ul class="hotel-rooms-list__list">
        <?php foreach ( $hotelRooms as $room ) : ?>
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
</section>

<?php
endif;
