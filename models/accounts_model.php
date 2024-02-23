<?php

require_once "connection.php";

class AccountsModel{

    public $con;

    public function __construct(){
        $this->con = Connection::connect();
    }

    public function getAccounts(){

        $result = pg_query($this->con, "SELECT * FROM cuenta");
        $resCuenta  = pg_fetch_all($result);

        return $resCuenta;
    }

}

?>