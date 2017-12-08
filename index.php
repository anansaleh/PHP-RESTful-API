<?php

include_once 'system/config.php';
include_once 'system/request.php';


if (!array_key_exists('HTTP_ORIGIN', $_SERVER)) {
    $_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];
}

// get the HTTP method, path and body of the request
Request::init($config_setting);

$paths = explode("/", Request::$requestPath, 2);

if($paths[0] !== 'queue'){
    pageNotfound();
    exit();
}
//echo strlen(trim($paths[1]));

//Check parameter for filter
$tmpArray =["citizen", "anonymous"];
$params= "";
if (strlen(trim( $paths[1] )) > 0) {
    $params= trim($paths[1], "/");
    if(!in_array(strtolower($params), $tmpArray)){
        pageNotfound();
        exit();
    }
}

////////////////////////////////////////
// Load controller

include_once 'system/database.php';
include_once 'api/api_controller.php';

// instantiate database and product object
$database = new Database($config_database);
$db = $database->getConnection();
$api = New API($db);

switch( Request::$requestMethod ){
    case 'GET':
        // required headers
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        
        $api->getAllQueue($params);
        break;
    case 'POST':
        // required headers
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        
        $api->create();
        break;
    default :
        pageNotfound();       
}
$database->close();

function pageNotfound(){
    header('HTTP/1.0 404 Not Found');
    echo json_encode(
        array(
            "status" => "error",
            "message" => "page not found."
            )
    );
}
?>
