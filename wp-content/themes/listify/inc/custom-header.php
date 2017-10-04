<?php
/**
 * Sample implementation of the Custom Header feature
 * http://codex.wordpress.org/Custom_Headers
 *
 * @package Listify
 */

/**
 * Setup the WordPress core custom header feature.
 *
 * @uses listify_header_style()
 * @uses listify_admin_header_style()
 * @uses listify_admin_header_image()
 *
 * @package Listify
 */
function listify_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'listify_custom_header_args', array(
		'video' => true,
		'default-image'          => '',
		'default-text-color'     => 'fff',
		'header-text'            => true,
		'width'                  => 100,
		'height'                 => 35,
		'flex-height'            => true,
		'flex-width'             => true,
		'wp-head-callback'       => '__return_true',
	) ) );
}
add_action( 'after_setup_theme', 'listify_custom_header_setup' );
