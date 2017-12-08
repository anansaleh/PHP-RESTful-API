<?php

/**
 * request_class
 * This class analysis the Request URL
 * It export the subfolder, viitual host, controllers and paarametrs from Url 
 * 
 *  1- Url Request: http://www.example.com/somthing1/something2/
 *  2- base URL: www.example.com
 *  3- subfolder: somthing1/ or null
 *  4- request query: somthing1/something2/ or something2/
 *  5- headers
 *  6- Request Method
 *
 * @author anan
 */
class Request {
    public static $requestPath;
    public static $requestMethod;
    
    /**
     * init()
     * This function read the $_SERVER values 
     * Analysis the request uri and script name 
     * and then create the local properties
     * 
     */
    public static function init($setting) {
        // get the HTTP method, path and body of the request
        self::$requestMethod = $_SERVER['REQUEST_METHOD'];
        
        //Parsing the request path
        
        $request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
        
        $requestPath= trim(str_replace($_SERVER["SCRIPT_NAME"], "", $_SERVER["REQUEST_URI"]), "/")."/";
        
        $request_subFolder = explode("/", trim($_SERVER["REQUEST_URI"], "/") . "/", 2)[0];

        if ($setting["subFolder"] != false) {            
            $url= $setting["host"] . "/" . $setting["subFolder"];
            if((strpos($url, $request_subFolder . "") > 0)){
                $paths = explode("/", $requestPath, 2);        
                 if ($paths[0] == trim($request_subFolder, "/")) {
                     unset($paths[0]);
                 }
                 $requestPath = implode("/", $paths);
            }
        }
        self::$requestPath = trim($requestPath, "/") . "/";
        
    }
}
