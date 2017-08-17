<?
class Model_Signin extends Model {
	public function generateUrl() {
		//Генерация ссылки для аутентификации
		$url = 'https://accounts.google.com/o/oauth2/auth';
		$params = array(
			'client_id'     => config::GOOGLE_CLIENT_ID,
			'redirect_uri'  => config::GOOGLE_REDIRECT_URI,
			'response_type' => 'code',
			'scope'         => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile'
		);
		$link = config::AUTH_URI . '?' . http_build_query($params);
		return $link;
	}
	
	public function getInfo($code) {
		// Getting token
		$params = array(
			'client_id'     => config::GOOGLE_CLIENT_ID,
			'client_secret' => config::GOOGLE_CLIENT_SECRET,
			'redirect_uri'  => config::GOOGLE_REDIRECT_URI,
			'grant_type'    => 'authorization_code',
			'code'          => $code
		);

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, config::TOKEN_URI);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, urldecode(http_build_query($params)));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$result = curl_exec($curl);
		curl_close($curl);

		$tokenInfo = json_decode($result, true);

		// Getting user info
		if (isset($tokenInfo['access_token'])) {
			$params['access_token'] = $tokenInfo['access_token'];

			$data = json_decode(file_get_contents('https://www.googleapis.com/oauth2/v1/userinfo' . '?' . urldecode(http_build_query($params))), true);
		}
		return $data;
	}

	public function findUser($id){
		$db = DB_Mysqli::getInstance(); // Connect BD
		$response = $db->listByKeyOne('user', $id);
		if (!$response['SQL_PARSE'] && $response) {
			return true;
		}
		else {
			return false;
		}
	}

	public function addOrRefreshUser($data, $type = 'refresh'){
		$arrayInsert = array(
			'id' => $data['id'],
			'email' => $data['email'],
			'given_name' => $data['given_name'],
			'family_name' => $data['family_name'],
			'link' => $data['link'],
			'picture' => $data['picture'],
			'locale' => $data['locale']
			);
		
		$db = DB_Mysqli::getInstance(); // Connect BD
		$response = ($type == 'add')
			? $db->insertAssocOne('user', $arrayInsert)
			: $db->updateAssocByKey('user', $arrayInsert, $data['id'], 'id');
		
		if (!$response['SQL_PARSE']) {
			return true;
		}
		else {
			return false;
		}
	}
	public function getUser($id) {
		$db = DB_Mysqli::getInstance(); // Connect BD
		$response = $db->listByKeyOne('user', $id);
		if (!$response['SQL_PARSE'] && $response) {
			return $response;
		}
		else {
			return false;
		}
	}
}


