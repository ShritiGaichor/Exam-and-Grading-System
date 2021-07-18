<?php
define('starttime', microtime(true));#for calculating page parcing time..
require_once 'config.php';

session_start();

//entity exist? function??
$entity = isset($_REQUEST['entity']) ? ucfirst($_REQUEST['entity']) : 'App';
$request = isset($_REQUEST['request']) ? $_REQUEST['request'] : 'dashboard';
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
$ajax = isset($_REQUEST['ajax']) ? true : false;

if(isset($_REQUEST['validate']))
    if(User::validate())
        App::login ($_REQUEST['username']);
    else
        echo 'Wrong user password..';
    
if(isset($_REQUEST['logout'])) App::logout ();

if(!$ajax)  require includes. '_header.php';

if(isset($_SESSION['user'])){
    if(!$ajax)  require includes. '_menu.php';
    if($request == 'dashboard' or $request == 'list' or $request == 'options')
        $entity::$request();
    else{
        
        $x = new $entity($id);
        $x->$request();
    }
}
else
    App::showlogin ();

if(!$ajax)  require includes. '_footer.php';
