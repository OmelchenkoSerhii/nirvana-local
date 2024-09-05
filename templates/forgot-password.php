<?php
/*
Template Name: Forgot Password Page
*/
?>
<?php 
    global $current_user; wp_get_current_user();

    $login_background = get_field('login_background','option');
?>


<?php get_header(); ?>
        <div class="page-blocks">
            <section class="login-block pt-10 pt-lg-18 pb-5" style="background-image: url(<?php echo $login_background; ?>);min-height: 85vh; background-repeat: no-repeat;background-size: 100%;backround-color: #F47920;">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-5 login-form">
                            <h1 class="text--center mb-5"><?php the_title(); ?></h1>
                            <?php if(get_the_content()): ?>
                                <div class="content-block mb-5"><?php the_content(); ?></div>
                            <?php endif; ?>
                            <?php echo do_shortcode('[theme-my-login action="lostpassword"]'); ?>
                        </div>
                    </div>
                </div>
            </section>  
        </div>
<?php get_footer(); ?>