<?php
/**
 * Template part for displaying hotel location popup
 */

if ( ! is_page_template( 'api-templates/template-api-flights.php' ) ) {
	return;
}

?>

<div id="popup-select-flight" class="popup-block popup-select-flight">
	<div class="popup-block__background"></div>
	<div class="popup-block__inner">
		<div class="popup-select-flight__container position-relative">
			<div class="popup-block__content text--color--dark p-3">
				<span class="popup-block__close"></span>

				<hr class="hr mx-n3 mt-3 mb-4">

				<div class="popup-select-flight__header text--size--16">
					<div>
						<h3>London (LHR) - Berlin (BER)</h3>
						<p class="mt-05">British Airways - Thu, 21 July</p>
					</div>

					<div class="mt-2">
						<p>19:50 - 22:50</p>
						<p>2H (Direct)</p>
					</div>
				</div>

				<hr class="hr mx-n3 mt-3">

				<div class="popup-select-flight__slider py-3 mx-n3 pl-3">
					<div class="d-flex justify-content-between align-items-center">
						<p class="font--weight--700">
							<!-- TODO: ALEX REPLACE Berlin ON VARIABLE -->
							<?php
							printf(
								/* translators: 1: Destination. */
								esc_html__( 'Select your fare to %s', 'nirvana' ),
								'<span data-name="destination">Berlin</span>'
							);
							?>
						</p>

						<div class="slider-arrows d-inline-flex g-10 mr-5">
							<button class="button--arrow button--arrow-left button--orange"></button>
							<button class="button--arrow button--orange"></button>
						</div>
					</div>

					<div class="popup-select-flight__slider-list mt-3">
						<?php for ( $i = 0; $i < 3; $i++ ) : ?>
							<div class="popup-select-flight__slider-item">
								<div class="popup-select-flight__slider-item-inner p-2 bg--white">

									<h3>£244</h3>

									<p class="text--size--12 text--opacity ">£732 return for 3 travellers</p>

									<?php the_tag( 'Basic', 'orange', 'mt-2' ); ?>

									<p class="mt-05 font--weight--700 text--size--16">Cabin: <span>Economy</span></p>

									<ul class="mt-2 text--size--16 g-10 d-flex flex-column">
										<li class="d-flex align-items-center"><?php the_svg_by_sprite( 'list-icon-dollar', 9, 13, 'mr-1' ); ?><span class="text--opacity">Seat choice</span></li>
										<li class="d-flex align-items-center"><?php the_svg_by_sprite( 'list-icon-dark-cancel', 9, 9, 'mr-1' ); ?><span class="text--opacity">Cancellation</span></li>
										<li class="d-flex align-items-center"><?php the_svg_by_sprite( 'list-icon-dollar', 9, 13, 'mr-1' ); ?><span class="text--opacity">Changes</span><span class="ml-auto text--opacity">£50</span></li>
										<li class="d-flex align-items-center"><?php the_svg_by_sprite( 'list-icon-dark-accept', 9, 7, 'mr-1' ); ?><span class="text--opacity">Personal Item</span></li>
									</ul>

									<hr class="hr mx-n2 my-2">

									<div class="d-flex justify-content-between mt-1">
										<span class="font--weight--700">Carry on</span>
										<span class="text--opacity">Incl. up to 23 kg</span>
									</div>

									<div class="d-flex justify-content-between mt-1">
										<span class="font--weight--700">1st checked bag</span>
										<span class="text--opacity">£50 up to 23 kg</span>
									</div>

									<div class="d-flex justify-content-between mt-1">
										<span class="font--weight--700">2nd checked bag</span>
										<span class="text--opacity">£50 up to 23 kg</span>
									</div>

									<button class="button button--orange text-uppercase w-100 mt-3"><?php esc_html_e( 'Select', 'nirvana' ); ?></button>
								</div>
							</div>
						<?php endfor; ?>
					</div>
				</div>

				<div class="popup-select-flight__info text--size--12 text--opacity text--color--dark pt-3">
					<p><?php esc_html_e( 'Baggage fees reflect the airline\'s standard fees based. Nunc ac felis nec mauris interdum euismod quis vel nunc. Cras vehicula ligula a augue facilisis viverra. Donec bibendum congue mauris, ut tempus augue efficitur ut. Suspendisse nec tempor diam. Mauris varius lorem eu est fermentum vestibulum sit amet id libero. Donec odio mauris, gravida nec est vel, fermentum bibendum leo. Aenean aliquet enim sed dolor varius, eu dapibus leo tincidunt. Nullam leo mi, imperdiet sagittis commodo non, lobortis at ipsum. Aliquam vel massa scelerisque, fermentum mauris varius, tempor lectus. Suspendisse fermentum sodales libero quis viverra. Sed tempus magna nibh, ut egestas dolor malesuada imperdiet. Suspendisse at lectus ante. Fusce risus est, rutrum sit amet libero in, blandit pellentesque eros. Vestibulum tristique, felis sed molestie cursus, ante tellus imperdiet purus, eu dignissim dolor massa quis.', 'nirvana' ); ?></p>
				</div>
			</div>
		</div>
	</div>
</div> <!-- #popup-select-flight -->

<?php
