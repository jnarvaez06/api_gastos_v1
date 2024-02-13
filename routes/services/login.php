<?php
include_once "controllers/login_controller.php";

if (empty($_POST)) {
    $gActions->emitResponse(400, 'Error', array('key'=>'message', 'value'=>"Empty Post data.")); 
    return;
}

$login = new LoginController;
$result = $login->PostData($method, $_POST);

$gActions->emitResponse($result['code'], $result['result'], $result['message']);

?>