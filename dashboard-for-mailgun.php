<?php
/**
 * Plugin Name: Dashboard for Mailgun
 * Plugin URI: https://wordpress.org/plugins/dashboard-for-mailgun/
 * Description: Dashboard for Mailgun on your WordPress admin.
 * Version: 0.1
 * Author: Konstantinos Galanakis
 * Author URI: https://github.com/kmgalanakis
 * Text Domain: dashboard-for-mailgun
 * Domain Path: /languages
 *
 * @package dashboard-for-mailgun
 */

if ( defined( 'MAILGUN_DASHBOARD_VERSION' ) || ! is_admin() ) {
	return;
}

define( 'MAILGUN_DASHBOARD_VERSION', '0.1' );

define( 'MAILGUN_DASHBOARD_PATH', dirname( __FILE__ ) );

define( 'MAILGUN_DASHBOARD_URL', plugin_dir_url( __FILE__ ) );

define( 'MAILGUN_DASHBOARD_VIEWS_PATH', dirname( __FILE__ ) . '/application/views' );

define( 'MAILGUN_DASHBOARD_CONTEXT', 'dashboard-for-mailgun' );

require_once MAILGUN_DASHBOARD_PATH . '/application/bootstrap.php';
