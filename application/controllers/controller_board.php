<?
class Controller_Board extends Controller
{
	public function __construct($status=false) {
		$this->model = new Model_Board();
		$this->view = new View();
	}
	public function action_index() {
		$unsortedData = $this->model->getMessages();
		$this->view->generate('board_view.php', 'template_view.php', $unsortedData);
	}
}