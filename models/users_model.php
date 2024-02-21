<?php

require_once "connection.php";


class UsersModel{

    public $con;

    public function __construct(){
        $this->con = Connection::connect();
    }

    public function createUser($postFields){

        $sql = "SELECT usu_correo FROM usuario WHERE usu_correo = '{$postFields['email']}'";
        $res = pg_query($this->con, $sql);
        $usu_correo = pg_fetch_result($res, 0);

        if ($usu_correo != "") {
            return array('status'=>false, 'msg'=> 'Email already exists');
        }

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