<?php
/**
 * Template for displaying hotel room as card
 */

$room = $args['room'];
?>

<li class="summary-rooms-list__item pb-1 pt-1 mt-1">
	<div class="summary-rooms-list__item-inner  d-flex p-1">
		<div class="summary-rooms-list__item__img mr-2">
			<?php the_image( $room['image'] ); ?>
		</div>
		<div class="summary-rooms-list__item__content d-flex flex-column">
			<div class="d-flex">
				<h4><?php echo $room['hotel']['title']; ?></h4>
				<?php the_rating( $room['hotel']['rating'], 'ml-1' ); ?>
			</div>
			<h4 class="text--size--16 mt-1"><?php echo esc_html( $room['title'] ); ?>, <span class="text--opacity font--weight--400"><?php echo esc_html( $room['type'] ); ?></span></h4>

			<ul class="d-flex flex-wrap g-7 text--size--16 pt-1">
				<?php foreach ( $room['features'] as $feature ) : ?>
					<li class="d-flex align-items-center g-10 text-capitalize mr-3">
						<?php the_svg_by_sprite( 'list-icon-grey-plus', 20, 20, 'flex-shrink-0' ); ?>
						<span class="text--opacity"><?php echo esc_html( $feature ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>

			<div class="d-flex justify-content-between align-items-center mt-auto">
				<div class="summary-rooms-list__item__supplements d-flex align-items-center">
					<h5 class="text--size--12 text-uppercase mr-9">Room supplements</h5>
					<?php foreach ( $room['supplements'] as $supplement ) : ?>
						<div class="d-flex g-10 text--size--16">
							<div class="d-flex w-100 align-items-end justify-content-between">
								<span class="text--opacity"><?php echo esc_html( $supplement['name'] ); ?> &nbsp;</span>
								<span> <?php echo $order->getCurrencySymbol(); ?><?php echo esc_html( $supplement['price'] ); ?></span>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<span class="h3"><?php echo $order->getCurrencySymbol(); ?><?php echo $room['price']; ?></span>
			</div>


		</div>
	</div>
</li>

<?php
