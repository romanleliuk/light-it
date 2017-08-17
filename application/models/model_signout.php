<?php
class Model_Signout extends Model {
	public function logout() {
		session_destroy();
		self::redirectTo('/light-it/signin');
	}
}
