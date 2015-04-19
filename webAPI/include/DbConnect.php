<?php
class DbConnect {
    private $conn;
 
    function connect() {
        include_once __DIR__.'/config.php';

 		if("C:\\xampp\\htdocs\\webAPI" == getcwd()) $this->conn = new mysqli(DB_HOST_LOCAL, DB_USERNAME_LOCAL, DB_PASSWORD_LOCAL, DB_NAME_LOCAL);
 		else $this->conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

        if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();
        
        return $this->conn;
    }
}
?>