<?php

require_once "connection.php";

class SubCategoryModel{

    public $con;

    public function __construct(){
        $this->con = Connection::connect();
    }

    public function getSubCategory(){

        $result = pg_query($this->con, "SELECT * FROM subcategoria WHERE sub_usuario='{$_SESSION['usuId']}'");
        $data  = pg_fetch_all($result);

        return array('status'=>true, 'data'=>$data);
    }

    public function createSubCategory($postFields){

        $fields = array(
            $postFields['nombre'],
            $_SESSION['usuId'],
            true,
            ($postFields['categoria']) ?? "",
            ($postFields['tipo']) ?? ""
        );
        
        $sql = "INSERT INTO subcategoria (sub_nombre,sub_usuario,sub_estado,sub_catego,sub_tipo) VALUES ($1, $2, $3, $4, $5)";
        $result = pg_query_params($this->con, $sql, $fields);

        if($result){
            return array('status'=>true, 'msg'=>'');
        }else{
            return array('status'=>false, 'msg'=> pg_last_error($this->con));
        }
    }

    public function updateSubCategory($postFields){

        $update = "";
        if (isset($postFields['nombre'])) {
            $update .= ",sub_nombre='{$postFields['nombre']}'";
        }
        if (isset($postFields['estado'])) {
            $value = ($postFields['estado'] == 1) ? 'TRUE' : 'FALSE';
            $update .= ",sub_estado=$value";
        }
        if (isset($postFields['tipo'])) {
            $update .= ",sub_tipo='{$postFields['tipo']}'";
        }
        if (isset($postFields['categoria'])) {
            $update .= ",sub_catego={$postFields['categoria']}";
        }

        $sql = "UPDATE subcategoria SET 
                    sub_usuario=sub_usuario
                    $update
                WHERE cat_codigo={$postFields['codigo']} AND sub_usuario={$_SESSION['usuId']}";
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