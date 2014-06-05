<?php
/*Настройки*/
//error_reporting(E_ALL);
/*date_default_timezone_set('Europe/Kiev');*/
date_default_timezone_set('Europe/Moscow');
ini_set ("session.use_trans_sid", true); // не передавать COOKIE в строке
//ini_set('display_errors', 1);

/*Константы*/
define('HOST_DB','195.110.6.32');
define('LOGIN_DB','freepbx');
define('PASS_DB','00mostwanted8');
define('NAME_DB','asteriskcdrdb');
define('DS',DIRECTORY_SEPARATOR);
define('APP_PATH', __DIR__. DS. '..'. DS. 'app'. DS);
define('ROOT',$_SERVER['DOCUMENT_ROOT'].'/');
define('INDEXPAGE','auth');
define('ITEMS_PER_PAGE', '20');


