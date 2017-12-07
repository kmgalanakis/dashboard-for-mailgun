<?php // @codingStandardsIgnoreLine

namespace Controllers;

/**
 * "Mailgun Dashboard" plugin's settings page class.
 *
 * @category Class
 * @package  mailgun-dashboard
 * @author   Konstantinos Galanakis
 */
class Mailgun_Dashboard_Settings {

	const MAILGUN_DASHBOARD_API_KEY_OPTION_NAME = 'mailgun_api_key';

	const MAILGUN_DASHBOARD_DOMAIN_OPTION_NAME = 'mailgun_domain';

	const MAILGUN_DASHBOARD_OPTIONS_GROUP = 'mailgun_dashboard_options_group';

	/**
	 * Initialize "Mailgun Dashboard" plugin's settings page.
	 */
	public function initialize() {
		add_action( 'admin_init', array( $this, 'mailgun_dashboard_register_settings' ) );
	}

	/**
	 * Register the settings for the "Mailgun Dashboard" plugin.
	 */
	public function mailgun_dashboard_register_settings() {
		register_setting( self::MAILGUN_DASHBOARD_OPTIONS_GROUP, self::MAILGUN_DASHBOARD_API_KEY_OPTION_NAME );

		register_setting( self::MAILGUN_DASHBOARD_OPTIONS_GROUP, self::MAILGUN_DASHBOARD_DOMAIN_OPTION_NAME );

	}

	/**
	 * "Mailgun Dashboard" plugin's settings menu page callback.
	 */
	public function render_page() {
		ob_start();
		include( MAILGUN_DASHBOARD_VIEWS_PATH . '/settings.phtml' );
		echo ob_get_clean();
	}
}
