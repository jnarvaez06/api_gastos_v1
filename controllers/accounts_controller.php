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
                $valid = self::ValidateFields($postFields);
                if(!$valid['status']){
                    return $valid;
                }

                $response = $accounts->createAccounts($postFields); 
                break;
            
            default:
                # code...
                break;
        }

        return $response;
    }


    static function ValidateFields($postFields){
        $fields = array(
            'nombre' => array('isRequired'=> true,'dataType'=>'text', 'length'=>30),
        );

        $gActions = new GeneralActions;
        return $gActions->verifyPostFields($fields, $postFields);
    }
}

?>