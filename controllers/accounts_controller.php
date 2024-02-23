<?php

require_once "models/accounts_model.php";

class AccountsController{

    static public function getData($method){

        $accounts = new AccountsModel();

        switch ($method) {
            case 'getAccounts':
                $response = $accounts->getAccounts();                
                break;
            
            default:
                # code...
                break;
        }

        return $response;
    }
}

?>