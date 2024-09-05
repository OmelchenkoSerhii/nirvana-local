<?php 
$description = get_field('description'); 
$image = get_the_post_thumbnail_url();
$category = get_the_category();
$cat = get_the_category();
$date = get_field('event_start_date');
$event_end_date = get_field( 'event_end_date' );
$date_tbd = get_field('tbd_date');
$term_obj_list = get_the_terms( get_the_ID() , 'event_country' );
$terms_string = join(', ', wp_list_pluck($term_obj_list, 'name'));
?>
<div class="event-card d-flex flex-column flex-md-row">
	<div class="event-card__img-block-wrapper position-relative flex-shrink-0 m-auto m-md-0">
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

		<?php if($date && !$date_tbd): ?>
			<?php 
			$dateFormat = DateTime::createFromFormat('Ymd', $date); 
			if($event_end_date && $event_end_date != $date):
				$dateFormatEnd = DateTime::createFromFormat('Ymd', $event_end_date);
				?>
				<time class="event-card__date position-absolute d-flex flex-column text-center z-1 justify-content-center" datetime="<?php echo $date; ?>">
					<span class="p1 text-uppercase"><?php echo $dateFormat->format('M'); ?></span>
					<span class="h2"><?php echo $dateFormat->format('j'); ?></span>
					<span class="p1"><?php echo $dateFormat->format('Y'); ?></span>
				</time>
				<time class="event-card__date event-card__date-end position-absolute d-flex flex-column text-center z-1 justify-content-center" datetime="<?php echo $event_end_date; ?>">
					<span class="p1 text-uppercase"><?php echo $dateFormatEnd->format('M'); ?></span>
					<span class="h2"><?php echo $dateFormatEnd->format('j'); ?></span>
					<span class="p1"><?php echo $dateFormatEnd->format('Y'); ?></span>
				</time>
			<?php else: ?>
				<time class="event-card__date position-absolute d-flex flex-column text-center z-1 justify-content-center" datetime="<?php echo $date; ?>">
					<span class="p1 text-uppercase"><?php echo $dateFormat->format('M'); ?></span>
					<span class="h2"><?php echo $dateFormat->format('j'); ?></span>
					<span class="p1"><?php echo $dateFormat->format('Y'); ?></span>
				</time>
			<?php endif; ?>
		<?php else: ?>
			<time class="event-card__date position-absolute d-flex flex-column text-center z-1 justify-content-center">
				<span class="h3">TBD</span>
			</time>
		<?php endif; ?>
		<?php if($image) : ?>
			<?php the_image( $image, false, 'w-100 h-100 object-cover', 'w-100 h-100' ); ?>
		<?php else: ?>
			<?php the_image( get_template_directory_uri() . '/assets/images/ironman-united-kingdom.jpg', false, 'w-100 h-100 object-cover', 'w-100 h-100' ); ?>
	    <?php endif;?>
		
	</div>
	<div class="event-card__main-content text--color--dark p-2 p-sm-3 pr-md-3 pr-lg-0 pl-3 d-flex flex-column">
		<h3><a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a></h3>
		<?php if($terms_string): ?>
			<span class="p2"><?php echo $terms_string; ?></span>
		<?php endif; ?>
		
		<?php 
		$two_columns = get_field( 'two_columns' );
		$left_text = $two_columns['left_text'];
		$excerpt = get_the_excerpt();
		if($excerpt):
		?>
			<div class="p2 pr-2 mt-2 mt-sm-3 mb-3 mb-sm-4 text--opacity text--height--22">
				<p><?php echo $excerpt; ?></p>
			</div>
		<?php elseif($left_text): ?>
			<div class="p2 pr-2 mt-2 mt-sm-3 mb-3 mb-sm-4 text--opacity text--height--22">
				<?php echo excerpt_str($left_text , 20); ?>
			</div>
		<?php endif; ?>

		<div class="event-card__cta d-flex flex-wrap justify-content-between gy-10 py-2 mt-auto">
			<div class="d-flex gy-20 event-card__cta__inner" style="z-index: 99">
				<?php if (!get_field('hide_notify_me_button')) : ?>
					<?php if(get_field('enable_notify_me_button')): ?>
						<?php 
						$event_start_date = get_field( 'event_start_date' );
						if($event_start_date && !$date_tbd):
							$dateFormat = DateTime::createFromFormat('Ymd', $event_start_date);
							?>
							<a href="#popup-notify" class="button button--dark js-notify-list-btn" data-name="<?php the_title(); ?>" data-destination="<?php echo esc_html( get_event_country_by_taxonomy() ); ?>" data-year="<?php echo $dateFormat->format('Y'); ?>" data-month="<?php echo $dateFormat->format('F'); ?>"><?php esc_html_e( 'Notify me', 'nirvana' ); ?></a>
						<?php else: ?>
							<a href="#popup-notify" class="button button--dark js-notify-list-btn" data-name="<?php the_title(); ?>" data-destination="<?php echo esc_html( get_event_country_by_taxonomy() ); ?>" data-year="TBD" data-month="TBD"><?php esc_html_e( 'Notify me', 'nirvana' ); ?></a>
						<?php endif; ?>	
					<?php else: ?>
						<a href="<?php the_link_on_event_book(); ?>" class="button button--orange"><?php esc_html_e( 'Book Now', 'nirvana' ); ?></a>
					<?php endif; ?>
				<?php endif; ?>
				<a class="button button--white button--arrow" href="<?php the_permalink(); ?>">View</a>
			</div>
			<?php if(get_field('show_race_entires') && get_field('slots_remaining')): ?>
				<div class="px-2 bg--dark text--color--default d-none align-items-center py-2 d-none">
					<svg class="mr-1 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#f47920" fill-rule="evenodd"><path d="M7.344.781v1.211c0 .862-.701 1.563-1.562 1.563a1.56 1.56 0 0 1-1.387-.843.78.78 0 0 0-1.034-.342L.44 3.79a.78.78 0 0 0-.36 1.048l7.227 14.688a.78.78 0 0 0 .511.412.78.78 0 0 0 .308.063h3.203a.78.78 0 0 0 .781-.781c0-.862.701-1.563 1.563-1.563s1.563.701 1.563 1.563a.78.78 0 0 0 .781.781h3.203a.78.78 0 0 0 .781-.781V.781A.78.78 0 0 0 19.219 0H8.125a.78.78 0 0 0-.781.781zm-3.9 3.286c.585.661 1.429 1.05 2.337 1.05.569 0 1.102-.154 1.563-.42v2.314L3.728 8.712l-1.899-3.86 1.615-.785zm.974 6.047l2.926 5.946V8.738l-2.926 1.376zm12.28 8.323a3.13 3.13 0 0 0-6.052 0H8.906V13.75h9.531v4.688h-1.739zm1.739-6.25V1.563H8.906v10.625h9.531z"/><path opacity=".201" d="M16.698 18.438a3.13 3.13 0 0 0-6.052 0H8.906V13.75h9.531v4.688h-1.739z"/><path opacity=".199" d="M3.444 4.067c.585.661 1.429 1.05 2.338 1.05.569 0 1.102-.154 1.563-.42v2.314L3.728 8.712l-1.899-3.86 1.615-.785z"/></svg>
					<span class="text--size--14 font--weight--700 text-uppercase">Race Entires</span>
					<svg class="mx-1" xmlns="http://www.w3.org/2000/svg" width="2" height="10" fill="none"><path fill="#f47920" d="M0 0h1.5v10H0z"/></svg>
					<span class="p1"><?php echo get_field('slots_remaining'); ?> slots remaining</span>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
