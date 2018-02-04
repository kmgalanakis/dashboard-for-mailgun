<?php // @codingStandardsIgnoreLine
/**
 * Dashboard for Mailgun plugin's main bootstrap file.
 *
 * @package  dashboard-for-mailgun
 * @author   Konstantinos Galanakis
 */
require_once MAILGUN_DASHBOARD_PATH . '/inc/autoload.php';

$mgd_main = new \Mailgun_Dashboard\Controllers\Mailgun_Dashboard_Main();
$mgd_main->initialize();
