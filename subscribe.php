<?php

require_once dirname(__FILE__) . '/vendor/autoload.php';

//if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
//    header('HTTP/1.1 403 Forbidden');
//    die;
//}

$success = FALSE;
$message = '';

function dlResponse(bool $success, string $msg = '') {
    echo json_encode(['success' => $success, 'msg' => $msg]);
    die;
}

$config = dirname(__FILE__) . '/config.inc.php';
if (!file_exists($config)) {
    dlResponse(false, 'Config file not found');
}
require $config;
if (!isset($api_key) or ! isset($list_id)) {
    dlResponse(false, 'Config incorrect');
}

$MailChimp = new \DrewM\MailChimp\MailChimp($api_key);
if (!empty($_REQUEST['email2'])
        and empty($_REQUEST['email'])
        and empty($_REQUEST['text'])
        and empty($_REQUEST['email3'])) {
    $result = $MailChimp->post("lists/$list_id/members", [
        'email_address' => trim($_REQUEST['email']),
        'status' => 'subscribed',
    ]);
    // always say ok
    dlResponse(true);
}