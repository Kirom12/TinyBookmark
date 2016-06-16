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
      $data = array(
         'page' => $this->_default_page_data
      );

      $this->_view('login', $data);

      exit();
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

      define('IN_CODE','1');
      require "$_SERVER[DOCUMENT_ROOT]/includes/passwd.php";

      $pseudo = htmlspecialchars($_POST['pseudo']);
      $password = htmlspecialchars($_POST['password']);

      if ($pseudo === PSEUDO && md5($password) === PASSWORD_HASH) {
         $_SESSION['auth'] = $pseudo;
      }

      $redirect_url = BASE_URL;
      header("HTTP/1.0 302 Found", true);
      header("Location: $redirect_url");
      exit();
   }

   public function disconnectAction() {
      if (isset($_SESSION['auth'])) {
         unset($_SESSION['auth']);
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