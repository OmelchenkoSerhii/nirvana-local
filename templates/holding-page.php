<?php
/*
Template Name: Template Holding Page
*/
$content = get_field('content');
$email = get_field('email');
$phone = get_field('phone');
$image = get_field( 'image' );
?>

<?php get_header(); ?>
<section class="section vh-100  d-flex justify-content-center align-items-center ">
    <div class="container">
        <div class="row">
            <div class="col-12">

                <div class="holding-page__logo  d-flex justify-content-center">
                    <a href="<?php echo get_home_url(); ?>/" title="<?php echo get_bloginfo( 'name' ); ?>">
                        <?php if ( ! empty( $image ) ) : ?>
                            <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
                        <?php endif; ?>
                    </a>
                </div>

                <div class="content-block holding-page__content d-flex flex-column align-items-center justify-content-center">
                    <?php if($content) : ?>
                        <?php echo $content;?>
                    <?php endif; ?>
                    <?php if($email) : ?>
                        <a href="mailto:<?php echo $email;?>"><?php echo $email;?></a>
                    <?php endif; ?>
                    <?php if($phone) : ?>
                        <a href="tel:<?php echo $phone;?>"><?php echo $phone;?></a>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>
