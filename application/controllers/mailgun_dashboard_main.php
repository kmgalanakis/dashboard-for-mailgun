<?php // @codingStandardsIgnoreLine

namespace Controllers;

use \Controllers\Mailgun_Dashboard_Admin_Menu;
use \Controllers\Mailgun_Dashboard_Dashboard;

/**
 * "Mailgun Dashboard" plugin's main class.
 *
 * @category Class
 * @package  mailgun-dashboard
 * @author   Konstantinos Galanakis
 */
class Mailgun_Dashboard_Main {

	const MAILGUN_API_URL = 'https://api:%s@api.mailgun.net/v3/%s';

	/**
	 * "Mailgun Dashboard" plugin's main class initialization.
	 */
	public function initialize() {
		$this->add_hooks();
	}

	/**
	 * "Mailgun Dashboard" plugin's main class hooks initialization.
	 */
	public function add_hooks() {
		add_action( 'init', array( $this, 'initialize_classes' ) );

		add_action( 'init', array( $this, 'register_assets' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	/**
	 * "Mailgun Dashboard" plugin's various secondary classes initialization.
	 */
	public function initialize_classes() {
		$mgd_admin_menu = new Mailgun_Dashboard_Admin_Menu();
		$mgd_admin_menu->initialize();
	}

	/**
	 * "Mailgun Dashboard" plugin's main class assets registration.
	 */
	public function register_assets() {
		wp_register_script(
			'mailgun_dashboard_chart_js',
			MAILGUN_DASHBOARD_URL . '/node_modules/chart.js/dist/Chart.js',
			array(),
			MAILGUN_DASHBOARD_VERSION,
			false
		);

		wp_register_script(
			'mailgun_dashboard_datatables_js',
			MAILGUN_DASHBOARD_URL . '/node_modules/datatables.net/js/jquery.dataTables.js',
			array(),
			MAILGUN_DASHBOARD_VERSION,
			true
		);

		wp_register_style(
			'mailgun_dashboard_datatables_css',
			MAILGUN_DASHBOARD_URL . '/node_modules/datatables.net-dt/css/jquery.dataTables.css',
			array(),
			MAILGUN_DASHBOARD_VERSION
		);

		wp_register_style(
			'mailgun_dashboard_css',
			MAILGUN_DASHBOARD_URL . '/res/css/mailgun_dashboard.css',
			array(),
			MAILGUN_DASHBOARD_VERSION
		);
	}

	/**
	 * "Mailgun Dashboard" plugin's main class assets enqueueing.
	 */
	public function enqueue_assets() {
		wp_enqueue_script( 'mailgun_dashboard_chart_js' );

		wp_enqueue_script( 'mailgun_dashboard_datatables_js' );

		wp_enqueue_style( 'mailgun_dashboard_datatables_css' );

		wp_enqueue_style( 'mailgun_dashboard_css' );
	}
}
