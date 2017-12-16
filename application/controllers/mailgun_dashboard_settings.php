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

	const MAILGUN_DASHBOARD_SETTINGS_SOURCE_NAME = 'mailgun_settings_source';

	const MAILGUN_DASHBOARD_OPTIONS_GROUP = 'mailgun_dashboard_options_group';

	const MAILGUN_DASHBOARD_SETTINGS_PAGE_SCREEN_ID = 'mailgun-dashboard_page_mailgun-dashboard-settings';

	/**
	 * Initialize "Mailgun Dashboard" plugin's settings page.
	 */
	public function initialize() {
		add_action( 'admin_init', array( $this, 'mailgun_dashboard_register_settings' ) );

		add_action( 'init', array( $this, 'register_assets' ), 11 );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	/**
	 * Register the settings for the "Mailgun Dashboard" plugin.
	 */
	public function mailgun_dashboard_register_settings() {
		register_setting( self::MAILGUN_DASHBOARD_OPTIONS_GROUP, self::MAILGUN_DASHBOARD_API_KEY_OPTION_NAME );

		register_setting( self::MAILGUN_DASHBOARD_OPTIONS_GROUP, self::MAILGUN_DASHBOARD_DOMAIN_OPTION_NAME );

		register_setting( self::MAILGUN_DASHBOARD_OPTIONS_GROUP, self::MAILGUN_DASHBOARD_SETTINGS_SOURCE_NAME );
	}

	/**
	 * "Mailgun Dashboard" plugin's settings menu page callback.
	 */
	public function render_page() {
		ob_start();
		include( MAILGUN_DASHBOARD_VIEWS_PATH . '/settings.phtml' );
		echo ob_get_clean();
	}

	/**
	 * Register "Mailgun Dashboard" plugin's settings assets.
	 *
	 * @since 0.1.0
	 */
	public function register_assets() {
		wp_register_script( 'settings-js',
			MAILGUN_DASHBOARD_URL . '/res/js/settings.js',
			array( 'jquery' ),
			MAILGUN_DASHBOARD_VERSION,
			true
		);

		$settings_script_texts = array();

		wp_localize_script(
			'settings-js',
			'mailgun_dashboard_settings_texts',
			$settings_script_texts
		);
	}

	/**
	 * Enqueue "Mailgun Dashboard" plugin's settings assets.
	 */
	public function enqueue_assets() {
		if ( get_current_screen()->id === self::MAILGUN_DASHBOARD_SETTINGS_PAGE_SCREEN_ID ) {
			wp_enqueue_script( 'settings-js' );
		}
	}
}
