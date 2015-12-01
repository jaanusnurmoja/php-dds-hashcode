<?php
namespace SK\Digidoc\Example\Helpers;

use SK\Digidoc\Example\Common\CommonUtils;
use SK\Digidoc\Example\DigiDocService\DigiDocService;

/**
 * Class RequestHelper
 *
 * @package SK\Digidoc\Example\Helpers
 */
class RequestHelper
{

    public static $htmlDigiDocActions = array(
        'PARSE_OLD_DOCUMENT',
        'CREATE_NEW_DOCUMENT',
        'ADD_DATAFILE',
        'REMOVE_DATA_FILE',
        'ID_SIGN_COMPLETE',
        'MID_SIGN_COMPLETE',
        'REMOVE_SIGNATURE',
    );

    public static $noneHtmlViewActions = array(
        'DOWNLOAD',
        'MID_SIGN',
        'ID_SIGN_CREATE_HASH',
    );

    /**
     * @return bool
     */
    public static function isSupportedAction()
    {
        $allActions = array_merge(self::$htmlDigiDocActions, self::$noneHtmlViewActions);

        return in_array(CommonUtils::getRequestAction(), $allActions, true);
    }


    /**
     * Get directory where view files are kept
     *
     * @return string
     */
    public static function getViewBase()
    {
        $fullPath = __DIR__.'/../../../../../views';
//        CommonUtils::debugLog('Full path is:'.$fullPath);

        return $fullPath;
    }

    /**
     * Check if there is open session then try to close it
     *
     * @param array          $server
     * @param DigiDocService $dds
     *
     * @throws \Exception
     */
    public static function killDdsSession($server, $dds)
    {
        if (array_key_exists('ddsSessionCode', $server)) {
            // If the session data of previous dds session still exists we will initiate a cleanup.
            FileHelper::deleteIfExists(FileHelper::getUploadDirectory());
            try {
                $dds->CloseSession(array('Sesscode' => CommonUtils::getDdsSessionCode()));
                CommonUtils::debugLog('DDS session \''.CommonUtils::getDdsSessionCode().'\' closed.');
            } catch (\Exception $e) {
                CommonUtils::debugLog('Closing DDS session '.CommonUtils::getDdsSessionCode().' failed.');
            }
        }

        DocHelper::getHashcodeSession()->end(); // End the Hashcode container session.
        session_destroy(); // End the HTTP session.
    }

    /**
     * @param array              $actionList
     * @param string             $requestedAction
     * @param DigiDocService     $dds
     * @param CsrfTokenGenerator $csrfSigner
     */
    public static function loadDigiDocActionTemplates($actionList, $requestedAction, $dds, $csrfSigner)
    {
        $views = array();
        $views[] = self::getViewBase().'/start_from_beginning.php';
        foreach ($actionList as $action) {
            $filename = self::getViewBase().DIRECTORY_SEPARATOR.strtolower($action).'.php';
//            if ($requestedAction === $action && file_exists($filename)) {
            $actionFileExists = file_exists($filename);
            if ($requestedAction === $action && $actionFileExists) {
                /** @noinspection PhpIncludeInspection */
                $views[] = $filename;
                break;
            }
        }

        self::loadHtmlTemplate($csrfSigner, $dds, $views);
    }

    /**
     * @param array              $server
     * @param DigiDocService     $dds
     * @param CsrfTokenGenerator $csrfSigner
     *
     * @throws \Exception
     */
    public static function loadStartPageTemplate($server, $dds, $csrfSigner)
    {
        self::killDdsSession($server, $dds);
        self::loadHtmlTemplate($csrfSigner, $dds, array(self::getViewBase().'/default.php'));
    }

    /**
     * @param \Kunststube\CSRFP\SignatureGenerator $csrfSigner
     */
    public static function csrfValidate($csrfSigner)
    {
        if (!(array_key_exists('_token', $_POST) && $csrfSigner->validateSignature($_POST['_token']))) {
            header('HTTP/1.1 400 Bad Request');
            exit('CSRF token missing from post form or CSRF validation failed with token:'.$_POST['_token']);
        }
    }

    private static function loadHtmlTemplate($csrfSigner, $dds, array $viewFiles = array())
    {
        /** @noinspection PhpIncludeInspection */
        include self::getViewBase().'/common/header.php';

        foreach ($viewFiles as $file) {
            CommonUtils::debugLog('Open file: '.$file);
            /** @noinspection PhpIncludeInspection */
            include $file;
        }

        /** @noinspection PhpIncludeInspection */
        include self::getViewBase().'/common/footer.php';
    }
}
