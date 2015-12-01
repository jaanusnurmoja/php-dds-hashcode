<?php
use SK\DigiDoc\Example\Common\CommonUtils;
use SK\Digidoc\Example\Helpers\DocHelper;
use SK\Digidoc\Example\Helpers\FileHelper;

try {
    $fileUploadInputName = 'dataFile';
    //Check if the there were any errors on the first datafile upload.
    FileHelper::checkUploadedFileForErrors($fileUploadInputName);
    $containerType = DocHelper::getDesiredContainerType('containerType');

    // Start the Session with DDS
    $startSessionResponse = $dds->StartSession(array('bHoldSession' => 'true'));
    $ddsSessionCode = $startSessionResponse['Sesscode'];

    // Create an empty container to DDS session.
    $format = $containerType['format'];
    $version = $containerType['version'];
    $containerShortType = $containerType['shortType'];

    $uploadedFileName = basename($_FILES[$fileUploadInputName]['name']);

    // Following 2 parameters are necessary for the next potential requests.
    $_SESSION['ddsSessionCode'] = $ddsSessionCode;
    $_SESSION['originalContainerName'] = DocHelper::getNewCMontainerName($uploadedFileName, $containerShortType);

    // Store the data file to a more permanent place
    $pathToDatafile = FileHelper::moveUploadedFileToUploadDir($fileUploadInputName);

    $dds->CreateSignedDoc(
        array(
            'Sesscode' => CommonUtils::getDdsSessionCode(),
            'Format'   => $format,
            'Version'  => $version,
        )
    );

    // Add data file as HASHCODE to the container in DDS session
    $datafile_mime_type = $_FILES[$fileUploadInputName]['type'];
    DocHelper::addDatafileViaDds($pathToDatafile, $datafile_mime_type);

    // Get the HASHCODE container from DDS
    $get_signed_doc_response = $dds->GetSignedDoc(array('Sesscode' => CommonUtils::getDdsSessionCode()));
    $container_data = $get_signed_doc_response['SignedDocData'];
    if (strpos($container_data, 'SignedDoc') === false) {
        $container_data = base64_decode($container_data);
    }

    // Create container with datafiles on the local server disk so that there would be one with help of which it is possible
    // to restore the container if download is initiated.
    $path_to_created_container = DocHelper::createContainerWithFiles(
        $container_data,
        array(new \SK\Digidoc\FileSystemDataFile($pathToDatafile))
    );

    FileHelper::deleteIfExists($pathToDatafile);

    // Show information to user about the uploaded document.
    include 'show_doc_info.php';
    CommonUtils::showSuccess('Container created and datafile added.');

    CommonUtils::debugLog(
        "Container created, datafile added and session started with hashcode form of container. DDS session ID: '$ddsSessionCode'."
    );

} catch (Exception $e) {
    CommonUtils::showErrorText($e);
}