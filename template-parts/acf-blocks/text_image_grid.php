<?php
$content = get_sub_field('content');
$images = get_sub_field('images');

$content_vertical_position = get_sub_field('content_vertical_position');
$columns = get_sub_field('columns');
//Block options
$options = get_acf_block_options();


if($content):
    ?>
    <section 
        <?php if($options['id'] != ''): ?> id="<?php echo $id; ?>" <?php endif; ?>
        class="section contentBlock <?php echo $options['class']; ?>" 
        <?php if($options['style'] != ''): ?> style="<?php echo $options['style']; ?>" <?php endif; ?>
    >
        <div class="container">
            <div class="row align-items-<?php echo $content_vertical_position; ?>">
                <div class="col-lg-3 col-md-5 offset-lg-1 contentImageGrid__content__wrapper">
                    <div class="content-block">
                        <?php if($content) : ?>
                            <?php echo $content; ?>
                        <?php endif;?>
                    </div>
                </div>
                <div class="col-md-6 offset-lg-2 offset-md-1 contentImageGrid__images__wrapper">
                    <ul class="row">
                        <?php foreach( $images as $image ): ?>
           
                            <li class="col-6  col-md-<?php echo 12/$columns; ?>">
                                <div class="contentImageGrid__image">
                                    <img src="<?php echo $image['url'] ;?>" alt="">
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?php
endif;