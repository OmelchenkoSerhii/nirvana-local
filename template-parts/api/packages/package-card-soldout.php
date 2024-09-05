<?php

/**
 * Template part for displaying package as card in list
 */

$tourPostID = $args['tourPostID'];
$tourID = $args['tourID'];
$order   = $args['order'];


$image = get_the_post_thumbnail_url($tourPostID, 'full');
$tourDescritpion = get_field('description', $tourPostID);
$inclusions = get_field('inclusions', $tourPostID);
$showCardLabel    = get_field('add_card_label', $tourPostID);
$cardLabels       = get_field('card_label_tags', $tourPostID);
$title = get_the_title($tourPostID);

$imageGallery = false;
$isActive = false;

?>
    <li class="accommodations__item bg--light mb-2 disabled" style="order: 999999;" >
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
                <h2 class="h3"><?php echo esc_html($title); ?></h2>

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
                    <span class="button button--sm button--disabled mt-2 mb-1" style="z-index:11;"><?php _e('Please adjust your dates to view', 'nirvana'); ?></span>
                <?php endif; ?>
            </div>

            <div class="accommodations__item-additional-content d-flex flex-column pb-2 pr-2 ml-auto">

                <?php if ($showCardLabel && $cardLabels) : ?>
                    <?php foreach ($cardLabels as $item) : ?>
                        <?php $label = $item['label']; ?>
                        <?php if ($label) : ?>
                            <?php the_icon_bookmark_primary($label); ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </div>
        
    </li>
