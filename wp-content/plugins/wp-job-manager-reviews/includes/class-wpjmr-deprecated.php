<?php
/**
 * Deprecated Classes and Functions.
 *
 * @since 2.0.0
 *
 * @package Reviews
 * @category Deprecated
 * @author Astoundify
 */

/**
 * Listing Class
 *
 * @deprecated 2.0.0
 */
class WPJMR_Listing {

	/**
	 * Listing Class
	 *
	 * @deprecated 2.0.0
	 *
	 * @param int $listing_id Post ID.
	 * @return bool
	 */
	public function current_user_is_owner( $listing_id = null ) {
		//_deprecated_function( __FUNCTION__, '2.0.0', '' );
		$post = get_post( $listing_id );
		$current_user_id = get_current_user_id();
		if ( $current_user_id && absint( $post->post_author ) === absint( $current_user_id ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Listing average review.
	 *
	 * @deprecated 2.0.0
	 *
	 * @param int $post_id Post ID.
	 * @return int
	 */
	public function reviews_average( $post_id ) {
		//_deprecated_function( __FUNCTION__, '2.0.0', 'wpjmr_get_reviews_average' );
		return wpjmr_get_reviews_average( $post_id );
	}

	/**
	 * Review Count
	 *
	 * @deprecated 2.0.0
	 *
	 * @param int $post_id Post ID.
	 * @return int
	 */
	public function reviews_count( $post_id = null ) {
		//_deprecated_function( __FUNCTION__, '2.0.0', 'wpjmr_get_reviews_count' );
		return wpjmr_get_reviews_count( $post_id );
	}

	/**
	 * Display star
	 *
	 * @deprecated 2.0.0
	 *
	 * @param int $post_id Post ID.
	 * @return string
	 */
	public function display_stars( $post_id = null ) {
		//_deprecated_function( __FUNCTION__, '2.0.0', 'wpjmr_reviews_get_stars' );
		return wpjmr_reviews_get_stars( $post_id );
	}
}

/**
 * Review Class
 *
 * @deprecated 2.0.0
 */
class WPJMR_Review {

	/**
	 * Get reviews.
	 *
	 * @deprecated 2.0.0
	 *
	 * @param int $comment_id Comment ID.
	 * @return array
	 */
	public function get_review( $comment_id ) {
		//_deprecated_function( __FUNCTION__, '2.0.0', 'get_comment_meta' );
		return get_comment_meta( $comment_id, 'review_stars', true );
	}

	/**
	 * Get review average.
	 *
	 * @deprecated 2.0.0
	 *
	 * @param int $comment_id Comment ID.
	 * @return int
	 */
	public function get_review_average( $comment_id ) {
		//_deprecated_function( __FUNCTION__, '2.0.0', 'wpjmr_get_review_average' );
		return wpjmr_get_review_average( $comment_id );
	}

	/**
	 * Average rating for a listing.
	 *
	 * @deprecated 2.0.0
	 *
	 * @param int $post_id Post ID.
	 * @return int
	 */
	public function average_rating_listing( $post_id ) {
		//_deprecated_function( __FUNCTION__, '2.0.0', 'wpjmr_get_reviews_average' );
		return wpjmr_get_reviews_average( $post_id );
	}

	/**
	 * Review count.
	 *
	 * @deprecated 2.0.0
	 *
	 * @param int $post_id Post ID.
	 * @return int
	 */
	public function review_count( $post_id ) {
		//_deprecated_function( __FUNCTION__, '2.0.0', 'wpjmr_get_reviews_count' );
		return wpjmr_get_reviews_count( $post_id );
	}

	/**
	 * Get stars
	 *
	 * @deprecated 2.0.0
	 *
	 * @param int $post_id Post ID.
	 * @return string
	 */
	public function get_stars( $post_id ) {
		//_deprecated_function( __FUNCTION__, '2.0.0', 'wpjmr_reviews_get_stars' );
		return wpjmr_reviews_get_stars( $post_id );
	}

	/**
	 * Average rating review.
	 *
	 * @deprecated 2.0.0
	 *
	 * @param int $post_id Post ID.
	 * @return int
	 */
	public function average_rating_review( $post_id ) {
		//_deprecated_function( __FUNCTION__, '2.0.0', 'wpjmr_get_reviews_average' );
		return wpjmr_get_reviews_average( $post_id );
	}
}

/**
 * Reviews Class
 *
 * @deprecated 2.0.0
 */
class WPJMR_Reviews {

	/**
	 * All ratings data,
	 *
	 * @deprecated 2.0.0
	 *
	 * @param int $post_id Post ID.
	 * @return array
	 */
	public function get_reviews_by_id( $post_id = '' ) {
		//_deprecated_function( __FUNCTION__, '2.0.0', 'wpjmr_get_review_average' );
		$reviews = get_transient( 'wpjmr_reviews_' . $post_id );
		if ( false !== $reviews ) {
			return $reviews;
		}
		$reviews = array();
		$comments = wpjmr_get_reviews( $post_id );
		foreach ($comments as $comment_id => $review_average ){
			$reviews[] = get_comment( $comment_id );
		}
		set_transient( 'wpjmr_reviews_' . $post_id, $reviews, 12 * HOUR_IN_SECONDS );
		return $reviews;
	}
}
