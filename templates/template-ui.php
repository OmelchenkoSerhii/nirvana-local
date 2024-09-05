<?php
/*
Template Name: Ui Kit
*/
?>

<?php get_header(); ?>

<div class="container" style="padding-top: 150px">

	<div class="ui__block py-5">
		<h2 class="ui__block__title text-color-primary mb-4">Colors</h2>
		<?php get_template_part( '/template-parts/ui/colors'); ?>
	</div>

	<div class="ui__block py-5">
		<h2 class="ui__block__title text-color-primary mb-4">Typography</h2>
		<?php get_template_part( '/template-parts/ui/typography'); ?>
	</div>

	<div class="ui__block py-5">
		<h2 class="ui__block__title text-color-primary mb-4">Buttons</h2>
		<?php get_template_part( '/template-parts/ui/buttons'); ?>
	</div>

	<?php if(false): ?>
	<div class="ui__block py-5">
		<h2 class="ui__block__title text-color-primary mb-4">Icons</h2>
		<?php get_template_part( '/template-parts/ui/icons'); ?>
	</div>
	<?php endif; ?>

	<div class="ui__block py-5">
		<h2 class="ui__block__title text-color-primary mb-4">Form</h2>
		<?php get_template_part( '/template-parts/ui/form'); ?>
	</div>
	
</div> <!-- .container -->

<?php get_footer(); ?>
