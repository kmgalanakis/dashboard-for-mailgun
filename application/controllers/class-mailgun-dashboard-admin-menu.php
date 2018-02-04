<?php // @codingStandardsIgnoreLine

namespace Mailgun_Dashboard\Controllers;

use \Mailgun_Dashboard\Controllers\Mailgun_Dashboard_Dashboard;
use \Mailgun_Dashboard\Controllers\Mailgun_Dashboard_Settings;

/**
 * "Dashboard for Mailgun" plugin's admin menu class.
 *
 * @category Class
 * @package  dashboard-for-mailgun
 * @author   Konstantinos Galanakis
 */
class Mailgun_Dashboard_Admin_Menu {

	/**
	 * Mailgun_Dashboard_Dashboard The "Dashboard for Mailgun" dashboard page.
	 *
	 * @var Mailgun_Dashboard_Dashboard $dashboard_page
	 */
	protected $dashboard_page = null;

	/**
	 * Mailgun_Dashboard_Settings The "Dashboard for Mailgun" settings page.
	 *
	 * @var Mailgun_Dashboard_Settings $settings_page
	 */
	protected $settings_page = null;

	/**
	 * Initialize "Dashboard for Mailgun" plugin's admin menu.
	 */
	public function initialize() {
		add_action( 'admin_menu', array( $this, 'mailgun_dashboard_admin_menu' ) );

		$this->dashboard_page = new Mailgun_Dashboard_Dashboard();
		$this->dashboard_page->initialize();

		$this->settings_page = new Mailgun_Dashboard_Settings();
		$this->settings_page->initialize();
	}

	/**
	 * Build "Dashboard for Mailgun" plugin's admin menu.
	 */
	public function mailgun_dashboard_admin_menu() {
		add_menu_page(
			__( 'Dashboard for Mailgun', MAILGUN_DASHBOARD_CONTEXT ), // @codingStandardsIgnoreLine
			__( 'Dashboard for Mailgun', MAILGUN_DASHBOARD_CONTEXT ), // @codingStandardsIgnoreLine
			'manage_options',
			'dashboard-for-mailgun',
			array( $this->dashboard_page, 'render_page' ),
			'dashicons-chart-area',
			6
		);

		add_submenu_page(
			'dashboard-for-mailgun',
			__( 'Dashboard', MAILGUN_DASHBOARD_CONTEXT ), // @codingStandardsIgnoreLine
			__( 'Dashboard', MAILGUN_DASHBOARD_CONTEXT ), // @codingStandardsIgnoreLine
			'manage_options',
			'dashboard-for-mailgun',
			array( $this->dashboard_page, 'render_page' )
		);

		add_submenu_page(
			'dashboard-for-mailgun',
			__( 'Dashboard for Mailgun Settings', MAILGUN_DASHBOARD_CONTEXT ), // @codingStandardsIgnoreLine
			__( 'Settings', MAILGUN_DASHBOARD_CONTEXT ), // @codingStandardsIgnoreLine
			'manage_options',
			'dashboard-for-mailgun-settings',
			array( $this->settings_page, 'render_page' )
		);
	}
}
