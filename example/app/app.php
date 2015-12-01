<?php
require __DIR__.'/../../vendor/autoload.php';
require __DIR__.'/config/configuration.php';

use SK\Digidoc\Example\DigiDocService\DigiDocService;
use SK\Digidoc\Example\Helpers\CsrfTokenGenerator;
use SK\Digidoc\Example\Helpers\DocHelper;
use SK\Digidoc\Example\Helpers\RequestHelper;

// Set timezone
date_default_timezone_set('Europe/Tallinn');
$_REQUEST['requestId'] = uniqid('sk_dds_hashcode', true);

// Start session
session_start();

$csrfSigner = new CsrfTokenGenerator(CSRFP_SECRET);
$dds = DigiDocService::instance();

// Defend against CSRF attack
if ($_POST) {
    RequestHelper::csrfValidate($csrfSigner);
}

// Render view based on POST method action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && RequestHelper::isSupportedAction()) {
    // Some kind of document processing request has probably already been instantiated.
    // Following request_act-s return something else than text/html.
    $requestedAction = $_POST['request_act'];

    $isJsonView = in_array($requestedAction, RequestHelper::$noneHtmlViewActions, true);
    if ($isJsonView) {
        /** @noinspection PhpIncludeInspection */
        include __DIR__.'/views/'.trim(strtolower($requestedAction)).'.php';
    } else {
        // Rest of the requestActions-s all return text/html.
        RequestHelper::loadDigiDocActionTemplates(
            RequestHelper::$htmlDigiDocActions,
            $requestedAction,
            $dds,
            $csrfSigner
        );
    }

    DocHelper::persistHashcodeSession();
} else {
    // Default behavior is to show the index page.
    RequestHelper::loadStartPageTemplate($_SERVER, $dds, $csrfSigner);
}
