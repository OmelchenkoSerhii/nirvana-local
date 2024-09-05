<?php
/**
 * Template Name: API Accommodation
 */
?>

<?php get_header( 'booking' ); ?>

<?php 
$api = new NiravanaAPI();
?>

<div class="page-blocks">
	<section class="booking text--color--dark">
			<div class="booking__col-content px-2">
				<?php get_template_part( 'template-parts/api/accommodation/order', 'accommodation-single',  array(
					'api' => $api,
				)); ?>
			</div>
			
	</section>
</div>

<?php
get_footer( '' );
