<?php
/**
 * Custom Post Types
 *
 * @package nirvana
 */

/**
 * Post Type: Testimonials
 */
function cptui_register_my_cpts_testimonial() {
	$labels = array(
		'name'          => __( 'Testimonials', 'nirvana' ),
		'singular_name' => __( 'Testimonial', 'nirvana' ),
	);

	$args = array(
		'label'                 => __( 'Testimonials', 'nirvana' ),
		'labels'                => $labels,
		'description'           => '',
		'public'                => true,
		'publicly_queryable'    => true,
		'show_ui'               => true,
		'show_in_rest'          => true,
		'rest_base'             => '',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
		'has_archive'           => false,
		'show_in_menu'          => true,
		'show_in_nav_menus'     => true,
		'delete_with_user'      => false,
		'exclude_from_search'   => false,
		'capability_type'       => 'post',
		'map_meta_cap'          => true,
		'hierarchical'          => false,
		'rewrite'               => array(
			'slug'       => 'testimonial',
			'with_front' => true,
		),
		'query_var'             => true,
		'menu_icon'             => 'dashicons-format-chat',
		'supports'              => array( 'title' ),
		'show_in_graphql'       => false,
	);

	register_post_type( 'testimonial', $args );
}

add_action( 'init', 'cptui_register_my_cpts_testimonial' );

/**
 * Post Type: Events
 */
function cptui_register_my_cpts_event() {
	$labels = array(
		'name'                     => __( 'Events', 'nirvana' ),
		'singular_name'            => __( 'Event', 'nirvana' ),
		'menu_name'                => __( 'Events', 'nirvana' ),
		'all_items'                => __( 'All Events', 'nirvana' ),
		'add_new'                  => __( 'Add New', 'nirvana' ),
		'add_new_item'             => __( 'Add New Event', 'nirvana' ),
		'edit_item'                => __( 'Edit Event', 'nirvana' ),
		'new_item'                 => __( 'New Event', 'nirvana' ),
		'view_item'                => __( 'View Event', 'nirvana' ),
		'view_items'               => __( 'View Events', 'nirvana' ),
		'search_items'             => __( 'Search Event', 'nirvana' ),
		'not_found'                => __( 'No Events Found', 'nirvana' ),
		'not_found_in_trash'       => __( 'No Event Found in Bin', 'nirvana' ),
		'parent'                   => __( 'Parent Event', 'nirvana' ),
		'featured_image'           => __( 'Featured Image For This Event', 'nirvana' ),
		'set_featured_image'       => __( 'Set Featured Image For This Event', 'nirvana' ),
		'remove_featured_image'    => __( 'Remove Featured Image For This Event', 'nirvana' ),
		'use_featured_image'       => __( 'Use Featured Image For This Event', 'nirvana' ),
		'archives'                 => __( 'Events Archives', 'nirvana' ),
		'insert_into_item'         => __( 'Insert Into Event', 'nirvana' ),
		'uploaded_to_this_item'    => __( 'Uploaded To This Event', 'nirvana' ),
		'filter_items_list'        => __( 'Filter Events List', 'nirvana' ),
		'items_list_navigation'    => __( 'Events List Navigation', 'nirvana' ),
		'items_list'               => __( 'Events List', 'nirvana' ),
		'attributes'               => __( 'Events Attributes', 'nirvana' ),
		'name_admin_bar'           => __( 'Event', 'nirvana' ),
		'item_published'           => __( 'Event Published', 'nirvana' ),
		'item_published_privately' => __( 'Event Published Privately', 'nirvana' ),
		'item_reverted_to_draft'   => __( 'Event Reverted To Draft', 'nirvana' ),
		'item_scheduled'           => __( 'Event Scheduled', 'nirvana' ),
		'item_updated'             => __( 'Event Updated', 'nirvana' ),
		'parent_item_colon'        => __( 'Parent Event', 'nirvana' ),
	);

	$args = array(
		'label'                 => __( 'Events', 'nirvana' ),
		'labels'                => $labels,
		'description'           => '',
		'public'                => true,
		'publicly_queryable'    => true,
		'show_ui'               => true,
		'show_in_rest'          => true,
		'rest_base'             => '',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
		'rest_namespace'        => 'wp/v2',
		'has_archive'           => true,
		'show_in_menu'          => true,
		'show_in_nav_menus'     => true,
		'delete_with_user'      => false,
		'exclude_from_search'   => false,
		"capability_type"       =>  array('event' , 'events'),
		'capabilities' => array(
			'edit_post' => 'edit_event',
			'edit_posts' => 'edit_events',
			'edit_others_posts' => 'edit_other_events',
			'publish_posts' => 'publish_events',
			'read_post' => 'read_event',
			'read_private_posts' => 'read_private_events',
			'delete_post' => 'delete_event'
		),
		'map_meta_cap'          => true,
		'hierarchical'          => true,
		'can_export'            => false,
		'rewrite'               => array(
			'slug'       => 'events/%event_category%',
			'with_front' => true,
		),
		'query_var'             => true,
		'menu_icon'             => 'dashicons-calendar-alt',
		'supports'              => array( 'title', 'thumbnail', 'page-attributes' , 'excerpt' ),
		'show_in_graphql'       => false,
	);

	register_post_type( 'event', $args );
}

