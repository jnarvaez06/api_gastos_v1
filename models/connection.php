<?php

class Connection{

    static public function infoDatabase(){

        // $infoDB = array(
        //     'host' => "localhost",
        //     'database' => "gastos",
        //     "port"=>"5432",
        //     'user' => "admin",
        //     'password' => "administrador"
        // );
        $infoDB = array(
            'host' => "gastos.c10wic6iyk0z.us-east-1.rds.amazonaws.com",
            'database' => "gastos",
            "port"=>"5432",
            'user' => "jnarvaez",
            'password' => "postgres-ec2"
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