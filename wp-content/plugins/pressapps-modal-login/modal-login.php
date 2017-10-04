<?php
/*
Plugin Name: PA Modal Login
Plugin URI: http://pressapps.co/plugins/modal-login
Description: Modal login form with Google reCAPTCHA, redirect, email and styling options.
Version: 1.5.0
Author: PressApps
Author URI: http://www.pressapps.co
Text Domain: pressapps
License: GPLv2 or later

Copyright 2013 PressApps
*/

/*-----------------------------------------------------------------------------------*/
/* Return option page data */
/*-----------------------------------------------------------------------------------*/
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])){
    if($_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
        $_SERVER['HTTPS'] = 'on';
}


$paml_options = get_option( 'paml_options' );

/*-----------------------------------------------------------------------------------*/
/* Define Constants */
/*-----------------------------------------------------------------------------------*/

define( 'PAML_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'PAML_PLUGIN_URL', plugins_url("", __FILE__) );

define( 'PAML_PLUGIN_INCLUDES_DIR', PAML_PLUGIN_DIR . "/includes/" );
define( 'PAML_PLUGIN_INCLUDES_URL', PAML_PLUGIN_URL . "/includes/" );

define( 'PAML_PLUGIN_ASSETS_DIR', PAML_PLUGIN_DIR . "/assets/" );
define( 'PAML_PLUGIN_ASSETS_URL', PAML_PLUGIN_URL . "/assets/" );

/*-----------------------------------------------------------------------------------*/
/* Load text domain */
/*-----------------------------------------------------------------------------------*/

function paml_load_textdomain() {
	load_plugin_textdomain( 'pressapps', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
}
add_action( 'plugins_loaded', 'paml_load_textdomain' );

/*-----------------------------------------------------------------------------------*/
/* Load primary class */
/*-----------------------------------------------------------------------------------*/

require_once PAML_PLUGIN_INCLUDES_DIR. 'modal-login-class.php';

/*-----------------------------------------------------------------------------------*/
/* Load widget class */
/*-----------------------------------------------------------------------------------*/

require_once PAML_PLUGIN_INCLUDES_DIR . 'widget/modal-login-widget.php';

/*-----------------------------------------------------------------------------------*/
/* Load the admin page */
/*-----------------------------------------------------------------------------------*/

if ( is_admin() ) {
	require_once PAML_PLUGIN_INCLUDES_DIR . 'admin.php';
}

/*-----------------------------------------------------------------------------------*/
/* Login / logout links */
/*-----------------------------------------------------------------------------------*/

function add_modal_login_link( $login_text = 'Login', $logout_text = 'Logout', $show_admin = false ) {
	global $paml_class;

	if ( isset( $paml_class ) ) {
		echo $paml_class->modal_login_btn( $login_text, $logout_text, $show_admin );
	} else {
		echo __( 'Error: Modal Login class failed to load', 'pressapps' );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Register link */
/*-----------------------------------------------------------------------------------*/

function add_modal_register_link( $register_text = 'Register', $logged_in_text = 'You are alredy logged in' ) {
	global $paml_class;

	if ( isset( $paml_class ) ) {
		echo $paml_class->modal_register_btn( $register_text, $logged_in_text );
	} else {
		echo __( 'Error: Modal Login class failed to load', 'pressapps' );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Shortcode function  */
/*-----------------------------------------------------------------------------------*/
function modal_login( $params = array() ) {
	$params_str = '';
	foreach( $params as $parameter => $value ) {
		if( $value ) {
			$params_str .= sprintf( ' %s="%s"', $parameter, $value);
		}
	}
	echo do_shortcode( "[modal_login $params_str]" );
}

/*-----------------------------------------------------------------------------------*/
/* Load modal login class */
/*-----------------------------------------------------------------------------------*/

if ( class_exists( 'PAML_Class' ) ) {
	$paml_class = new PAML_Class;
}
