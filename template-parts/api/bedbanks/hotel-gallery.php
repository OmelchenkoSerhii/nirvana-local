<?php
/**
 * Template for displaying hotel gallery
 */


$images      = $args['images'];

if ( empty( $images ) ) {
	return;
}
?>

<div class="hotel-gallery position-relative mx-n1">
	<div class="hotel-gallery__list">
		<?php foreach ( $images as $image ) : ?>
			<div class="hotel-gallery__item px-1">
				<div class="image-ratio" style="padding-bottom: 70%;">
					<img src="<?php echo $image->ImageFormat->URL; ?>" alt="">
				</div>
			</div>
		<?php endforeach; ?>
	</div>
	<div class="slider-arrows">
		<button class="button--arrow button--arrow-left button--orange"></button>
		<button class="button--arrow button--orange"></button>
	</div>
</div>

<?php
