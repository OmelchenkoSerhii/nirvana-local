<?php
$content = get_sub_field('content');

$width = get_sub_field('width')?get_sub_field('width'):6;

//Block options
$options = get_acf_block_options();


if($content):
    ?>
    <div 
        <?php if($options['id'] != ''): ?> id="<?php echo $id; ?>" <?php endif; ?>
        class="section contentBlock <?php echo $options['class']; ?>" 
        <?php if($options['style'] != ''): ?> style="<?php echo $options['style']; ?>" <?php endif; ?>
    >
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-<?php echo $width; ?>">
                    <div class="content-block animate fade-up"><?php echo $content; ?></div>
                </div>
            </div>
        </div>
    </div>
    <?php
endif;
