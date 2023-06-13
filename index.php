<?php
require_once "routes.php";


function getParam($variableName, $default = null) {

    if(array_key_exists($variableName, $_REQUEST))
        return $_REQUEST[$variableName];

    $urlParts = explode('/', preg_replace('/\?.+/', '', $_SERVER['REQUEST_URI']));
    $position = array_search($variableName, $urlParts);
    if($position !== false && array_key_exists($position+1, $urlParts))
        return $urlParts[$position+1];

    return $default;
}
try{
    
session_start();
$url = $_SERVER['REQUEST_URI'];
$separed_url = explode('/', $url);
if(array_key_exists($separed_url[1], $routes)){
    $function = $routes[$separed_url[1]];
    $params = getParam($separed_url[1]);
    if($params != null){
        $function($params);
    }
    else{
        $function();
    }
}
else{
    if(str_starts_with($separed_url[1], '?')){
        $function = $routes[$separed_url['']];
        $function();
    }
}
}
catch(Exception $e){
    echo $e;
}
?>