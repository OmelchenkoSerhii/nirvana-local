<?php

/**
 * Template For Event Category
 *
 * StyleSheet: /sass/parts/event/category-event
 *
 * @package nirvana
 */

get_header();

$cat_id = get_queried_object()->term_id;

// Get Hero Banner Template Part.
get_template_part('/template-parts/event-cat','banner',array('term' => get_queried_object()));
?>

<?php
$query = array(
	'post_type'      => 'event',
	'posts_per_page' => -1,
	'paged'           => (isset($_GET['page'])) ? $_GET['page'] : 1,
	'filter_params'  => array( //all params that gonna be filtered
		'search' => true,
		'sort' => false,
		'year' => false,
		'month' => true,
		'letter' => false,
		'tax' => array(
			'event_country',
		),
		'pre_tax_query' => true,
	),
	'pre_tax_query' => array(
		'event_category' => array( $cat_id ),
	),
	'template' => '/template-parts/event/events-list',
);

$query = filter_get_params($query);
//End copy
?>

<div class="filters-feed" data-post="<?php echo $query['post_type']; ?>" data-page="<?php echo $query['paged']; ?>" data-ppp="<?php echo $query['posts_per_page']; ?>" data-template="<?php echo $query['template']; ?>"  data-pretaxname="event_category" data-pretax="<?php echo $cat_id; ?>">
	<div class="category-event__filters bg--light-orange pt-10 pb-20">
		<div class="container">
			<div class="content-block">
				<h2 class="text-center mb-3 mb-md-5 mb-lg-7">EVENTS WE COVER</h2>
			</div>
			<div class="category-event__filters-inner">
				<div  class="row justify-content-center">
					<div for="date" class="col-lg-3 col-md-4">
						<?php echo filter_month($query); ?>
					</div>
					<div for="country" class="col-lg-3 col-md-4">
						<?php echo filter_taxonomy('event_country' , 'Countries' , $query); ?>
					</div>
					<div for="region" class="col-lg-3 col-md-4">
						<?php echo filter_taxonomy('event_region' , 'Regions' , $query); ?>
					</div>
					<div class="col-lg-9 mt-3">
						<?php echo filter_search($query); ?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php //api_sync_events(); ?>
	<div class="category-event__list bg--light pb-10">
		<div class="container">		
			<div class="events__list__wrapper">
				<div class="result">
					<div class="filters-feed__result row">
						<?php get_template_part($query['template'], '',  $query); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php get_template_part( '/template-parts/event/event' , 'notify-popup' ); ?>

<?php
get_footer();
