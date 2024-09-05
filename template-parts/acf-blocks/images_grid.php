<?php 

$images = get_sub_field('images');
$columns = get_sub_field('columns');


//Block options
$options = get_acf_block_options();

if( $images ): ?>
<section 
    <?php if($options['id'] != ''): ?> id="<?php echo $id; ?>" <?php endif; ?>
    class="section imagesGrid imagesGrid--<?php echo $columns; ?> <?php echo $options['class']; ?>" 
    <?php if($options['style'] != ''): ?> style="<?php echo $options['style']; ?>" <?php endif; ?>
>
    <div <?php echo $id; ?> class="container">
        <ul class="row">
            <?php foreach( $images as $image ): ?>
                <?php 
                $imgWidth = $image['width'];
                $imgHeight = $image['height'];
                $imgRatio = 100*$imgHeight/$imgWidth;
                ?>
                <li class="col-12 col-md-<?php echo 12/$columns; ?>">
                    <?php image_acf($image); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
<?php endif; ?> 
