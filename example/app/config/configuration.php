<?php
/**
 * DDS digidoc endpoint URL
 */
define('DDS_ENDPOINT_URL', 'https://tsp.demo.sk.ee/');

/**
 * Service name for the MID services in DDS(Will be displayed to users mobile phones screen during signing process)
 */
define('DDS_MID_SERVICE_NAME', 'Testimine');

/**
 * Explanatory message for the MID services in DDS.(Will be displayed to users mobile phones screen during signing
 * process)
 */
define('DDS_MID_INTRODUCTION_STRING', 'SK näidis hashcode allkirjastamine.');

/**
 * Directory where the uploaded files are copied and temporary files stored. SHOULD END WITH A DIRECTORY_SEPARATOR!!!
 */
define('HASHCODE_APP_UPLOAD_DIRECTORY', dirname(__DIR__).DIRECTORY_SEPARATOR.'upload'.DIRECTORY_SEPARATOR);

/**
 * If this is set to TRUE, then all SOAP envelopes used for communication with DigiDocService are logged.
 */
define('LOG_ALL_DDS_REQUESTS_RESPONSES', false);

/**
 * If this is set to FALSE, then all information logging in this application will be turned off.
 */
define('HASHCODE_APP_LOGGING_ON', true);

define('SYSTEM_UPLOAD_PATH_ENVIRONMENT_VARIABLE', 'DDS_SYSTEM_UPLOAD_PATH');

define('CSRFP_SECRET', 'djf49u0ujnvandbep980u30r9303ri9ur949rgiu0ru');
