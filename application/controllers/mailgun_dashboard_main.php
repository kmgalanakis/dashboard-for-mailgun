<?php

namespace Controllers;

use \Controllers\Mailgun_Dashboard_Admin_Menu;
use \Controllers\Mailgun_Dashboard_Dashboard;

class Mailgun_Dashboard_Main {

	public function initialize() {
		$this->add_hooks();
	}

	public function add_hooks() {
		add_action( 'init', array( $this, 'initialize_classes' ) );

		add_action( 'init', array( $this, 'register_assets' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	public function initialize_classes() {
		$mgd_admin_menu = new Mailgun_Dashboard_Admin_Menu();
		$mgd_admin_menu->initialize();
	}

	public function register_assets() {
		wp_register_script(
			'mailgun_dashboard_chart_js',
			MAILGUN_DASHBOARD_URL . '/node_modules/chart.js/dist/Chart.js',
			array(),
			MAILGUN_DASHBOARD_VERSION,
			false
		);
	}

	public function enqueue_assets() {
		wp_enqueue_script( 'mailgun_dashboard_chart_js' );
	}
}