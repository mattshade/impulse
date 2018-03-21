<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Polylang Support.
 *
 * @since 2.0.0
 */
class WPJMR_Polylang {

	/**
	 * Constructor Class.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {

		// Register polylang string.
		add_action( 'init', array( $this, 'register_strings' ), 5 );

		// Make category label translateable.
		add_filter( 'wpjmr_category_label', array( $this, 'translate_category_label' ) );
	}

	/**
	 * Register Polylang String.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function register_strings() {
		// Var.
		$strings = array();

		// Add Categories as translateable strings.
		$categories = wpjmr_get_categories();
		foreach ( $categories as $category ) {
			$strings[] = $category;
		}

		// Make filterable.
		$strings = apply_filters( 'wpjmr_ppl_strings', $strings );

		// Register each strings.
		foreach ( $strings as $string ) {
			pll_register_string( __( 'WP Job Manager Reviews', 'wp-job-manager-reviews' ), $string, __( 'WP Job Manager Reviews', 'wp-job-manager-reviews' ) );
		}
	}

	/**
	 * Translate category label.
	 *
	 * @since 2.0.0
	 *
	 * @param string $category Category label.
	 * @return string
	 */
	public function translate_category_label( $category ) {
		return pll__( $category );
	}

}

