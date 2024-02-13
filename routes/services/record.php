<?php
require_once "controllers/records_controller.php";
$gActions = new GeneralActions;

if (empty($_POST)) {
    $gActions->emitResponse(400, 'Error', array('key'=>'message', 'value'=>"Empty Post data.")); 
    return;
}

$records = new RecordsController;
$records->PostData($method, $_POST);


?>