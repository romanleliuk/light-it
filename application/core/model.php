<?
/**
 * General Model
 */
class Model
{
	/**
	 * Return true if user LogIn or false if user Didn't SingIn;
	*/
	static public function isLogined() {
		return $status = ( !empty($_SESSION) ) ?  true : false;
	}
	/**
	 * Check post
	 * @return bool - true if post clear OR false if post is not clear;
	*/
	public function isClearPost() {
			return $state = ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST)) ? true : false; 
	}
	/**
	 * Redirecting to path
	 * @param string $path
	 */
	public function redirectTo($path = "") {
		if (!headers_sent()) {
			$domain = "http://" . $_SERVER['HTTP_HOST'];
			header("Location: " . $domain . $path);
			exit;
		}
	}
	/**
	 * Create new array inside session
	 * @param mixed $key
	 * @param array $arrayPut
	 */
	public function setSession($key , array $arrayPut) {
		unset($_SESSION[$key]);
		$_SESSION[$key] = $arrayPut;
		return true;
	}
	/**
	 * Update session information
	 * @param mixed $name
	 * @param array $arrayUpdate
	 */
	public function updateSession($name, array $arrayUpdate) {
		foreach ($_SESSION as $keySession => $fld) {
			if($keySession == $tableName) {
				foreach ($_SESSION[$keySession] as $field => $fieldValue) {
					if($arrayUpdate[$field]) {
						$_SESSION[$keySession][$field] = $arrayUpdate[$field];
					}
					else {
						continue;
					}
				}
			}
			else {
				continue;
			}
		}
	}
	/**
	 * Adding information to an already created session
	 * @param mixed $name
	 * @param array $arrayUpdate
	 */
	public function concatSession($key, $value) {
		$_SESSION[$key] = array_merge($_SESSION[$key],$value);
	}
	/**
	 * Set cookie
	 * @param string $name
	 * @param string $value
	 * @param int $time
	 * @param string $path
	 */
	public function setCookies($name, $value, $time, $path) {
		@setcookie($name, $value, $time, $path);
	}
	/**
	 * Set cookie
	 * @param string $html
	 * @return string
	 */
	public function clearHtml($html) {
		return strip_tags(trim($html));
	}	
}
