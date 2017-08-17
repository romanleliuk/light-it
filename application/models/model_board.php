<?php
class Model_Board extends Model {
	public function getMessages() {
		$db = DB_Mysqli::getInstance(); // Connect BD
		$response = array_merge($db->customGetMessage('normal'), $db->customGetMessage('reverse'));
		if (!$response['SQL_PARSE'] && $response) {
			return $response;
		}
		else {
			return false;
		}
	}
	public function addMessage() {
		$parent = $this->clearHtml($_POST['parent']);
		$message = $this->clearHtml($_POST['message']);

		$requestArray = array(
			'id_user' => (int)$_SESSION['user']['id_user'],
			'message' => $message,
			'time'   => time(),
			'parent_id_message' => $parent
		);

		if($parent == 'null') {
			array_pop($requestArray);
		}

		$bd = DB_Mysqli::getInstance(); // Connect BD
		$response = $bd->insertAssocOne('message', $requestArray);
		if (!$response['SQL_PARSE']) {
			$returnArray['success'] = true;
		}
		else {
			$returnArray['success'] = false;
			$returnArray['error'] = $response['SQL_PARSE'];
		}
		return $returnArray;
	}
}