<?php

require_once "connection.php";


class UsersModel{

    public $con;

    public function __construct(){
        $this->con = Connection::connect();
    }

    public function getUserLogin(){

        $result = pg_query($this->con, "SELECT * FROM usuario");
        $resCuenta  = pg_fetch_all($result);

        return $resCuenta;
    }

    public function createUser($postFields){

        $passw = password_hash($postFields["password"], PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuario (usu_nombre,usu_apellido,usu_correo,usu_clave) VALUES ($1, $2, $3, $4)";
        $result = pg_query_params($this->con, $sql, array($postFields['nombre'],$postFields['apellido'],$postFields['email'], $passw));

        if($result){
            return array('status'=>true, 'msg'=>'');
        }else{
            return array('status'=>false, 'msg'=> pg_last_error($this->con));
        }
    }
}


?>