<?php
/**
 * Display favorites.
 *
 * @uses $wp_customize
 * @since 1.10.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$wp_customize->add_setting( 'listing-card-display-bookmarks', array(
	'default' => true
) );

$wp_customize->add_control( 'listing-card-display-bookmarks', array(
	'label' => __( 'Display favorites', 'listify' ),
	'type' => 'checkbox',
	'priority' => 5.4,
	'section' => 'search-results'
) );
