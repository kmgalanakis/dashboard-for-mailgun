<?php

namespace Controllers;

use \Controllers\Mailgun_Dashboard_Settings;

class Mailgun_Dashboard_Dashboard {
	public function initialize() {

		add_action( 'wp_ajax_mgd_get_mailgun_log', array( $this, 'mgd_get_mailgun_log' ) );

		add_action( 'init', array( $this, 'register_assets' ), 11 );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	public function render_page() {
		ob_start();
		include( MAILGUN_DASHBOARD_VIEWS_PATH . '/dashboard.phtml' );
		echo ob_get_clean();
	}

	public function register_assets() {
		wp_register_script( 'dashboard-js',
			MAILGUN_DASHBOARD_URL . '/res/js/dashboard.js',
			array( 'jquery' ),
			MAILGUN_DASHBOARD_VERSION,
			true );

		$dashboard_script_texts = array(
			'url' => $this->getDashboardDataURL(),
			'username' => 'api',
			'password' => get_option( Mailgun_Dashboard_Settings::MAILGUN_DASHBOARD_API_KEY_OPTION_NAME )
		);
		wp_localize_script(
			'dashboard-js',
			'mailgun_dashboard_dashboard_texts',
			$dashboard_script_texts
		);
	}

	public function enqueue_assets() {
		wp_enqueue_script( 'dashboard-js' );
	}

	public function getDashboardDataURL() {
		$domain = get_option( Mailgun_Dashboard_Settings::MAILGUN_DASHBOARD_DOMAIN_OPTION_NAME );

		if ( isset( $domain ) ) {
			$url = 'https://api.mailgun.net' . '/v3/' . $domain . '/log';
			return $url;
		}
		
		return null;
	}

	public function get_API_data() {
		$api_key = get_option( Mailgun_Dashboard_Settings::MAILGUN_DASHBOARD_API_KEY_OPTION_NAME );
		$domain = get_option( Mailgun_Dashboard_Settings::MAILGUN_DASHBOARD_DOMAIN_OPTION_NAME );

		if (
			isset( $api_key )
			&& isset( $domain )
		) {
			$url = 'https://api:' . $api_key . '@' . 'api.mailgun.net' . '/v3/' . $domain . '/log';
			$response = wp_remote_get( $url );
			$body = wp_remote_retrieve_body( $response );
			return json_decode( $body );
		} else {
			return null;
		}
	}

	public function mgd_get_mailgun_log() {
		$data['lorem'] = 'ipsum';
		wp_send_json_success( $data );
	}
}
