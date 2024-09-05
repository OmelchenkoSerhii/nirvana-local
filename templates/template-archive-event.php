<?php
/**
 * Template Name: Archive All Events
 *
 * @package nirvana
 */

get_header();

// Get Hero Banner Template Part.
get_template_part( '/template-parts/hero-banner' );

// Get All Events Grid List Template Part.
get_template_part( '/template-parts/event/archive-event', 'grid-list' );

get_footer();
