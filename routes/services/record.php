<?php
require_once "controllers/records_controller.php";
$gActions = new GeneralActions;

$data = new RecordsController();
$response = $data->getData($method,$_POST);

if ($response['status']) {
    $gActions->emitResponse(200,"Success",array('key'=>'response','value'=>$response));
}else{
    $gActions->emitResponse(400, 'Error', array('key'=>'message', 'value'=>$response['msg'])); 
}


?>