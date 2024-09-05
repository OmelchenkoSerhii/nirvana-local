<?php
$heading = get_sub_field( 'heading' );
$items   = get_sub_field( 'items' );

$heading_as_first_item = get_sub_field( 'heading_as_first_item' );

// Layout
$count_items_in_row = get_sub_field( 'count_items_in_row' );
$justify_items      = get_sub_field( 'justify_items' );
$text_align         = get_sub_field( 'text_align' );
$image_align        = get_sub_field( 'image_align' ) ?? 'text-center';

// Block options
$options = get_acf_block_options();

if ( $items ) :
	?>
	<section 
		<?php if ( $options['id'] ) : ?>
			id="<?php echo esc_attr( $options['id'] ); ?>"
		<?php endif; ?>
		class="section icons-section <?php echo esc_attr( $options['class'] ); ?>" 
		<?php if ( $options['style'] ) : ?>
			style="<?php echo esc_attr( $options['style'] ); ?>"
		<?php endif; ?>
	>
		<div class="container">

			<?php if ( $heading && ! $heading_as_first_item ) : ?>
				<div class="row icons-section__heading mb-4 mb-sm-7">
					<div class="col-12">
						<div class="content-block animate fade-up"><?php echo $heading; ?></div>
					</div>
				</div>
			<?php endif; ?>

			<ul class="row gy-30 icons-section__list justify-content-<?php echo $justify_items; ?> text-<?php echo $text_align; ?>">
				<?php if ( $heading && $heading_as_first_item ) : ?>
					<li class="col-md-6 col-lg-3">
						<div class="content-block animate fade-up"><?php echo $heading; ?></div>
					</li>
				<?php endif; ?>

				<?php
				foreach ( $items as $item ) :

					$icon            = $item['icon'];
					$icon_background = $item['icon_background'];
					$heading         = $item['heading'];
					$text            = $item['text'];
					$after_heading   = $item['after_heading'] ?? '';
					?>
					<li class="col-6 col-sm-4 col-lg-<?php echo intval( 12 / $count_items_in_row ); ?>">
						<div class="icon-block animate fade-up">
							<?php if ( $icon ) : ?>
								<div class="icon-block__icon mb-2 mb-sm-3 <?php echo esc_attr( $image_align ); ?>" style="background-color: <?php echo $icon_background; ?>">
									<img src="<?php echo $icon['url']; ?>" alt="<?php echo $icon['alt']; ?>">
								</div>
							<?php endif; ?>
							<div class="content-block icon-block__content">
								<?php if ( $heading ) : ?>
									<div class="d-flex justify-content-center">
										<h3 class="pb-0 text--size--18 text--transform--none font--weight--700 ticker" data-ticker-after="<?php echo esc_attr( $after_heading ); ?>"><?php echo $heading; ?></h3>
									</div>
								<?php endif; ?>

								<?php if ( $text ) : ?>
									<div class="mt-2 mt-sm-3">
										<?php echo $text; ?>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>

		</div>
	</section>
	<?php
endif;
