<?php

require_once "connection.php";

class RecordsModel{

    public $con;

    public function __construct(){
        $this->con = Connection::connect();
    }

    public function getRecords(){

        $result = pg_query($this->con, "SELECT * FROM registro WHERE reg_usuario='{$_SESSION['usuId']}'");
        $data  = pg_fetch_all($result);

        return array('status'=>true, 'data'=>$data);
    }

    public function createRecords($postFields){

        $fields = array(
            $postFields['descripcion'],
            $postFields['fecha'],
            $postFields['tipo'],
            $postFields['valor'],
            true,//estado
            $postFields['cuenta'],
            ($postFields['categoria']) ?? NULL,
            ($postFields['subcategoria']) ?? NULL,
            ($postFields['transfer']) ?? NULL,
            ($postFields['codtransfer']) ?? NULL,
            $_SESSION['usuId'],
        );
        
        $sql = "INSERT INTO registro (reg_descrip,reg_fecha,reg_tipo,reg_valor,reg_estado,reg_cuenta,reg_catego,reg_subcatego,reg_transfer,reg_codtransfer,reg_usuario) 
            VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11) RETURNING reg_codigo;";
        $result = pg_query_params($this->con, $sql, $fields);
        $id = pg_fetch_assoc($result);

        if($result){
            return array('status'=>true, 'id'=>$id['reg_codigo']);
        }else{
            return array('status'=>false, 'msg'=> pg_last_error($this->con));
        }
    }

    public function updateRecord($postFields){

        $update = "";$errs=array();
        if (isset($postFields['descripcion'])) {
            $update .= ",reg_descrip='{$postFields['descripcion']}'";
        }
        if (isset($postFields['fecha'])) {
            $update .= ",reg_fecha='{$postFields['fecha']}'";
        }
        if (isset($postFields['tipo'])) {
            $update .= ",reg_tipo='{$postFields['tipo']}'";
        }
        if (isset($postFields['valor'])) {
            $update .= ",reg_valor={$postFields['valor']}";
        }
        if (isset($postFields['estado'])) {
            $value = ($postFields['estado'] == 1) ? 'TRUE' : 'FALSE';
            $update .= ",reg_estado=$value";
        }
        if (isset($postFields['cuenta'])) {
            $update .= ",reg_cuenta='{$postFields['cuenta']}'";
            $errs[] = self::validateExistsField(array(array('table'=> 'cuenta', 'field'=> array('fld'=>'cue_codigo','usu'=>'cue_usuario'), 'value' => $postFields['cuenta'])));
        }
        if (isset($postFields['categoria'])) {
            $update .= ",reg_catego={$postFields['categoria']}";
            $errs[] = self::validateExistsField(array(array('table'=> 'categoria', 'field'=> array('fld'=>'cat_codigo','usu'=>'cat_usuario'), 'value' => $postFields['categoria'])));
        }
        if (isset($postFields['subcategoria'])) {
            $update .= ",reg_subcatego={$postFields['subcategoria']}";
            $errs[] = self::validateExistsField(array(array('table'=> 'subcategoria', 'field'=> array('fld'=>'sub_codigo','usu'=>'sub_usuario'), 'value' => $postFields['subcategoria'])));       
        }

        $errs[] = self::validateExistsField(array( array('table'=> 'registro', 'field'=> array('fld'=>'reg_codigo','usu'=>'reg_usuario'), 'value' => $postFields['codigo'])));
        
        if (!empty($errs)) {
            foreach ($errs as $key) {
                $errorRet=array();
                if (!$key['status']) {
                    $errorRet = array('status'=>$key['status'], 'msg'=>$key['msg']);
                    break;
                }
            }
            if (!empty($errorRet)) {
                return $errorRet;
            }
        }

        $sql = "UPDATE registro SET 
                    reg_usuario=reg_usuario
                    $update
                WHERE reg_codigo={$postFields['codigo']} AND reg_usuario={$_SESSION['usuId']}";
        $result = pg_query($this->con, $sql);
        $aff = pg_affected_rows($result);

        if($aff>0){
            return array('status'=>true, 'msg'=>'');
        }else{
            return array('status'=>false, 'msg'=> "There was an error trying to update the registry ->".pg_last_error($this->con));
        }
    }

    public function validateExistsField($fields){

        foreach ($fields as $val) {

            $sql = "SELECT COUNT({$val['field']['fld']}) AS count
                    FROM {$val['table']} 
                    WHERE {$val['field']['fld']} = {$val['value']} 
                        AND {$val['field']['usu']}={$_SESSION['usuId']}";
            $res = pg_query($this->con, $sql);
            $row = pg_fetch_assoc($res);

            if ($row['count']<=0) {
                return array('status'=>false, 'msg'=>"Id {$val['value']} not found in {$val['table']} ");
            }
        }

        return array('status'=>true, 'msg'=>'');
    }

    public function getTransferId(){
        $sql = "SELECT MAX(reg_codtransfer) AS max
                FROM registro
                WHERE reg_usuario={$_SESSION['usuId']}";
        $res = pg_query($this->con, $sql);
        $row = pg_fetch_assoc($res);
        $id = $row['max'];

        if ($id=="")$id=1;

        return $id;
    }

    public function getRecordIdsFromTransfer($id){
        $sql = "SELECT reg_codigo
                FROM registro
                WHERE reg_codtransfer = $id AND reg_usuario={$_SESSION['usuId']}
                ORDER BY reg_codigo";
        $result = pg_query($this->con, $sql);
        $data  = pg_fetch_all($result);

        return $data;
    }

}

?>