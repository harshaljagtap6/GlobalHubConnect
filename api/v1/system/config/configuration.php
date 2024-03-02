<?php
if (empty($_SERVER['SERVER_NAME'])) {
    require_once dirname(__FILE__)."/../../../../config.php";
}else if($_SERVER['SERVER_NAME'] == 'localhost'){
    require_once $_SERVER['DOCUMENT_ROOT']."/config.php";
}else{
    require_once $_SERVER['DOCUMENT_ROOT']."/config.php";
}
/**
 * GENERAL SETTINGS
 */
// error_reporting(E_ALL);				// display all errors
ini_set('display_errors','1');
@ini_set('register_globals', 'Off');	// make globals off runtime
@ini_set('magic_quotes_runtime', 'Off');// Magic quotes for
// date_default_timezone_set('UTC');
/**
 * SITE CONFIGURATION
 */
$path_http = pathinfo('https://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
$arrDirPath = explode("/", $path_http["dirname"]); 						//server path is deined here
define("SERVER_ROOT_DIR_PATH", substr(getcwd(), 0, (strlen(getcwd())-strlen($arrDirPath[count($arrDirPath)-1]))));
define("SERVER_ROOT_PATH", substr(getcwd(), 0, (strlen(getcwd())-strlen($arrDirPath[count($arrDirPath)-1]))));
$serverPath = $arrDirPath;
array_pop($serverPath);
$serverUrl = implode("/",$serverPath);
define("SERVER_URL_PATH", $serverUrl."/");
define("SERVER_PATH", $serverUrl."/");
$path_https = pathinfo('https://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
define("SERVER_SSL_PATH", $path_https["dirname"]."/");					// server https path is deined here
/**
 * DATABASE CONFIGURATION
 */

define('SITE_URL', $root_path);

/************************ SET VALUE ****************************/

?>
