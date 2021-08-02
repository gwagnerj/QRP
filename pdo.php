<?php
require_once('../password.php');
// echo $system_password;

try {
    $pdo=new PDO ('mysql:host=localhost;port=3306;dbname=wagnerj_QRP','wagnerj_wagnerj',$system_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e)
{
   throw new \PDOException($e->getMessage(), (int)$e->getCode());
}











/* 
class Database {
    private $host = "localhost";
    private $db_name = "[...]";
    private $username = "[...]";
    private $password = "[...]";

    public $conn = null;

    public function dbConnection() {
        if ( $this->conn !== null ) {
            // already have an open connection so lets reuse it
            return $this->conn;
        }

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
        } catch(PDOException $exception) {
            exit('Database failed to connect: ' . $exception);
        }

        return $this->conn;
    }
}

 */





?>
