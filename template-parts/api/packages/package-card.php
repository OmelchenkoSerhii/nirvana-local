<?php

/**
 * Template part for displaying package as card in list
 */

$package = $args['package'];
$order = $args['order'];
$api   = $args['api'];

$TMcode = $package->TMcode;
$TourName = $package->TourName;
$title = $package->TourName;
$duration = $package->Duration;
$tourDescritpion = false;

$TourPriceDetailId = array();
$price = false;
$travellersRooms = $order->getRooms();
foreach($travellersRooms as $room):
    $travellersQtt = $room['adults_qtt'] + $room['childs_qtt'];
    if (is_array($package->PricingTypes->PricingType)) :
        foreach ($package->PricingTypes->PricingType as $PricingType) :
            if ($PricingType->Occupancy == $travellersQtt) :
                $TourPriceDetailId[] = $PricingType->TourPriceDetailID;
                if($price):
                    $price = ($price<$PricingType->AdultPrice)?$price:$PricingType->AdultPrice;
                else:
                    $price = $PricingType->AdultPrice;
                endif;
            endif;
        endforeach;
    else :
        $TourPriceDetailId[] = $package->PricingTypes->PricingType->TourPriceDetailID;
        $price = $package->PricingTypes->PricingType->AdultPrice;
    endif;
endforeach;

$link = get_field('api_package_single_info_page', 'option');
$link  = $link . '?tourcode=' . $package->Tcode;

$image = false;
$imageGallery = false;
$inclusions = false;
$showCardLabel    = false;
$cardLabels       = false;

$args = array(
    'meta_key' => 'tmcode',
    'meta_value' => $TMcode,
    'post_type' => 'tour',
    'posts_per_page' => -1,

);
$tourPosts = get_posts($args);


$tourSoldOut = false;
if ($tourPosts) :
    $tourPostID = $tourPosts[0]->ID;
    $image = get_the_post_thumbnail_url($tourPostID, 'full');
    $tourDescritpion = get_field('description', $tourPostID);
    $inclusions = get_field('inclusions', $tourPostID);
    $showCardLabel    = get_field('add_card_label', $tourPostID);
    $cardLabels       = get_field('card_label_tags', $tourPostID);
    $title = get_the_title($tourPostID);
    $tourSoldOut = get_field('sold_out', $tourPostID);
endif;

$isActive = ($order->getNightsQtt() == $duration || ( $order->getNightsQtt()==0 && $duration == 1 ));
$isPriority = $package->PriorityHotel;
$isActive = $tourSoldOut?false:$isActive;
$itemOrder = 1;
if(!$isActive):
    if($isPriority):
        $itemOrder = 300 + $duration;
    else:
        $itemOrder = 400 + $duration;
    endif; 
else:
    if($isPriority):
        $itemOrder = 100 + $duration;
    else:
        $itemOrder = 200 + $duration;
    endif; 
endif;
if($tourSoldOut) $itemOrder = 220;


if ( $TourPriceDetailId ):
?>
    <li class="accommodations__item bg--light mb-2 <?php if (!$isActive) echo 'disabled' ?>" data-price="<?php echo intval($price); ?>" data-night="<?php echo intval($duration); ?>" style="order:<?php echo $itemOrder; ?>">
        <div class="d-flex flex-wrap flex-md-nowrap g-20">
            <?php
            if ($image || $imageGallery) :
            ?>
                <div class="accommodations__item-image-wrapper" href="">
                    <?php if ($imageGallery) : ?>
                        <ul class="accommodations__item-image-gallery">
                            <?php foreach ($imageGallery as $image) : ?>
                                <li class="accommodations__item-image-gallery__item">
                                    <div class="image-ratio" style="padding-bottom: 100%;">
                                        <img src="<?php echo $image['url']; ?>" alt="">
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <div class="image-ratio" style="padding-bottom: 100%;">
                            <img src="<?php echo $image; ?>" alt="">
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="accommodations__item-content p-2 d-flex flex-column align-items-start">
                <?php if($isActive): ?>
                    <h2 class="h3"><a href="<?php echo $link; ?>"><?php echo esc_html($title); ?></a></h2>
                <?php else: ?>
                    <h2 class="h3"><?php echo esc_html($title); ?></h2>
                <?php endif; ?>
                <?php if (false) : ?>
                    <span class="link--style--2 mt-1"><?php echo esc_html($duration) . ' ' . __('nights', 'nirvana'); ?></span>
                <?php endif; ?>

                <?php if ($tourDescritpion) : ?>
                    <div class="text--size--16 pt-2 accommodations__item-content-description">
                        <?php echo $tourDescritpion; ?>
                    </div>
                <?php endif; ?>

                <?php if ($inclusions) : ?>
                    <ul class="accommodations__item__inclusions pt-2">
                        <?php foreach ($inclusions as $item) : ?>
                            <li>
                                <span class="accommodations__item__inclusions__inner">

                                    <?php echo $item['name']; ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <?php if ($isActive) : ?>
                    <a class="button button--sm button--orange mt-2 mb-1" href="<?php echo $link; ?>"><?php _e('View', 'nirvana'); ?></a>
                <?php else : ?>
                    <?php if(!$tourSoldOut): ?>
                        <span class="button button--sm button--disabled mt-2 mb-1" style="z-index:11;"><?php _e('Please adjust your dates to view', 'nirvana'); ?></span>
                    <?php else: ?>
                        <span class="button button--sm button--disabled mt-2 mb-1" style="z-index:11;"><?php _e('SOLD OUT', 'nirvana'); ?></span>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <div class="accommodations__item-additional-content d-flex flex-column pb-2 pr-2 ml-auto">

                <div class="icon-bookmark-list d-flex g-15">
                    <?php if ($showCardLabel && $cardLabels) : ?>
                        <?php foreach ($cardLabels as $item) : ?>
                            <?php 
                                $label = $item['label']; 
                                $color = $item['color']; 
                            ?>
                            <?php if ($label) : ?>
                                <?php the_icon_bookmark($label , 'icon-bookmark--'.$color); ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>


                <span class="h3 mt-auto text-nowrap text-right">
                    <?php
                    if(!$tourSoldOut):
                        printf(
                            /* translators: 1: Currency 2: Room price */
                            esc_html__('From %1$s%2$s/pp', 'nirvana'),
                            $order->getCurrencySymbol(),
                            esc_html(number_format($price, 2))
                        );
                    else:
                        _e('SOLD OUT');
                    endif;
                    ?>
                </span>

                <span class="text--size--16 text--opacity mt-05 text-nowrap text-right">
                    <?php
                    printf(
                        /* translators: 1: Nights 2: Adults */
                        esc_html__('%1$s nights, %2$s', 'nirvana'),
                        $order->getNightsQtt(),
                        $order->getPeopleQtt(),
                    );
                    ?>
                </span>

            </div>
        </div>
        
    </li>
<?php endif; ?>