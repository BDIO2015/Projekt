<?php
class projectController{
	private $conn;
	public function __construct($conn) 
	{
		$this->conn = $conn;
	}

	public function isProjectExist()
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

	public function createProject()
	{
		if(isset($_POST['nazwa']) && isset($_POST['opis']) && isset($_POST['id_koordynator']))
		{
			$stmt = $this->conn->prepare("INSERT INTO `projekty` (`id_projekt`, `nazwa`, `opis`, `termin`, `miejsce`, `wytyczne`, `id_koordynator`, `studenci`, `id_kierownik`, `id_ocena`) 
										  VALUES (NULL, ?, ?, '', NULL, NULL, ?, NULL, NULL, NULL);");
            $stmt->bind_param("sss", $_POST['nazwa'], $_POST['opis'], $_POST['id_koordynator']);
            $result = $stmt->execute();
            $error = $stmt->error;
            $stmt->close();
            if ($result) return "{\"status\":201,\"result\":\"Project created successful\"}";
            else return "{\"status\": 400,\"result\":\"".$error."\"}";
		} else return "{\"status\": 400, \"result\":\"Bad params\"}";
	}

	public function deleteProject()
	{
		if(isset($_POST['id_projekt']))
		{
			if(!(json_decode($this->isProjectExist())->status == 200)) return "{\"status\": 400, \"result\":\"Project with id ".$_POST['id_projekt']." doesn't exist \"}";
			if(!(json_decode($GLOBALS['db']->getThreadController()->deleteThreads())->status == 200)) return "{\"status\": 400, \"result\":\"Can't delete threads\"}";

			$stmt = $this->conn->prepare("DELETE FROM `projekty` WHERE id_projekt = ?");
            $stmt->bind_param("s", $_POST['id_projekt']);
            $result = $stmt->execute();
            $error = $stmt->error;
            $stmt->close();
            if ($result) return "{\"status\":201,\"result\":\"Project deleted successful\"}";
            else return "{\"status\": 400,\"result\":\"".$error."\"}";
		} else return "{\"status\": 400, \"result\":\"Bad params\"}";
	}

	public function updateProject()
	{
		if(isset($_POST['id_projekt']))
		{
			$i = 0;
			if(isset($_POST['nazwa'])){$nazwa = $i > 0 ? ", " : "";$nazwa .= " `nazwa` = \"".$_POST['nazwa']."\"";$i++;}else $nazwa = "";
			if(isset($_POST['opis'])){$opis = $i > 0 ? ", " : "";$opis .= " `opis` = \"".$_POST['opis']."\"";$i++;}else $opis = "";
			if(isset($_POST['termin'])){$termin = $i > 0 ? ", " : "";$termin .= " `termin` = \"".$_POST['termin']."\"";$i++;}else $termin = "";
			if(isset($_POST['miejsce'])){$miejsce = $i > 0 ? ", " : "";$miejsce .= " `miejsce` = \"".$_POST['miejsce']."\"";$i++;}else $miejsce = "";
			if(isset($_POST['wytyczne'])){$wytyczne = $i > 0 ? ", " : "";$wytyczne .= " `wytyczne` = \"".$_POST['wytyczne']."\"";$i++;}else $wytyczne = "";
			if(isset($_POST['id_koordynator'])){$id_koordynator = $i > 0 ? ", " : "";$id_koordynator .= " `id_koordynator` = \"".$_POST['id_koordynator']."\"";$i++;}else $id_koordynator = "";
			if(isset($_POST['id_kierownik'])){$id_kierownik = $i > 0 ? ", " : "";$id_kierownik .= " `id_kierownik` = \"".$_POST['id_kierownik']."\"";$i++;}else $id_kierownik = "";
			if(isset($_POST['id_ocena'])){$id_ocena = $i > 0 ? ", " : "";$id_ocena .= " `id_ocena` = \"".$_POST['id_ocena']."\"";$i++;}else $id_ocena = "";

			if($i > 0)
            {
                $query = "UPDATE `projekty` SET";
                $query .= $nazwa.$opis.$termin.$miejsce.$wytyczne.$id_koordynator.$id_kierownik.$id_ocena;
                $query .= " WHERE `id_projekt` = ".$_POST['id_projekt'];   
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $result = $stmt->execute();
                $error = $stmt->error;
                $stmt->close();
                if ($result) return "{\"status\":200,\"result\":\"Update project successful\"}";
                 else return "{\"status\": 400,\"result\":\"".$error."\"}";
            } else return "{\"status\": 400,\"result\":\"Set some params\"}";
		}
	}

	public function addUser()
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
            } else return "{\"status\": 400,\"result\":\"".$error."\"}";
		} else return "{\"status\": 400, \"result\":\"Bad params\"}";
	}

	public function removeUser()
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