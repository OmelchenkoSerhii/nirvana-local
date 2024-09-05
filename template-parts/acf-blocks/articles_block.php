<?php //ARTICLES BLOCK ?>

<?php 
$heading = get_sub_field('heading');

$selectArticles = get_sub_field('select_articles');


if($selectArticles ){
    $posts = get_sub_field('articles');
} else{
    $posts = get_posts( array(
        'numberposts' => 4,
        'orderby'     => 'date',
        'order'       => 'DESC',
        'post_type'   => 'post',
        'suppress_filters' => true, 
    ) );
}

//Block options
$options = get_acf_block_options();

if($posts): ?>
    <section 
        <?php if($options['id'] != ''): ?> id="<?php echo $id; ?>" <?php endif; ?>
        class="section articles-block bg--light-blue <?php echo $options['class']; ?>" 
        <?php if($options['style'] != ''): ?> style="<?php echo $options['style']; ?>" <?php endif; ?>
    >
        <div class="container articles-block__container">
            <div class="row articles-block__row">
                <?php if($heading): ?>
                    <div class="col-lg-4 offset-lg-4 spacing-pb-60">
                        <div class="content-block"><?php echo $heading; ?></div>
                        <?php if($button): ?>
                            <div class="logos-carousel__button text--center spacing-pt-30"><?php echo button_acf($button); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            <ul class="row articles-block__list">
                <?php 
                $loopCounter = 0; 
                foreach($posts as $post): setup_postdata($post);
                    if($loopCounter == 0):
                        get_template_part('template-parts/post/post','archive-item-featured');
                    else:
                        get_template_part('template-parts/post/post','archive-item');
                    endif;
                    $loopCounter++;
                endforeach; 
                wp_reset_postdata();
                ?>
            </ul>
        </div>
    </section>
<?php endif; ?>