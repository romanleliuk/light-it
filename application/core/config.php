<?php
/**
 * Configuration class, final
 */
final class config {
	// For router
	const DEFAULT_CONTROLLER = 'signin';
	const DEFAULT_ACTION = 'index';
	
	const PATH_MODELS = 'application/models/model_';
	const PATH_MODELS_API = '/../models/';
	const PATH_CONTROLLERS = 'application/controllers/controller_';
	const PATH_VIEWS = 'application/views/';
	
	const ACTION_PRE = 'action_';
	const MODEL_PRE = 'Model_';
	const CONTROLLER_PRE = 'Controller_';
	
	//For login
	const FAIL_LOGIN = 'Invalid username!';
	const FAIL_PASSWORD = 'Wrong password!';
	
	// other
	const DOMAIN_NAME = 'http://localhost/light-it/';
	
	// DB
	const DB_LOCAL_PASSWORD = '';
	const DB_SERVER_PASSWORD = '';

	// GoogleAuth
	const GOOGLE_CLIENT_ID = '983362370773-93ijuq89mindcirr354hmlf3kp1kea5e.apps.googleusercontent.com';
	const GOOGLE_CLIENT_SECRET = 'eCxmaPfKxG3qgLUXNWGG4GH_';
	const GOOGLE_REDIRECT_URI = 'http://localhost/light-it/signin/process';
	const AUTH_URI = 'https://accounts.google.com/o/oauth2/auth';
	const TOKEN_URI = 'https://accounts.google.com/o/oauth2/token';

	public static $db_password;
	
	public static function refreshData() {
		self::$db_password = ($_SERVER['SERVER_ADDR'] == '127.0.0.1' && $_SERVER['SERVER_PORT'] == '80')
			? self::DB_LOCAL_PASSWORD 
			: self::DB_SERVER_PASSWORD;
	}
}

?>