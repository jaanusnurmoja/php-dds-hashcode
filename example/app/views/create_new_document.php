<?php
use SK\DigiDoc\Example\Common\CommonUtils;
use SK\Digidoc\Example\Helpers\DocHelper;
use SK\Digidoc\Example\Helpers\FileHelper;
use SK\Digidoc\FileSystemDataFile;

try {
    $fileUploadInputName = 'dataFile';

    //Check if the there were any errors on the first datafile upload.
    FileHelper::checkUploadedFileForErrors($fileUploadInputName);
    $containerType = DocHelper::getDesiredContainerType('containerType');

    // Start the Session with DDS
    $startSessionResponse = $dds->StartSession(['bHoldSession' => 'true']);
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

    $dds->CreateSignedDoc([
        'Sesscode' => CommonUtils::getDdsSessionCode(),
        'Format'   => $format,
        'Version'  => $version,
    ]);

    // Add data file as HASHCODE to the container in DDS session
    $datafileMimeType = $_FILES[$fileUploadInputName]['type'];
    DocHelper::addDatafileViaDds($pathToDatafile, $datafileMimeType);

    // Get the HASHCODE container from DDS
    $getSignedDocResponse = $dds->GetSignedDoc(['Sesscode' => CommonUtils::getDdsSessionCode()]);
    $containerData = $getSignedDocResponse['SignedDocData'];

    if (strpos($containerData, 'SignedDoc') === false) {
        $containerData = base64_decode($containerData);
    }

    // Create container with datafiles on the local server disk so that there would be one with help of which it is possible
    // to restore the container if download is initiated.
    $pathToCreatedContainer = DocHelper::createContainerWithFiles(
        $containerData,
        [new FileSystemDataFile($pathToDatafile)]
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
