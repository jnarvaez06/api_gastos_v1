<?php

require_once "models/category_model.php";

class CategoryController{

    static public function getData($method, $postFields=array()){

        $category = new CategoryModel();

        switch ($method) {
            case 'getCategory':
                $response = $category->getCategory();                
                break;

            case 'createCategory':
                $valid = self::ValidateFields($postFields,'create');
                if(!$valid['status']){
                    return $valid;
                }

                $response = $category->createCategory($postFields); 

                break;

            case 'updateCategory':
                $valid = self::ValidateFields($postFields,'update');
                if(!$valid['status']){
                    return $valid;
                }

                $response = $category->updateCategory($postFields); 

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
            );            
        }else{
            $fields = array(
                'nombre' => array('isRequired'=> true,'dataType'=>'text', 'length'=>50),
                'codigo' => array('isRequired'=> true,'dataType'=>'numeric'),
                'estado' => array('isRequired'=> false,'dataType'=>'enum', 'values'=>array(1,0)),
            );
        }

        $gActions = new GeneralActions;
        return $gActions->verifyPostFields($fields, $postFields);
    }
}

?>