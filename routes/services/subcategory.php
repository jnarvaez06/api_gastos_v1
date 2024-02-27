<?php
require_once "controllers/subcategory_controller.php";
$gActions = new GeneralActions;

$data = new SubCategoryController();
$response = $data->getData($method,$_POST);

if ($response['status']) {
    $gActions->emitResponse(200,"Success",array('key'=>'response','value'=>$response));
}else{
    $gActions->emitResponse(400, 'Error', array('key'=>'message', 'value'=>$response['msg'])); 
}

?>