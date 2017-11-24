<?php

class MailgunDashboard_Main {

	public function initialize() {
		$this->add_hooks();
	}

	public function add_hooks() {
		add_action( 'init', array( $this, 'load_class_autoloader' ) );

		add_action( 'init', array( $this, 'register_autoloaded_classes' ) );

		add_action( 'init', array( $this, 'initialize_classes' ) );
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
}