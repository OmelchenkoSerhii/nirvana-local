<?php
/**
 * Template Name: Contact Us
 *
 * StyleSheet: /sass/pages/contact-us
 *
 * @package nirvana
 */

$contents  = get_field( 'contents' );
$map_image = get_field( 'map_image' );

get_header();
if (!post_password_required()) :
?>
<section class="bg--light-orange mt-13 pt-5 pt-md-13">
	<div class="container">
		<div class="row gy-30">
			<div class="col-md-6">
				<h1 class="mb-6"><?php the_field( 'title' ); ?></h1>
				<div class="contact-us__contents">
					<?php foreach ( $contents as $content ) : ?>
						<div class="contact-us__content">
							<div class="contact-us__content-icon">
								<img src="<?php echo esc_url( $content['icon']['link'] ); ?>" alt="<?php echo esc_attr( $content['icon']['alt'] ); ?>">
							</div>
							<h3 class="contact-us__content-title p2"><?php echo $content['title']; ?></h3>
							<div class="contact-us__content-text">
								<?php foreach ( $content['content'] as $layout ) : ?>
									<?php if ( 'text' === $layout['acf_fc_layout'] ) : ?>
										<p><?php echo wp_kses_post( $layout['text'] ); ?></p>
									<?php elseif ( 'link' === $layout['acf_fc_layout'] ) : ?>
										<?php the_acf_link( $layout['link'], false ); ?>
									<?php endif; ?>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="col-md-6">
				<div class="contact-us__form text--color--dark">
					<?php echo do_shortcode( '[ninja_form id="' . get_field( 'ninja_form' ) . '"]' ); ?>
				</div>
			</div>
		</div>
	</div>
</section>

<div class="contact-us__map">
	<img class="w-100" src="<?php echo esc_url( $map_image['link'] ); ?>" alt="<?php echo esc_attr( $map_image['alt'] ); ?>">
</div>


<script>
	jQuery(document).on( 'nfFormReady', function(){
		<?php
		$terms = get_terms( array(
			'taxonomy'   => 'event_category',
			'hide_empty' => false,
		) );
		$options = array();
		if($terms):
			foreach($terms as $term):
				$the_query = new WP_Query( array(
					'post_type'      => 'event',
					'posts_per_page' => -1,
					'post_status'    => 'publish',
					'fields' => 'ids',
					'orderby'  => 'title',
    				'order' => 'ASC', 
					
					'tax_query' => array(
						array (
							'taxonomy' => 'event_category',
							'field' => 'slug',
							'terms' => $term->slug,
						)
					),
				));
				while ( $the_query->have_posts() ) :
					$the_query->the_post();
					$title = get_the_title(get_the_ID());
					if(!$options[$term->name]) $options[$term->name] = array();
					array_push($options[$term->name] , $title);
				endwhile;
				wp_reset_postdata();
			endforeach;
		endif;
		?>
		var events = <?php echo json_encode($options); ?>;
		Object.keys(events).forEach(key => {
			jQuery('.event-series').append('<option value="'+key+'">'+key+'</option>');
			events[key].forEach(event => {
				jQuery('.events-select').append('<option value="'+event+'">'+event+'</option>');
			});
		});
		jQuery('.events-select').append('<option value="Other">Other</option>');

		// Set initial values based on URL parameters
		const urlParams = new URLSearchParams(window.location.search);
		const seriesParam = urlParams.get('event_series');
		const eventParam = urlParams.get('event_title');

		if (seriesParam && events[seriesParam]) {
			jQuery('.event-series').val(seriesParam).change();
		}

		jQuery('.event-series').on('change' , function(){
			console.log(jQuery(this).val());
			let series = jQuery(this).val();
			jQuery('.events-select option').remove();
			Object.keys(events).forEach(key => {
				if(key == series){
					jQuery('.events-select').append('<option value="select">Select Event</option>');
					events[key].forEach(event => {
						jQuery('.events-select').append('<option value="'+event+'">'+event+'</option>');
					});
					jQuery('.events-select').append('<option value="Other">Other</option>');
				}
			});
			if (eventParam) {
				jQuery('.events-select').val(eventParam);
			}
		});
		if (seriesParam) {
			jQuery('.event-series').trigger('change');
		}
	});
</script>
<?php else: ?>

	<div class="section section--fullscreen">
		<div class="container">
			<?php echo get_the_password_form(); ?>
		</div>
	</div>

<?php endif; ?>

<?php
get_footer();
