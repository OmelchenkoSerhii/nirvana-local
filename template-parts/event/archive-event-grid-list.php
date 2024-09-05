<?php
/**
 * Template Part Name: Arhive Event Grid List
 *
 * @package nirvana
 */

?>

<div class="event-grid-list py-8 py-sm-12 animate fade-up">
	<div class="container">
		<?php 
		$event_categories =  get_terms( array(
			'taxonomy' => 'event_category',
			'hide_empty' => true,
		) );
		
		if($event_categories):
			?>
			<ul class="event-grid-list__list row my-n1">
			<?php
			foreach($event_categories as $term):
				$logo = get_field('nav_logo', $term);
				$link = get_term_link($term->slug, 'event_category');
				$name = $term->name;
				if($logo):
					?>
					<li class="event-grid-list__item col-lg-3 col-6 py-1">
						<a class="event-grid-list__item-inner" href="<?php echo $link; ?>">
							<div class="event-grid-list__img-block-wrapper">
								<img class="" src="<?php echo $logo['url']; ?>" alt="<?php echo $name; ?>">
							</div>
						</a>
					</li>
					<?php
				else:
					?>
					<li class="event-grid-list__item col-lg-3 col-6 py-1">
						<a class="event-grid-list__item-inner" href="<?php echo $link; ?>">
							<span class="event-grid-list__item-name"><?php echo $name; ?></span>
						</a>
					</li>
					<?php
				endif;
			endforeach; 
			?>
			</ul>
			<?php
		endif;
		?>
		
	</div>
</div>

<?php
