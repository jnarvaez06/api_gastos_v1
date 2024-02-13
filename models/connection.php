<?php

class Connection{

    static public function infoDatabase(){

        $infoDB = array(
            'host' => "localhost",
            'database' => "gastos",
            "port"=>"5432",
            'user' => "admin",
            'password' => "administrador"
        );

        return $infoDB;
    }

    static public function connect(){
        try{
            $con = pg_connect("host=".Connection::infoDatabase()['host']." dbname=".Connection::infoDatabase()['database']." port=".Connection::infoDatabase()['port']." user=".Connection::infoDatabase()['user']." password=".Connection::infoDatabase()['password']."");
        
        }catch( Exception $e){
            echo "err: ". $e;
        }

        return $con;
    }
    
}

?>