<?php

require_once "connection.php";

class AccountsModel{

    public $con;

    public function __construct(){
        $this->con = Connection::connect();
    }

    public function getAccounts(){

        $result = pg_query($this->con, "SELECT * FROM cuenta WHERE cue_usuario='{$_SESSION['usuId']}'");
        $resCuenta  = pg_fetch_all($result);

        return array('status'=>true, 'data'=>$resCuenta);
    }

    public function createAccounts($postFields){

        $sql = "INSERT INTO cuenta (cue_nombre,cue_usuario,cue_estado) VALUES ($1, $2, $3)";
        $result = pg_query_params($this->con, $sql, array($postFields['nombre'],$_SESSION['usuId'],true));

        if($result){
            return array('status'=>true, 'msg'=>'');
        }else{
            return array('status'=>false, 'msg'=> pg_last_error($this->con));
        }
    }

    public function updateAccounts($postFields){

        $state = "";
        if (isset($postFields['estado'])) {
            $value = ($postFields['estado'] == 1) ? 'TRUE' : 'FALSE';
            $state = ",cue_estado=$value";
        }

        $sql = "UPDATE cuenta SET 
                    cue_nombre='{$postFields['nombre']}'
                    $state 
                WHERE cue_codigo={$postFields['codigo']} AND cue_usuario={$_SESSION['usuId']}";
        $result = pg_query($this->con, $sql);
        $aff = pg_affected_rows($result);

        if($aff>0){
            return array('status'=>true, 'msg'=>'');
        }else{
            return array('status'=>false, 'msg'=> "There was an error trying to update the registry ->".pg_last_error($this->con));
        }
    }

}

?>