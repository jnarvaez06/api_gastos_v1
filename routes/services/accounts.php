<?php

require_once "controllers/accounts_controller.php";
$gActions = new GeneralActions;

$data = new AccountsController();
$response = $data->getData($method,$_POST);

if ($response['status']) {
    $gActions->emitResponse(200,"Success",array('key'=>'data','value'=>$response));
}else{
    $gActions->emitResponse(400, 'Error', array('key'=>'message', 'value'=>$response['msg'])); 
}
?>