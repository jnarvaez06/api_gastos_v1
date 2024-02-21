<?php
require_once "vendor/autoload.php";

use Firebase\JWT\JWT;


class GeneralActions{

    public function emitResponse($status, $result, $data=array()){
        
        $json = array(
            'status' => $status,
            'result' => $result,
        );
    
        if(!empty($data)){
            $json[$data['key']] = $data['value'];
        }
    
        echo json_encode($json, http_response_code($json['status']));
    }

    public function verifyPostFields($fields, $postFields){
                
        $validExist = self::verifyExistField($fields, $postFields);
        if (!$validExist['status']) {
            return $validExist;
        }
        
        $validRequired = self::verifyRequiredField($fields, $postFields);
        if (!$validRequired['status']) {
            return $validRequired;
        }
        
        $validDataType = self::verifyDataTypeField($fields, $postFields);
        if (!$validDataType['status']) {
            return $validDataType;
        }

        return array('status'=>true, 'msg'=>'');
    }

    private function verifyExistField($fields, $postFields){
        foreach ($postFields as $key => $value) {
            if (!in_array($key, array_keys($fields))) {
                return array('status'=>false, 'msg'=>"Incorrect field [$key]");
            }
        }
        return array('status'=>true, 'msg'=>'');
    }

    private function verifyRequiredField($fields, $postFields){

        foreach ($fields as $key => $value) {
            $postField = isset($postFields[$key]) ? $postFields[$key] : "";

            if($value['isRequired'] && $postField==""){
                return array('status'=>false, 'msg'=>"Field [$key] is required");
            }
        }
        return array('status'=>true, 'msg'=>'');
    }

    private function verifyDataTypeField($fields, $postFields){
        foreach ($postFields as $key => $value) {
            $field = $fields[$key];

            switch ($field['dataType']) {

                case 'text': // SI ES TEXTO REVISA EL TAMAÑO DE LA CADENA
                    $length = $field['length'];
                    if ( strlen($value) > $length) {
                        return array('status'=>false, 'msg'=>"Field [$key] must be less than $length characters");
                    }
                    break;

                case 'numeric';
                    if (!is_numeric($value)) {
                        return array('status'=>false, 'msg'=>"Field [$key] must be a number");
                    }
                    break;

                case 'enum';
                    if(!in_array($value, $field['values'])){
                        return array('status'=>false, 'msg'=>"Field [$key] must be contains ".json_encode($field['values']));
                    }
                    break;

                case 'date':
                    $fechaObj = DateTime::createFromFormat('Y-m-d', $value);

                    if (!($fechaObj !== false && $fechaObj->format('Y-m-d') === $value)) {
                        // La cadena tiene el formato correcto y es una fecha válida
                        return array('status'=>false, 'msg'=>"Field [$key] is not a valid date with the desired format (YYYY-MM-DD)");
                    }
                    break;

                case 'email':
                    $emailFiltrado = filter_var($value, FILTER_VALIDATE_EMAIL);

                    // Verificar si el correo electrónico es válido
                    if ($emailFiltrado === false) {
                        return array('status'=>false, 'msg'=>"Field [$key] must be a valid email");
                    }
                                
                default:
                    # code...
                    break;
            }
        }
        return array('status'=>true, 'msg'=>'');
    }
    
    static public function jwt($id,$login){

        $time = time();
        $token = array(
            'iat' => $time, //TIEMPO INICIAL TOKEN
            'exp' => $time+(60*60*24), // TIEMPO EXPIRACION (1 DIA)
            'data'=> [
                'id'=>$id,
                'login'=>$login,
            ]
        );

        $jwt = JWT::encode($token, "liJ6HGK5Lhg3ku7cbOjknJ8",'HS256');

        return array('token'=>$jwt);
    }
}

?>