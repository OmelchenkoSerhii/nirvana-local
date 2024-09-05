<?php
/*
=====================
	Shortcodes
=====================
*/


/*
=====================
	Shortcodes TinyMCE
=====================
*/
function true_add_mce_shortcodes() {
	if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
		return;
	}
	if ( 'true' == get_user_option( 'rich_editing' ) ) {
		add_filter( 'mce_external_plugins', 'true_add_tinymce_script' );
		add_filter( 'mce_buttons', 'true_register_mce_shortcodes' );
    }
    wp_enqueue_style('button-icon',get_stylesheet_directory_uri() .'/inc/shortcodes/shortcodes.css');
}
add_action('admin_head', 'true_add_mce_shortcodes');
 
function true_add_tinymce_script( $plugin_array ) {
    $plugin_array['true_mce_shortcodes'] = get_stylesheet_directory_uri() .'/inc/shortcodes/shortcodes.js';
	return $plugin_array;
}
 
function true_register_mce_shortcodes( $buttons ) {
	array_push( $buttons, 'true_mce_shortcodes' );
	return $buttons;
}

add_filter( 'acf/fields/wysiwyg/toolbars' , 'my_toolbars'  );
function my_toolbars( $toolbars ) {
    return array();
}


/*
=====================
	Button shortcode
=====================
*/
function shortcode_button( $atts ){
	$atts = shortcode_atts( array(
		'href' => '',
    'target' => '',
    'label' => ''
	), $atts );
  
	return '<a class="button" href="'.$atts['href'].'" target="'.$atts['target'].'"><span class="button__text">'.$atts['label'].'</span></a>';
}
add_shortcode( 'button', 'shortcode_button' );