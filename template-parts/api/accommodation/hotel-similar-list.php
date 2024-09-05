<?php
/**
 * Template for displaying hotel similar list
 */


$order  = $args['order'];
$api    = $args['api'];
$hotels = $args['hotels'];


if ( $hotels ) :
	?>

<section class="hotel-similar-list py-8">
	<h2 class="h3 ml-1"><?php esc_html_e( 'Similar hotels', 'nirvana' ); ?></h2>
	<div class="hotel-similar-list__content mt-2 position-relative">
		<ul class="hotel-similar-list__list">
			<?php if ( is_array( $hotels ) ) : ?>
				<?php foreach ( $hotels as $hotel ) : ?>
					<?php
					get_template_part(
						'template-parts/api/accommodation/hotel',
						'card-slider',
						array(
							'hotel' => $hotel,
							'order' => $order,
							'api'   => $api,
						)
					);
					?>
				<?php endforeach; ?>
			<?php else : ?>
				<?php
				get_template_part(
					'template-parts/api/accommodation/hotel',
					'card-slider',
					array(
						'hotel' => $hotels,
						'order' => $order,
						'api'   => $api,
					)
				);
				?>
			<?php endif; ?>
	
		</ul>
		<div class="slider-arrows">
			<button type="button" class="button--arrow button--arrow-left button--orange"></button>
			<button type="button" class="button--arrow button--orange"></button>
		</div>
	</div>
</section>

	<?php
endif;
