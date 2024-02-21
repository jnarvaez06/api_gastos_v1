<?php

require_once "models/users_model.php";


class UsersController{

    static public function getData($method, $postFields){

        $user = new UsersModel;

        switch ($method) {

            case 'createUser':
                $valid = self::ValidateFields($postFields);
                if(!$valid['status']){                    
                    return $valid;
                }

                $response = $user->createUser($postFields);
                break;
            
            default:
                # code...
                break;
        }

        return $response;
    }

    static function ValidateFields($postFields){
        $fields = array(
            'nombre' => array('isRequired'=> true,'dataType'=>'text', 'length'=>100),
            'apellido' => array('isRequired'=> true,'dataType'=>'text', 'length'=>100),
            'email' => array('isRequired'=> true,'dataType'=>'email',),
            'password' => array('isRequired'=> true,'dataType'=>'text', 'length'=>3000),
        );

        $gActions = new GeneralActions;
        return $gActions->verifyPostFields($fields, $postFields);
    }
}

?>