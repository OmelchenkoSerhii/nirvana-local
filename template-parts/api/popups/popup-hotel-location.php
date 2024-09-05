<?php
/**
 * Template part for displaying hotel location popup
 */

$hotelId = $args['hotel_id'];

if ( ! is_page_template( 'api-templates/template-api-accommodations.php' ) ) {
	return;
}

$link = get_field('api_accommodation_single_page','option');

?>

<div id="popup-hotel-location-<?php echo $hotelId; ?>" class="popup-block popup-hotel-location">
	<div class="popup-block__background"></div>
	<div class="popup-block__inner">
		<div class="container popup-block__container position-relative">
			<div class="popup-block__content text-color-light p-2">
				<span class="popup-block__close"></span>
				<div class="row">
					<div class="col-md-4 d-flex flex-column text--color--dark">
						<div class="popup-hotel-location__image-wrapper">
							<img class="w-100 h-auto" src="<?php echo get_template_directory_uri(); ?>/assets/images/temp--kam-idris.jpg" alt="">
							<!-- IF PRIORITY HOTEL -->
							<?php the_icon_bookmark_primary(); ?>
							<!-- ENDIF -->
							<!-- IF NIGHTS MINIMUM STAY -->
							<?php // the_icon_bookmark_min_nights(); ?>
							<!-- ENDIF -->
						</div>

						<div class="popup-hotel-location__subhead mt-2 d-flex justify-content-between">
							<span class="text-uppercase text--color--light-orange text--size--12 font--weight--700">Berlin</span>
							<span class="text-uppercase text--opacity text--size--12 font--weight--700">8.0 miles from event</span>
						</div>

						<span class="d-block h3 mt-2">Sheraton Berlin Grand Hotel Esplanade</span>

						<?php the_rating( 3, 'mt-1' ); ?>

						<p class="text--opacity mt-2">Rhoncus efficitur turpis. In vel magna aliquam, viverra nibh a, viverra risus. Sed rutrum pretium porta. Nam hendrerit consectetur metus sit amet varius. Integer sit amet viverra sem. Aenean vitae nunc ligula. Mauris accumsan erat sapien, quis efficitur dui convallis vel. Nulla ornare ipsum condimentum massa malesuada.</p>

						<div class="d-flex align-items-center justify-content-between mt-auto pt-2">
							<div>
								<span class="d-block h3">From £135</span>
								<span class="d-block text--opacity mt-05">1 night, 2 adults</span>
							</div>
							<a href="<?php echo $link; ?>" class="button button--orange">Book</a>
						</div>
					</div>
					<div class="col-md-8">
						<iframe class="w-100 h-100" width="100%" height="600" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q=London+(Nirvana)&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"><a href="https://www.maps.ie/distance-area-calculator.html">measure area map</a></iframe>
					</div>
				</div>
			</div>
		</div>
	</div>
</div> <!-- #popup-hotel-location -->

<?php
