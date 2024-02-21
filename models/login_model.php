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

        $sql = "SELECT usu_nombre,usu_apellido,usu_correo,usu_clave FROM usuario WHERE usu_correo = '{$postFields['user']}'";
        $res = pg_query($this->con, $sql);
        if ($res) {
            $row = pg_fetch_assoc($res);
            if ($row && password_verify($postFields['password'], $row['usu_clave'])) {
                return "OK";
            } else {
                return "";
            }
        }else{
            return "";
        }
        

    }
}


?>