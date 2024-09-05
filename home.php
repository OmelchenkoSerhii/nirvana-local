

<?php get_header(); ?>

		  <?php if( have_posts() ): ?>
                <section class="articles-archive section--spacing--lg">
                    <div class="container">
                        <h2 class="pb-5"><?php echo wp_title();?></h2>
                        <ul class="row">
                            <li class="col-lg-3 col-sm-6 animate fade-up">
                                <?php get_template_part('template-parts/post/post','archive-item'); ?>
                            </li>
                        </ul>
                    </div>
                </section>

            <?php else: ?>
                <div id="post-404" class="noposts">
                    <p>
                        <?php _e('None found.','example'); ?>
                    </p>
                </div>
            <?php endif; wp_reset_query(); ?>
		

<?php get_footer(); ?>