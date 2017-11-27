<?php
/*
Plugin Name: Mailgun&#174; Dashboard
Plugin URI: https://wordpress.org/plugins/mailgun-dashboard/
Description: Mailgun&#174; Dashboard for WordPress
Version: 0.1
Author: Konstantinos Galanakis
Author URI: https://github.com/kmgalanakis
Text Domain: mailgun-dashboard
Domain Path: /languages
*/

if ( defined( 'MAILGUN_DASHBOARD_VERSION' ) ) {
	return;
}

define( 'MAILGUN_DASHBOARD_VERSION', '0.1' );

define( 'MAILGUN_DASHBOARD_PATH', dirname( __FILE__ ) );

require_once MAILGUN_DASHBOARD_PATH . '/application/bootstrap.php';