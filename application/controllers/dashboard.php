<?php

class MailgunDashboard_Dashboard {

	public function render_page() {
		$api_data = $this->get_API_data();
		ob_start();
		include( MAILGUN_DASHBOARD_VIEWS_PATH . '/dashboard.phtml' );
		echo ob_get_clean();
	}

	public function get_API_data() {
		// WordPress HTTP API
		$response = wp_remote_get( 'xxx' );
		$body = wp_remote_retrieve_body( $response );
		return json_decode( $body );
	}
}
