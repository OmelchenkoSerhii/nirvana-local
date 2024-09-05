<?php

/**
 * Single Template: Event
 *
 * StyleSheet: /sass/parts/event/single-event
 *
 * @package nirvana
 */

get_header();


if (!post_password_required()) :

	// Get Hero Banner Template Part.
	get_template_part('/template-parts/hero-banner');
?>

	<?php
	$date_tbd = get_field('tbd_date');
	if (!$date_tbd) :
	?>
		<div class="single-event__time-to-start py-5 ">
			<div class="container">
				<div class="row gy-20">
					<div class="col-md-6">
						<div class="d-flex g-20 justify-content-center" data-time-to-start="<?php echo esc_html(gmdate('Y-m-d', strtotime(get_field('event_start_date')))); ?>">
							<div class="d-inline-flex flex-column">
								<span class="h2" data-time-type="days"><?php esc_html_e('00', 'nirvana'); ?></span>
								<span class="p1 text-uppercase text-center text--color--light-orange font--weight--700"><?php esc_html_e('Days', 'nirvana'); ?></span>
							</div>
							<div class="d-inline-flex flex-column">
								<span class="h2" data-time-type="hrs"><?php esc_html_e('00', 'nirvana'); ?></span>
								<span class="p1 text-uppercase text-center text--color--light-orange font--weight--700"><?php esc_html_e('Hrs', 'nirvana'); ?></span>
							</div>
							<div class="d-inline-flex flex-column">
								<span class="h2" data-time-type="mins"><?php esc_html_e('00', 'nirvana'); ?></span>
								<span class="p1 text-uppercase text-center text--color--light-orange font--weight--700"><?php esc_html_e('Mins', 'nirvana'); ?></span>
							</div>
							<div class="d-inline-flex flex-column">
								<span class="h2" data-time-type="secs"><?php esc_html_e('00', 'nirvana'); ?></span>
								<span class="p1 text-uppercase text-center text--color--light-orange font--weight--700"><?php esc_html_e('Secs', 'nirvana'); ?></span>
							</div>
						</div>
					</div>

					<?php if (get_field('show_race_entires') && get_field('slots_remaining')) : ?>
						<div class="col-md-6">
							<!-- TODO: -->
							<div class="d-flex justify-content-center justify-content-md-end align-items-center flex-column flex-md-row h-100 gy-20">
								<div class="px-md-2 text--color--default d-flex align-items-center">
									<svg class="mr-1 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#f47920" fill-rule="evenodd">
										<path d="M7.344.781v1.211c0 .862-.701 1.563-1.562 1.563a1.56 1.56 0 0 1-1.387-.843.78.78 0 0 0-1.034-.342L.44 3.79a.78.78 0 0 0-.36 1.048l7.227 14.688a.78.78 0 0 0 .511.412.78.78 0 0 0 .308.063h3.203a.78.78 0 0 0 .781-.781c0-.862.701-1.563 1.563-1.563s1.563.701 1.563 1.563a.78.78 0 0 0 .781.781h3.203a.78.78 0 0 0 .781-.781V.781A.78.78 0 0 0 19.219 0H8.125a.78.78 0 0 0-.781.781zm-3.9 3.286c.585.661 1.429 1.05 2.337 1.05.569 0 1.102-.154 1.563-.42v2.314L3.728 8.712l-1.899-3.86 1.615-.785zm.974 6.047l2.926 5.946V8.738l-2.926 1.376zm12.28 8.323a3.13 3.13 0 0 0-6.052 0H8.906V13.75h9.531v4.688h-1.739zm1.739-6.25V1.563H8.906v10.625h9.531z" />
										<path opacity=".201" d="M16.698 18.438a3.13 3.13 0 0 0-6.052 0H8.906V13.75h9.531v4.688h-1.739z" />
										<path opacity=".199" d="M3.444 4.067c.585.661 1.429 1.05 2.338 1.05.569 0 1.102-.154 1.563-.42v2.314L3.728 8.712l-1.899-3.86 1.615-.785z" />
									</svg>
									<span class="text--size--14 font--weight--700 text-uppercase">Race Entires</span>
									<svg class="mx-1" xmlns="http://www.w3.org/2000/svg" width="2" height="10" fill="none">
										<path fill="#f47920" d="M0 0h1.5v10H0z" />
									</svg>
									<span class="p1"><?php echo get_field('slots_remaining'); ?> slots remaining</span>
								</div>
								<a href="<?php the_link_on_event_book(); ?>" class="button button--orange"><?php esc_html_e('Purchase a ticket', 'nirvana'); ?></a>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>


	<?php
	$two_columns = get_field('two_columns');
	$heading     = $two_columns['heading'];
	$left_text   = $two_columns['left_text'];
	$right_text  = $two_columns['right_text'];
	$show_as_single_column  = $two_columns['show_as_single_column'];
	$add_benefits  = $two_columns['add_benefits'];
	$benefits_title  = $two_columns['benefits_title'];
	$benefits_subtitle = $two_columns['benefits_subtitle'];
	$benefits_list  = $two_columns['benefits_list'];
	?>
	<?php if ($two_columns && ($heading || $left_text || $right_text)) : ?>
		<div class="single-event__hero-text bg--light text--color--dark py-12">
			<div class="container">
				<div class="row">
					<div class="col-md-8 offset-md-2">
						<div class="content-block">
							<?php if ($heading) : ?>
								<h2><?php echo wp_kses_post($heading); ?></h2>
							<?php endif; ?>
							<?php if($show_as_single_column): ?>
								<?php if ($left_text) : ?>
										<?php echo wp_kses_post($left_text); ?>
								<?php endif; ?>
								<?php if ($left_text) : ?>
										<?php echo wp_kses_post($right_text); ?>
								<?php endif; ?>
							<?php else: ?>
								<div class="row">
									<?php if ($left_text) : ?>
										<div class="col-sm-6">
											<?php echo wp_kses_post($left_text); ?>
										</div>
									<?php endif; ?>
									<?php if ($left_text) : ?>
										<div class="col-sm-6">
											<?php echo wp_kses_post($right_text); ?>
										</div>
									<?php endif; ?>
								</div>
							<?php endif; ?>
							<?php if($add_benefits && $benefits_list): ?>
								<?php if($benefits_title): ?>
									<h3><?php echo $benefits_title; ?></h3>
								<?php endif; ?>
								<?php if($benefits_subtitle): ?>
									<p><?php echo $benefits_subtitle; ?></p>
								<?php endif; ?>
								<ul class="two-column-list">
									<?php foreach($benefits_list as $item): ?>
										<?php if($item['text']): ?>
											<li><?php echo $item['text']; ?></li>
										<?php endif; ?>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
						</div>
						
					</div>
				</div>
				<?php if (!get_field('hide_notify_me_button')) : ?>
					<div class="text-center mt-4">
						<?php if (get_field('enable_notify_me_button')) : ?>
							<a href="#popup-notify" class="button button--orange mt-3"><?php esc_html_e('Notify me', 'nirvana'); ?></a>
						<?php else : ?>
							<a href="<?php the_link_on_event_book(); ?>" class="button button--orange mt-3"><?php esc_html_e('Book Now', 'nirvana'); ?></a>
						<?php endif; ?>

					</div>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>

	<?php
	$event_content = get_field('event_content');
	if ($event_content) :
	?>
		<?php
		$video_source = get_field('video_source');
		$video_file   = get_field('video_file');
		$video_url    = get_field('video_url');

		if (!$video_file && !$video_url) :
			$video_source = get_field('video_source', $event_content);
			$video_file   = get_field('video_file', $event_content);
			$video_url    = get_field('video_url', $event_content);
		endif;
		?>
		<?php if ($video_file || $video_url) : ?>
			<div class="section videoBlock bg--light pb-6 pb-sm-12 pt-0">
				<div class="container">
					<div class="videoBlock__video">
						<?php if ($video_source == 'url') : ?>
							<div class="video video--oembed">
								<?php echo $video_url; ?>
							</div>
						<?php else : ?>
							<video class="video video--html5 w-100" controls="false" data-poster="">
								<source src="<?php echo esc_url($video_file); ?>" type="video/mp4" />
							</video>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php endif; ?>


		<?php
		$added_values = get_field('added_values', $event_content);
		?>
		<?php if ($added_values['values']) : ?>
			<div class="single-event__trip py-15">
				<div class="container animate fade-in">
					<div class="content-block">
						<h2><?php echo wp_kses_post($added_values['title']); ?></h2>
					</div>
					<div class="single-event__trip-inner justify-content-between mt-6 mt-sm-8 mt-md-10 mt-lg-12">
						<?php foreach ($added_values['values'] as $value) : ?>
							<div class="single-event__trip-item position-relative animate fade-in">
								<?php if ($value['image']) : ?>
									<?php the_image($value['image']['url'], true, '', 'h-100'); ?>
								<?php endif; ?>
								<div class="content-block bg--dark p-2 p-sm-3 p-md-4 p-lg-5 w-100">
									<h3 class="text--size--40 font--weight--900 p-0"><?php echo esc_html($value['title']); ?></h3>
									<p class="text--opacity mt-2 mt-lg-3"><?php echo esc_html($value['text']); ?></p>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		<?php endif; ?>
		
		<?php
		$features = get_field('features', $event_content);
		$show_what_we_offer_box = get_field('show_what_we_offer_box');
		?>
		<?php if ($features && $show_what_we_offer_box) : ?>
			<div class="single-event__features bg--light-orange py-12 text--color--light animate fade-up">
				<div class="container">
					<div class="content-block">
						<h2 class="text-center"><?php echo wp_kses_post($features['heading']); ?></h2>
					</div>
					<div class="row mt-8 gy-default justify-content-center">
						<?php foreach ($features['columns'] as $column) : ?>
							<div class="col-12 col-sm-6 col-md-3">
								<div class="text-center">
									<div>
										<img class="mb-3" src="<?php echo esc_url($column['icon']); ?>" alt="<?php the_sub_field('title'); ?>">
									</div>
									<span class="p2 font--weight--700 d-block"><?php echo wp_kses_post($column['title']); ?></span>
									<p class="mt-2"><?php echo wp_kses_post($column['description']); ?></p>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		<?php endif; ?>


		<?php
		$cta_book_now = get_field('cta_book_now', $event_content);
		$notify_me = get_field('enable_notify_me_button');
		?>
		<?php if ($cta_book_now || $notify_me) : ?>
			<div class="section bg--light-orange text--color--light animate fade-up">
				<div class="container">
					<div class="content-block text-center">
						<h2><?php echo wp_kses_post($cta_book_now['title']); ?></h2>
						<p><?php echo wp_kses_post($cta_book_now['text']); ?></p>
						<?php if (!get_field('hide_notify_me_button')) : ?>
							<?php if ($notify_me) : ?>
								<a href="#popup-notify" class="button button--white"><?php esc_html_e('Notify me', 'nirvana'); ?></a>
							<?php else : ?>
								<a href="<?php the_link_on_event_book(); ?>" class="button button--white"><?php esc_html_e('Book Now', 'nirvana'); ?></a>
							<?php endif; ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php endif; ?>

	<?php endif; ?>

	<button type="button" class="button button--scroll-down"></button>
<?php
else :
?>
	<div class="section section--fullscreen">
		<div class="container">
			<?php echo get_the_password_form(); ?>
		</div>
	</div>
<?php
endif;
?>

<?php
get_footer();
