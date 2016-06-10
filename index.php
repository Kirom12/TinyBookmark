<?php
require "vendor/autoload.php";

use \App\Bookmark;

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));
define('BOOKMARKS', __DIR__ . DS . 'library' . DS . 'bookmarks.xml');

$request_uri = parse_url($_SERVER['REQUEST_URI']);
$request_uri = explode("/", $request_uri['path']);
$script_name = explode("/", dirname($_SERVER['SCRIPT_NAME']));

$app_dir = array();
foreach ($request_uri as $key => $value) {
    if (isset($script_name[$key]) && $script_name[$key] == $value) {
        $app_dir[] = $script_name[$key];
    }
}

define('APP_DIR', rtrim(implode('/', $app_dir), "/"));
define('BASE_URL', "http" . "://" . $_SERVER['HTTP_HOST'] . APP_DIR);

Bookmark::getInstance()->dispatch();