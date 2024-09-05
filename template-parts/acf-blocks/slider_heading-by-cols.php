<?php
/**
 * ACF Block Template: Slider (Heading by cols)
 */

// Content
$left_text  = get_sub_field( 'left_text' );
$right_text = get_sub_field( 'right_text' );
$button_1   = get_sub_field( 'button_1' );
$gallery    = get_sub_field( 'gallery' );

// Block options
$options = get_acf_block_options();
?>

<section 
	<?php if ( $options['id'] ) : ?>
		id="<?php echo $options['id']; ?>"
	<?php endif; ?>
	class="slider--heading-by-cols <?php echo $options['class']; ?>" 
	<?php if ( $options['style'] ) : ?>
		style="<?php echo $options['style']; ?>"
	<?php endif; ?>
>
	<div class="container">
		<div class="row gy-30">
			<div class="col-lg-4">
				<?php echo wp_kses_post( $left_text ); ?>
				<?php the_acf_link( $button_1 ); ?>
			</div>
			<div class="col-lg-8">
				<div class="content-block text--columns--2">
					<?php echo wp_kses_post( $right_text ); ?>
				</div>
			</div>
			<div class="col-12 slider">
				<div class="slider--single">
					<?php foreach ( $gallery as $image ) : ?>
						<div>
							<?php the_image( $image, false, 'w-100', 'w-100 h-100' ); ?>
						</div>
					<?php endforeach; ?>
				</div>
				<div class="slider__nav">
					<span id="prev" class="slider__arrow-rs slider__arrow-rs--next button button--arrow"></span>
					<span id="next" class="slider__arrow-rs slider__arrow-rs--prev button button--arrow button--arrow-left"></span>
				</div>
			</div>
		</div>
	</div>
</section>
