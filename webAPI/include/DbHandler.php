<?php

include_once __DIR__.'/userController.php';
include_once __DIR__.'/projectController.php';
include_once __DIR__.'/threadController.php';


class DbHandler {
 
    private $conn;
    public $userController;
    public $projektController;
    public $threadController;


    public function __construct() {
        require_once __DIR__.'/DbConnect.php';
        $db = new DbConnect();
        $this->conn = $db->connect();

        $this->userController = new userController($this->conn);
        $this->projectController = new projectController($this->conn);
        $this->threadController = new threadController($this->conn);
    }  

    public function getUserController() { return $this->userController; }
    public function getProjectController() { return $this->projectController; }
    public function getThreadController() { return $this->threadController; }

}
 
?>