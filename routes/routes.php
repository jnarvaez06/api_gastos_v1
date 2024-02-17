<?php
include_once "config/general.php";
$gActions = new GeneralActions;

echo "<pre>";
print_r($_SERVER);
echo "</pre>";

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
        'getAccounts' => "services/accounts.php",
        'createRecord' => "services/record.php",
        'login'=>"services/login.php"
    );

    $redirect = isset($routeConfig[$method]) ? $routeConfig[$method] : "";

    if ($redirect == "") {
        $gActions->emitResponse(400, "Invalid Method, <$method> doesn't exist");
        return;
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