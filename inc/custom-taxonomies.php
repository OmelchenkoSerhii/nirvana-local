<?php
/*
	=====================
		Custom Taxonomies
	=====================	
*/


function cptui_register_my_taxes_event_country() {

	/**
	 * Taxonomy: Countries.
	 */

	$labels = [
		"name" => __( "Countries", "nirvana" ),
		"singular_name" => __( "Country", "nirvana" ),
	];

	
	$args = [
		"label" => __( "Countries", "nirvana" ),
		"labels" => $labels,
		"public" => false,
		"publicly_queryable" => false,
		"hierarchical" => true,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'event_country', 'with_front' => true, ],
		"show_admin_column" => false,
		"show_in_rest" => true,
		"show_tagcloud" => false,
		"rest_base" => "event_country",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"rest_namespace" => "wp/v2",
		"show_in_quick_edit" => false,
		"sort" => false,
		"show_in_graphql" => false,
	];
	register_taxonomy( "event_country", [ "event" ], $args );
}
add_action( 'init', 'cptui_register_my_taxes_event_country' );


function cptui_register_my_taxes_event_category() {

	/**
	 * Taxonomy: Event Categories.
	 */

	$labels = [
		"name" => __( "Event Categories", "nirvana" ),
		"singular_name" => __( "Event Category", "nirvana" ),
	];

	
	$args = [
		"label" => __( "Event Categories", "nirvana" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => true,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'event_category', 'with_front' => true, ],
		"show_admin_column" => false,
		"show_in_rest" => true,
		"show_tagcloud" => false,
		"rest_base" => "event_category",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"rest_namespace" => "wp/v2",
		"show_in_quick_edit" => false,
		"sort" => false,
		"show_in_graphql" => false,
	];
	register_taxonomy( "event_category", [ "event" ], $args );
}
add_action( 'init', 'cptui_register_my_taxes_event_category' );

function cptui_register_my_taxes_event_region() {

	/**
	 * Taxonomy: Event Regions.
	 */

	$labels = [
		"name" => __( "Event Regions", "nirvana" ),
		"singular_name" => __( "Event Region", "nirvana" ),
	];

	
	$args = [
		"label" => __( "Event Regions", "nirvana" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => true,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'event_region', 'with_front' => true, ],
		"show_admin_column" => false,
		"show_in_rest" => true,
		"show_tagcloud" => false,
		"rest_base" => "event_region",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"rest_namespace" => "wp/v2",
		"show_in_quick_edit" => false,
		"sort" => false,
		"show_in_graphql" => false,
	];
	register_taxonomy( "event_region", [ "event" ], $args );
}
add_action( 'init', 'cptui_register_my_taxes_event_region' );
