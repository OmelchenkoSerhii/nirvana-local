<?php
/*
=====================
	Add Styles And Scripts
=====================
*/

add_action( 'wp_enqueue_scripts', 'theme_load_scripts' );
function theme_load_scripts(){
	
    wp_enqueue_script( 'jquery', 'https://code.jquery.com/jquery-3.6.0.min.js', false , false , true);
    wp_enqueue_script( 'vendors', get_template_directory_uri() . '/dist/vendors.min.js', false , false , true);
    wp_enqueue_script( 'main', get_template_directory_uri() . '/dist/main.min.js', array('jquery'), false , true);
    //send PHP variables to JS
    wp_localize_script( 'main', 'customjs_ajax_object',
        array( 
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'ajax_nonce' => wp_create_nonce( "secure_nonce_name" ),
            'site_url' => get_site_url(),
            'theme_url' => get_template_directory_uri()
        )
    );
	/*theme css*/
	wp_enqueue_style( 'main', get_template_directory_uri() . '/dist/main.min.css', array(), false , 'all');

	/*additional css*/
    wp_enqueue_style( 'slick',get_template_directory_uri() . '/js/libs/slick/slick.css');
    wp_enqueue_style( 'plyr',get_template_directory_uri() . '/js/libs/plyr/plyr.css');
}

