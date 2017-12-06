<?php

use SK\DigiDoc\Example\Common\CommonUtils;
use SK\Digidoc\Example\Helpers\DocHelper;
use SK\Digidoc\Example\Helpers\FileHelper;
use SK\Digidoc\FileSystemDataFile;

try {
    $errorOnDdsAdd = false;
    try {
        $file_upload_input_name = 'dataFile';

        //Check if the there were any errors on the datafile upload.
        FileHelper::checkUploadedFileForErrors($file_upload_input_name);

        // Store the data file to a more permanent place
        $path_to_datafile = FileHelper::moveUploadedFileToUploadDir($file_upload_input_name);

        // Add data file as HASHCODE to the container in DDS session
        $datafile_mime_type = $_FILES[$file_upload_input_name]['type'];

        DocHelper::addDatafileViaDds($path_to_datafile, $datafile_mime_type);
    } catch (Exception $e) {
        CommonUtils::showErrorText($e);
        $errorOnDdsAdd = true;
    }

    if (!$errorOnDdsAdd) {
        // Get the HASHCODE container from DDS
        $get_signed_doc_response = $dds->GetSignedDoc(array('Sesscode' => CommonUtils::getDdsSessionCode()));
        $container_data = $get_signed_doc_response['SignedDocData'];
        if (strpos($container_data, 'SignedDoc') === false) {
            $container_data = base64_decode($container_data);
        }

        // Merge previously added datafiles to an array with the new datafile.
        $datafiles = DocHelper::getDatafilesFromContainer();
        array_push($datafiles, new FileSystemDataFile($path_to_datafile));

        // Rewrite the local container with new content
        DocHelper::createContainerWithFiles($container_data, $datafiles);

        //Delete the datafile from server as it exists in the container anyway.
        FileHelper::deleteIfExists($path_to_datafile);
    }

    // Show information to user about the uploaded document.
    include 'show_doc_info.php';

    if (!$errorOnDdsAdd) {
        CommonUtils::showSuccess('Datafile successfully added.');
        CommonUtils::debugLog('User successfully added a datafile \''.basename($path_to_datafile).'\' to the container.');
    }
} catch (Exception $e) {
    if (isset($path_to_datafile)) {
        FileHelper::deleteIfExists($path_to_datafile);
    }
    CommonUtils::showErrorText($e);
}