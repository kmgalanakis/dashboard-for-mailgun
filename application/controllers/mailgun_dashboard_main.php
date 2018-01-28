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

		add_action( 'init', array( $this, 'delete_mailgun_settings_source_option' ) );
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
			'mailgun_dashboard_chartjs',
			MAILGUN_DASHBOARD_URL . '/assets/js/third-party/Chart.js',
			array( 'mailgun_dashboard_moment' ),
			MAILGUN_DASHBOARD_VERSION,
			false
		);

		wp_register_script(
			'mailgun_dashboard_bootstrap',
			MAILGUN_DASHBOARD_URL . '/assets/js/third-party/bootstrap.js',
			array(),
			MAILGUN_DASHBOARD_VERSION,
			false
		);

		wp_register_script(
			'mailgun_dashboard_moment',
			MAILGUN_DASHBOARD_URL . '/assets/js/third-party/moment.js',
			array(),
			MAILGUN_DASHBOARD_VERSION,
			false
		);

		wp_register_script(
			'mailgun_dashboard_daterangepicker',
			MAILGUN_DASHBOARD_URL . '/assets/js/third-party/daterangepicker.js',
			array( 'jquery', 'mailgun_dashboard_bootstrap', 'mailgun_dashboard_moment' ),
			MAILGUN_DASHBOARD_VERSION,
			false
		);

		wp_register_script(
			'mailgun_dashboard_datatables',
			MAILGUN_DASHBOARD_URL . '/assets/js/third-party/jquery.dataTables.js',
			array( 'jquery' ),
			MAILGUN_DASHBOARD_VERSION,
			true
		);

		wp_register_style(
			'mailgun_dashboard_datatables_css',
			MAILGUN_DASHBOARD_URL . 'assets/css/third-party/jquery.dataTables.css',
			array(),
			MAILGUN_DASHBOARD_VERSION
		);

		wp_register_style(
			'mailgun_dashboard_bootstrap_css',
			MAILGUN_DASHBOARD_URL . 'assets/css/third-party/bootstrap.css',
			array(),
			MAILGUN_DASHBOARD_VERSION
		);

		wp_register_style(
			'mailgun_dashboard_fontawesome_css',
			MAILGUN_DASHBOARD_URL . 'assets/css/third-party/font-awesome.min.css',
			array(),
			MAILGUN_DASHBOARD_VERSION
		);

		wp_register_style(
			'mailgun_dashboard_daterangepicker_css',
			MAILGUN_DASHBOARD_URL . '/assets/css/third-party/daterangepicker.css',
			array( 'mailgun_dashboard_bootstrap_css', 'mailgun_dashboard_fontawesome_css' ),
			MAILGUN_DASHBOARD_VERSION
		);

		wp_register_style(
			'mailgun_dashboard_css',
			MAILGUN_DASHBOARD_URL . '/assets/css/mailgun_dashboard.css',
			array( 'mailgun_dashboard_datatables_css', 'mailgun_dashboard_daterangepicker_css' ),
			MAILGUN_DASHBOARD_VERSION
		);
	}

	/**
	 * "Mailgun Dashboard" plugin's method to remove the Mailgun source settings option from the database when
	 * the Mailgun plugin is not activated.
	 */
	public function delete_mailgun_settings_source_option() {
		if ( ! class_exists( 'Mailgun' ) ) {
			$mailgun_dashboard_settings = get_option( Mailgun_Dashboard_Settings::MAILGUN_DASHBOARD_OPTION_NAME );
			unset( $mailgun_dashboard_settings[ Mailgun_Dashboard_Settings::MAILGUN_DASHBOARD_SETTINGS_SOURCE_NAME ] );
			update_option( Mailgun_Dashboard_Settings::MAILGUN_DASHBOARD_OPTION_NAME, $mailgun_dashboard_settings );
		}
	}
}
