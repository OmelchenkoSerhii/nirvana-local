<?php
$content = get_sub_field( 'content' );

$imageLayout   = get_sub_field( 'media_layout' );
$imagePosition = get_sub_field( 'image_position' );
$imageType     = get_sub_field( 'image_type' );
$image         = get_sub_field( 'image' );
$video_type    = get_sub_field( 'video_type' );
$video_file    = get_sub_field( 'video_file' );
$video_url     = get_sub_field( 'video_url' );
$lottie        = get_sub_field( 'lottie_svg' );

$imageClasses   = '';
$contentClasses = '';
if ( $imagePosition == 'left' ) :
	$contentClasses .= 'offset-lg-1';
	$imageClasses   .= '';
elseif ( $imagePosition == 'right' ) :
	$contentClasses .= '';
	$imageClasses   .= 'offset-lg-1';
endif;

//Block options
$options = get_acf_block_options();
?>

<section 
	<?php if ( $options['id'] ) : ?>
		id="<?php echo $options['id']; ?>"
	<?php endif; ?>
	class="section  heroBanner heroBanner--<?php echo $imagePosition; ?> heroBanner--<?php echo $imageLayout; ?> bg--primary text-color-white <?php echo $options['class']; ?>" style="background-image: url(<?php echo esc_url($image['url']); ?>); background-repeat:no-repeat; background-position-x: 80%;background-size: cover;" 
	<?php if ( $options['style'] ) : ?>
		style="<?php echo $options['style']; ?>"
	<?php endif; ?>
>
	<div class="container">
		<div class="row">
			<?php if ( 'overlap' === $imagePosition && 'image' === $imageType ) : ?>
				<div class="heroBanner__image__inner w-100">
					<?php image_acf( $image, 'animate zoom-in' ); ?>
				</div>
				<div class="col-12">
					<div class="content-block animate fade-up"><?php echo $content; ?></div>
				</div>
			<?php elseif ( 'overlap' === $imagePosition && 'video' === $imageType ) : ?>
				<div class="heroBanner__image__inner w-100">
					<div class="videoBlock h-100">
						<video loop muted autoplay controls playsinline width="100%" height="auto" class="video">
							<source src="<?php echo esc_url( $video_url ); ?>" type="video/mp4">
							<?php esc_html_e( 'Your browser does not support the video tag.', 'nirvana' ); ?>
						</video>
					</div>
				</div>
				<div class="col-12">
					<div class="content-block animate fade-up"><?php echo $content; ?></div>
				</div>
			<?php else : ?>
				<div class="col-12 col-lg-4 col-md-5 <?php echo $contentClasses; ?> heroBanner__content">
					<div class="heroBanner__content__inner pt-2 pt-md-5">
						<?php if ( $content ) : ?>
							<div class="content-block animate fade-up"><?php echo $content; ?></div>
						<?php endif; ?>
					</div>
				</div>
				<div class="col-12 col-lg-7 col-md-6 <?php echo $imageClasses; ?> heroBanner__image heroBanner__image--<?php echo $imageLayout; ?>">
					<div class="heroBanner__image__inner">
						<?php if ( 'image' === $imageType ) : ?>
							<?php if ( $image ) : ?>
								<?php image_acf( $image, 'animate zoom-in' ); ?>
							<?php endif; ?>
						<?php elseif ( 'video' === $imageType ) : ?>
							<?php if ( 'file' === $video_type && $video_file ) : ?>
								<div class="videoBlock">
									<video loop width="100%" height="auto" class="video">
										<source src="<?php echo esc_url( $video_file['url'] ); ?>" type="video/mp4">
										<?php esc_html_e( 'Your browser does not support the video tag.', 'nirvana' ); ?>
									</video>
								</div>
							<?php elseif ( 'url' === $video_type && $video_url ) : ?>
								<div class="videoBlock">
									<video loop width="100%" height="auto" class="video">
										<source src="<?php echo esc_url( $video_url ); ?>" type="video/mp4">
										<?php esc_html_e( 'Your browser does not support the video tag.', 'nirvana' ); ?>
									</video>
								</div>
							<?php endif; ?>
						<?php else : ?>
							<?php if ( $lottie ) : ?>
								<div class="lottie" data-path="<?php echo esc_url( $lottie['url'] ); ?>"></div>
							<?php endif; ?>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
