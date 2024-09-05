<?php 
$content = get_sub_field('text');
$image = get_sub_field('image');
$offset = get_sub_field('add_image_offset');

//Block options
$options = get_acf_block_options();
?>
<section 
    <?php if($options['id'] != ''): ?> id="<?php echo $id; ?>" <?php endif; ?>
    class="textImageBanner <?php if($offset) echo 'textImageBanner--offset'; ?> <?php echo $options['class']; ?>" 
    <?php if($options['style'] != ''): ?> style="<?php echo $options['style']; ?>" <?php endif; ?>
>
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-5">
                <?php if($content): ?>
                    <div class="content-block textImageBanner__content text-color-white"><?php echo $content; ?></div>
                <?php endif; ?>
            </div>
            <div class="col-md-6 offset-lg-2 offset-md-1">
                <?php if($image): ?>
                    <div class="textImageBanner__image">
                        <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>