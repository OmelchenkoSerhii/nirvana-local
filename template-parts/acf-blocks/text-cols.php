<?php
$content = get_sub_field('content');
$content_2 = get_sub_field('content_2');
$heading = get_sub_field('heading');

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
            <div class="row">
                <?php if($heading): ?>
                    <div class="col-md-5 mb-3 offset-md-1">
                        <div class="content-block animate fade-up"><?php echo $heading; ?></div>
                    </div>
                    <div class="col-md-6"></div>
                <?php endif; ?>
                <div class="col-md-5 offset-md-1">
                    <div class="content-block animate fade-up"><?php echo $content; ?></div>
                </div>
                <div class="col-md-5 offset-md-1">
                    <div class="content-block animate fade-up"><?php echo $content_2; ?></div>
                </div>
            </div>
        </div>
    </section>
    <?php
endif;