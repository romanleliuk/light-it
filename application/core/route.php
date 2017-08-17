<?php
/**
 * Routing implementation
 * Roman Leliuk,2017
 */
class Route {
	/**
	 * Constructor
	 * @param string $uri - requested address
	 */
	public function __construct($uri) {
		if(!isset($_GET)) {
		 	$uri = explode("/", $uri);
			$controller_name = (!empty($uri[1])) ? $uri[1] : config::DEFAULT_CONTROLLER;
			$action = (!empty($uri[2])) ? $uri[2] : config::DEFAULT_ACTION;
			$variable = (!empty($uri[3])) ? $uri[3] : false;
			$value = (!empty($uri[4])) ? $uri[4] : false;
		}
		else {
			$uri = explode("?", $uri);
			
			$clear_uri = explode("/", $uri[0]);
			$controller_name = (!empty($clear_uri[2])) ? $clear_uri[2] : config::DEFAULT_CONTROLLER;
			$action = (!empty($clear_uri[3])) ? $clear_uri[3] : config::DEFAULT_ACTION;

			$get_uri = explode ("=", $uri['1']);
			$variable = (!empty($get_uri[0])) ? $get_uri[0] : false;
			$value = (!empty($get_uri[1])) ? $get_uri[1] : false;
		}

		$controller_path = config::PATH_CONTROLLERS.strtolower($controller_name).'.php';
		$model_path = config::PATH_MODELS.strtolower($controller_name).'.php';
		
		// ONE MODEL, ONE CONTROLLER
		if ( file_exists($controller_path) && file_exists($model_path) ) {
			require_once "$controller_path";
			require_once "$model_path";
			$controller_name = config::CONTROLLER_PRE . ucfirst($controller_name);
			$action_name = config::ACTION_PRE . $action;
			$controller = new $controller_name(); // 
			$controller->uri = $uri;
		}
		else {
			Page_404::getInstance();
		}
		// Check method and run action
		if(method_exists($controller, $action_name)) {
			if ($variable && $value) {
				$controller->$action_name($variable, $value);
			}
			else if($variable && !$value) {
				$controller->$action_name($variable);
			}
			else {
				$controller->$action_name();
			}
		}
		else {
			Page_404::getInstance();
		}
	}
	private function __clone(){}
}
/**
 * Page 404 generation
 * Use singleton
 */
class Page_404 { 
	static private $_instance = null;

	private function __construct() {
		header('HTTP/1.1 404 Not Found');
		header("Status: 404 Not Found");
		include 'application/views/404_view.php';
	}

	private function __clone (){}

	static function getInstance() {
		if(self::$_instance == null) {
			self::$_instance = new Page_404();
		}
		return self::$_instance;
	}
}
?>