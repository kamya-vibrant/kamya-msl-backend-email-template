<?php

require_once('envconfig.php');
$env_path = str_replace(basename(__DIR__),"",realpath(__DIR__)); ;
$__DotEnvironment = new DotEnvironment($env_path."/.env");

if (!defined('ENV')) define("ENV", getenv('APP_STATE'));

if (!defined('GIT_WEBHOOK_API')) :
	define('GIT_WEBHOOK_API', getenv('GIT_WEBHOOK_API'));
endif;

// Mailgun API Credentials start
 if(getenv("APP_STATE") == 'test' || getenv('APP_STATE') == 'local' || getenv("APP_STATE") == 'dev'){
	if(!defined('MAILGUN_API_KEY')) define("MAILGUN_API_KEY",getenv("MAILGUN_API_KEY_TEST"));
	if(!defined('MAILGUN_DOMAIN')) define("MAILGUN_DOMAIN",getenv("MAILGUN_DOMAIN_TEST"));
} else {
	if(!defined('MAILGUN_API_KEY')) define("MAILGUN_API_KEY",getenv("MAILGUN_API_KEY_PROD"));
	if(!defined('MAILGUN_DOMAIN')) define("MAILGUN_DOMAIN",getenv("MAILGUN_DOMAIN_PROD"));
}
// Mailgun API Credentials end

if (!defined('FATZEBRA_TESTMODE')) define("FATZEBRA_TESTMODE", getenv('FATZEBRA_TESTMODE'));

if(!FATZEBRA_TESTMODE){
	if (!defined('FATZEBRA_UN')) define("FATZEBRA_UN", getenv('FATZEBRA_UN'));
	if (!defined('FATZEBRA_TK')) define("FATZEBRA_TK", getenv('FATZEBRA_TK'));
}else{
	if (!defined('FATZEBRA_UN')) define("FATZEBRA_UN", getenv('FATZEBRA_UN_TESTMODE'));
	if (!defined('FATZEBRA_TK')) define("FATZEBRA_TK", getenv('FATZEBRA_TK_TESTMODE'));
}

if(ENV=="live"){
	if (!defined('DB_HOST')) {
		define("DB_HOST", getenv('LIVE_DB_HOST'));
		define("DB_USER", getenv('LIVE_DB_USER'));
		define("DB_PASS", getenv('LIVE_DB_PASS'));
		define("DB_NAME", getenv('LIVE_DB_NAME'));
		define("DB_PORT", getenv('LIVE_DB_PORT'));

		define("ONEHIVE_PORTAL", getenv('ONEHIVE_PORTAL_LIVE'));
		define("MSL_PORTAL", getenv('MSL_PORTAL_LIVE'));
	}
}elseif(ENV=="dev"){
	if (!defined('DB_HOST')) {
		define("DB_HOST", getenv('DEV_DB_HOST'));
		define("DB_USER", getenv('DEV_DB_USER'));
		define("DB_PASS", getenv('DEV_DB_PASS'));
		define("DB_NAME", getenv('DEV_DB_NAME'));
		define("DB_PORT", getenv('DEV_DB_PORT'));

		define("ONEHIVE_PORTAL", getenv('ONEHIVE_PORTAL_DEV'));
		define("MSL_PORTAL", getenv('MSL_PORTAL_DEV'));
	}
}else{
	if (!defined('DB_HOST')) {
		define("DB_HOST", getenv('LOCAL_DB_HOST'));
		define("DB_USER", getenv('LOCAL_DB_USER'));
		define("DB_PASS", getenv('LOCAL_DB_PASS'));
		define("DB_NAME", getenv('LOCAL_DB_NAME'));
		define("DB_PORT", getenv('LOCAL_DB_PORT'));

		define("ONEHIVE_PORTAL", getenv('ONEHIVE_PORTAL_LOCAL'));
		define("MSL_PORTAL", getenv('MSL_PORTAL_LOCAL'));
	}
}

$dbh = new PDO("mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME, DB_USER, DB_PASS);
?>

