<?php
date_default_timezone_set('Europe/Kiev');

define('SALT', "mimimi");
define('TEMPLATE', "templates/html/");
define('LANG', "templates/lang/");

//DB
define('DB_HOST', 'localhost');
define('DB_LOGIN', 'user2');
define('DB_NAME', 'user2');
define('DB_PASS','tuser2');

//main path
define('PATH', '/~user2/PHP/booker/');

//for parsing url
define('CONTROLLER', '3');
define('ACTION', '4');
define('PARAM', '5');

//path to templates
define('TEMPLATE', "/usr/home/user2/public_html/PHP/booker/templates/html/");
define('LANG', "/usr/home//user2/public_html/PHP/booker/templates/lang/");

//encode
define('STRING_LENGHT', 10);

//room
define('START_ROOM', 0);
define('END_ROOM', 3);

//error
define('ERROR_EMPTY', 'Field is empty');
define('ERROR_WRONG_DATA', 'Wrong data');
define('ERROR_ACCESS', 'Wrong name or password');
define('ERROR_EXISTS', 'E-mail or name already exists');
define('ERROR_BUSY', 'Sorry, this time alredy busy!');
define('ERROR_WEEKEND', 'Warrning, this day a weekend!');
define('ERROR_ACCESS', 'Access denied!');
?>
