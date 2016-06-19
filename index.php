<?php
require "vendor/autoload.php";

use \App\Bookmark;

if (is_file($config_file = __DIR__ . DIRECTORY_SEPARATOR . 'config.php')) {
	require_once $config_file;
}

define('DS', DIRECTORY_SEPARATOR);

if (!defined('ENABLE_AUTH')) {
	define('ENABLE_AUTH', false);
}

if (!defined('PASSWD_DIRECTORY')) {
	define('PASSWD_DIRECTORY', $_SERVER['DOCUMENT_ROOT'] . DS . 'includes');
}

if (!defined('BOOKMARKS_LIBRARY')) {
	define('BOOKMARKS_LIBRARY', __DIR__ . DS . 'library' . DS . 'bookmarks.xml');
}

define('ROOT', __DIR__);

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