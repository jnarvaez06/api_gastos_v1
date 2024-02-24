<?php

require_once "models/accounts_model.php";

class AccountsController{

    static public function getData($method, $postFields=array()){

        $accounts = new AccountsModel();

        switch ($method) {
            case 'getAccounts':
                $response = $accounts->getAccounts();                
                break;

            case 'createAccounts':
                $valid = self::ValidateFields($postFields,'create');
                if(!$valid['status']){
                    return $valid;
                }

                $response = $accounts->createAccounts($postFields); 

                break;

            case 'updateAccounts':
                $valid = self::ValidateFields($postFields,'update');
                if(!$valid['status']){
                    return $valid;
                }

                $response = $accounts->updateAccounts($postFields); 

                break;
            
            default:
                # code...
                break;
        }

        return $response;
    }


    static function ValidateFields($postFields, $action){

        if($action=='create'){
            $fields = array(
                'nombre' => array('isRequired'=> true,'dataType'=>'text', 'length'=>30),
            );            
        }else{
            $fields = array(
                'nombre' => array('isRequired'=> true,'dataType'=>'text', 'length'=>30),
                'codigo' => array('isRequired'=> true,'dataType'=>'numeric'),
                'estado' => array('isRequired'=> false,'dataType'=>'enum', 'values'=>array(1,0)),
            );
        }

        $gActions = new GeneralActions;
        return $gActions->verifyPostFields($fields, $postFields);
    }
}

?>