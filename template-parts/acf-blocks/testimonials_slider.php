<?php
$heading = get_sub_field('heading');
$items = get_sub_field('items');

$heading_as_first_item = get_sub_field('heading_as_first_item');

//Block options
$options = get_acf_block_options();

if($items):
    ?>
    <section 
        <?php if($options['id'] != ''): ?> id="<?php echo $id; ?>" <?php endif; ?>
        class="section testimonials-slider <?php echo $options['class']; ?>" 
        <?php if($options['style'] != ''): ?> style="<?php echo $options['style']; ?>" <?php endif; ?>
    >
        <div class="container-fluid px-0">

            <?php if($heading && !$heading_as_first_item ): ?>
                <div class="row testimonials-slider__heading">
                    <div class="col-md-6 offset-md-3">
                        <div class="content-block animate fade-up"><?php echo $heading; ?></div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="testimonials-slider__container">
                <div class="testimonials-slider__wrapper animate fade-up">
                    <ul class="testimonials-slider__list ">
                        <?php foreach($items as $testimonial): 
                            $text = get_field('text',$testimonial);
                            $image = get_field('image',$testimonial);
                            $name = get_field('name',$testimonial);
                            $position = get_field('position',$testimonial);
                            ?>
                            <li class="testimonials-slider__item">
                                <div class="testimonials-slider__item__inner">
                                    <div class="text--size--25 testimonials-slider__item__content">
                                        <?php echo $text; ?>
                                    </div>
                                    <div class="testimonials-slider__item__footer">
                                        <?php if($image): ?>
                                            <div class="testimonials-slider__author-image">
                                                <?php image_acf($image); ?>
                                            </div>
                                        <?php endif; ?>
    
                                        <div class="testimonials-slider__item__footer__content font--weight--400">
                                            <?php if($name): ?>
                                                <span class="text-transform-none font--weight--700"><?php echo $name; ?></span>
                                            <?php endif; ?>
                                            <?php if($position): ?>
                                                <span><?php echo $position; ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; wp_reset_postdata();
                        ?>
                    </ul>
                    <div class="testimonials-slider__nav">
                        <span id="prev" class="testimonials-slider__nav__btn testimonials-slider__nav__prev button button--arrow button--arrow-left"></span>
                        <span id="next" class="testimonials-slider__nav__btn testimonials-slider__nav__next button button--arrow"></span>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <?php
endif;