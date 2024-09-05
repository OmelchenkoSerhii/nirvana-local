<?php 
	$description = get_field('description'); 
	$image = get_the_post_thumbnail_url();
	$category = get_the_category();
	$cat = get_the_category();
	$date = get_field('event_start_date');
	$term_obj_list = get_the_terms( get_the_ID() , 'event_country' );
	$terms_string = join(', ', wp_list_pluck($term_obj_list, 'name'));
?>
<div class="event-card d-flex flex-column flex-md-row">
	<div class="event-card__img-block-wrapper position-relative flex-shrink-0 m-auto m-md-0">
		<?php if($date): ?>
			<?php $dateFormat = DateTime::createFromFormat('Ymd', $date); ?>
			<time class="event-card__dated-flex flex-column text-center z-1 justify-content-center" datetime="<?php echo $date; ?>">
				<span class="p1 text-uppercase"><?php echo $dateFormat->format('M'); ?></span>
				<span class="h2"><?php echo $dateFormat->format('j'); ?></span>
				<span class="p1"><?php echo $dateFormat->format('Y'); ?></span>
			</time>
		<?php endif; ?>
	</div>
	<div class="event-card__main-content text--color--dark p-2 p-sm-3 pr-md-3 pr-lg-0 d-flex flex-column">
		<div class="event-booked-card__cat">
			<div class="text--size--16">Event Series</div>
			<?php echo get_the_category();?>
		</div>
		<div class="event-booked-card__cat">
			<div class="text--size--16">Event Name</div>
			<?php echo get_the_title();?>
		</div>
		<div class="event-booked-card__cat">
			<div class="text--size--16">Balance Due</div>
			<?php echo $price ;?>
		</div>
		<a class="button" href="# ">make payment</a>
	</div>
</div>
