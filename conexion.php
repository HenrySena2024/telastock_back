<?php

class conexion{

    private $host = "localhost";
    private $db_name = "telastock";
    private $user = "root";
    private $pass = "";
    
    public $conn;

    public function getConn() {

        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->user, $this->pass);
            $this->conn->exec("set names utf8"); 
        } catch (PDOException $error) {
            echo "Error al conectarse". $error->getMessage();
            die();
        }
        return $this->conn;
    }

}

?>