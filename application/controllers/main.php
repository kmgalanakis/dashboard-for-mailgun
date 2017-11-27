<?php

class MailgunDashboard_Main {

	public function initialize() {
		$this->add_hooks();
	}

	public function add_hooks() {
		add_action( 'init', array( $this, 'load_class_autoloader' ) );

		add_action( 'init', array( $this, 'register_autoloaded_classes' ) );

		add_action( 'init', array( $this, 'initialize_classes' ) );

		add_action( 'init', array( $this, 'register_assets' ) );

		add_action( 'init', array( $this, 'enqueue_assets' ) );
	}

	public function load_class_autoloader() {
		require_once MAILGUN_DASHBOARD_PATH. '/application/controllers/class_autoloader.php';
		$mgd_autoloader = new MailgunDashboard_Autoloader();
		$mgd_autoloader->initialize();
	}

	public function initialize_classes() {
		$mgd_admin_menu = new MailgunDashboard_Admin_Menu();
		$mgd_admin_menu->initialize();
	}

	public function register_autoloaded_classes() {
		$classmap = include( MAILGUN_DASHBOARD_PATH . '/application/autoload_classmap.php' );

		do_action( 'mailgun_dashboard_register_classmap', $classmap );
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