<?php

namespace Controllers;

use \Controllers\Mailgun_Dashboard_Settings;
use \Controllers\Mailgun_Dashboard_Main;

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
			'decimal' => '',
			'emptyTable' => __( 'No data available in table', MAILGUN_DASHBOARD_CONTEXT ),
			'info' => __( 'Showing _START_ to _END_ of _TOTAL_ entries', MAILGUN_DASHBOARD_CONTEXT ),
			'infoEmpty' => __( 'Showing 0 to 0 of 0 entries', MAILGUN_DASHBOARD_CONTEXT ),
			'infoFiltered' => __( '(filteredfrom_MAX_totalentries)', MAILGUN_DASHBOARD_CONTEXT ),
			'infoPostFix' => '',
			'thousands' => ',',
			'lengthMenu' => __( 'Show _MENU_ entries', MAILGUN_DASHBOARD_CONTEXT ),
			'loadingRecords' => __( 'Loading...', MAILGUN_DASHBOARD_CONTEXT ),
			'processing' => __( 'Processing...', MAILGUN_DASHBOARD_CONTEXT ),
			'search' => __( 'Search: ', MAILGUN_DASHBOARD_CONTEXT ),
			'zeroRecords' => __( 'No matching records found', MAILGUN_DASHBOARD_CONTEXT ),
			'first' => __( 'First', MAILGUN_DASHBOARD_CONTEXT ),
			'last' => __( 'Last', MAILGUN_DASHBOARD_CONTEXT ),
			'next' => __( 'Next', MAILGUN_DASHBOARD_CONTEXT ),
			'previous' => __( 'Previous', MAILGUN_DASHBOARD_CONTEXT ),
			'sortAscending' => ':activatetosortcolumnascending',
			'sortDescending' => ':activatetosortcolumndescending',
			'eventStatus' => array( 'info' => __( 'Info', MAILGUN_DASHBOARD_CONTEXT ), 'error' => __( 'Error', MAILGUN_DASHBOARD_CONTEXT ) ),
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

	public function mgd_get_mailgun_log() {
		$api_key = get_option( Mailgun_Dashboard_Settings::MAILGUN_DASHBOARD_API_KEY_OPTION_NAME );
		$domain = get_option( Mailgun_Dashboard_Settings::MAILGUN_DASHBOARD_DOMAIN_OPTION_NAME );

		if (
			isset( $api_key )
			&& isset( $domain )
		) {
			$url = sprintf( Mailgun_Dashboard_Main::MAILGUN_API_URL, $api_key, $domain );
			$endpoint = '/log';
			$response = wp_remote_get( $url . $endpoint );

			if( is_wp_error( $response ) ) {
				wp_send_json_error( 'Remote request failed.' );
			}
			$data = wp_remote_retrieve_body( $response );

			wp_send_json_success( $data );
		} else {
			wp_send_json_error( 'Mailgun API key or domain, not set.' );
		}
	}
}
