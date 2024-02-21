<?php

require_once "controllers/accounts_controller.php";
$gActions = new GeneralActions;

if (empty($_POST)) {
    $gActions->emitResponse(400, 'Error', array('key'=>'message', 'value'=>"Empty Post data.")); 
    return;
}

$data = new AccountsController();
$response = $data->getData($method);

$gActions->emitResponse(200,"Success",array('key'=>'data','value'=>$response));

?>