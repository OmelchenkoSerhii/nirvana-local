<?php
$content = get_sub_field( 'text' );
$form_id = get_sub_field( 'ninja_form' );

//Block options
$options = get_acf_block_options();
?>

<section 
	<?php if ( $options['id'] ) : ?>
		id="<?php echo $options['id']; ?>"
	<?php endif; ?>
		class="section form-block <?php echo $options['class']; ?>"
	<?php if ( $options['style'] ) : ?>
		style="<?php echo $options['style']; ?>"
	<?php endif; ?>
>
	<div class="container">
		<div class="row justify-content-center">
			<?php if ( $content ) : ?>
				<div class="col-12">
					<?php if ( $content ) : ?>
						<div class="content-block form-block__content animate fade-up mb-3">
							<?php echo $content; ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( $form_id ) : ?>
				<div class="col-12">
					<div class="form animate fade-up delay-1">
						<?php echo do_shortcode( '[ninja_form id="' . $form_id . '"]' ); ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
