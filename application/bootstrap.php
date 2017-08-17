<?php
require_once 'application/core/config.php';
require_once 'application/core/model.php';
require_once 'application/core/view.php';
require_once 'application/core/controller.php';
require_once 'application/core/route.php';

require_once 'application/database/SQL/Mysqli.php';
require_once 'application/database/SQL/Exception.php';

config::refreshData();

$router = new Route($_SERVER['REQUEST_URI']);

// Old router
// require_once 'application/core/route.php';
// Route::start(); 
// spl_autoload_register(function ($class) {
//     include '/application/core/' . $class . '.php';
// });


?>