<?php
include_once "models/login_model.php";

class LoginController{

    static function PostData($method, $postFields){
        $gActions = new GeneralActions;

        $valid = self::ValidateFields($postFields);
        if(!$valid['status']){
            return array(
                'code'=> 400,
                'result'=>'Error',
                'message'=>array('key'=>'message', 'value'=>$valid['msg'])
            );
        }

        $model = new LoginModel;
        $auth = $model->validateUser($postFields);

        if($auth==""){
            return array(
                'code'=> 400,
                'result'=>'Error',
                'message'=>array('key'=>'message', 'value'=>"Wrong credentials")
            );
        }

        $tokenJWT = $gActions->jwt($auth,$postFields['user']);

        // ASOCIA EL TOKEN AL USUARIO
        $assoc = $model->associateTokenUser($tokenJWT);

        if ($assoc['status']) {
            return array(
                'code'=> 200,
                'result'=>'Success',
                'message'=>array('key'=>'message', 'value'=>array('token'=>$tokenJWT['token']))
            );
        }else{
            return array(
                'code'=> 400,
                'result'=>'Error',
                'message'=>array('key'=>'message', 'value'=>"Authentication Failed")
            );
        }
        
    }

    static function ValidateFields($postFields){
        $fields = array(
            'user' => array('dataType'=>'text', 'isRequired'=>true, 'length'=>100),
            'password' => array('dataType'=>'text', 'isRequired'=>true,'length'=>100000),
        );

        $gActions = new GeneralActions;
        return $gActions->verifyPostFields($fields, $postFields);
    }

}


?>