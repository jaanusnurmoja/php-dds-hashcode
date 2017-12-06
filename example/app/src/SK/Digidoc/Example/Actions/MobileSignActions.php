<?php

namespace SK\Digidoc\Example\Actions;

use SK\Digidoc\Example\Common\CommonUtils;
use SK\Digidoc\Example\Helpers\DocHelper;
use SK\Digidoc\Example\Helpers\MobileIDException;

/**
 * Class MobileSignActions
 *
 * @package SK\Digidoc\Example\Actions
 */
class MobileSignActions
{

    /**
     * @param \SK\Digidoc\Example\DigiDocService\DigiDocService $dds
     * @param array                                             $response
     *
     * @return mixed
     * @throws \Exception
     */
    public static function prepareMobileSign($dds, $response)
    {
        $phoneNumber = trim($_POST['phoneNo']);
        $identityCode = trim($_POST['idCode']);
        CommonUtils::debugLog(
            'User started the process of signing with MID. Mobile phone is \'$phoneNumber\' and ID code is \'$id_code\'.'
        );

        // In actual live situation, the language could be taken from the users customer database for example.
        $language = 'EST';

        $mobileSignResponse = $dds->MobileSign(
            array(
                'Sesscode'                    => CommonUtils::getDdsSessionCode(),
                'SignerIDCode'                => $identityCode,
                'SignerPhoneNo'               => $phoneNumber,
                'ServiceName'                 => DDS_MID_SERVICE_NAME,
                'AdditionalDataToBeDisplayed' => DDS_MID_INTRODUCTION_STRING,
                'Language'                    => $language,
                'MessagingMode'               => 'asynchClientServer',
                'ReturnDocInfo'               => false,
                'ReturnDocData'               => false,
            )
        );

        $response['challenge'] = $mobileSignResponse['ChallengeID'];

        return $response;
    }

    /**
     * @param \SK\Digidoc\Example\DigiDocService\DigiDocService $dds
     * @param string                                            $statusCode
     *
     * @throws \SK\Digidoc\Example\Helpers\MobileIDException
     */
    public static function handleMobileSignError($dds, $statusCode)
    {
        $messages = $dds->getMidStatusResponseErrorMessages;
        if (array_key_exists($statusCode, $messages)) {
            throw new MobileIDException($messages[$statusCode]);
        }
        throw new MobileIDException("There was an error signing with Mobile ID. Status code is '$statusCode'.");
    }

    /**
     * @param \SK\Digidoc\Example\DigiDocService\DigiDocService $dds
     * @param array                                             $response
     *
     * @return mixed
     * @throws \Exception
     */
    public static function mobileSignSuccessResponse($dds, $response)
    {
        $datafiles = DocHelper::getDatafilesFromContainer();
        $signedResponse = $dds->GetSignedDoc(array('Sesscode' => CommonUtils::getDdsSessionCode()));
        $containerData = $signedResponse['SignedDocData'];
        if (strpos($containerData, 'SignedDoc') === false) {
            $containerData = base64_decode($containerData);
        }

        // Rewrite the local container with new content
        DocHelper::createContainerWithFiles($containerData, $datafiles);

        $response['is_success'] = true;

        return $response;
    }

}