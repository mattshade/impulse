<?php
/**
 * Job dashboard shortcode content if user is not logged in.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/job-dashboard-login.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @author      Automattic
 * @package     WP Job Manager
 * @category    Template
 * @version     1.19.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div id="job-manager-job-dashboard">

	<p class="account-sign-in"><?php _e( 'You need to be signed in to manage your listings.', 'wp-job-manager' ); ?> <a class="button" href="https://www.impulsesurvey.com/myaccount"><?php _e( 'Sign in', 'wp-job-manager' ); ?></a></p>

</div>
