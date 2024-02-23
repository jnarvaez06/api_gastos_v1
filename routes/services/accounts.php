<?php

require_once "controllers/accounts_controller.php";
$gActions = new GeneralActions;

$data = new AccountsController();
$response = $data->getData($method);

$gActions->emitResponse(200,"Success",array('key'=>'data','value'=>$response));

?>