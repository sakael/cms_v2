<?php

session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';

use Aws\S3\S3Client;

//DB 
define('READ_HOST','dbcluster-ams3-123bestdeal-nl-read-only-do-user-3545834-0.db.ondigitalocean.com');
define('WRITE_HOST','dbcluster-ams3-123bestdeal-nl-do-user-3545834-0.db.ondigitalocean.com');
define('USER','doadmin');
define('PASSWORD','m54q3939jzhzwj5e');
define('DB_NAME','cms');
define('PORT','25060');

// DigitalOcean Cluster
DB::$read_host = READ_HOST;
DB::$write_host = WRITE_HOST;
DB::$user = USER;
DB::$password = PASSWORD;
DB::$dbName = DB_NAME;
DB::$port = PORT;
DB::$encoding = 'utf8';

//Set language variable globally
if (isset($_REQUEST['lang'])) {
    setcookie('language', $_REQUEST['lang'], time() + 63072000, '/');
    define('language', $_REQUEST['lang']);
} elseif (isset($_COOKIE['language'])) {
    define('language', $_COOKIE['language']);
} else {
    define('language', 'nl');
}



define('LANGUAGE', language);
//Set files directory for uploaded files
define('FILES_DIR', __DIR__ . '/files/');
//Set tmp directory for tmp files
define('TMP_DIR', __DIR__ . '/tmp/');
//Set Root directory
define('ROOT_DIR', __DIR__);
//Set Image URL
define('IMAGE_PATH', 'https://ams3.cdn.123bestdeal.nl');

define('SITE_URL','https://beta.123bestdeal.nl');

//Set Zebra printers
define('ZEBRA_IP_1', '10.0.0.24:6101');
define('ZEBRA_IP_2', '10.0.0.25:6100');
define('PRESECRET', 'gg39^*&T(#@ewb^(#');

//sendinblue
if (!defined('SENDINBLUE_KEY')) {
    define('SENDINBLUE_KEY', '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');
}

///MSP
define('MSP_TEST_API', false);
if (!defined('BASE_URL')) {
    if (isset($_SERVER['SERVER_PORT'])) {
        define('BASE_URL', ($_SERVER['SERVER_PORT'] == 443 ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . dirname($_SERVER['SCRIPT_NAME']) . "/");
    }
}
if (!defined('API_KEY')) {
    if (MSP_TEST_API) {
        define('API_KEY', 'bcb5b09ab095f631fb358e332f174d95c3bef7d1');
    } else {
        define('API_KEY', '5db426ae8544137c8132ad43bcec8f269d60fb89');
    }
}
if (!defined('API_URL')) {
    if (MSP_TEST_API) {
        define('API_URL', 'https://testapi.multisafepay.com/v1/json/'); //test is https://testapi.multisafepay.com/v1/json/, for live environment use https://api.multisafepay.com/v1/json/
    } else {
        define('API_URL', 'https://api.multisafepay.com/v1/json/'); //test is https://testapi.multisafepay.com/v1/json/, for live environment use https://api.multisafepay.com/v1/json/
    }
}
if (!defined('TOOLKIT_VERSION')) {
    define('TOOLKIT_VERSION', '2.0.3');
}

if (!defined('EMAIL_TEMPLATES')) {
    define('EMAIL_TEMPLATES', ['3' => '6', '1' => '5','4'=>'7','5'=>'8','8'=>'9','9'=>'10','10'=>'11','11'=>'12','12'=>'13','13'=>'14','14'=>'15','15'=>'16','16'=>'17','17'=>'18','19'=>'19']);
}


// For DigitalOcean Spaces

$GLOBALS['s3'] = new S3Client([
    'version' => 'latest',
    'region'  => 'ams3',
    'endpoint' => 'http://134.209.203.45:9000',
    'use_path_style_endpoint' => true,
    'credentials' => [
            'key'    => 'HBJ38YZMP0675D4JB1YT',
            'secret' => 'aSCHCEiD4iTUVJbCL7b8nnnq4whTQHndnz+xRmDY',
        ],
]);
