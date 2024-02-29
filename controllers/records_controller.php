<?php

require_once "models/record_model.php";

class RecordsController{

    static public function getData($method, $postFields=array()){

        $record = new RecordsModel();

        switch ($method) {
            case 'getRecords':
                $response = $record->getRecords();                
                break;

            case 'createRecord':                
                $response = self::createRecordController($record, $postFields);
                break;

            case 'updateRecord':
                $valid = self::ValidateFields($postFields,'update');
                if(!$valid['status']){
                    return $valid;
                }

                $response = $record->updateRecord($postFields); 
                break;
            
            case 'createTransfer':
                $response = self::createTransferController($record, $postFields);
                break;

            case 'updateTransfer':
                $response = self::updateTransferController($record, $postFields);
                break;

            default:
                $response = array('status'=>false, 'msg'=> 'Error in redirect method');
                break;
        }

        return $response;
    }


    static function ValidateFields($postFields, $action){

        $fields = array(
            'create' => array(
                'descripcion' => array('isRequired'=> true,'dataType'=>'text', 'length'=>250),
                'fecha' => array('isRequired'=> true,'dataType'=>'date'),
                'tipo' => array('isRequired'=> true,'dataType'=>'enum', 'values'=>array('I','G')),                
                'valor' => array('isRequired'=> true,'dataType'=>'numeric'),
                'cuenta' => array('isRequired'=> true,'dataType'=>'numeric'),
                'categoria' => array('isRequired'=> true,'dataType'=>'numeric'),
                'subcategoria' => array('isRequired'=> true,'dataType'=>'numeric')
            ),
            'update' => array(
                'codigo' => array('isRequired'=> true,'dataType'=>'numeric'),
                'descripcion' => array('isRequired'=> false,'dataType'=>'text', 'length'=>250),
                'fecha' => array('isRequired'=> false,'dataType'=>'date'),
                'tipo' => array('isRequired'=> false,'dataType'=>'enum', 'values'=>array('I','G')),
                'estado' => array('isRequired'=> false,'dataType'=>'enum', 'values'=>array('1','0')),
                'valor' => array('isRequired'=> false,'dataType'=>'numeric'),
                'cuenta' => array('isRequired'=> false,'dataType'=>'numeric'),
                'categoria' => array('isRequired'=> false,'dataType'=>'numeric'),
                'subcategoria' => array('isRequired'=> false,'dataType'=>'numeric')
            ),
            'createTransfer' => array(
                'descripcion' => array('isRequired'=> true,'dataType'=>'text', 'length'=>250),
                'fecha' => array('isRequired'=> true,'dataType'=>'date'),
                'valor' => array('isRequired'=> true,'dataType'=>'numeric'),
                'cuentaOrigen' => array('isRequired'=> true,'dataType'=>'numeric'),
                'cuentaDestino' => array('isRequired'=> true,'dataType'=>'numeric'),
            ),
            'updateTransfer' => array(
                'idTransfer' => array('isRequired'=> true,'dataType'=>'numeric'),
                'descripcion' => array('isRequired'=> false,'dataType'=>'text', 'length'=>250),
                'fecha' => array('isRequired'=> false,'dataType'=>'date'),
                'valor' => array('isRequired'=> false,'dataType'=>'numeric'),
                'estado' => array('isRequired'=> false,'dataType'=>'enum', 'values'=>array('1','0')),
                'cuentaOrigen' => array('isRequired'=> true,'dataType'=>'numeric'),
                'cuentaDestino' => array('isRequired'=> true,'dataType'=>'numeric'),
            ),
        );

        $gActions = new GeneralActions;
        return $gActions->verifyPostFields($fields[$action], $postFields);
    }

