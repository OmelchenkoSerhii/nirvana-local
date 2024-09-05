<?php
$content = get_sub_field( 'content' );

$enable_buttons = get_sub_field( 'enable_buttons' );
$button1        = get_sub_field( 'button_1' );
$button2        = get_sub_field( 'button_2' );

$content_vertical_position = get_sub_field( 'content_vertical_position' );
$imageLayout               = get_sub_field( 'media_layout' );
$imagePosition             = get_sub_field( 'image_position' );
$imageType                 = get_sub_field( 'image_type' );
$image                     = get_sub_field( 'image' );
$set_original_size_image   = get_sub_field( 'set_original_size_image' );
$video                     = get_sub_field( 'video_file' );
$lottie                    = get_sub_field( 'lottie_svg' );

$icon_type            = get_sub_field( 'icon_type' );
$icon                 = get_sub_field( 'icon' );
$icon_background      = get_sub_field( 'icon_background' );
$number               = get_sub_field( 'number' );
$after_number         = get_sub_field( 'after_number' );
$after_number_content = get_sub_field( 'after_number_content' );

$applySteps = get_sub_field( 'apply_steps' );
$step       = get_sub_field( 'step' );


$imageClasses   = '';
$contentClasses = '';
if ( $imagePosition == 'left' ) :
	$contentClasses .= ' col-12 col-md-6';
	$imageClasses   .= ' col-12 col-md-6';
else :
	$contentClasses .= ' col-12 col-md-6';
	$imageClasses   .= ' col-12 col-md-6';
endif;

//Block options
$options = get_acf_block_options();

if ( $options['background_image'] ) {
	if ( 'left' === $imagePosition ) {
		$contentClasses = ' col-10 col-md-4 offset-1';
		$imageClasses   = ' col-10 col-md-5 offset-1';
	} else {
		$contentClasses = ' col-10 col-md-4 offset-1';
		$imageClasses   = ' col-10 col-md-5 offset-1';
	}
}
?>
<section
	<?php if ( $options['id'] ) : ?>
		id="<?php echo $options['id']; ?>"
	<?php endif; ?>
	class="section contentImageBlock contentImageBlock--<?php echo $imagePosition; ?> <?php echo ( $applySteps ) ? 'step--section' : ''; ?> contentImageBlock--<?php echo $imageLayout; ?> <?php echo $options['class']; ?>" 
	<?php if ( $options['style'] ) : ?>
		style="<?php echo $options['style']; ?>"
	<?php endif; ?>
>
	<div class="container position-relative">
		<?php if ( $options['background_image'] ) : ?>
			<div class="contentImageBlock__container-image position-absolute start-0 top-0 w-100 h-100">
				<?php the_image( $options['background_image'], false, 'w-100 h-100 object-cover', 'h-100 w-100' ); ?>
			</div>
		<?php endif; ?>

		<div class="row align-items-<?php echo $content_vertical_position; ?> <?php echo ( $options['background_image'] ) ? 'py-14' : ''; ?>">
			<div class="<?php echo $contentClasses; ?> contentImageBlock__content">
				<div class="contentImageBlock__content__inner">
					<?php if ( $applySteps ) { ?>
						<?php if ( $step ) : ?>
							<div class="step animate fade-up"><?php echo $step; ?></div>
						<?php endif; ?>
					<?php } ?>
					<?php if ( $content ) : ?>
						<div class="content-block animate fade-up"><?php echo $content; ?></div>
					<?php endif; ?>

					<?php if ( $enable_buttons && ( $button1 || $button2 ) ) : ?>
						<div class="buttonsBlock mt-3 animate fade-up">
							<div class="buttonsBlock__inner">
								<?php the_acf_link( $button1 ); ?>
								<?php the_acf_link( $button2 ); ?>
							</div>
						</div>
					<?php endif; ?>

				</div>
			</div>
			<div class="<?php echo $imageClasses; ?> contentImageBlock__image contentImageBlock__image--<?php echo $imageLayout; ?>">
				<div class="contentImageBlock__image__inner animate fade-up">
					<?php if ( $imageType == 'image' ) : ?>

						<?php if ( $image ) : ?>
							<?php if ( $set_original_size_image ) : ?>
								<?php the_image( $image, true, '', 'animate', '', true ); ?>
							<?php else : ?>
								<?php the_image( $image, true, '', 'animate' ); ?>
							<?php endif; ?>
						<?php endif; ?>

					<?php elseif ( $imageType == 'video' ) : ?>

						<?php if ( $video ) : ?>
							<div class="videoBlock">
								<video loop width="100%" height="auto" class="video">
									<source src="<?php echo $video['url']; ?>" type="video/mp4">
									<?php _e( 'Your browser does not support the video tag.', 'rocket-saas' ); ?>
								</video>
							</div>
						<?php endif; ?>

					<?php elseif ( $imageType == 'image-icon' ) : ?>

						<div class="contentImageBlock__image-icon">
							<div class="contentImageBlock__image-icon__img">
								<?php if ( $image ) : ?>
									<?php image_acf( $image, 'animate' ); ?>
								<?php endif; ?>
							</div>

							<?php if ( $icon_type == 'icon' ) : ?>
								<?php if ( $icon ) : ?>
									<div class="contentImageBlock__image-icon__icon-block"  style="background-color: <?php echo $icon_background; ?>">
										<img src="<?php echo $icon['url']; ?>" alt="<?php echo $icon['alt']; ?>">
									</div>
								<?php endif; ?>
							<?php else : ?>
								<div class="contentImageBlock__image-icon__text-block"  style="background-color: <?php echo $icon_background; ?>">                                    
										<div class="contentImageBlock__image-icon__text-block__top font--secondary">
											<?php
											if ( $number ) :
												echo $number;
											endif;
											if ( $after_number ) :

												?>
											<span class="text-color-primary"><?php echo $after_number; ?></span><?php endif; ?>
										</div>

										<?php if ( $after_number_content ) : ?>
											<h4 class="contentImageBlock__countUp__info text--uppercase"><?php echo $after_number_content; ?></div>
										<?php endif; ?>
								</div>
							<?php endif; ?>
						</div>

					<?php else : ?>

						<?php if ( $lottie ) : ?>
							<div class="lottie" data-path="<?php echo $lottie['url']; ?>"></div>
						<?php endif; ?>

					<?php endif; ?>

				</div>
			</div>
		</div>
	</div>
</section>
