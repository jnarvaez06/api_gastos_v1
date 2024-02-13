<?php

require_once "connection.php";


class LoginModel{

    public $con;

    public function __construct(){
        $this->con = Connection::connect();
    }

    public function getUserLogin(){

        $result = pg_query($this->con, "SELECT * FROM usuario");
        $resCuenta  = pg_fetch_all($result);

        return $resCuenta;
    }

    public function validateUser($postFields){

        $sql = "SELECT usu_codigo FROM usuario WHERE usu_correo = '{$postFields['user']}' AND usu_clave = '{$postFields['password']}'";
        $res = pg_query($this->con, $sql);
        $usu_codigo = pg_fetch_result($res, 0);

        return $usu_codigo;
    }
}


?>