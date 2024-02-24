<?php

require_once "connection.php";

class CategoryModel{

    public $con;

    public function __construct(){
        $this->con = Connection::connect();
    }

    public function getCategory(){

        $result = pg_query($this->con, "SELECT * FROM categoria WHERE cat_usuario='{$_SESSION['usuId']}'");
        $data  = pg_fetch_all($result);

        return array('status'=>true, 'data'=>$data);
    }

    public function createCategory($postFields){

        $sql = "INSERT INTO categoria (cat_nombre,cat_usuario,cat_estado) VALUES ($1, $2, $3)";
        $result = pg_query_params($this->con, $sql, array($postFields['nombre'],$_SESSION['usuId'],true));

        if($result){
            return array('status'=>true, 'msg'=>'');
        }else{
            return array('status'=>false, 'msg'=> pg_last_error($this->con));
        }
    }

    public function updateCategory($postFields){

        $state = "";
        if (isset($postFields['estado'])) {
            $value = ($postFields['estado'] == 1) ? 'TRUE' : 'FALSE';
            $state = ",cat_estado=$value";
        }

        $sql = "UPDATE categoria SET 
                    cat_nombre='{$postFields['nombre']}'
                    $state 
                WHERE cat_codigo={$postFields['codigo']} AND cat_usuario={$_SESSION['usuId']}";
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