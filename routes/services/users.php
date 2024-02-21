<?php

require_once "controllers/users_controller.php";
$gActions = new GeneralActions;

if (empty($_POST)) {
    $gActions->emitResponse(400, 'Error', array('key'=>'message', 'value'=>"Empty Post data.")); 
    return;
}

$data = new UsersController();
$response = $data->getData($method,$_POST);

if ($response['status']) {
    $gActions->emitResponse(200,"Success",array('key'=>'data','value'=>$response['msg']));
}else{
    $gActions->emitResponse(400, 'Error', array('key'=>'message', 'value'=>$response['msg'])); 
}


?>