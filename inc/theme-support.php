<?php

/*
	=====================
		Theme support
	=====================	
*/



/*
	=====================
		Svg and json support
	=====================	
*/
function cc_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    $mimes['json'] = 'application/json';
    return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');

