<?php
class Database{
  
    // specify your own database credentials
    private $host = "bgceftiqs9aiirhidsog-mysql.services.clever-cloud.com";
    private $db_name = "bgceftiqs9aiirhidsog";
    private $username = "u9hwqdxokergb05c";
    private $password = "Jb8b0WfUyRZdZBddii2f";
    public $conn;
  
    // get the database connection
    public function getConnection(){
  
        $this->conn = null;
  
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
  
        return $this->conn;
    }
}
?>