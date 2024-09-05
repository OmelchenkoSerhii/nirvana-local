<div class="order-booking-type">
    <h3 class="mb-3"><?php _e('Please choose from one of the following options:', 'nirvana'); ?></h3>

    <?php 
    $booking_types = get_field('booking_types' , 'option');
    if($booking_types):
    ?>
        <ul class="row order-booking-type__list">
            <?php foreach($booking_types as $type): ?>
                <?php 
                $image = $type['image'];
                $name = $type['name'];
                $description = $type['description'];
                $link = $type['link'];
                ?>
                <li class="col-lg-4 order-booking-type__item">
                    <div class="order-booking-type__item-inner text-center">
                        <?php if($image): ?>
                            <div class="order-booking-type__item-image-wrapper">
                                <div class="order-booking-type__item-image">
                                    <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
                                </div>
                            </div>
                        <?php endif; ?>
                        <h4 class="mt-3"><?php echo $name; ?></h4>
                        <p class="pb-3"><?php echo $description; ?></p>
                        <?php if( $link ): 
                            $link_title = __('View','nirvana');
                            ?>
                            <a class="button button--orange mt-auto d-block" href="<?php echo esc_url( $link ); ?>" ><?php echo esc_html( $link_title ); ?></a>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>