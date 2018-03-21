<?php
/**
 * Jetpack Integration
 *
 * @since 2.1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ){
	exit;
}

// Disable jetpack comment in job listing post type.
add_filter( 'jetpack_comment_form_enabled_for_job_listing', '__return_false' );
