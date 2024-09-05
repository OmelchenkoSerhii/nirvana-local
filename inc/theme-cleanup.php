<?php
/*
	=====================
		Clean up
	=====================	
*/


/*
	=====================
		Clean up the WordPress head
	=====================	
*/

// remove header links
//add_action('init', 'wp_head_cleanup');
function wp_head_cleanup() {
    remove_action( 'wp_head', 'feed_links_extra', 3 );                      // Category Feeds
    remove_action( 'wp_head', 'feed_links', 2 );                            // Post and Comment Feeds
    remove_action( 'wp_head', 'rsd_link' );                                 // EditURI link
    remove_action( 'wp_head', 'wlwmanifest_link' );                         // Windows Live Writer
    remove_action( 'wp_head', 'index_rel_link' );                           // index link
    remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );              // previous link
    remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );               // start link
    remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );   // Links for Adjacent Posts
    remove_action( 'wp_head', 'wp_generator' );                             // WP version
    remove_action( 'wp_head', 'wp_shortlink_wp_head');                      // Remove shortlink
    remove_action('wp_head', 'rest_output_link_wp_head', 10);               // Disable Link header for the REST API
    remove_action('template_redirect', 'rest_output_link_header', 11, 0);   // Disable Link header for the REST API
    if (!is_admin()) {
        //wp_deregister_script('jquery');                                     // De-Register jQuery
    }
}

// remove WP version from RSS
add_filter('the_generator', 'wp_rss_version');
function wp_rss_version() { return ''; }

// remove query strings from all resources
function crunchify_cleanup_query_string( $src ){ 
	$parts = explode( '?', $src ); 
	return $parts[0]; 
} 
// add_filter( 'script_loader_src', 'crunchify_cleanup_query_string', 15, 1 ); 
// add_filter( 'style_loader_src', 'crunchify_cleanup_query_string', 15, 1 );


add_action('admin_init', function () {
    // Redirect any user trying to access comments page
    global $pagenow;
    
    if ($pagenow === 'edit-comments.php') {
        wp_redirect(admin_url());
        exit;
    }

    // Remove comments metabox from dashboard
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');

    // Disable support for comments and trackbacks in post types
    foreach (get_post_types() as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
});

// Close comments on the front-end
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);

// Hide existing comments
add_filter('comments_array', '__return_empty_array', 10, 2);

// Remove comments page in menu
add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
});

// Remove comments links from admin bar
add_action('init', function () {
    if (is_admin_bar_showing()) {
        remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
    }
});
