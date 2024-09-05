<?php
/**
 * ACF Block Template: Slider (Heading by cols)
 */

// Content
$items = get_sub_field( 'items' );

// Block options
$options = get_acf_block_options();
?>

<section 
	<?php if ( $options['id'] ) : ?>
		id="<?php echo $options['id']; ?>"
	<?php endif; ?>
	class="slider--numeric <?php echo $options['class']; ?>" 
	<?php if ( $options['style'] ) : ?>
		style="<?php echo $options['style']; ?>"
	<?php endif; ?>
>
	<div class="container">
		<div>
			<ul class="slider--numeric__numbers">
				<?php foreach ( $items as $item ) : ?>
					<li>
						<h3><?php echo esc_html( $item['number'] ); ?></h3>
					</li>
				<?php endforeach; ?>
			</ul>

			<div class="slider--numberic__numbers-nav mt-5 d-flex justify-content-center g-10 position-relative">
				<button class="slick-arrow button button--orange button--arrow button--arrow-left position-static"></button>
				<button class="slick-arrow button button--orange button--arrow position-static"></button>
			</div>
		</div>

		<div class="row mt-5">
			<div class="col-10 col-sm-8 col-lg-6 offset-1 offset-sm-2 offset-lg-3">
				<ul class="slider--numeric__content">
					<?php foreach ( $items as $item ) : ?>
						<li>
							<?php echo wp_kses_post( $item['content'] ); ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>
</section>
