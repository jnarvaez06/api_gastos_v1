<?php

class RecordsController{

    static function PostData($method, $postFields){
        $gActions = new GeneralActions;

        $valid = self::ValidateFields($postFields);
        if(!$valid['status']){
            $gActions->emitResponse(400, 'Error', array('key'=>'message', 'value'=>$valid['msg'])); 
            return;
        }

        

    }

    static function ValidateFields($postFields){
        $fields = array(
            'descripcion' => array('dataType'=>'text', 'length'=>100),
            'valor' => array('dataType'=>'numeric',),
            'tipo' => array('dataType'=>'enum','values'=>array('I','G')),
            'fecha_creacion' => array('dataType'=>'date'),
        );

        $gActions = new GeneralActions;
        return $gActions->verifyPostFields($fields, $postFields);
    }

}


?>