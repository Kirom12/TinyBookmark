<?php
namespace App;

class Bookmark 
{
	protected static $_instance = null;

	protected $_action;

	protected $_default_page_data = array(
		'title' => 'Tiny Bookmark', // will use APP_NAME by default
		'description' => 'Tiny PHP bookmark',
		'tags' => array('bookmark', 'link')
	);

	private function __construct(){}

	public function dispatch()
	{
		session_start();

		$action = $this->_getAction();
		$actionMethod = "{$action}Action";

		if ($action === null || !method_exists($this, $actionMethod)) {
			$this->_404();
		}
		if (!isset($_SESSION['auth']) && $actionMethod != "connectAction") {
	 		$this->_login();
		}

		$this->$actionMethod();
	}

	/**
	 * Check if the action exist
	 * @return string action
	 */
	protected function _getAction()
	{
		if (isset($_REQUEST['a'])) {
			$action = $_REQUEST['a'];

			if (in_array("{$action}Action", get_class_methods(get_class($this)))) {
				$this->_action = $action;
			}
		} else {
			$this->_action = 'index';
		}

		return $this->_action;
	}

	protected function _view($view ,$data = array())
	{
		extract($data);

		$content = ROOT . DS . 'view' . DS . "{$view}.php";

		if (!isset($layout)) {
			$layout = ROOT . DS . 'view' . DS . 'layout' . DS . 'layout.php';
		}

		if (file_exists($content)) {
			ob_start();
			include($content);
			$content = ob_get_contents();
			ob_end_clean();

			include $layout;
		} else {
			$this->_404("View {$view} not found");
		}
	}

	protected function _login()
	{
		$this->_reconnectFromCookie();

		$data = array(
	 		'page' => $this->_default_page_data
		);

		$this->_view('login', $data);

		exit();
	}

	protected function _reconnectFromCookie()
	{
		if (isset($_COOKIE['remember']) && !isset($_SESSION['auth'])) {
			define('IN_CODE','1');
			require_once $_SERVER['DOCUMENT_ROOT'] . DS . 'includes' . DS . 'passwd.php';

			$pseudo = PSEUDO;

			$remember_token = $_COOKIE['remember'];
			$expected = file_get_contents($_SERVER['DOCUMENT_ROOT'] . DS . 'includes' . DS . 'token');

			if ($expected == $remember_token) {
				$_SESSION['auth'] = $pseudo;
				setcookie('remember', $remember_token, time() + 60 * 60 * 24 * 14);

				$redirect_url = BASE_URL;
				header("HTTP/1.0 302 Found", true);
				header("Location: $redirect_url");
				exit();
			} else {
				setcookie('remember', null, -1);
			}
		}
	}

	protected function _404($message = 'Page not found.')
	{
		echo $message;

  		exit();
	}

	protected function _parseXML($file)
	{
		//$xml = simplexml_load_file($file);
		$xml = json_decode(json_encode((array) simplexml_load_file($file)), 1);

		if (!isset($xml['category'][0])) {
			$category = $xml['category'];
			unset($xml['category']);
			$xml['category'][0] = $category;
		}
		foreach ($xml['category'] as $k => $part) {
			if (!isset($part['link'][0])) {
				$link = $part['link'];
				unset($xml['category'][$k]['link']);
				$xml['category'][$k]['link'][0] = $link;
			}
		}

		return $xml;
	}

	protected function _strRandom($length)
	{
		$alphabet = "0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";
		return substr(str_shuffle(str_repeat($alphabet, $length)), 0, $length);
	}

	public function indexAction()
	{
		$bookmarks = $this->_parseXML(BOOKMARKS);

		$data = array(
			'bookmarks' => $bookmarks,
			'page' => $this->_default_page_data
		);

		$this->_view('index', $data);
	}

	public function connectAction() {
		if ($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_POST['pseudo']) || !isset($_POST['password'])){
			$this->_404();
		}

		// Add the password file outside the site
		// Site -> /var/www/public_html
		// Passwd -> /var/www/includes
		$passwdDirectory = $_SERVER['DOCUMENT_ROOT'] . DS . 'includes';
		if (!file_exists($passwdDirectory . DS . 'passwd.php')) {
			$this->_404('Password file not found');
		}

		define('IN_CODE','1');
		require_once $passwdDirectory . DS . 'passwd.php';

		$pseudo = htmlspecialchars($_POST['pseudo']);
		$password = htmlspecialchars($_POST['password']);

		if ($pseudo === PSEUDO && md5($password) === PASSWORD_HASH) {
			$_SESSION['auth'] = $pseudo;

			if (isset($_POST['remember']) && $_POST['remember'] === 'true') {
				$remember_token = $this->_strRandom(120);

				file_put_contents($passwdDirectory . DS . 'token', $remember_token);
				setcookie('remember', $remember_token, time() + 60 * 60 * 24 * 14);
			}
		}

		$redirect_url = BASE_URL;
		header("HTTP/1.0 302 Found", true);
		header("Location: $redirect_url");
		exit();
	}

	public function disconnectAction() {
		if (isset($_SESSION['auth'])) {
			unset($_SESSION['auth']);
			setcookie('remember', null, -1);
			unlink($_SERVER['DOCUMENT_ROOT'] . DS . 'includes' . DS . 'token');
		}

		$redirect_url = BASE_URL;
		header("HTTP/1.0 302 Found", true);
		header("Location: $redirect_url");
		exit();
	}

	/**
	 * Singleton
	 * 
	 * @return Bookmark
	 */
	public static function getInstance()
	{
		if(is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
}