<?php
use SK\DigiDoc\Example\Common\CommonUtils;
use SK\Digidoc\Example\Helpers\FileHelper;

try {
    CommonUtils::debugLog('User started the download of the container.').
    $path_to_original_container = FileHelper::getUploadDirectory().DIRECTORY_SEPARATOR.CommonUtils::getOriginalContainerName();
    header("Content-Disposition: attachment; filename=\"".CommonUtils::getOriginalContainerName()."\"");
    header('Content-Type: application/force-download');
    header('Content-Length: '.filesize($path_to_original_container));
    header('Connection: close');
    readfile($path_to_original_container);
    die();
} catch (Exception $e) {
    include __DIR__.'/../template/header.php';
    echo('<p><a href="">Start from the beginning</a></p>');
    CommonUtils::showErrorText($e);
    include __DIR__.'/../template/footer.php';
}