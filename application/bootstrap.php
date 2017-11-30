<?php

require_once MAILGUN_DASHBOARD_PATH . '/vendor/autoload.php';

$mgd_main = new \Controllers\Mailgun_Dashboard_Main();
$mgd_main->initialize();