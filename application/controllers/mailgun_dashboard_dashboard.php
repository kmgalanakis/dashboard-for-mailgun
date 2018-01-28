<?php // @codingStandardsIgnoreLine

namespace Controllers;

use \Controllers\Mailgun_Dashboard_Settings;
use \Controllers\Mailgun_Dashboard_Main;

/**
 * "Mailgun Dashboard" plugin's dashboard page class.
 *
 * @category Class
 * @package  mailgun-dashboard
 * @author   Konstantinos Galanakis
 */
class Mailgun_Dashboard_Dashboard {

	const MAILGUN_DASHBOARD_DASHBOARD_PAGE_SCREEN_ID = 'toplevel_page_mailgun-dashboard';

	/**
	 * Initialize "Mailgun Dashboard" plugin's dashboard page.
	 *
	 * @since 0.1.0
	 */
	public function initialize() {

		add_action( 'init', array( $this, 'register_assets' ), 11 );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );

		add_action( 'wp_ajax_mgd_get_mailgun_dashboard_api', array( $this, 'mgd_get_mailgun_dashboard_api' ) );

		$defaults = array(
			Mailgun_Dashboard_Settings::MAILGUN_DASHBOARD_DOMAIN_OPTION_NAME => '',
			Mailgun_Dashboard_Settings::MAILGUN_DASHBOARD_API_KEY_OPTION_NAME => '',
			Mailgun_Dashboard_Settings::MAILGUN_DASHBOARD_SETTINGS_SOURCE_NAME => '',
		);

