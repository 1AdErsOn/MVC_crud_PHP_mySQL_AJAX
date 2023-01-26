<?php
class Conexion{
    static public function conectar(){
        $dbHost     = "localhost"; 
        $dbUsername = "root"; 
        $dbPassword = ""; 
        $dbName     = "base_test";
        
        // Connect to the database
        $conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
        if($conn->connect_error){
            die("Failed to connect with MySQL: " . $conn->connect_error);
        }else{
            return $conn;
        }
    }
}