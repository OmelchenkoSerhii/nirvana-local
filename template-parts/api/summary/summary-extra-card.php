<?php
$item  = $args['item'];
$order = $args['order'];

?>

<li class="mb-1">
	<div class="extras-card p-1">
		<div class="extras-card__header d-flex align-items-center py-3">
			<?php if ( false ) : ?>
				<div class="extras-card__image">
					<div class="image-ratio" style="padding-bottom: 100%;">
						<img src="<?php echo get_template_directory_uri() . '/assets/images/temp--kam-idris.jpg'; ?>" alt="">
					</div>
				</div>
			<?php endif; ?>
			<div class="extras-card__content">
				<div class="extras-card__content-inner d-flex justify-content-between align-items-center">
					<div class="extras-card__content-title">
						<h3><?php echo isset($item['Name'])?$item['Name']:$item['name']; ?></h3>
						<span class="extras-card__dropdown-btn d-none">
							<span class="show">Show more</span>
							<span class="hide">Show less</span>
							<span class="extras-card__dropdown-btn__icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="7" height="7" viewBox="0 0 7 7">
									<rect id="Rectangle" width="7" height="2.333" transform="translate(0 4.667)" fill="#f47920"/>
									<rect id="Rectangle_Copy_11" data-name="Rectangle Copy 11" width="7" height="2.333" transform="translate(7) rotate(90)" fill="#f47920"/>
								</svg>
							</span>
						</span>
					</div>
					<div class="extras-card__content-right d-flex justify-content-between justify-content-sm-end align-items-center">
						<?php
							$price = 0;
							if(isset($item['price'])):
								$price = $item['price'];
							else:
								foreach($item['travellers'] as $traveller):
									$price += isset($traveller['price'])?$traveller['price']:0;
								endforeach;
							endif;
							?>
						<?php if($price!=0): ?>
							<span class="h3 extras-card__price " style="min-width: 120px;text-align: end;">
								<?php echo $order->getCurrencySymbol(); ?><?php echo number_format( $price, 2, '.', ',' ); ?>
							</span>
						<?php endif; ?>
						<span class="extras-card__qtt text-align-right">Qty <?php echo sizeof( $item['travellers'] ); ?></span>
					</div>
				</div>
			</div>
		</div>
		<?php if ( false ) : ?>
			<div class="extras-card__dropdown">
				<?php if ( $args['extra']['description'] ) : ?>
					<div class="content-block">
						<h4>Description</h4>
						<p><?php echo $args['extra']['description']; ?></p>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</li>
