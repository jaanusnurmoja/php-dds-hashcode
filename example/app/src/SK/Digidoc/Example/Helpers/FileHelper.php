<?php
namespace SK\Digidoc\Example\Helpers;

use SK\Digidoc\Example\Common\CommonUtils;

/**
 * Class FileHelper
 *
 * Utility methods to work with DigiDoc Service files
 *
 * @package SK\Digidoc\Example\Helpers
 */
class FileHelper
{

    /**
     * Array of allowed digital documents. Array keys are the file extensions and values are the corresponding arrays of
     * mime types.
     */
    public static $allowedDigitalDocuments = array(
        'ddoc'  => array('application/x-ddoc'),
        'bdoc'  => array('application/vnd.etsi.asic-e+zip', 'application/vnd.bdoc-1.0'),
        'asice' => array('application/vnd.etsi.asic-e+zip', 'application/vnd.bdoc-1.0'),
        'sce'   => array('application/vnd.etsi.asic-e+zip', 'application/vnd.bdoc-1.0'),
    );

    /**
     * Checks if there is errors with the named file in the request.
     *
     * @param string $inputName        - Name of the input used to upload the file.
     * @param array  $allowedFileTypes - Map where keys are allowed file extensions and values are
     *                                   arrays of corresponding *allowed MIME types. If this is left as null
     *                                   then there is no restrictions in file and *mime types.
     *
     * @throws \Exception
     * @throws FileException - Throws an Exception with the corresponding message if there is a problem with the file
     *                         upload.
     */
    public static function checkUploadedFileForErrors($inputName, $allowedFileTypes = null)
    {
        if (($_FILES[$inputName]['error'] > 0)) {
            throw self::uploadErrorCodeToException($_FILES[$inputName]['error']);
        }

        if ($allowedFileTypes !== null) {
            $extension = self::getUploadedFilesExtension($inputName);
            if (!array_key_exists($extension, $allowedFileTypes)) {
                throw new FileException('Uploaded file is in unsupported type.');
            }

            $mimeType = $_FILES[$inputName]['type'];
            if (!in_array($mimeType, $allowedFileTypes[$extension], true)) {
                throw new FileException("Uploaded file has an unsupported mime type '$mimeType'.");
            }
        }

        $fileName = $_FILES[$inputName]['name'];
        CommonUtils::debugLog("User uploaded file '$fileName' successfully.");
    }

    /**
     * Parses the uploaded containers file extension.
     *
     * @param string $name - Name of the input used to upload the file.
     *
     * @return string - File extension as string.
     */
    public static function getUploadedFilesExtension($name)
    {
        return self::parseFileExtension($_FILES[$name]['name']);
    }

    /**
     * Parses file extension. Splits file name by '.' and returns the second half lowered.
     *
     * @param string $filename - File name or path.
     *
     * @return string - File extension as string.
     */
    public static function parseFileExtension($filename)
    {
        $temp = explode('.', $filename);

        return strtolower(end($temp));
    }

    /**
     * Moves the uploaded file to upload directory specified in configuration.php HASHCODE_APP_UPLOAD_DIRECTORY constant
     *
     * @param  string $fileInputName - Name of the file input through which the file is uploaded.
     *
     * @return string - New location of the datafile.
     *
     * @throws FileException - If there was a problem with moving the file to the user specified directory.
     * @throws \Exception
     */
    public static function moveUploadedFileToUploadDir($fileInputName)
    {
        $filename = $_FILES[$fileInputName]['name'];
        $uploadDirectory = self::getUploadDirectory();

        if (!file_exists($uploadDirectory) && !mkdir($uploadDirectory)) {
            throw new FileException("There was a problem creating a directory '$uploadDirectory' for uploaded file storage.");
        }

        $destination = $uploadDirectory.DIRECTORY_SEPARATOR.$filename;
        if (!move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $destination)) {
            throw new FileException('There was a problem saving the uploaded file to disk.');
        }
        CommonUtils::debugLog("Uploaded file moved to location '$destination'.");

        return $destination;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public static function getUploadDirectory()
    {
        $dirPath = HASHCODE_APP_UPLOAD_DIRECTORY.CommonUtils::getDdsSessionCode();

        CommonUtils::debugLog('System variable name: '.SYSTEM_UPLOAD_PATH_ENVIRONMENT_VARIABLE);
        CommonUtils::debugLog('System upload directory: '.getenv(SYSTEM_UPLOAD_PATH_ENVIRONMENT_VARIABLE));

        if (getenv(SYSTEM_UPLOAD_PATH_ENVIRONMENT_VARIABLE) !== false) {
            $dirPath = getenv(SYSTEM_UPLOAD_PATH_ENVIRONMENT_VARIABLE).DIRECTORY_SEPARATOR.CommonUtils::getDdsSessionCode();
        }

        CommonUtils::debugLog("Upload directory: '$dirPath'.");

        return $dirPath;
    }

    /**
     * Deletes a directory or a file if one exists on a given path.
     *
     * @param string $path - Path to delete. WARNING! Deletes everything in this path recursively with its contents.
     */
    public static function deleteIfExists($path)
    {
        if (!file_exists($path)) {
            return;
        }
        if (!is_dir($path)) {
            unlink($path);

            return;
        }

        foreach (glob($path.'/*') as $file) {
            if (is_dir($file)) {
                self::deleteIfExists($file);
            } else {
                unlink($file);
            }
        }
        rmdir($path);
    }

    /**
     * Resolves possible upload errors to human readable messages.
     * http://php.net/manual/en/features.file-upload.errors.php
     *
     * @param $code - Upload error code.
     *
     * @return \Exception - Corresponding exception.
     */
    private static function uploadErrorCodeToException($code)
    {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
                $message = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = 'The uploaded file was only partially uploaded';
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = 'No file was uploaded';
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $message = 'Missing a temporary folder';
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $message = 'Failed to write file to disk';
                break;
            case UPLOAD_ERR_EXTENSION:
                $message = 'File upload stopped by extension';
                break;

            default:
                $message = 'Unknown upload error';
                break;
        }

        return new \Exception($message, $code);
    }
}
