<?php
/**
 * Template part for requiring popups
 */

$popups_directory = get_template_directory() . '/template-parts/api/popups/popup-';

require_once $popups_directory . 'special-flight.php';
require_once $popups_directory . 'select-flight.php';
