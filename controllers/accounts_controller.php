<?php

require_once "models/accounts_model.php";

class AccountsController{

    static public function getData($method){

        $response = AccountsModel::getData($method);

        return $response;
    }
}

?>