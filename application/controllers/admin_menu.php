<?php

class MailgunDashboard_Admin_Menu {

	protected $dashboard_page = null;
	protected $settings_page = null;

	public function initialize() {
		add_action( 'admin_menu', array( $this, 'mailgun_dashboard_admin_menu' ) );

		$this->dashboard_page = new MailgunDashboard_Dashboard();

		$this->settings_page = new MailgunDashboard_Settings();
		$this->settings_page->initialize();

	}

	public function mailgun_dashboard_admin_menu() {
		add_menu_page(
			'Mailgun&#174; dashboard',
			'Mailgun&#174; dashboard',
			'manage_options',
			'mailgun-dashboard',
			array( $this->dashboard_page, 'render_page'),
			'dashicons-chart-area',
			6
		);

		add_submenu_page(
			'mailgun-dashboard',
			'Dashboard',
			'Dashboard',
			'manage_options',
			'mailgun-dashboard',
			array( $this->dashboard_page, 'render_page')
		);

		add_submenu_page(
			'mailgun-dashboard',
			'Mailgun&#174; Dashboard Settings',
			'Settings',
			'manage_options',
			'mailgun-dashboard-settings',
			array( $this->settings_page, 'render_page')
		);
	}
}
