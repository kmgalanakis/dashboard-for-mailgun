<?php // @codingStandardsIgnoreLine

namespace Controllers;

use \Controllers\Mailgun_Dashboard_Dashboard;
use \Controllers\Mailgun_Dashboard_Settings;

/**
 * "Mailgun Dashboard" plugin's admin menu class.
 *
 * @category Class
 * @package  mailgun-dashboard
 * @author   Konstantinos Galanakis
 */
class Mailgun_Dashboard_Admin_Menu {

	/**
	 * Mailgun_Dashboard_Dashboard The "Mailgun Dashboard" dashboard page.
	 *
	 * @var Mailgun_Dashboard_Dashboard $dashboard_page
	 */
	protected $dashboard_page = null;

	/**
	 * Mailgun_Dashboard_Settings The "Mailgun Dashboard" settings page.
	 *
	 * @var Mailgun_Dashboard_Settings $settings_page
	 */
	protected $settings_page = null;

	/**
	 * Initialize "Mailgun Dashboard" plugin's admin menu.
	 */
	public function initialize() {
		add_action( 'admin_menu', array( $this, 'mailgun_dashboard_admin_menu' ) );

		$this->dashboard_page = new Mailgun_Dashboard_Dashboard();
		$this->dashboard_page->initialize();

		$this->settings_page = new Mailgun_Dashboard_Settings();

		$this->settings_page->initialize();
	}

	/**
	 * Build "Mailgun Dashboard" plugin's admin menu.
	 */
	public function mailgun_dashboard_admin_menu() {
		add_menu_page(
			__( 'Mailgun&#174; dashboard', MAILGUN_DASHBOARD_CONTEXT ), // @codingStandardsIgnoreLine
			__( 'Mailgun&#174; dashboard', MAILGUN_DASHBOARD_CONTEXT ), // @codingStandardsIgnoreLine
			'manage_options',
			'mailgun-dashboard',
			array( $this->dashboard_page, 'render_page' ),
			'dashicons-chart-area',
			6
		);

		add_submenu_page(
			'mailgun-dashboard',
			__( 'Dashboard', MAILGUN_DASHBOARD_CONTEXT ), // @codingStandardsIgnoreLine
			__( 'Dashboard', MAILGUN_DASHBOARD_CONTEXT ), // @codingStandardsIgnoreLine
			'manage_options',
			'mailgun-dashboard',
			array( $this->dashboard_page, 'render_page' )
		);

		add_submenu_page(
			'mailgun-dashboard',
			__( 'Mailgun&#174; Dashboard Settings', MAILGUN_DASHBOARD_CONTEXT ), // @codingStandardsIgnoreLine
			__( 'Settings', MAILGUN_DASHBOARD_CONTEXT ), // @codingStandardsIgnoreLine
			'manage_options',
			'mailgun-dashboard-settings',
			array( $this->settings_page, 'render_page' )
		);
	}
}
