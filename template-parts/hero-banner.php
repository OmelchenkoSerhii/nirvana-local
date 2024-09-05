<?php
/**
 * Template Part Name: Hero Banner
 *
 * @package nirvana
 */

?>

<?php if ( is_page_template( 'templates/template-archive-event.php' ) ) : ?>
<div class="hero-banner">
	<div class="hero-banner__background">
		<div class="image-ratio pb-0 w-100 h-100">
			<img src="<?php echo get_template_directory_uri() . '/assets/images/events-we-cover (1).jpg'; ?>" alt="Hero Image">
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="content-block">
					<h1 class="text-center"><?php esc_html_e( 'Events we cover', 'nirvana' ); ?></h1>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<?php if ( is_page_template( 'templates/template-category-event.php' ) ) : ?>
<div class="hero-banner">
	<div class="hero-banner__background">
		<div class="image-ratio pb-0 w-100 h-100">
			<img src="<?php echo get_template_directory_uri() . '/assets/images/temp--bg-group-event.jpg'; ?>" alt="Hero Image">
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-12 col-md-6 col-lg-4 offset-md-3 offset-lg-4">
				<div class="image-ratio" style="padding-bottom: 48%;">
					<img src="<?php echo get_template_directory_uri() . '/assets/images/ironman-full-logo (1).png'; ?>" alt="Hero Image">
				</div>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<?php if ( is_singular( 'event' ) ) : ?>
	<div class="hero-banner">
		<div class="hero-banner__background">
			<?php if ( get_field( 'hero_image' ) ) : ?>
				<?php the_image( get_field( 'hero_image' ), true, '', 'pb-0 w-100 h-100', get_the_title() ); ?>
			<?php endif; ?>
		</div>

		<div class="container pt-16 pb-8">
			<div class="row">
				<div class="col-md-6">
					<div class="content-block">
						<img class="mb-3 mw-50" src="<?php the_field( 'hero_event_logo' ); ?>" alt="<?php the_field( 'hero_title' ); ?>" />
						<h1 class="mb-1 h2 text-uppercase"><?php the_field( 'hero_title' ); ?></h1>
						<span class="mb-0 h3 text--uppercase d-block"><?php the_field( 'hero_subtitle' ); ?></span>

						<?php if (!get_field('hide_notify_me_button')) : ?>
							<?php if(get_field('enable_notify_me_button')): ?>
								<a href="#popup-notify" class="button button--orange mt-3"><?php esc_html_e( 'Notify me', 'nirvana' ); ?></a>
							<?php else: ?>
								<a href="<?php the_link_on_event_book(); ?>" class="button button--orange mt-3"><?php esc_html_e( 'Book Now', 'nirvana' ); ?></a>
							<?php endif; ?>
						<?php endif; ?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="content-block d-flex justify-content-center flex-column align-items-end text--size--28 font--weight--700 h-100 text-uppercase">
						<?php 
						$event_start_date = get_field( 'event_start_date' );
						$event_end_date = get_field( 'event_end_date' );
						$date_tbd = get_field('tbd_date');
						if($event_start_date && !$date_tbd):
							$dateFormat = DateTime::createFromFormat('Ymd', $event_start_date);
							if($event_end_date && ($event_start_date != $event_end_date)):
								$dateFormatEnd = DateTime::createFromFormat('Ymd', $event_end_date);
								?>
								<time id="event-startdate" datetime="<?php echo esc_html( gmdate( 'Y-m-d', strtotime( $dateFormat->format('F j, Y') ) ) ); ?>"><?php echo $dateFormat->format('F j, Y'); ?> - <?php echo $dateFormatEnd->format('F j, Y'); ?></time>
							<?php else: ?>
								<time id="event-startdate" datetime="<?php echo esc_html( gmdate( 'Y-m-d', strtotime( $dateFormat->format('F j, Y') ) ) ); ?>"><?php echo $dateFormat->format('F j, Y'); ?></time>
							<?php endif; ?>
						<?php else: ?>
							<time id="event-startdate">TBD</time>
						<?php endif; ?>
						<span><?php echo esc_html( get_event_country_by_taxonomy() ); ?></span>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php if(get_field('enable_notify_me_button') && !get_field('hide_notify_me_button')): ?>
		<?php get_template_part( '/template-parts/event/event' , 'notify-popup' , array('event_id' => get_the_ID()) ); ?>
	<?php endif; ?>

<?php endif; ?>

<?php
