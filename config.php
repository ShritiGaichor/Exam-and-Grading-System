<?php
error_reporting(E_ALL);#E_ALL = 32767, E_STRICT = 2048
ini_set("display_errors", 1);#must be 0 for production environment & 1 while testing..
date_default_timezone_set('Asia/Calcutta');

const institute = 'SRM College';
const url = 'http://www.srmc.in/';
const title = 'Exam & Grading System';

if($_SERVER['HTTP_HOST'] == 'mis1.loc'){
    define('dbhost', '127.0.0.1');#MySQL connection parameters
    define('dbuser', 'root');
    define('dbpass', 'root');
    define('db', 'examsystem');
    define('docroot', '/');# public_html for images, GET, POST
}
else {
    define('dbhost', '127.0.0.1');#MySQL connection parameters
    define('dbuser', 'root');
    define('dbpass', 'root');
    define('db', 'coltene');
    define('docroot', '/');# www for images, GET, POST
}

define('root', __DIR__ . '/');# root reference
define('includes', root . 'includes/');# /includes
define('scripts', docroot . 'scripts/');# /jscripts
define('images', docroot . 'images/');# /images

spl_autoload_register(function($cls) {
    if(file_exists(includes . strtolower($cls) . '.php'))       require_once(includes . strtolower($cls) . '.php' );
//    else                                            require_once(includes . 'property.php' );
//    if(method_exists($cls, 'init'))                 $cls::init();//static init call
});

$con = new mysqli(dbhost, dbuser, '', db);
if ($con->connect_errno)  die("Error : Failed to connect database. Reason : " .  $con->connect_error);

$error = array(); $limit = 5;


class Department extends Common1{};
class Class1 extends Common1{};
class Examtype extends Common1{};
class Sem extends Common1{};
