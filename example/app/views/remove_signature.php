<?php
use SK\DigiDoc\Example\Common\CommonUtils;
use SK\Digidoc\Example\Helpers\DocHelper;

try {
    // Check if all required POST parameters are set for this operation.
    if (!array_key_exists('signatureId', $_POST)) {
        throw new InvalidArgumentException('There was an error. You need to start again.');
    }

    $signatureId = $_POST['signatureId'];
    $errorOnDdsRemoval = false;
    try {
        // Remove the datafile from the container in DDS session.
        $dds->RemoveSignature(
            array(
                'Sesscode'    => CommonUtils::getDdsSessionCode(),
                'SignatureId' => $signatureId,
            )
        );
    } catch (Exception $e) {
        CommonUtils::showErrorText($e);
        $errorOnDdsRemoval = true;
    }

    if (!$errorOnDdsRemoval) {
        // Get the HASHCODE container from DDS
        $getSignedDocumentResponse = $dds->GetSignedDoc(array('Sesscode' => CommonUtils::getDdsSessionCode()));
        $containerData = $getSignedDocumentResponse['SignedDocData'];
        if (strpos($containerData, 'SignedDoc') === false) {
            $containerData = base64_decode($containerData);
        }

        // Rewrite the container on the local disk.
        $datafiles = DocHelper::getDatafilesFromContainer();

        // Rewrite the local container with new content
        DocHelper::createContainerWithFiles($containerData, $datafiles);
    }

    // Show information to user about the document.
    include 'show_doc_info.php';

    if (!$errorOnDdsRemoval) {
        CommonUtils::showSuccess('Signature successfully removed.');
        CommonUtils::debugLog("User successfully removed signature  with ID '$signatureId' from the container.");
    }

} catch (Exception $e) {
    CommonUtils::showErrorText($e);
}
