<?php
/**
 * Style display switcher.
 *
 * Pre 1.8.0 users could switch between a grid and a list
 * display style. List style has been soft deprecated since 1.8.0
 *
 * @uses $wp_customize
 * @since 1.5.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! apply_filters( 'listify_listings_display_style_switcher', false ) ) {
	return;
}

$wp_customize->add_setting( 'listing-archive-display-style', array(
	'default' => 'grid'
) );

$wp_customize->add_control( 'listing-archive-display-style', array(
	'label' => __( 'Default Card Layout', 'listify' ),
	'type' => 'select',
	'choices' => array(
		'grid' => __( 'Grid', 'listify' ),
		'list' => __( 'List', 'listify' )
	),
	'priority' => 10,
	'section' => 'search-results'
) );
