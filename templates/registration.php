<?php
/*
Template Name: Registration Page
*/
?>
<?php 
$login_background = get_field('login_background','option');
?>

<?php get_header(); ?>
    <div class="page-blocks">
        <section class="login-block pt-10 pt-lg-18 pb-5" style="background-image: url(<?php echo $login_background; ?>);min-height: 85vh; background-repeat: no-repeat;background-size: 100%;backround-color: #F47920;">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 login-form">
                        <h1 class="text--center mb-5"><?php the_title(); ?></h1>
                        <?php if(get_the_content()): ?>
                            <div class="content-block mb-5"><?php the_content(); ?></div>
                        <?php endif; ?>

                        <?php if(false): ?>
                            <div style="padding: 15px; background-color: #fff; border: 1px solid red; color: #000; display: none;">
                            <?php
                            $api = new NiravanaAPI();
                            $response = $api->SearchClientByEmail('lorna6smith@gmail.com');
                            print_r($response);
                            ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php echo do_shortcode(get_field('account_registration_form','option')); ?>
                    </div>
                </div>
            </div>
        </section>  
    </div>
<?php get_footer(); ?>