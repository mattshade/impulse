<?php
/**
 * Job Listing: Comments
 *
 * @since Listify 1.0.0
 */
class Listify_Widget_Listing_Comments extends Listify_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->widget_description = __( 'Display the listing comments.', 'listify' );
		$this->widget_id          = 'listify_widget_panel_listing_comments';
		$this->widget_name        = __( 'Listify - Listing: Reviews', 'listify' );
		$this->widget_areas       = array( 'single-job_listing-widget-area', 'single-job_listing' );
		$this->widget_notice      = __( 'Add this widget only in "Single Listing" widget areas.' );
		$this->settings           = array(
			'title' => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Title:', 'listify' ),
			),
			'icon' => array(
				'type'    => 'text',
				'std'     => 'ion-reply',
				'label'   => '<a href="http://ionicons.com/">' . __( 'Icon Class:', 'listify' ) . '</a>',
			),
		);
		parent::__construct();
	}

	function widget( $args, $instance ) {
		global $job_preview, $post;

		if ( ! is_singular( 'job_listing' ) && ! $job_preview ) {
			echo $this->widget_areas_notice(); // WPCS: XSS ok.
			return false;
		}

		if ( 'publish' != $post->post_status ) {
			return;
		}

		global $job_manager, $comments_widget_title, $comments_widget_icon, $comments_widget_before_title, $comments_widget_after_title;

		extract( $args );

		$comments_widget_title = apply_filters( 'widget_title', isset( $instance['title'] ) ? $instance['title'] : '', $instance, $this->id_base );
		$icon = isset( $instance['icon'] ) ? $instance['icon'] : null;

		if ( $icon ) {
			if ( strpos( $icon, 'ion-' ) !== false ) {
				$comments_widget_icon = $icon;
			} else {
				$comments_widget_icon = 'ion-' . $icon;
			}
		}

		ob_start();
// if (is_user_logged_in()){
// 		echo $before_widget;
//
// echo do_shortcode('IUser is logged in. [wcm_restrict] allo [/wcm_restrict] This content is availale for everyone.');
// echo do_shortcode('[wcm_nonmember]Whoops, this video is for members only. If you have a membership, please log in. If not, you can definitely get access! <a href="/membership/">Purchase a membership here</a>.[/wcm_nonmember]');
//
// //comments_template()
//
// 		echo $after_widget;
// 	}else{
		echo $before_widget;
		if( wc_memberships_is_user_active_member( null, 'monthly' ) || wc_memberships_is_user_active_member( null, 'annual' ) ) {
	// do stuff for the current user with active "premium_gold" plan (even in trial period)
	comments_template();
}else{
	echo '<h3>Wuh oh! It looks like you are not a member.</h3><a href="/shop/" type="submit" class="single_add_to_cart_button button alt">Choose A Plan</a>';
}

		echo $after_widget;

		echo apply_filters( $this->widget_id, ob_get_clean() );
	}
}
