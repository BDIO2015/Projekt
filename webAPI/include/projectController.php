<?php
class projectController{
	private $conn;
	public function __construct($conn) 
	{
		$this->conn = $conn;
	}

	function isProjectExist()
	{
		if(isset($_POST['id_projekt']))
        {
            $stmt = $this->conn->prepare("SELECT * FROM `projekty` WHERE `id_projekt` = ? ");
            $stmt->bind_param("s", $_POST['id_projekt']);
            $result = $stmt->execute();
            $projekt = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            if ($projekt) return "{\"status\":200,\"result\":".json_encode($projekt)."}";
            else return "{\"status\": 400, \"result\":\"Projekt don't exist\"}";
       } else return "{\"status\": 400, \"result\":\"Bad params\"}";
	}

	function createProject()
	{

	}

	function addUser()
	{
		if(isset($_POST['id_projekt']) && isset($_POST['id_uzytkownik']))
		{
			if(!(json_decode($GLOBALS['db']->getUserController()->isUserExist())->status == 200)) return "{\"status\": 400, \"result\":\"User with id ".$_POST['id_uzytkownik']." doesn't exist \"}";

			$stmt = $this->conn->prepare("SELECT studenci FROM `projekty` WHERE id_projekt = ?");
            $stmt->bind_param("s", $_POST['id_projekt']);
            $result = $stmt->execute();
            $error = $stmt->error;
            $field = $stmt->get_result();
            $stmt->close();
            if ($result) 
            {
            	$user = $_POST['id_uzytkownik'];
	            $studenci = $field->fetch_assoc()['studenci'];

            	if ($studenci != "")
            	{
	            	$studenci = json_decode($studenci);
	            	foreach ($studenci as $student) {
	            		$id = $student->id_student;
	            		if($id == $user) return "{\"status\": 400, \"result\":\"User is already in this project\"}";
	            	}
	            }

	            $add = "{\"id_student\":".$user."}";
	            $studenci[] = json_decode($add);
	            
	            $query = "UPDATE `projekty` SET `studenci` = '".json_encode($studenci)."' WHERE `id_projekt` = ".$_POST['id_projekt'];  
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $result = $stmt->execute();
                $error = $stmt->error;
                $stmt->close();

	            if ($result) return "{\"status\":200,\"result\":\"User has been added to the project\"}";
            	 else return "{\"status\": 400,\"result\":\"".$error."\"}";
            }
            else return "{\"status\": 400,\"result\":\"".$error."\"}";
		} else return "{\"status\": 400, \"result\":\"Bad params\"}";
	}

	function removeUser()
	{
		if(isset($_POST['id_projekt']) && isset($_POST['id_uzytkownik']))
		{
			if(!(json_decode($GLOBALS['db']->getUserController()->isUserExist())->status == 200)) return "{\"status\": 400, \"result\":\"User with id ".$_POST['id_uzytkownik']." doesn't exist \"}";

			$stmt = $this->conn->prepare("SELECT studenci FROM `projekty` WHERE id_projekt = ?");
            $stmt->bind_param("s", $_POST['id_projekt']);
            $result = $stmt->execute();
            $error = $stmt->error;
            $field = $stmt->get_result();
            $stmt->close();
            if ($result) 
            {
            	$user = $_POST['id_uzytkownik'];
	            $studenci = $field->fetch_assoc()['studenci'];

	            $removed = false;
	            $data = array();
            	if ($studenci != "")
            	{
	            	$studenci = json_decode($studenci);
	            	foreach ($studenci as $student) {
	            		$id = $student->id_student;
	            		if($id != $user) $data[] = json_decode("{\"id_student\":".$id."}");
	            		else $removed = true;
	            		
	            	}
	            }

	            if($removed == false) return "{\"status\": 400,\"result\":\"User doesn't belong to the project\"}";

	            $query = "UPDATE `projekty` SET `studenci` = '".json_encode($data)."' WHERE `id_projekt` = ".$_POST['id_projekt'];  
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $result = $stmt->execute();
                $error = $stmt->error;
                $stmt->close();
	            if ($result) return "{\"status\":200,\"result\":\"User has been deleted from project\"}";
            	 else return "{\"status\": 400,\"result\":\"".$error."\"}";
            }else return "{\"status\": 400,\"result\":\"".$error."\"}";
		} else return "{\"status\": 400, \"result\":\"Bad params\"}";
	}
}