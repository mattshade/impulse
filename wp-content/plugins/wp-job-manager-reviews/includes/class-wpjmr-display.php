<?php
/**
 * Display review stars in comment.
 *
 * @since 2.0.0
 *
 * @package Reviews
 * @category Core
 * @author Astoundify
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Display Review.
 * Handles displaying review on the front end.
 *
 * @since 2.0.0
 */
class WPJMR_Display {

	/**
	 * Constructor Class.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		// Appends ratings to comment body.
		add_filter( 'get_comment_text', array( $this, 'review_comment_text' ), 10, 3 );
		add_filter( 'get_comment_text', array( $this, 'display_review_gallery' ), 11, 3 );
	}

	/**
	 * Add stars to comment.
	 * Add the stars based on categories to default comment text.
	 *
	 * @since 1.0.0
	 *
	 * @param string $content Text of the comment.
	 * @param object $comment The comment object.
	 * @param array  $args    An array of arguments.
	 * @return string Comment content.
	 */
	public function review_comment_text( $content, $comment, $args ) {
		// Check post type & only display in front end.
		if ( 'job_listing' !== get_post_type( $comment->comment_post_ID ) || ! is_singular( 'job_listing' ) ) {
			return $content;
		}

		// Bail if not top level comment.
		if ( 0 !== intval( $comment->comment_parent ) ) {
			return $content;
		}

		// Get comment ID.
		$comment_id = $comment->comment_ID;

		// Maybe migrate old data.
		wpjmr_maybe_migrate_data( $comment_id );

		// Get reviews: Array/list of ratings with slug and rating.
		$ratings = get_comment_meta( $comment_id, 'review_stars', true );
		$review_average = wpjmr_sanitize_number( get_comment_meta( $comment_id, 'review_average', true ) );
		if ( ! $ratings || ! is_array( $ratings ) || ! $review_average ) {
			return $content;
		}

		// Display rating and json markup before comment text.
		$stars = wpjmr_review_get_stars( $comment_id );
		$json  = sprintf( '<script type="application/ld+json">%s</script>', wp_json_encode( $this->json_ld( $comment_id, $content, $review_average ) ) );
		return $stars . $json . $content;

		 

	}

//print_r(wp_json_encode($end_rating_data));
	/**
	 * Display Review Gallery
	 *
	 * @since 2.0.0
	 *
	 * @param string $content Text of the comment.
	 * @param object $comment The comment object.
	 * @param array  $args    An array of arguments.
	 * @return string Comment content.
	 */
	public function display_review_gallery( $content, $comment, $args ) {
		return $content . wpjmr_get_gallery( $comment->comment_ID );
	}

	/**
	 * Return reivew data in JSON-LD format.
	 *
	 * @since 1.9.0
	 *
	 * @param int $comment_id Review ID.
	 * @param string $content Comment text.
	 * @param int $review_average Review average.
	 * @return array Review data in JSON-LD format.
	 */
	public function json_ld( $comment_id, $content, $review_average ) {
		$markup = array();

		$markup['@type']         = 'Review';
		$markup['url']           = get_comment_link( $comment_id );
		$markup['datePublished'] = get_comment_date( 'c', $comment_id );
		$markup['description']   = $content;
		$markup['reviewRating']  = array(
			'@type'       => 'rating',
			'ratingValue' => $review_average,
		);
		$markup['author']        = array(
			'@type'       => 'Person',
			'name'        => get_comment_author( $comment_id ),
		);

		return $markup;
	}



}
