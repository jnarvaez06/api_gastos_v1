<?php

require_once "models/subcategory_model.php";

class SubCategoryController{

    static public function getData($method, $postFields=array()){

        $subcategory = new SubCategoryModel();

        switch ($method) {
            case 'getSubCategory':
                $response = $subcategory->getSubCategory();                
                break;

            case 'createSubCategory':
                $valid = self::ValidateFields($postFields,'create');
                if(!$valid['status']){
                    return $valid;
                }

                $response = $subcategory->createSubCategory($postFields); 

                break;

            case 'updateSubCategory':
                $valid = self::ValidateFields($postFields,'update');
                if(!$valid['status']){
                    return $valid;
                }

                $response = $subcategory->updateSubCategory($postFields); 

                break;
            
            default:
                # code...
                break;
        }

        return $response;
    }


    static function ValidateFields($postFields, $action){

        if($action=='create'){
            $fields = array(
                'nombre' => array('isRequired'=> true,'dataType'=>'text', 'length'=>50),
                'categoria' => array('isRequired'=> true,'dataType'=>'numeric'),
                'tipo' => array('isRequired'=> false,'dataType'=>'enum', 'values'=>array('Deber','Querer','Necesitar')),
            );            
        }else{
            $fields = array(
                'nombre' => array('isRequired'=> false,'dataType'=>'text', 'length'=>50),
                'categoria' => array('isRequired'=> false,'dataType'=>'numeric'),
                'codigo' => array('isRequired'=> true,'dataType'=>'numeric'),
                'estado' => array('isRequired'=> false,'dataType'=>'enum', 'values'=>array(1,0)),
                'tipo' => array('isRequired'=> false,'dataType'=>'enum', 'values'=>array('Deber','Querer','Necesitar'))
            );
        }

        $gActions = new GeneralActions;
        return $gActions->verifyPostFields($fields, $postFields);
    }
}

?>