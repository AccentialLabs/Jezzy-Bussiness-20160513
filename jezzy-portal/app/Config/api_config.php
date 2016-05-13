<?php

/**
 * Status possible for field validation in the generation of URL
 */
$paramsValidationStatus = array(
    'invalid_param' => 'REQUIRED_PARAM_FIELD'
);

/**
 * Remote FTP configuration to upload files
 */
$ftpUploadConfig = array (
		'host' => 'ftp.trueone.com.br',
		'user' => 'public',
		'password' => '8I%Mz@2mRQdt',
		'uploadFolder' => 'uploads/',
		'uploadPath' => 'public_html/',
		'url' => 'http://trueone.com.br' 
);

Configure::write ( 'ftpUploadConfig', $ftpUploadConfig );

/**
 * URL used according to the environment set up
 */
$apiEnvironment = 'testing';
$environmentURL = array(
    'local' => '',
    'development' => '',
    'testing' => 'http://localhost/jezzy-business-api/api/',
    'homolog' => '',
    'production' => ''
);

if (!defined('API_ENVIRONMENT_URL')) {
    define('API_ENVIRONMENT_URL', $environmentURL [$apiEnvironment]);
}

/**
 * FTP path to upload files
 */
if (!defined('FTP_ENVIRONMENT_URL')) {
    define('FTP_ENVIRONMENT_URL', "http://54.94.182.35/adventa/uploads/companies/");
}
/**
 * Local path for the upload
 */
if (!defined('LOCAL_PATH_TO_UPLOAD')) {
    define('LOCAL_PATH_TO_UPLOAD', "/var/www/acclabs/adventa/uploads/companies/");
}

if (!defined('LOCAL_PATH_TO_UPLOAD_IMAGE')) {
    define('LOCAL_PATH_TO_UPLOAD_IMAGE', "/var/www/acclabs/adventa/uploads/offers/");
}
if (!defined('FTP_ENVIRONMENT_URL_IMAGES')) {
    define('FTP_ENVIRONMENT_URL_IMAGES', "http://54.94.182.35/adventa/uploads/offers/");
}

/*
$ftpAccentialUploadConfig = array (
		'host' => '54.94.182.35',
		'user' => 'acclabs-ftp',
		'password' => 'ACCftp1000' 
);
*/

$ftpAccentialUploadConfig = array (
		'host' => '192.168.1.200',
		'user' => 'ftpuser',
		'password' => 'ACCftp1000' 
);

Configure::write ( 'ftpAccentialUploadConfig', $ftpAccentialUploadConfig );