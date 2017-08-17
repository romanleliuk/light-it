<?
class Controller_Signin extends Controller
{
	public function __construct($status=false) {
		$this->model = new Model_Signin();
		$this->view = new View();
	}
	public function action_index() {
		if(!$this->model->isLogined()) {
			$data = $this->model->generateUrl();
			$this->view->generate('signin_view.php', 'template_view.php', $data);
		}
		else {
			$this->model->redirectTo('/light-it/board');
		}
	}
	public function action_process($variable,$value) {
		if ($variable == 'code' && $value) {
			$userData = $this->model->getInfo($value);
			
			$user = ($userData['id']) 
				? $this->model->findUser($userData['id']) 
				: false;
			
			$status = ($user) 
				? $this->model->addOrRefreshUser($userData)
				: $this->model->addOrRefreshUser($userData,'add'); 
			
			if ($status) {
				$data = true;
				$newData = $this->model->getUser($userData['id']);
				$this->model->setSession('user', $newData);
			}
			else {
				$data = false;
			}

			($data) 
				? $this->model->redirectTo('/light-it/board') // if all good
				: $this->model->redirectTo('/light-it/signin'); // if something bad
		}
		else {
			$this->model->redirectTo('/light-it/signin'); // wrong code or empty
		}
	}
}