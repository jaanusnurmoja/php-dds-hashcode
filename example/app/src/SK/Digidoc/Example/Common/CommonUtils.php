<?php
namespace SK\Digidoc\Example\Common;

use SK\Digidoc\DigidocException;

/**
 * Class CommonUtils
 *
 * @package SK\Digidoc\Example\Common
 */
class CommonUtils
{

    /**
     * Helper method for getting the POST parameter 'request_act'.
     *
     * @return string - Request act that was passed to index controller.
     */
    public static function getRequestAction()
    {
        $actionKey = 'request_act';

        return array_key_exists($actionKey, $_POST) ? $_POST[$actionKey] : null;
    }

    /**
     * Generates the Javascript that shows the green success message to user.
     *
     * @param string $message - Success message.
     */
    public static function showSuccess($message)
    {
        $message = htmlspecialchars($message);
        echo("<script>
        document.getElementById('success').style.display = 'block';
        document.getElementById('success').innerHTML = '$message';
        </script>");
    }

    /**
     * Generates HTML that represents the red error message to show to user. Also logs the exception if logging is
     * turned on.
     *
     * @param \Exception $exception - The Exception that the error is based on.
     */
    public static function showErrorText($exception)
    {
        $code = $exception->getCode();
        $message = ((bool) $code ? $code.': ' : '').$exception->getMessage();
        CommonUtils::debugLog($message);
        echo('<p class="alert alert-danger">'.htmlspecialchars($message, ENT_HTML5).'</p>');
    }

    /**
     * Helper method for getting the DigiDocService session code from HTTP session.
     *
     * @return string - Session code of the current DigiDocService session.
     * @throws \Exception - It is expected that if this method is called then dds session is started and session code is
     *                      loaded to HTTP session. If it is not so then an exception is thrown.
     */
    public static function getDdsSessionCode()
    {
        if (!isset($_SESSION['ddsSessionCode'])) {
            throw new \Exception('There is no active session with DDS.');
        }

        return $_SESSION['ddsSessionCode'];
    }

    /**
     * Helper method for getting the name of the container currently handled. Used for example at the moment of
     * downloading the container to restore the original file name.
     *
     * @return string - File name of the container in the moment it was uploaded.
     * @throws DigidocException- It is expected that if this method is called then dds session is started and the original
     *                     container name is loaded to HTTP session. If it is not so then an exception is thrown.
     */
    public static function getOriginalContainerName()
    {
        if (!isset($_SESSION['originalContainerName'])) {
            throw new DigidocException('There is no with files version of container, so the container can not be restored.');
        }

        return $_SESSION['originalContainerName'];
    }

    /**
     * Logging helper method. Logging that is done through this method can be turned of by setting the constant
     * HASHCODE_APP_LOGGING_ON to FALSE.
     *
     * @param string $message - Message to be logged.
     */
    public static function debugLog($message)
    {
        if (HASHCODE_APP_LOGGING_ON) {
            error_log('['.$_REQUEST['requestId'].'] ['.session_id().'] '.$message);
        }
    }
}