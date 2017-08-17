<?

class Controller_Api extends Controller {
	function action_message() {
		require_once __DIR__. '/../models/model_board.php';
		$this->model = new Model_Board;
		$response = $this->model->addMessage();
		$this->json($response);
	}
}
?>