add_action( 'init', 'cptui_register_my_cpts_event' );


function cptui_register_my_cpts_booking() {

	/**
	 * Post Type: Bookings.
	 */

	$labels = [
		"name" => __( "Bookings", "nirvana" ),
		"singular_name" => __( "Booking", "nirvana" ),
	];

	$args = [
		"label" => __( "Bookings", "nirvana" ),
		"labels" => $labels,
		"description" => "",
		"public" => false,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"rest_namespace" => "wp/v2",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => true,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"can_export" => false,
		"rewrite" => [ "slug" => "booking-post", "with_front" => false ],
		"query_var" => true,
		"menu_icon" => "dashicons-cart",
		"supports" => [ "title" ],
		"show_in_graphql" => false,
	];

	register_post_type( "booking", $args );
}

add_action( 'init', 'cptui_register_my_cpts_booking' );


function cptui_register_my_cpts_hotel() {

	/**
	 * Post Type: Hotels.
	 */

	$labels = [
		"name" => __( "Hotels", "nirvana" ),
		"singular_name" => __( "Hotel", "nirvana" ),
	];

	$args = [
		"label" => __( "Hotels", "nirvana" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"rest_namespace" => "wp/v2",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => array('hotel' , 'hotels'),
		'capabilities' => array(
			'edit_post' => 'edit_hotel',
			'edit_posts' => 'edit_hotels',
			'edit_others_posts' => 'edit_other_hotels',
			'publish_posts' => 'publish_hotels',
			'read_post' => 'read_hotel',
			'read_private_posts' => 'read_private_hotels',
			'delete_post' => 'delete_hotel'
		),
		"map_meta_cap" => true,
		"hierarchical" => false,
		"can_export" => false,
		"rewrite" => [ "slug" => "hotel", "with_front" => true ],
		"query_var" => true,
		"menu_icon" => "dashicons-building",
		"supports" => [ "title", "thumbnail" ],
		"show_in_graphql" => false,
	];

	register_post_type( "hotel", $args );
}

add_action( 'init', 'cptui_register_my_cpts_hotel' );


function cptui_register_my_cpts_event_content() {

	/**
	 * Post Type: Event Content.
	 */

	$labels = [
		"name" => __( "Event Content", "nirvana" ),
		"singular_name" => __( "Events Content", "nirvana" ),
	];

	$args = [
		"label" => __( "Event Content", "nirvana" ),
		"labels" => $labels,
		"description" => "",
		"public" => false,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"rest_namespace" => "wp/v2",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => array('event' , 'events'),
		'capabilities' => array(
			'edit_post' => 'edit_event',
			'edit_posts' => 'edit_events',
			'edit_others_posts' => 'edit_other_events',
			'publish_posts' => 'publish_events',
			'read_post' => 'read_event',
			'read_private_posts' => 'read_private_events',
			'delete_post' => 'delete_event'
		),
		"map_meta_cap" => true,
		"hierarchical" => false,
		"can_export" => false,
		"rewrite" => [ "slug" => "event_content", "with_front" => false ],
		"query_var" => true,
		"menu_icon" => "dashicons-text",
		"supports" => [ "title", "editor", "thumbnail" ],
		"show_in_graphql" => false,
	];
	register_post_type( "event_content", $args );
}

add_action( 'init', 'cptui_register_my_cpts_event_content' );


/** 
 * 
 * Rewrite permalinks
 * 
*/
add_filter('post_type_link', 'events_category_permalink_structure', 10, 4);
function events_category_permalink_structure($post_link, $post, $leavename, $sample) {
    if (false !== strpos($post_link, '%event_category%')) {
        $event_category_type_term = get_the_terms($post->ID, 'event_category');
        if (!empty($event_category_type_term))
            $post_link = str_replace('%event_category%', array_pop($event_category_type_term)->
            slug, $post_link);
        else
            $post_link = str_replace('%event_category%', 'uncategorized', $post_link);
    }
    return $post_link;
}

function cptui_register_my_cpts_tour() {

	/**
	 * Post Type: Tours.
	 */

	$labels = [
		"name" => esc_html__( "Tours", "nirvana" ),
		"singular_name" => esc_html__( "Tour", "nirvana" ),
	];

	$args = [
		"label" => esc_html__( "Tours", "nirvana" ),
		"labels" => $labels,
		"description" => "",
		"public" => false,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"rest_namespace" => "wp/v2",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type"       =>  array('tour' , 'tours'),
		'capabilities' => array(
			'edit_post' => 'edit_tour',
			'edit_posts' => 'edit_tours',
			'edit_others_posts' => 'edit_other_tours',
			'publish_posts' => 'publish_tours',
			'read_post' => 'read_tour',
			'read_private_posts' => 'read_private_tours',
			'delete_post' => 'delete_tour'
		),
		"map_meta_cap" => true,
		"hierarchical" => false,
		"can_export" => false,
		"rewrite" => [ "slug" => "tour", "with_front" => false ],
		"query_var" => true,
		"menu_icon" => "dashicons-calendar-alt",
		"supports" => [ "title", "thumbnail" ],
		"show_in_graphql" => false,
	];

	register_post_type( "tour", $args );
}

add_action( 'init', 'cptui_register_my_cpts_tour' );


function cptui_register_my_cpts_tour_content() {

	/**
	 * Post Type: Tour Content.
	 */

	$labels = [
		"name" => esc_html__( "Tours Content", "nirvana" ),
		"singular_name" => esc_html__( "Tours Content", "nirvana" ),
	];

	$args = [
		"label" => esc_html__( "Tours Content", "nirvana" ),
		"labels" => $labels,
		"description" => "",
		"public" => false,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"rest_namespace" => "wp/v2",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type"       =>  array('tour' , 'tours'),
		'capabilities' => array(
			'edit_post' => 'edit_tour',
			'edit_posts' => 'edit_tours',
			'edit_others_posts' => 'edit_other_tours',
			'publish_posts' => 'publish_tours',
			'read_post' => 'read_tour',
			'read_private_posts' => 'read_private_tours',
			'delete_post' => 'delete_tour'
		),
		"map_meta_cap" => true,
		"hierarchical" => false,
		"can_export" => false,
		"rewrite" => [ "slug" => "tour_content", "with_front" => false ],
		"query_var" => true,
		"menu_icon" => "dashicons-calendar-alt",
		"supports" => [ "title" ],
		"show_in_graphql" => false,
	];

	register_post_type( "tour_content", $args );
}

add_action( 'init', 'cptui_register_my_cpts_tour_content' );


function add_tours_capabilities_to_admin() {
    $role = get_role('administrator');
    if ($role) {
        $role->add_cap('edit_tour');
        $role->add_cap('edit_tours');
        $role->add_cap('edit_other_tours');
        $role->add_cap('publish_tours');
        $role->add_cap('read_tour');
        $role->add_cap('read_private_tours');
        $role->add_cap('delete_tour');
    }
}
add_action('admin_init', 'add_tours_capabilities_to_admin');