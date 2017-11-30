<?php

namespace Controllers;

use \Controllers\Mailgun_Dashboard_Settings;

class Mailgun_Dashboard_Dashboard {

	public function render_page() {
		$api_data = $this->get_API_data();
		ob_start();
		include( MAILGUN_DASHBOARD_VIEWS_PATH . '/dashboard.phtml' );
		echo ob_get_clean();
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
}
