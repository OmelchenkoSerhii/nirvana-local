<?php
$image = get_sub_field('image');


$style = get_sub_field('layout');

//Block options
$options = get_acf_block_options();

if( !empty( $image ) ): ?>

<section 
    <?php if($options['id'] != ''): ?> id="<?php echo $id; ?>" <?php endif; ?>
    class="section imageBlock <?php echo $options['class']; ?>" 
    <?php if($options['style'] != ''): ?> style="<?php echo $options['style']; ?>" <?php endif; ?>
>
    <div <?php echo $id; ?> class="container">
        <?php if($style == 'full'): ?>
            <?php image_acf($image); ?>
        <?php else: ?>
            <div class="row">
                <div class="col-lg-8 offset-lg-2"><?php image_acf($image); ?></div>
            </div>
        <?php endif;  ?>
    </div>
</section>
<?php endif;