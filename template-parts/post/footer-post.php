
<div class="blog-post__footer">
    <div class="container">
        <div class="row">

            <?php 
            
            $args = array(
                'post_type' => 'post',
                'category__in' => wp_get_post_categories($post->ID),
                'posts_per_page' => 3,
                'orderby' => 'rand',
                'post__not_in'=>array($post->ID)
            );
            if(get_field('related_articles')):
                $relatedPosts = get_field('related_articles');
            else:
                $relatedPosts = get_posts( $args );
            endif;
            
            $relatedPostsTitle = get_field('related_articles_heading');
            if(!$relatedPostsTitle) $relatedPostsTitle =  __('Related articles','rocket-saas');
            if($relatedPosts):
            ?>
            <div class="section col-lg-12 blog-post__footer__related">
                <h2 class="spacing-pb-25 text--center blog-post__footer__related__title"><?php echo $relatedPostsTitle; ?></h2>
                <ul class="row archive__list">
                                    <?php 
                                    foreach($relatedPosts as $post): setup_postdata( $post ); ?>
                                        <?php get_template_part('template-parts/post/post','archive-item'); ?>
                                        <?php 
                                    endforeach; ?>
                                    <?php wp_reset_postdata(); 
                                    ?>
                </ul>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>
