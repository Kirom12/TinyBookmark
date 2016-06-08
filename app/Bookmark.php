<?php
namespace App;

class Bookmark 
{
	protected static $_instance = null;

	protected $_action;

	protected $_default_page_data = array(
        'title' => 'Tiny Bookmark', // will use APP_NAME by default
        'description' => 'Tony PHP bookmark',
        'tags' => array('bookmark', 'link'),
        'page' => ''
    );

	private function __construct(){}

   	public function dispatch()
   	{
   		$action = $this->_getAction();
   		$actionMethod = "{$action}Action";

   		if ($action === null || !method_exists($this, $actionMethod)) {
   			$this->_404();
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

   	protected function _404($message = 'Page not found.')
   	{
   		echo $message;
   	}

   	public function indexAction()
   	{
   		$data = array(
   			'page' => $this->_default_page_data
		);

		$this->_view('index', $data);
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