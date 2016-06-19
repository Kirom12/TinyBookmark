<?php
// Configuration file

// Enable authentification
define('ENABLE_AUTH', true);

// Location passwd.php (Authentification file) (absolute path)
// passwd.php must contain :
// >if(!defined('IN_CODE')) die('Not autorize');
// >define('PSEUDO', '');
// >define('PASSWORD_HASH', '');
//define('PASSWD_DIRECTORY', '/var/www/includes');

// Location of bookmarks.xml
// define('BOOKMARKS_LIBRARY', '');