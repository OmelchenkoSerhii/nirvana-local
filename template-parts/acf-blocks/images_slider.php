<?php 

$images = get_sub_field('images');
$columns = get_sub_field('columns');


//Block options
$options = get_acf_block_options();

if( $images ): ?>
<section 
    <?php if($options['id'] != ''): ?> id="<?php echo $id; ?>" <?php endif; ?>
    class="section imagesSlider imagesSlider--<?php echo $columns; ?> <?php echo $options['class']; ?>" 
    <?php if($options['style'] != ''): ?> style="<?php echo $options['style']; ?>" <?php endif; ?>
>
    <div class="container">
        <ul class="images-slider" data-col="<?php echo $columns; ?>">
            <?php foreach( $images as $image ): ?>

                <li class="images-slider__item">
                    <?php image_acf($image , '' , true, '' , 60); ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="images-slider__nav">
            <span id="prev" class="images-slider__nav__btn images-slider__nav__prev button button--arrow button--arrow-left"></span>
            <span id="next" class="images-slider__nav__btn images-slider__nav__next button button--arrow"></span>
        </div>
    </div>
</section>
<?php endif; ?> 
