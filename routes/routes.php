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

if ($requestMethod=='POST' && empty($_POST)) {
    $gActions->emitResponse(400, 'Error', array('key'=>'message', 'value'=>"Empty Post data...")); 
    return;
}


if (!empty($routes) && $requestMethod != "") {

    $routeConfig = array(
        'createUser'=> array('method'=>'services/users.php','type'=>'POST'),
        'login'=> array('method'=>"services/login.php",'type'=>'POST'),
        'getAccounts' => array('method'=>"services/accounts.php",'type'=>'GET'),
        'createAccounts' => array('method'=>"services/accounts.php",'type'=>'POST'),
        'updateAccounts' => array('method'=>"services/accounts.php",'type'=>'POST'),
        'createCategory' => array('method'=>"services/category.php",'type'=>'POST'),
        'getCategory' => array('method'=>"services/category.php",'type'=>'GET'),
        'updateCategory' => array('method'=>"services/category.php",'type'=>'POST'),
        'createSubCategory' => array('method'=>"services/subcategory.php",'type'=>'POST'),
        'getSubCategory' => array('method'=>"services/subcategory.php",'type'=>'GET'),
        'updateSubCategory' => array('method'=>"services/subcategory.php",'type'=>'POST'),
        'createRecord' => array('method'=>"services/record.php",'type'=>'POST'),
        'updateRecord' => array('method'=>"services/record.php",'type'=>'POST'),
        'createTransfer' => array('method'=>"services/record.php",'type'=>'POST'),
        'updateTransfer' => array('method'=>"services/record.php",'type'=>'POST'),
    );

    $redirect = isset($routeConfig[$method]['method']) ? $routeConfig[$method]['method'] : "";

    if ($redirect == "") {
        $gActions->emitResponse(400, "Invalid Method, <$method> doesn't exist");
        return;
    }

    $requestReceived = ($routeConfig[$method]['type']) ?? "";

    if ($requestReceived != $requestMethod) {
        $gActions->emitResponse(400, "Invalid http request, <$method> must be $requestReceived");
        return;
    }

    if (!in_array($method,array('createUser','login'))) {
        include_once "models/login_model.php";
        $login = new LoginModel;

        $auth1 = ($_SERVER['HTTP_AUTHORIZATION']) ?? "";
        $auth2 = ($_SERVER['HTTP_AUTH2']) ?? "";

        $authHTTP = ($auth1 != "") ? $auth1 : $auth2;

        $token  = str_replace('Bearer ', '', $authHTTP);

        $data   = $login->getDataUserToken($token);
        $res    = $gActions->ValidToken($data['usu_expira']);        
        
        if (!$res['status']) {
            $gActions->emitResponse(400, 'Error', array('key'=>'message', 'value'=>$res['msg']));
            return;
        }else{
            $login->startSession($data);
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