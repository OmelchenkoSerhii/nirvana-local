<?php
/**
 * Template Part Name: Event Category Hero Banner
 *
 * @package nirvana
 */


$term = $args['term'];
if($term):
    $banner_title = get_field('banner_title',$term);
    $banner_subtitle = get_field('banner_subtitle',$term);
    $banner_logo = get_field('logo',$term);
    $banner_image = get_field('banner_image',$term);
    if(!$banner_title) $banner_title = $term->name;
    if(!$banner_image) $banner_image = array('url' => get_template_directory_uri() . '/assets/images/temp--bg-group-event.jpg');
endif; 
?>

<div class="hero-banner">
	<div class="hero-banner__background">
		<div class="image-ratio pb-0 w-100 h-100">
			<img src="<?php echo $banner_image['url']; ?>" alt="Hero Image">
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-12 col-md-12 col-lg-12 d-flex flex-column align-items-center text-center">
				<?php if($banner_logo): ?>
                    <img src="<?php echo $banner_logo['url']; ?>" class="hero-banner__logo mb-2" alt="Events Logo">
                <?php endif; ?>
                <?php if($banner_title): ?>
                    <h1 class="hero-banner__title  h2 mb-2"><?php echo $banner_title; ?></h1>
                <?php endif; ?>
                <?php if($banner_subtitle): ?>
                    <h3 class="hero-banner__subtitle"><?php echo $banner_subtitle; ?></h3>
                <?php endif; ?>
			</div>
		</div>
	</div>
</div>