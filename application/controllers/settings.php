<?php

class MailgunDashboard_Settings {

	const MAILGUN_DASHBOARD_API_KEY_OPTION_NAME = 'mailgun_api_key';
	const MAILGUN_DASHBOARD_DOMAIN_OPTION_NAME = 'mailgun_domain';

	const MAILGUN_DASHBOARD_OPTIONS_GROUP = 'mailgun_dashboard_options_group';

	public function initialize() {
		add_action( 'admin_init', array( $this, 'mailgun_dashboard_register_settings' ) );
	}

	public function mailgun_dashboard_register_settings() {

//		add_option( self::MAILGUN_DASHBOARD_API_KEY_OPTION_NAME, '' );
//
//		add_option( self::MAILGUN_DASHBOARD_DOMAIN_OPTION_NAME, '' );

		register_setting( self::MAILGUN_DASHBOARD_OPTIONS_GROUP, self::MAILGUN_DASHBOARD_API_KEY_OPTION_NAME );

		register_setting( self::MAILGUN_DASHBOARD_OPTIONS_GROUP, self::MAILGUN_DASHBOARD_DOMAIN_OPTION_NAME );

	}

	public function render_page() {
		ob_start();
		include( MAILGUN_DASHBOARD_VIEWS_PATH . '/settings.phtml' );
		echo ob_get_clean();
	}
}
