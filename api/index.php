<?php
include 'db.php';
require 'Slim/Slim.php';
require 'Login/Login_Model.php';
require_once('Slim/TokenAuth.php');
\Slim\Slim::registerAutoloader();

/*
Hybrid Login
*/
$site_url = "http://localhost/dealwebsite";
$login_config = 'Login/config.php';
require_once( "Login/Hybrid/Auth.php" );


$app = new \Slim\Slim();
$app->container->singleton( 'hybridInstance', function () {
    global $login_config;
    $instance = new Hybrid_Auth($login_config);

    return $instance;
} );

$model = new \Model\Login_Model( getDB() );
$app->add(new \TokenAuth($model));

//include login apis
include 'login_api.php';

//include deals apis
include 'deals_api.php';

$app->run();
?>