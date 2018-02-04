<?php // @codingStandardsIgnoreLine

namespace Mailgun_Dashboard\Controllers;

/**
 * "Dashboard for Mailgun" plugin's settings page class.
 *
 * @category Class
 * @package  dashboard-for-mailgun
 * @author   Konstantinos Galanakis
 */
class Mailgun_Dashboard_Settings {

	const MAILGUN_DASHBOARD_API_KEY_OPTION_NAME = 'mailgun_api_key';

	const MAILGUN_DASHBOARD_DOMAIN_OPTION_NAME = 'mailgun_domain';

	const MAILGUN_DASHBOARD_OPTION_NAME = 'mailgun_dashboard';

	const MAILGUN_DASHBOARD_SETTINGS_SOURCE_NAME = 'mailgun_settings_source';

	const MAILGUN_DASHBOARD_OPTIONS_GROUP = 'mailgun_dashboard_options_group';

	const MAILGUN_DASHBOARD_SETTINGS_PAGE_SCREEN_ID = 'dashboard-for-mailgun_page_dashboard-for-mailgun-settings';

	/**
	 * Initialize "Dashboard for Mailgun" plugin's settings page.
	 */
	public function initialize() {
		add_action( 'admin_init', array( $this, 'mailgun_dashboard_register_settings' ) );

		add_action( 'init', array( $this, 'register_assets' ), 11 );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	/**
	 * Register the settings for the "Dashboard for Mailgun" plugin.
	 */
	public function mailgun_dashboard_register_settings() {
		register_setting( self::MAILGUN_DASHBOARD_OPTIONS_GROUP, self::MAILGUN_DASHBOARD_OPTION_NAME, array( $this, 'mailgun_api_key_validation' ) );
	}

	/**
	 * "Dashboard for Mailgun" plugin's settings menu page callback.
	 */
	public function render_page() {
		ob_start();
		include( MAILGUN_DASHBOARD_VIEWS_PATH . '/settings.phtml' );
		echo ob_get_clean();
	}

	/**
	 * Register "Dashboard for Mailgun" plugin's settings assets.
	 *
	 * @since 0.1.0
	 */
	public function register_assets() {
		wp_register_script( 'settings-js',
			MAILGUN_DASHBOARD_URL . '/assets/js/mailgun_dashboard_settings.js',
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
	 * Enqueue "Dashboard for Mailgun" plugin's settings assets.
	 */
	public function enqueue_assets() {
		if ( get_current_screen()->id === self::MAILGUN_DASHBOARD_SETTINGS_PAGE_SCREEN_ID ) {
			wp_enqueue_script( 'settings-js' );
		}
	}

	/**
	 * Data validation callback function for options.
	 *
	 * @param array $mailgun_dashboard_settings An array of options posted from the options page.
	 *
	 * @return array
	 *
	 * @since 0.1.0
	 */
	public function mailgun_api_key_validation( $mailgun_dashboard_settings ) {
		$defaults = array(
			Mailgun_Dashboard_Settings::MAILGUN_DASHBOARD_API_KEY_OPTION_NAME => '',
		);

		$mailgun_dashboard_settings_copy = wp_parse_args( $mailgun_dashboard_settings, $defaults );

		$mailgun_dashboard_api_key = trim( $mailgun_dashboard_settings_copy[ Mailgun_Dashboard_Settings::MAILGUN_DASHBOARD_API_KEY_OPTION_NAME ] );

		if ( ! empty( $mailgun_dashboard_api_key ) ) {
			$pos = strpos( $mailgun_dashboard_api_key, 'key-' );
			if (
				false === $pos
				|| $pos > 4
			) {
				$mailgun_dashboard_api_key = "key-{$mailgun_dashboard_api_key}";
			}

			$pos = strpos( $mailgun_dashboard_api_key, 'api:' );
			if (
				false !== $pos
				&& 0 == $pos
			) {
				$mailgun_dashboard_api_key = substr( $mailgun_dashboard_api_key, 4 );
			}
			$mailgun_dashboard_settings[ Mailgun_Dashboard_Settings::MAILGUN_DASHBOARD_API_KEY_OPTION_NAME ] = $mailgun_dashboard_api_key;
		}

		return $mailgun_dashboard_settings;
	}
}
