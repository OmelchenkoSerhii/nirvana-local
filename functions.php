<?php
require_once 'vendor/autoload.php';

require get_template_directory() . '/inc/theme-setup.php';
require get_template_directory() . '/inc/theme-support.php';
require get_template_directory() . '/inc/theme-cleanup.php';
require get_template_directory() . '/inc/theme-enqueue.php';

require get_template_directory() . '/inc/custom-post-types.php';
require get_template_directory() . '/inc/custom-taxonomies.php';

require get_template_directory() . '/inc/acf.php';
require get_template_directory() . '/inc/theme-functions.php';
require get_template_directory() . '/inc/theme-components.php';
require get_template_directory() . '/inc/theme-shortcodes.php';
require get_template_directory() . '/inc/theme-api.php';
require get_template_directory() . '/inc/theme-filters.php';
require get_template_directory() . '/inc/theme-registration.php';
require get_template_directory() . '/inc/celtic/celtic.php';
