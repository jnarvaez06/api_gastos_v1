<?php

require_once "connection.php";

class AccountsModel{

    static public function getData($method){

        $con = Connection::connect();
        $resData = array();

        switch ($method) {
            case 'getAccounts':
                $accounts = new AccountsModel();
                $resData = $accounts->getAccounts($con);                
                break;
            
            default:
                # code...
                break;
        }

        return $resData;

    }


    static public function getAccounts($con=null){

        $result = pg_query($con, "SELECT * FROM cuenta");
        $resCuenta  = pg_fetch_all($result);

        return $resCuenta;
    }

}

?>