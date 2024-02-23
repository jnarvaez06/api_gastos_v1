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

        return array('status'=>true, 'msg'=>$resCuenta);
    }

    public function createAccounts($postFields){

        $sql = "INSERT INTO cuenta (cue_nombre,cue_usuario,cue_estado) VALUES ($1, $2, $3)";
        $result = pg_query_params($this->con, $sql, array($postFields['nombre'],1,true));

        if($result){
            return array('status'=>true, 'msg'=>'');
        }else{
            return array('status'=>false, 'msg'=> pg_last_error($this->con));
        }
    }

}

?>