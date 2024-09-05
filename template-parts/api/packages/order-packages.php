<?php

/**
 * Template part for displaying packages list
 */
$debug = false;

$order = $args['order'];
$api = $args['api'];
$packages = $order->searchPackages();
?>
<?php if($debug): ?>
<pre style="color: #000; z-index: 2; position: relative; ">
	<?php print_r($packages); ?>
</pre>
<?php endif; ?>

<div class="accommodations js-packages-search" data-order="<?php echo $order->getOrderNumber(); ?>">
	
	<?php if(true): ?>
	<div class="pb-4">
		<?php
		get_template_part(
			'template-parts/api/order',
			'dates-select',
			array(
				'order' => $order,
				'api' => $api,
			)
		);
		?>
	</div>
	<?php endif; ?>

	<div class="book-accommodation__form-loading" style="display: none;">
		<img src="<?php echo get_template_directory_uri(); ?>/assets/images/loader.svg" alt="">
	</div>

	<div class="accommodations-filters pb-2 d-flex flex-wrap g-20 align-items-center justify-content-between">
		<div class="accommodations-sort sort-dropdown js-sort-dropdown">
			<div class="sort-dropdown__header">
				<span class="mr-1">Sort by:</span>
				<span class="sort-dropdown__button d-flex align-items-center">
					<span class="sort-dropdown__selected font--weight--600 mr-1">Cost – Low to high</span>
					<span class="sort-dropdown__arrow"><img src="<?php echo get_template_directory_uri();?>/assets/images/icon-arrow-orange.svg" alt=""></span>
				</span>
			</div>
			<ul class="sort-dropdown__content p-1" style="display:none;">
				<li data-sort="price" data-type="desc">Cost – High to low</li>
				<li data-sort="price" data-type="asc" class="active">Cost – Low to high</li>
			</ul>
		</div>
		<div class="accommodations-sort sort-dropdown js-nights-dropdown">
			<div class="sort-dropdown__header">
				<span class="mr-1">Nights:</span>
				<span class="sort-dropdown__button d-flex align-items-center">
					<span class="sort-dropdown__selected font--weight--600 mr-1">Filter by nights:</span>
					<span class="sort-dropdown__arrow"><img src="<?php echo get_template_directory_uri();?>/assets/images/icon-arrow-orange.svg" alt=""></span>
				</span>
			</div>
			<ul class="sort-dropdown__content p-1" style="display:none;">
				
			</ul>
		</div>
	</div>

	<div class="js-package-result">
		<?php 
		get_template_part(
			'template-parts/api/packages/order-packages',
			'data',
			array(
				'order' => $order,
				'api' => $api,
				'packages' => $packages,
			)
		);
		?>
	</div>

	
</div>
