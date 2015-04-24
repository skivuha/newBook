<?php
require_once 'config.php';
require_once 'function.php';

set_include_path(get_include_path()
	.PATH_SEPARATOR.'lib/controllers'
	.PATH_SEPARATOR.'lib/models'
	.PATH_SEPARATOR.'lib/core'
	.PATH_SEPARATOR.'lib/view');

/**
 * Autoload a exists file for caused classes
 *
 * @param class: Name of class
 * @return bool: Return true if file exists, false is not.
 */
function __autoload($class)
{
	if(isset_file($class.'.php'))
	{
		require_once($class.'.php');
	}
	else
	{
		Error404Controller::index();
	}
}
$router = Router::getInstance();
$router->route();
?>