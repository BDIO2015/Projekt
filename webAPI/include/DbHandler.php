<?php
class DbHandler {
 
    private $conn;
 
    function __construct() {
        require_once __DIR__.'/DbConnect.php';
        $db = new DbConnect();
        $this->conn = $db->connect();
    }
 
    public function isUserExists() {
        if(isset($_POST['email']))
        {
            $stmt = $this->conn->prepare("SELECT * FROM `students` WHERE `email` = ? ");
            $stmt->bind_param("s", $_POST['email']);
            $result = $stmt->execute();
            $num_rows = $stmt->num_rows;
            $stmt->close();
            return $num_rows > 0;
        } else return false;
    }

    public function login() {
       if(isset($_POST['login']) && isset($_POST['haslo']))
       {
            $stmt = $this->conn->prepare("SELECT * FROM `students` WHERE `login` = ? and `haslo` = ?");
            $stmt->bind_param("ss", $_POST['login'], md5($_POST['haslo']));
            $result = $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            if ($user) return "{\"status\":200,\"result\":{\"user\":".json_encode($user)."}}";
            else return "{\"status\": 400, \"result\":\"User don't exist\"}";
       } else return "{\"status\": 400, \"result\":\"Bad params\"}";
    }

    public function createUser() {

        if(isset($_POST['email']) && isset($_POST['login']) && isset($_POST['haslo']) && isset($_POST['index']))
        {
            $stmt = $this->conn->prepare("INSERT INTO `students` (`ID_student`, `email`, `telefon`, `tworzenie_watkow`, `przedmiot`, `projekty`, `login`, `haslo`, `imie`, `nazwisko`, `nr_albumu`, `wydzial`, `kierunek`, `rok`, `specjalizacja`) 
                                                         VALUES (NULL, ?, '', 1, '', '', ?, ?, '', '', ?, '', '', '', '')");
            $stmt->bind_param("ssss", $_POST['email'], $_POST['login'], md5($_POST['haslo']), $_POST['index']);
            $result = $stmt->execute();
            $error = $stmt->error;
    
            $stmt->close();
            if ($result) return "{\"status\":201,\"result\":\"Account created\"}";
            else return "{\"status\": 400,\"result\":\"".$error."\"}";
        } else return "{\"status\": 400,\"result\":\"Bad params\"}";
    }
   
}
 
?>