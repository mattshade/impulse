<?php
/**
 * Output favorites colors
 *
 * @since 1.8.0
 * @package Customizer
 */
class 
	Listify_Customizer_OutputCSS_FavoritesColors
extends
	Listify_Customizer_OutputCSS {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * Add items to the CSS object that will be built and output.
	 *
	 * @since 1.5.0
	 * @return void
	 */
    public function output() {
        $heart = listify_theme_color( 'color-listing-heart' );

		Listify_Customizer_CSS::add( array(
			'selectors' => array( 
				'.wp-job-manager-bookmarks-form .bookmark-notice.bookmarked:before'
			),
			'declarations' => array(
				'color' => esc_attr( $heart )
			)
		) );
	}

}

new Listify_Customizer_OutputCSS_FavoritesColors();
