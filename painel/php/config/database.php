<?php

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

class Database {

    private $servername = "localhost";
    private $username = "iidscomb_admin";
    private $password = "iidsadmin";
    private $database = "iidscomb_db";
    
    /** private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "iidscomb_db";**/
    
    private $conn;
    public $err;

    public function __construct()
    {
        try 
        {
            $this->conn = new PDO("mysql:host=$this->servername;dbname=$this->database;charset=utf8", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e)
        {
            $this->conn = null;
            $this->err = "Connection failed: " . $e->getMessage();
        }
    }

    public function getConn()
    {
        return $this->conn;
    }
    
    public function getErr()
    {
        return $this->err;
    }
}

?>
