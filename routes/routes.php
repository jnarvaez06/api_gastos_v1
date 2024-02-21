<?php
include_once "config/general.php";
$gActions = new GeneralActions;

$routes = explode('/',$_SERVER['REQUEST_URI']);
$routes = processRoutes(array_merge(array_filter($routes)));

if(empty($routes)){
    $gActions->emitResponse(404, 'Error', array('key'=>'message', 'value'=>"Function Not Found")); 
    return;
}

$method =  $routes[0];

$requestMethod = $_SERVER['REQUEST_METHOD'];

if (!empty($routes) && $requestMethod != "") {

    $routeConfig = array(
        'createUser'=>'services/users.php',
        'login'=>"services/login.php",
        'getAccounts' => "services/accounts.php",
        'createAccounts' => "services/accounts.php",
        'createRecord' => "services/record.php"
    );

    $redirect = isset($routeConfig[$method]) ? $routeConfig[$method] : "";

    if ($redirect == "") {
        $gActions->emitResponse(400, "Invalid Method, <$method> doesn't exist");
        return;
    }

    if (!in_array($method,array('createUser','login'))) {
        include "services/login.php";
        $login = new LoginModel;

        $token  = str_replace('Bearer ', '', $_SERVER['HTTP_AUTHORIZATION']);
        $data   = $login->getDataUserToken($token);
        $res    = $gActions->ValidToken($data);

        if (!$res['status']) {
            $gActions->emitResponse(400, $res['msg']);
            return;
        }
    }


    // REDIRECCIONA A LAS ACCION OBTENIDA DE ROUTES
    include "$redirect";

    return;    
}


#################### FUNCTIONS ########################

function processRoutes($routes) {
    $newRoutes = array();
    $flag = false;
    foreach ($routes as $val) {
        if($val==$_SERVER['APP_NAME']){
            $flag = true;
            continue;
        }         
        if ($flag) {
            $newRoutes[] = $val;
        }
    }
    return $newRoutes;
}

?>