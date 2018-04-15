<?php
require __DIR__ . '/../src/Database.php';

session_start();
$response = [];
//connect to DB
$conn = Database::getInstance();

######### Dynamic load php class file depend on request #########
$uriPathInfo = $_SERVER['PATH_INFO'];
//explode path info
$path = explode('/', $uriPathInfo);
$requestClass = $path[1];

//load class file
$requestClass = preg_replace('#[^0-9a-zA-Z]#', '', $requestClass);//remove all non alfanum chars from request
$className = ucfirst(strtolower($requestClass));

$classFile = '../src/'.$className.'.php';
require_once $classFile;

######### END DYNAMIC LOAD #########

$pathId = isset($path[2]) ? $path[2] : null;

if (!isset($response['error'])) {//process request if no db error
    include_once $className.'.php';
}

header('Content-Type: application/json');//return json header

if (isset($response['error'])) {
    header("HTTP/1.0 400 Bad Request");//return proper http code if error
}

echo json_encode($response);