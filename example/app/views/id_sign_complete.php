<?php
use SK\DigiDoc\Example\Common\CommonUtils;
use SK\Digidoc\Example\Helpers\DocHelper;

/**
 * @param \SK\Digidoc\Example\DigiDocService\DigiDocService $dds
 *
 * @throws \Exception
 */
function handleIdCardSigningFailure($dds)
{
    echo('<p class="alert alert-danger">'.$_POST['error_message'].'</p>');
    if (!empty($_POST['signature_id'])) {
        // The fact that there has been an error and there is a signature ID means that there is a prepared
        // but not finalized signature in the session that needs to be removed.
        $dds->RemoveSignature(
            array('Sesscode' => CommonUtils::getDdsSessionCode(), 'SignatureId' => $_POST['signature_id'])
        );
        CommonUtils::debugLog(
            'Adding a signature to the container was not completed successfully so the prepared signature '.
            'was removed from the container in DigiDocService session.'
        );
    }
}

/**
 * @param \SK\Digidoc\Example\DigiDocService\DigiDocService $dds
 *
 * @throws \Exception
 */
function handleIdCardSigningSuccess($dds)
{
    if (!array_key_exists('signature_value', $_POST) || !array_key_exists('signature_id', $_POST)) {
        throw new InvalidArgumentException('There were missing parameters which are needed to sign with ID Card.');
    }

    // Everything is OK. Let's finalize the signing process in DigiDocService.
    $dds->FinalizeSignature(
        array(
            'Sesscode'       => CommonUtils::getDdsSessionCode(),
            'SignatureId'    => $_POST['signature_id'],
            'SignatureValue' => $_POST['signature_value'],
        )
    );

    // Rewrite the local container with new content
    $datafiles = DocHelper::getDatafilesFromContainer();
    $get_signed_doc_response = $dds->GetSignedDoc(array('Sesscode' => CommonUtils::getDdsSessionCode()));
    $container_data = $get_signed_doc_response['SignedDocData'];
    if (strpos($container_data, 'SignedDoc') === false) {
        $container_data = base64_decode($container_data);
    }

    DocHelper::createContainerWithFiles($container_data, $datafiles);
}

try {
    // Check if there was any kind of error during ID Card signing.
    if (array_key_exists('error_message', $_POST)) {
        handleIdCardSigningFailure($dds);
    } else {
        handleIdCardSigningSuccess($dds);
    }

    // Show information to user about the document.
    include 'show_doc_info.php';

    if (!array_key_exists('error_message', $_POST)) {
        CommonUtils::showSuccess('Signature successfully added.');
        CommonUtils::debugLog('User successfully added a signature with ID Card to the container.');
    }

} catch (Exception $e) {
    CommonUtils::showErrorText($e);
}