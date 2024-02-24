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

        $sql = "SELECT usu_codigo,usu_nombre,usu_apellido,usu_correo,usu_clave FROM usuario WHERE usu_correo = '{$postFields['user']}'";
        $res = pg_query($this->con, $sql);
        if ($res) {
            $row = pg_fetch_assoc($res);
            if ($row && password_verify($postFields['password'], $row['usu_clave'])) {
                return $row['usu_codigo'];
            } else {
                return "";
            }
        }else{
            return "";
        }
    }

    public function associateTokenUser($token){

        $sql = "UPDATE usuario SET usu_token=$1,usu_expira=$2 WHERE usu_codigo = $3";
        $result = pg_query_params($this->con, $sql, array($token['token'],$token['dataToken']['exp'],$token['dataToken']['data']['id']));

        if($result){
            return array('status'=>true, 'msg'=>'');
        }else{
            return array('status'=>false, 'msg'=> pg_last_error($this->con));
        }
    }

    public function getDataUserToken($token){
        $sql = "SELECT usu_codigo,usu_correo,usu_expira FROM usuario WHERE usu_token = '$token'";
        $res = pg_query($this->con, $sql);
        $row = pg_fetch_assoc($res);

        return array(
            'usu_codigo'=>$row['usu_codigo'],
            'usu_correo'=>$row['usu_correo'],
            'usu_expira'=>$row['usu_expira']
        );
    }

    public function startSession($data){
        session_start();
        $_SESSION['usuId'] = $data['usu_codigo'];
        $_SESSION['usuEmail'] = $data['usu_correo'];
    }
}


?>