<?php
use SK\DigiDoc\Example\Common\CommonUtils;
use SK\Digidoc\Example\Helpers\DocHelper;
use SK\Digidoc\Example\Helpers\FileHelper;

try {
    //Check if the uploaded file is a DDOC or BDOC with the correct mime type and there was no errors in uploading the file.
    $file_upload_input_name = 'container';
    FileHelper::checkUploadedFileForErrors(
        $file_upload_input_name,
        FileHelper::$allowedDigitalDocuments
    );
    // Create the hashcode version of the container and start the session with DDS.
    $hashcode_version_of_container = DocHelper::getEncodedHashcodeVersionOfContainer($file_upload_input_name);

    $start_session_response = $dds->StartSession(
        array(
            'bHoldSession' => 'true',
            'SigDocXML'    => $hashcode_version_of_container,
        )
    );

    $dds_session_code = $start_session_response['Sesscode'];
    $original_container_name = $_FILES[$file_upload_input_name]['name'];

    // Following 2 parameters are necessary for the show_doc_info view and for the next potential requests.
    $_SESSION['ddsSessionCode'] = $dds_session_code;
    $_SESSION['originalContainerName'] = $original_container_name;

    // Try to move the uploaded file to user specified upload directory (configuration.php HASHCODE_APP_UPLOAD_DIRECTORY)
    FileHelper::moveUploadedFileToUploadDir($file_upload_input_name);

    // Show information to user about the uploaded document.
    include 'show_doc_info.php';

    CommonUtils::showSuccess('Uploaded container parsed and session started.');
    CommonUtils::debugLog(
        "Uploaded container parsed and session started with hashcode form of container. DDS session ID: '$dds_session_code'."
    );

} catch (Exception $e) {
    CommonUtils::showErrorText($e);
}