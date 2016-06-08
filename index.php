<?php
require "vendor/autoload.php";

use \App\Bookmark;

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));
define('BOOKMARKS', __DIR__ . DS . 'library' . DS . 'bookmarks.xml');

Bookmark::getInstance()->dispatch();