    static function createRecordController($record, $postFields) {

        $valid = self::ValidateFields($postFields,'create');
        if(!$valid['status']){
            return $valid;
        }

        $fields = array(
            array('table'=> 'cuenta', 'field'=> array('fld'=>'cue_codigo','usu'=>'cue_usuario'), 'value' => $postFields['cuenta']),
            array('table'=> 'categoria', 'field'=> array('fld'=>'cat_codigo','usu'=>'cat_usuario'), 'value' => $postFields['categoria']),
            array('table'=> 'subcategoria', 'field'=> array('fld'=>'sub_codigo','usu'=>'sub_usuario'), 'value' => $postFields['subcategoria']),
        );

        $ret = $record->validateExistsField($fields);

        if(!$ret['status']){
            return array('status'=>$ret['status'], 'msg'=>$ret['msg']);
        }

        return $record->createRecords($postFields); 
    }

    static function createTransferController($record, $postFields){
        $valid = self::ValidateFields($postFields,'createTransfer');
        if(!$valid['status']){
            return $valid;
        }

        $fields = array(
            array('table'=> 'cuenta', 'field'=> array('fld'=>'cue_codigo','usu'=>'cue_usuario'), 'value' => $postFields['cuentaOrigen']),
            array('table'=> 'cuenta', 'field'=> array('fld'=>'cue_codigo','usu'=>'cue_usuario'), 'value' => $postFields['cuentaDestino'])
        );

        $ret = $record->validateExistsField($fields);

        if(!$ret['status']){
            return array('status'=>$ret['status'], 'msg'=>$ret['msg']);
        }

        $idTransfer = $record->getTransferId();
        //ORIGEN
        $data = array(
            'descripcion' => $postFields['descripcion'],
            'fecha' => $postFields['fecha'],
            'tipo' => 'G',
            'valor'=>$postFields['valor'],
            'transfer' => true,
            'codtransfer' => $idTransfer,
        );
        $data['tipo'] = 'G';
        $data['cuenta'] = $postFields['cuentaOrigen'];

        $execOrigin = $record->createRecords($data);

        if ($execOrigin['status']) {
            //DESTINO
            $data['tipo'] = 'I';
            $data['cuenta'] = $postFields['cuentaDestino'];
            $execDestiny = $record->createRecords($data);

            if ($execDestiny['status']) {
                return array('status'=>true, 'idTransfer'=>$idTransfer);
            }else{
                return $execDestiny;
            }
        }else{
            return $execOrigin;
        }
    }

    static function updateTransferController($record, $postFields){
        $valid = self::ValidateFields($postFields,'updateTransfer');
        if(!$valid['status']){
            return $valid;
        }

        $fields = array(
            array('table'=> 'registro', 'field'=> array('fld'=>'reg_codtransfer','usu'=>'reg_usuario'), 'value' => $postFields['idTransfer'])
        );

        $ret = $record->validateExistsField($fields);

        if(!$ret['status']){
            return array('status'=>$ret['status'], 'msg'=>$ret['msg']);
        }

        $idsTransfer = $record->getRecordIdsFromTransfer($postFields['idTransfer']);

        $data=array();
        if (isset($postFields['descripcion'])) {
            $data['descripcion'] = $postFields['descripcion'];
        }
        if (isset($postFields['fecha'])) {
            $data['fecha'] = $postFields['fecha'];
        }
        if (isset($postFields['valor'])) {
            $data['valor'] = $postFields['valor'];
        }
        if (isset($postFields['estado'])) {
            $data['estado'] = $postFields['estado'];
        }

        $it=0;$exec=array();
        foreach ($idsTransfer as $key) {
            
            if($it==0){
                $data['cuenta'] = $postFields['cuentaOrigen'];
            }else{
                $data['cuenta'] = $postFields['cuentaDestino'];
            }
            $data['codigo'] = $key['reg_codigo'];

            $exec[] = $record->updateRecord($data);
            $it++;
        }

        $err=array();
        foreach ($exec as $key) {
            if (!$key['status']) {
                $err=$key;
                break;
            }
            $err=$key;
        }

        return $err;
    }
}

?>