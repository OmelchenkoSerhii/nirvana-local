<?php
/**
 * Template part for displaying special flight popup
 */

if ( ! is_page_template( 'api-templates/template-api-flights.php' ) ) {
	return;
}

?>

<div id="popup-special-flight" class="popup-block popup-special-flight">
	<div class="popup-block__background"></div>
	<div class="popup-block__inner">
		<div class="container popup-block__container position-relative">
			<div class="popup-block__content text-color-light p-2">
				<span class="popup-block__close"></span>
				<div class="row">
					<div class="col-12 d-flex flex-column text--color--dark">
						<div class="content-block text-center">
							<?php the_svg_by_sprite( 'telephone', 30, 29, 'mt-4 mb-2' ); ?>
							<h4><?php esc_html_e( 'Special flight request?', 'nirvana' ); ?></h4>
							<p class="text--opacity text--size--16">
								<?php echo wp_kses_post( __( 'Arranging a group booking<br>with different flight times/dates?', 'nirvana' ) ); ?>
							</p>
							<p class="text--opacity text--size--16">
								<?php esc_html_e( 'A one way or multi stop journey?', 'nirvana' ); ?>
							</p>
							<p class="font--weight--700 text--size--16">
								<?php esc_html_e( 'Talk to our expert team on', 'nirvana' ); ?><br>
								<a class="text--color--light-orange" href="tel:+44(0)1912571750">+44 (0)191 257 1750</a><br>
								<a class="text--color--light-orange" href="mailto:contactus@nirvanaeurope.com">contactus@nirvanaeurope.com</a>
							</p>
						</div>
					</div>
					<div class="col-12 mt-5">
						<div class="d-flex g-10">
							<a href="#" class="button button--dark-transparent w-100"><?php esc_html_e( 'Back to flights', 'nirvana' ); ?></a>
							<a href="#" class="button button--orange w-100"><?php esc_html_e( 'Go to next step', 'nirvana' ); ?></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div> <!-- #popup-special-flight -->

<?php
