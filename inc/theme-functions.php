<?php
/*
	=====================
		Theme functions
	=====================
*/



/*
	=====================
		Limit excerpt length function
	=====================
*/
function excerpt( $limit, $post_id = -1 ) {
	if ( -1 === $post_id ) :
		$excerpt = explode( ' ', get_the_excerpt(), $limit );
	else :
		$excerpt = explode( ' ', get_the_excerpt( $post_id ), $limit );
	endif;
	if ( count( $excerpt ) >= $limit ) {
		array_pop( $excerpt );
		$excerpt = implode( ' ', $excerpt ) . '...';
	} else {
		$excerpt = implode( ' ', $excerpt );
	}
	$excerpt = preg_replace( '`[[^]]*]`', '', $excerpt );
	return $excerpt;
}

function excerpt_str( $str , $limit ) {
	$str = strip_tags($str);
	$excerpt = explode( ' ', $str  , $limit );
	if ( count( $excerpt ) >= $limit ) {
		array_pop( $excerpt );
		$excerpt = implode( ' ', $excerpt ) . '...';
	} else {
		$excerpt = implode( ' ', $excerpt );
	}
	$excerpt = preg_replace( '`[[^]]*]`', '', $excerpt );
	return $excerpt;
}


/*
	=====================
		Don't scale down large images
	=====================
*/
add_filter( 'big_image_size_threshold', '__return_false' );


/*
	=====================
		Header nav menu
	=====================
*/
// Nav arrows
function filter_walker_nav_menu_start_el( $item_output, $item, $depth, $args ) {
	if ( ( in_array( 'menu-item-has-children', $item->classes ) || ( 'Events' === $item->title ) ) ) {
		return '<div class="menu-item__parent">' . $item_output . '</div>';
	}

	return $item_output;
}
//add_filter( 'walker_nav_menu_start_el', 'filter_walker_nav_menu_start_el', 10, 4 );


class EventsSubMenu extends Walker_Nav_Menu {
	function end_el( &$output, $item, $depth = 0, $args = array() ) {
		if ( 'Events' === $item->title ) {
			$output .= '<ul class="sub-menu">';

				$event_categories =  get_terms( array(
					'taxonomy' => 'event_category',
					'hide_empty' => true,
				) );
				
				if($event_categories):
					foreach($event_categories as $term):
						$logo = get_field('nav_logo', $term);
						$link = get_term_link($term->slug, 'event_category');
						$name = $term->name;
						if($logo):
							$output .= sprintf(
								'<li class="menu-item" id="menu-item-%s"><a href="%s">%s</a></li>',
								sanitize_key( $name ),
								esc_url( $link ),
								sprintf(
									'<img src="%s" alt="%s">',
									esc_url( $logo['url'] ),
									esc_attr( $logo['alt'] )
								),
							);
						else:
							$output .= sprintf(
								'<li class="menu-item" id="menu-item-%s"><a href="%s">%s</a></li>',
								sanitize_key( $name ),
								esc_url( $link ),
								sanitize_key( $name ),
							);
						endif;
					endforeach; 
				endif; 

			$output .= '</ul>';
		}
		$output .= "</li>\n";
	}
}

/*
	=====================
		Move Yoast to bottom
	=====================
*/
function yoasttobottom() {
	return 'low';
}
add_filter( 'wpseo_metabox_prio', 'yoasttobottom' );


/*
	=====================
		Remove Gutenberg Block Library CSS from loading on the frontend
	=====================
*/
function smartwp_remove_wp_block_library_css() {
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
}
add_action( 'wp_enqueue_scripts', 'smartwp_remove_wp_block_library_css' );


/*
	=====================
		Get width and height from SVG files
	=====================
*/
function fix_wp_get_attachment_image_svg( $image, $attachment_id, $size, $icon ) {
	if ( is_array( $image ) && preg_match( '/\.svg$/i', $image[0] ) && $image[1] <= 1 ) {
		if ( is_array( $size ) ) {
			$image[1] = $size[0];
			$image[2] = $size[1];
		} elseif ( ( $xml = simplexml_load_file( $image[0] ) ) !== false ) {
			$attr     = $xml->attributes();
			$viewbox  = explode( ' ', $attr->viewBox );
			$image[1] = isset( $attr->width ) && preg_match( '/\d+/', $attr->width, $value ) ? (int) $value[0] : ( count( $viewbox ) == 4 ? (int) $viewbox[2] : null );
			$image[2] = isset( $attr->height ) && preg_match( '/\d+/', $attr->height, $value ) ? (int) $value[0] : ( count( $viewbox ) == 4 ? (int) $viewbox[3] : null );
		} else {
			$image[1] = $image[2] = null;
		}
	}
	return $image;
}
add_filter( 'wp_get_attachment_image_src', 'fix_wp_get_attachment_image_svg', 10, 4 );


/*
	=====================
		Get SVG file content
	=====================
*/
function get_inline_svg( $name ) {
	if ( $name ) :
		return file_get_contents( esc_url( get_template_directory() . '/assets/images/' . $name ) );
	endif;
	return '';
}



/**
 *
 * WP Query by title
 *
 */

function posts_by_title( $where, $query ) {
	global $wpdb;

	$title = $query->get( 'title_equals' );

	if ( $title ) {
		$where .= " AND $wpdb->posts.post_title = '$title'";
	}

	return $where;
}
add_filter( 'posts_where', 'posts_by_title', 10, 2 );

/**
 * Get Link On Event Book
 */
function the_link_on_event_book( $post_id = 0 ) {
	if ( 0 === $post_id ) {
		global $post;
		$post_id = $post->ID;
	}

	$event_id = get_field( 'event_code', $post_id );

	$link = get_field('api_book_page', 'option').'?eventid='.$event_id;

	echo esc_url( $link );
}

/**
 * Get Event Country By Taxonomy Country
 */
function get_event_country_by_taxonomy( $post_id = 0 ) {
	if ( 0 === $post_id ) {
		global $post;
		$post_id = $post->ID;
	}

	$country_name = wp_get_post_terms( $post_id, 'event_country', array( 'fields' => 'names' ) );

	return $country_name[0];
}



/**
 * 
 * Maintenance page
 * 
 */

function redirect_to_login_page() {
	$maintenance_url = get_field('maintenance_page' , 'option');
	$protocol = ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
	$current_url = $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	
    if ( ! is_user_logged_in() && get_field('enable_maintenance_mode' , 'option') && $maintenance_url && $current_url !== $maintenance_url && $current_url !== home_url( '/login/' ) ) {
        $redirect_url = $maintenance_url;
        wp_redirect( $redirect_url );
        exit;
    }
}
add_action( 'template_redirect', 'redirect_to_login_page' );


/**
 * 
 * Calculate distance between 2 map points
 */
function calculateMapDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371) {
	// Convert from degrees to radians
	$latFrom = deg2rad($latitudeFrom);
	$lonFrom = deg2rad($longitudeFrom);
	$latTo = deg2rad($latitudeTo);
	$lonTo = deg2rad($longitudeTo);
  
	$latDelta = $latTo - $latFrom;
	$lonDelta = $lonTo - $lonFrom;
  
	$angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
	  cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
	return $angle * $earthRadius;
  }