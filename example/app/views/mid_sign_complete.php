<?php
use SK\DigiDoc\Example\Common\CommonUtils;

try {
    // Check if there was any kind of error during MID signing.
    if (array_key_exists('error_message', $_POST)) {
        echo("<p class=\"alert alert-danger\">".$_POST['error_message'].'</p>');
    }

    // Show information to user about the document.
    include 'show_doc_info.php';

    if (!array_key_exists('error_message', $_POST)) {
        CommonUtils::showSuccess('Signature successfully added.');
        CommonUtils::debugLog('User successfully added a signature with Mobile ID to the container.');
    }

} catch (Exception $e) {
    CommonUtils::showErrorText($e);
}
