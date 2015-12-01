<?php
use SK\Digidoc\Example\Actions\MobileSignActions;
use SK\Digidoc\Example\Common\CommonUtils;

header('Content-Type: application/json');
$response = array();

try {
    if (!array_key_exists('subAct', $_POST)) {
        throw new HttpInvalidParamException('There are missing parameters which are needed to sign with MID.');
    }

    $subAction = $_POST['subAct'];
    if ($subAction === 'START_SIGNING') {
        if (!array_key_exists('phoneNo', $_POST) || !array_key_exists('idCode', $_POST)) {
            throw new \HttpInvalidParamException('There were missing parameters which are needed to sign with MID.');
        }

        $response = MobileSignActions::prepareMobileSign($dds, $response);
    }

    if ($subAction === 'GET_SIGNING_STATUS') {
        $statusResponse = $dds->GetStatusInfo(
            array(
                'Sesscode'      => CommonUtils::getDdsSessionCode(),
                'ReturnDocInfo' => false,
                'WaitSignature' => false,
            )
        );

        $statusCode = $statusResponse['StatusCode'];
        CommonUtils::debugLog("User is asking about the status of mobile signing. The status is '$statusCode'.");

        $success = $statusCode === 'SIGNATURE';
        if ($success) {
            $response = MobileSignActions::mobileSignSuccessResponse($dds, $response);
        } elseif ($statusCode !== 'REQUEST_OK' && $statusCode !== 'OUTSTANDING_TRANSACTION') {
            //Process has finished unsuccessfully.
            MobileSignActions::handleMobileSignError($dds, $statusCode);
        }
    }
} catch (Exception $e) {
    $code = $e->getCode();
    $message = ((bool) $code ? $code.': ' : '').$e->getMessage();
    CommonUtils::debugLog($message);
    $response['error_message'] = $message;
}

echo json_encode($response);
