<?php // @codingStandardsIgnoreLine
/**
 * Mailgun Dashboard plugin's main bootstrap file.
 *
 * @package  mailgun-dashboard
 * @author   Konstantinos Galanakis
 */
require_once MAILGUN_DASHBOARD_PATH . '/vendor/autoload.php';

$mgd_main = new \Controllers\Mailgun_Dashboard_Main();
$mgd_main->initialize();
