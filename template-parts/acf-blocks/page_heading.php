<?php
$heading = get_sub_field('heading');
$text = get_sub_field('content');


//Block options
$options = get_acf_block_options();


if($heading || $text):
    ?>
    <section 
        <?php if($options['id'] != ''): ?> id="<?php echo $id; ?>" <?php endif; ?>
        class="page-heading bg--primary text-color-white <?php echo $options['class']; ?>" 
        <?php if($options['style'] != ''): ?> style="<?php echo $options['style']; ?>" <?php endif; ?>
    >
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2 text--center">
                    <div class="content-block animate fade-up">
                        <?php if($heading): ?>
                            <h1><?php echo $heading; ?></h1>
                        <?php endif; ?>
                        <?php echo $text; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
endif;