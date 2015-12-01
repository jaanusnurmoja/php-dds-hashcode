<?php
use SK\Digidoc\Example\Common\CommonUtils;
use SK\Digidoc\Example\Helpers\CertificateHelper;

header('Content-Type: application/json');
$response = array();

/**
 * Collect information about signer
 *
 * @param array $requestParameters - POST request form parameters about signer
 *
 * @return array
 */
function getPrepareSignatureParameters($requestParameters)
{
    $keyPrefix = 'signer';
    $roleParameters = array();
    $keys = array(
        'Role',
        'City',
        'State',
        'PostalCode',
    );

    foreach ($keys as $key) {
        $fullKey = $keyPrefix.$key;
        if (array_key_exists($fullKey, $requestParameters) === true) {
            $roleParameters[$key] = $requestParameters[$fullKey];
        }
    }

    return $roleParameters;
}

try {
    CommonUtils::debugLog('User started the preparation of signature with ID Card to the container.');

    if (!array_key_exists('signersCertificateHEX', $_POST)) {
        throw new InvalidArgumentException('There were missing parameters which are needed to sign with ID Card.');
    }

    // Let's prepare the parameters for PrepareSignature method.
    $prepareSignatureReqParams['Sesscode'] = CommonUtils::getDdsSessionCode();
    $prepareSignatureReqParams['SignersCertificate'] = $_POST['signersCertificateHEX'];
    $prepareSignatureReqParams['SignersTokenId'] = '';

    array_merge($prepareSignatureReqParams, getPrepareSignatureParameters($_POST));
    $prepareSignatureReqParams['SigningProfile'] = '';

    // Invoke PrepareSignature.
    $prepareSignatureResponse = $dds->PrepareSignature($prepareSignatureReqParams);

    // If we reach here then everything must be OK with the signature preparation.
    $response['signature_info_digest'] = $prepareSignatureResponse['SignedInfoDigest'];
    $response['signature_id'] = $prepareSignatureResponse['SignatureId'];
    $response['signature_hash_type'] = CertificateHelper::getHashType($response['signature_info_digest']);
    $response['is_success'] = true;
} catch (Exception $e) {
    $code = $e->getCode();
    $message = (!!$code ? $code.': ' : '').$e->getMessage();
    CommonUtils::debugLog($message);
    $response['error_message'] = $message;
}

echo json_encode($response);