		define( 'MAILGUN_DASHBOARD_SETTINGS', wp_parse_args( get_option( Mailgun_Dashboard_Settings::MAILGUN_DASHBOARD_OPTION_NAME ), $defaults ) );
	}

	/**
	 * "Mailgun Dashboard" plugin's dashboard menu page callback.
	 *
	 * @since 0.1.0
	 */
	public function render_page() {
		ob_start();
		include( MAILGUN_DASHBOARD_VIEWS_PATH . '/dashboard.phtml' );
		echo ob_get_clean();
	}

	/**
	 * Register "Mailgun Dashboard" plugin's dashboard assets.
	 *
	 * @since 0.1.0
	 */
	public function register_assets() {
		wp_register_script( 'mailgun_dashboard',
			MAILGUN_DASHBOARD_URL . '/assets/js/mailgun_dashboard.js',
			array( 'jquery', 'mailgun_dashboard_chartjs', 'mailgun_dashboard_datatables', 'mailgun_dashboard_daterangepicker' ),
			MAILGUN_DASHBOARD_VERSION,
			true
		);

		//@codingStandardsIgnoreStart
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
			'eventStatus' => array(
				'info' => __( 'Info', MAILGUN_DASHBOARD_CONTEXT ),
				'error' => __( 'Error', MAILGUN_DASHBOARD_CONTEXT ),
				'warn' => __( 'Warning', MAILGUN_DASHBOARD_CONTEXT ),
				'opened' => __( 'Opened', MAILGUN_DASHBOARD_CONTEXT ),
				'delivered' => __( 'Delivered', MAILGUN_DASHBOARD_CONTEXT ),
				'accepted' => __( 'Accepted', MAILGUN_DASHBOARD_CONTEXT ),
				'clicked' => __( 'Clicked', MAILGUN_DASHBOARD_CONTEXT ),
				'unsubscribed' => __( 'Unsubscribed', MAILGUN_DASHBOARD_CONTEXT ),
				'stored' => __( 'Stored', MAILGUN_DASHBOARD_CONTEXT ),
				'complained' => __( 'Complained', MAILGUN_DASHBOARD_CONTEXT ),
				'temporary_failed' => __( 'Temporary fail', MAILGUN_DASHBOARD_CONTEXT ),
				'permanent_failed' => __( 'Permanent fail', MAILGUN_DASHBOARD_CONTEXT ),
				'rejected' => __( 'Rejected', MAILGUN_DASHBOARD_CONTEXT ),
				'account_disabled' => __( 'Account disabled', MAILGUN_DASHBOARD_CONTEXT ),
			),
			'chartTitle' => __( 'Domain', MAILGUN_DASHBOARD_CONTEXT ) .
			                ' "' .  MAILGUN_DASHBOARD_SETTINGS[ Mailgun_Dashboard_Settings::MAILGUN_DASHBOARD_DOMAIN_OPTION_NAME ] . '" '
			                . __( 'chart', MAILGUN_DASHBOARD_CONTEXT ),
			'mailgun_api_failed' => __( 'Mailgun API failed', MAILGUN_DASHBOARD_CONTEXT ),
			'console_for_info' => __( 'See the console for further information', MAILGUN_DASHBOARD_CONTEXT ),
			'mailgun_api_error' => __( 'Mailgun API error', MAILGUN_DASHBOARD_CONTEXT ),
			'today' => __( 'Today', MAILGUN_DASHBOARD_CONTEXT ),
			'yesterday' => __( 'Yesterday', MAILGUN_DASHBOARD_CONTEXT ),
			'lastSevenDays' => __( 'Last 7 days', MAILGUN_DASHBOARD_CONTEXT ),
			'lastTwentyEightDays' => __( 'Last 28 days', MAILGUN_DASHBOARD_CONTEXT ),
		);
		//@codingStandardsIgnoreEnd

		wp_localize_script(
			'mailgun_dashboard',
			'mailgunDashboardDashboardTexts',
			$dashboard_script_texts
		);
	}

	/**
	 * Enqueue "Mailgun Dashboard" plugin's dashboard assets.
	 */
	public function enqueue_assets() {
		if ( get_current_screen()->id === self::MAILGUN_DASHBOARD_DASHBOARD_PAGE_SCREEN_ID ) {
			wp_enqueue_script( 'mailgun_dashboard' );

			wp_enqueue_style( 'mailgun_dashboard_css' );
		}
	}

	/**
	 * Load Mailgun's API data.
	 *
	 * @since 0.1.0
	 */
	public function mgd_get_mailgun_dashboard_api() {
		$defaults = array(
			Mailgun_Dashboard_Settings::MAILGUN_DASHBOARD_DOMAIN_OPTION_NAME => '',
			Mailgun_Dashboard_Settings::MAILGUN_DASHBOARD_API_KEY_OPTION_NAME => '',
			Mailgun_Dashboard_Settings::MAILGUN_DASHBOARD_SETTINGS_SOURCE_NAME => '',
		);

		$mailgun_dashboard_settings = wp_parse_args( get_option( Mailgun_Dashboard_Settings::MAILGUN_DASHBOARD_OPTION_NAME ), $defaults );

		if (
			class_exists( 'Mailgun' )
			&& $mailgun_dashboard_settings[ Mailgun_Dashboard_Settings::MAILGUN_DASHBOARD_SETTINGS_SOURCE_NAME ]
		) {
			$mailgun_options = get_option( 'mailgun' );

			$api_key = isset( $mailgun_options['apiKey'] ) ? $mailgun_options['apiKey'] : '';

			$domain = isset( $mailgun_options['domain'] ) ? $mailgun_options['domain'] : '';

		} else {
			$api_key = $mailgun_dashboard_settings[ Mailgun_Dashboard_Settings::MAILGUN_DASHBOARD_API_KEY_OPTION_NAME ];

			$domain = $mailgun_dashboard_settings[ Mailgun_Dashboard_Settings::MAILGUN_DASHBOARD_DOMAIN_OPTION_NAME ];
		}

		if (
			isset( $api_key )
			&& isset( $domain )
		) {
			$url = sprintf( Mailgun_Dashboard_Main::MAILGUN_API_URL, $api_key, $domain );

			$type = sanitize_text_field( $_POST['type'] );

			switch ( $type ) {
				case 'log':
					$decoded_data = $this->mgd_get_mailgun_log( $url );
					break;
				case 'events':
					$date_range_start = sanitize_text_field( $_POST['dateRangeStart'] );

					$date_range_end = sanitize_text_field( $_POST['dateRangeEnd'] );

					$decoded_data = $this->mgd_get_mailgun_events( $url, $date_range_start, $date_range_end );
					break;
				case 'stats':
					$resolution = sanitize_text_field( $_POST['resolution'] );

					$date_range_start = sanitize_text_field( $_POST['dateRangeStart'] );

					$date_range_end = sanitize_text_field( $_POST['dateRangeEnd'] );

					$events = array_map( 'sanitize_text_field', wp_unslash( $_POST['events'] ) );

					$decoded_data = $this->mgd_get_mailgun_stats( $url, $resolution, $events, $date_range_start, $date_range_end );
					break;
				default:
					$decoded_data = array();
					break;
			}

			wp_send_json_success( json_encode( $decoded_data ) );
		} else {
			wp_send_json_error( __( 'Mailgun API key or domain, not set.', MAILGUN_DASHBOARD_CONTEXT ) ); // @codingStandardsIgnoreLine
		}
	}

	/**
	 * Load log data from Mailgun's API.
	 *
	 * @param  string $url The authenticated API url.
	 * @return array  The log data.
	 *
	 * @since 0.1.0
	 */
	public function mgd_get_mailgun_log( $url ) {
		$endpoint = '/log';

		$response = wp_remote_get( $url . $endpoint );

		if ( is_wp_error( $response ) ) {
			wp_send_json_error( $response->errors );
		}
		$data = wp_remote_retrieve_body( $response );

		$decoded_data = json_decode( $data );

		$items = array_map(
			function( $item ) {
				$item->created_at = $this->mgd_adjust_date( $item->created_at );
				return $item;
			},
			$decoded_data->items
		);

		$decoded_data->items = $items;

		return $decoded_data;
	}

	/**
	 * Load log events from Mailgun's API.
	 *
	 * @param  string $url              The authenticated API url.
	 * @param  int    $date_range_start The start date timestamp for the Mailgun events query.
	 * @param  int    $date_range_end   The start date timestamp for the Mailgun events query.
	 * @return array  The events data.
	 *
	 * @since 0.1.0
	 */
	public function mgd_get_mailgun_events( $url, $date_range_start, $date_range_end ) {
		$endpoint = '/events';

		$endpoint .= '?limit=300';

		$endpoint .= '&begin=' . $date_range_end;

		$endpoint .= '&end=' . $date_range_start;

		$args = array(
			'timeout' => 30,
		);

		$response = wp_remote_get( $url . $endpoint, $args );

		if ( is_wp_error( $response ) ) {
			wp_send_json_error( $response->errors );
		}
		$data = wp_remote_retrieve_body( $response );

		$decoded_data = json_decode( $data );

		$items = array_map(
			function( $item ) {
				$item->timestamp = $this->mgd_adjust_date( $item->timestamp );
				return $item;
			},
			$decoded_data->items
		);

		$decoded_data->items = $items;

		return $decoded_data;
	}

	/**
	 * Load log stats from Mailgun's API.
	 *
	 * @param  string $url              The authenticated API url.
	 * @param  string $resolution       Can be either 'hour', 'day' or 'month'. Default is 'day'.
	 * @param  array  $events           The type of the events to fetch stats for.
	 * @param  int    $date_range_start The start date timestamp for the Mailgun stats query.
	 * @param  int    $date_range_end   The start date timestamp for the Mailgun stats query.
	 * @return array  The stats data.
	 *
	 * @since 0.1.0
	 */
	public function mgd_get_mailgun_stats(
		$url,
		$resolution = 'day',
		$events = array(
			'delivered',
			'failed',
			'stored',
			'accepted',
			'opened',
			'clicked',
			'unsubscribed',
			'complained',
		),
		$date_range_start,
		$date_range_end
	) {
		$endpoint = '/stats/total';

		$endpoint .= '?resolution=' . $resolution;

		$endpoint .= '&start=' . $date_range_start;

		$endpoint .= '&end=' . $date_range_end;

		foreach ( $events as $event ) {
			$endpoint .= '&event=' . $event;
		}

		$args = array(
			'timeout' => 30,
		);

		$response = wp_remote_get( $url . $endpoint, $args );

		if ( is_wp_error( $response ) ) {
			wp_send_json_error( $response->errors );
		}
		$data = wp_remote_retrieve_body( $response );

		$decoded_data = json_decode( $data );

		$stats = array_map(
			function( $item ) {
				$item->time = $this->mgd_adjust_date( $item->time );
				return $item;
			},
			$decoded_data->stats
		);

		$decoded_data->stats = $stats;

		return $decoded_data;
	}

	/**
	 * Adjust the Mailgun timestamps to match the format set in the WordPress settings.
	 *
	 * @param  string $time        The date string.
	 * @return string The adjusted date.
	 *
	 * @since 0.1.0
	 */
	public function mgd_adjust_date( $time ) {
		$time = $this->mgd_is_valid_timestamp( $time ) ? $time : strtotime( $time );
		return date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $time + get_option( 'gmt_offset' ) * HOUR_IN_SECONDS );
		return date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $time + get_option( 'gmt_offset' ) * HOUR_IN_SECONDS );
	}

	/**
	 * Checks for a valid timestamp. "Valid" means that the timestamp is an EPOCH float number.
	 *
	 * @param  string $timestamp   The timestamp to check..
	 * @return bool   Returns true if the timestamp is an EPOCH float number.
	 *
	 * @since 0.1.0
	 */
	public function mgd_is_valid_timestamp( $timestamp ) {
		return ( (string) (float) $timestamp === (string) $timestamp )
				&& ( $timestamp <= PHP_INT_MAX )
				&& ( $timestamp >= ~PHP_INT_MAX );
	}
}
