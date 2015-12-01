<?php
use SK\DigiDoc\Example\Common\CommonUtils;
use SK\Digidoc\Example\Helpers\DocHelper;

try {
    // Check if all required POST parameters are set for this operation.
    if (!array_key_exists('datafileId', $_POST) || !array_key_exists('datafileName', $_POST)) {
        throw new \InvalidArgumentException('There was an error. You need to start again.');
    }

    $datafileId = htmlspecialchars($_POST['datafileId']);
    $datafileName = htmlspecialchars($_POST['datafileName']);

    $errorOnDdsRemoval = false;
    try {
        // Remove the datafile from the container in DDS session.
        $dds->RemoveDataFile(
            array(
                'Sesscode'   => CommonUtils::getDdsSessionCode(),
                'DataFileId' => $datafileId,
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

        // Rewrite the container on the local disk with the remaining datafiles.
        $datafiles = DocHelper::removeDatafile($datafileName);

        // Rewrite the local container with new content
        DocHelper::createContainerWithFiles($containerData, $datafiles);
    }

    // Show information to user about the document.
    include 'show_doc_info.php';

    if (!$errorOnDdsRemoval) {
        CommonUtils::showSuccess('Datafile successfully removed.');
        CommonUtils::debugLog("User successfully removed datafile '$datafileName'.");
    }
} catch (Exception $e) {
    CommonUtils::showErrorText($e);
}
