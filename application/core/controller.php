<?
/**
 * General Controller
 */
class Controller {
	/**
	 * Instance choosen model
	 * @var object
	 */
	public $model;
	/**
	 * Instance choosen view
	 * @var object
	 */
	public $view;
	/**
	 * Instance api
	 * @var object
	 */
	public $api;
	/**
	 * Requested URI, exploded
	 * @var array
	 */
	public $uri;
	
	/**
	 * Constructor
	 * Creates the selected View
	 */
	public function __construct() {
		$this->view = new View();
	}
	
	/**
	 * Declaration default action
	 */
	public function action_index(){}

	/**
	 * Encding JSON
	 * @param array $arrToJson
	 * @return string cout
	 */
	public function json($arrToJson) {
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($arrToJson);
	}
}