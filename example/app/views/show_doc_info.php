<?php

use SK\DigiDoc\Example\Common\CommonUtils;

try {
    $get_signed_doc_info_response = $dds->GetSignedDocInfo(array('Sesscode' => CommonUtils::getDdsSessionCode()));
    $document_file_info = $get_signed_doc_info_response['SignedDocInfo'];
} catch (Exception $e) {
    CommonUtils::showErrorText($e);
}

if (isset($document_file_info)) {
    ?>
    <div class="datatable">
        <h4>General container info</h4>
        <table class="table" id="download-container">
            <tr>
                <th>Format</th>
                <th>Version</th>
                <th></th>
            </tr>
            <tr>
                <td><?php echo $document_file_info->Format ?></td>
                <td><?php echo $document_file_info->Version ?></td>
                <?php // The action that initiates the container download. ?>
                <td>
                    <?php printf('<input type="hidden" name="_token" value="%s">',
                        htmlspecialchars($csrfSigner->getSignature())); ?>
                    <button onclick="ee.sk.hashcode.DownloadContainer();">Save</button>
                </td>
            </tr>
        </table>
    </div>

    <div class="datatable" id="container-data-files">
        <h4>Files</h4>
        <?php printf('<input type="hidden" name="_token" value="%s">',
            htmlspecialchars($csrfSigner->getSignature())); ?>
        <table class="table">
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Mime</th>
                <th>Length</th>
                <th>
                    <?php // The action that initiates the addition of new datafile to the container in DDS session. ?>
                    <button data-toggle="modal" data-target="#addDatafileModal">Add datafile</button>
                </th>
            </tr>

            <?php
            if (isset($document_file_info->DataFileInfo)) {
                if (isset($document_file_info->DataFileInfo->Id)) {
                    $data_files = array($document_file_info->DataFileInfo);
                } else {
                    $data_files = $document_file_info->DataFileInfo;
                }
            } else {
                $data_files = array();
            }

            foreach ($data_files as &$data_file) {
                ?>
                <tr>
                    <td><?php echo isset($data_file->Id) ? htmlspecialchars($data_file->Id, ENT_HTML5) : '' ?></td>
                    <td><?php echo isset($data_file->Filename) ? htmlspecialchars($data_file->Filename, ENT_HTML5, 'UTF-8') : '' ?></td>
                    <td><?php echo isset($data_file->MimeType) ? htmlspecialchars($data_file->MimeType, ENT_HTML5, 'UTF-8') : '' ?></td>
                    <td><?php echo isset($data_file->Size) ? number_format($data_file->Size, 0, ',', ' ').' bytes' : '' ?></td>
                    <td>
                        <?php // The action that initiates the removal of this datafile. ?>
                        <button
                            onclick="ee.sk.hashcode.RemoveDataFile('<?php echo isset($data_file->Id) ? $data_file->Id
                                : '' ?>',
                                '<?php echo isset($data_file->Filename) ? htmlspecialchars($data_file->Filename, ENT_QUOTES) : '' ?>');">Remove
                            datafile
                        </button>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>

    <?php include 'modals/add_datafile_modal.php' ?>

    <div class="datatable" id="container-signatures">
        <h4>Signatures</h4>
        <?php printf('<input type="hidden" name="_token" value="%s">',
            htmlspecialchars($csrfSigner->getSignature())); ?>
        <div id="pluginLocation"></div>
        <table class="table">
            <tr>
                <th>Id</th>
                <th>Status</th>
                <th>Additional status info</th>
                <th>Time</th>
                <th>Role</th>
                <th>Signer</th>
                <th style="height: 35px; line-height: 35px;">
                    Add signature:
                    <?php // The action that initiates the addition of new signature with ID Card to the container in DDS session. ?>
                    <a href="#"><img src="assets/images/id-kaart-logo.gif" data-toggle="modal"
                                     data-target="#idSignModal"/></a>
                    <?php // The action that initiates the addition of new signature with Mobile ID to the container in DDS session. ?>
                    <a href="#"><img src="assets/images/mid-logo.gif" data-toggle="modal"
                                     data-target="#mobileSignModal"/></a>
                </th>
            </tr>

            <?php
            if (isset($document_file_info->SignatureInfo)) {
                if (isset($document_file_info->SignatureInfo->Id)) {
                    $signatures = array($document_file_info->SignatureInfo);
                } else {
                    $signatures = $document_file_info->SignatureInfo;
                }
            } else {
                $signatures = array();
            }

            foreach ($signatures as &$signature) {
                $signature_status_class = '';
                $signature_status = isset($signature->Status) ? $signature->Status : '';
                $alternative_info = '';
                if ($signature_status === 'OK') {
                    if (isset($signature->Error) && $signature->Error->Category = 'WARNING') {
                        $signature_status_class = 'alert-warning';
                        $alternative_info = 'WARNING('.$signature->Error->Code.'): '.$signature->Error->Description;
                    }
                } elseif ($signature_status === 'ERROR') {
                    $signature_status_class = 'alert-danger';
                    $alternative_info = 'TECHNICAL('.$signature->Error->Code.'): '.$signature->Error->Description;
                }
                ?>
                <tr class="<?php echo $signature_status_class ?>">
                    <td><?php echo isset($signature->Id) ? $signature->Id : '' ?></td>
                    <td><?php echo $signature_status ?></td>
                    <td>
                        <?php echo $alternative_info ?>
                    </td>
                    <td><?php echo isset($signature->SigningTime) ? $signature->SigningTime : '' ?></td>
                    <td><?php echo isset($signature->Role) ? $signature->Role : '' ?></td>
                    <td><?php echo isset($signature->Signer->CommonName) ? $signature->Signer->CommonName : '' ?></td>
                    <?php // The action that initiates the removal of this signature. ?>
                    <td>
                        <button
                            onclick="ee.sk.hashcode.RemoveSignature('<?php echo isset($signature->Id) ? $signature->Id
                                : '' ?>');">
                            Remove signature
                        </button>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
    <?php
    include 'modals/id_sign_modal.php';
    include 'modals/mobile_sign_modal.php';
}
?>