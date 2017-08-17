<?php
class Controller_Signout extends Controller {
	public function __construct() {
		$this->model = new Model_Signout();
	}
	
	public function action_index() {
		$data = $this->model->logout();	
	}
}