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
            if ($result) return "{\"status\":200,\"result\":\"Project deleted successful\"}";
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
	            
			$stmt = $this->conn->prepare("INSERT INTO `uzytkownicy_projekty` (`id_uzytkownik`, `id_projekt`) VALUES (?,?)");
			$stmt->bind_param("ii", $_POST['id_uzytkownik'], $_POST['id_projekt']);
			
			$result = $stmt->execute();
			$error = $stmt->error;
			$stmt->close();

			if ($result) return "{\"status\":200,\"result\":\"User has been added to the project\"}";
			 else return "{\"status\": 400,\"result\":\"".$error."\"}";
		} else return "{\"status\": 400,\"result\":\"".$error."\"}";
	}

	public function removeUser()
	{
		if(isset($_POST['id_projekt']) && isset($_POST['id_uzytkownik']))
		{	
			if(!(json_decode($GLOBALS['db']->getUserController()->isUserExist())->status == 200)) return "{\"status\": 400, \"result\":\"User with id ".$_POST['id_uzytkownik']." doesn't exist \"}";
			
			$stmt = $this->conn->prepare("DELETE FROM `uzytkownicy_projekty` WHERE (`id_uzytkownik` = ? AND `id_projekt` = ?)");
			$stmt->bind_param("ii", $_POST['id_uzytkownik'], $_POST['id_projekt']);

            $result = $stmt->execute();
            $error = $stmt->error;
            $stmt->close();
       
	        if ($result) return "{\"status\":200,\"result\":\"User has been deleted from project\"}";
			else return "{\"status\": 400,\"result\":\"".$error."\"}";
		} else return "{\"status\": 400, \"result\":\"Bad params\"}";
	}

	public function activateUser()
	{
		if(isset($_POST['id_projekt']) && isset($_POST['id_uzytkownik']))
		{	
			if(!(json_decode($GLOBALS['db']->getUserController()->isUserExist())->status == 200)) return "{\"status\": 400, \"result\":\"User with id ".$_POST['id_uzytkownik']." doesn't exist \"}";
			
			$stmt = $this->conn->prepare("UPDATE `uzytkownicy_projekty` SET `zatwierdzony` = 1 WHERE (`id_uzytkownik` = ? AND `id_projekt` = ?)");
			$stmt->bind_param("ii", $_POST['id_uzytkownik'], $_POST['id_projekt']);

            $result = $stmt->execute();
            $error = $stmt->error;
            $stmt->close();
       
	        if ($result) return "{\"status\":200,\"result\":\"User has been activated in project\"}";
			else return "{\"status\": 400,\"result\":\"".$error."\"}";
		} else return "{\"status\": 400, \"result\":\"Bad params\"}";
	}
	
	public function getUsers()
	{
		if(isset($_POST['id_projekt']))
		{
			if(isset($_POST['zatwierdzony']))
			{
				if($_POST['zatwierdzony'] == 0 || $_POST['zatwierdzony'] == 1)
				{
					$stmt = $this->conn->prepare("SELECT * FROM `uzytkownicy_projekty` WHERE (`id_projekt` = ? AND `zatwierdzony` = ?)");
					$stmt->bind_param("ii", $_POST['id_projekt'], $_POST['zatwierdzony']);
				}
				else return "{\"status\": 400, \"result\":\"Bad params\"}";
			}
			else 
			{
				$stmt = $this->conn->prepare("SELECT * FROM `uzytkownicy_projekty` WHERE (`id_projekt` = ?)");
				$stmt->bind_param("i", $_POST['id_projekt']);
			}
			$result = $stmt->execute();
            		$users = $stmt->get_result();
        	 	$error = $stmt->error;

	        	 if($result)
	        	 {
	        		 $data = array();
	                	 foreach ($users as $user) $data[] = $user;
	
	                	return "{\"status\":200,\"result\":".json_encode($data)."}";
	            	}
	            	else return "{\"status\": 400, \"result\":\"Users don't exist\"}";
		}
		else return "{\"status\": 400, \"result\":\"Bad params\"}";
	}

	public function evaluateProject()
	{
		if(isset($_POST['id_koordynator']) && isset($_POST['id_projekt']) && isset($_POST['ocena']) && isset($_POST['komentarz']))
		{
			$stmt = $this->conn->prepare("SELECT * FROM `projekty` WHERE `id_koordynator` = ? AND `id_projekt` = ? AND id_ocena IS NULL");
			$stmt->bind_param("ii", $_POST['id_koordynator'], $_POST['id_projekt']);
			$stmt->execute();
			$stmt->store_result(); 
			$result = $stmt->num_rows();
			$stmt->close();

			if ($result == 1) 
			{
				$stmt = $this->conn->prepare("INSERT INTO `oceny` (`ocena`, `data`, `komentarz`) VALUES (?,NOW(),?)");
				$stmt->bind_param("ss", $_POST['ocena'], $_POST['komentarz']);
				$result_oceny = $stmt->execute();
				$error_oceny = $stmt->error;
				$stmt->close();

				$stmt = $this->conn->prepare("UPDATE `projekty` SET `id_ocena` = ? WHERE `id_projekt` = ?");
				$last = $this->conn->insert_id;
				$stmt->bind_param("ii", $last, $_POST['id_projekt']);
				$result_projekty = $stmt->execute();
				$error_projekty = $stmt->error;
				echo $error_projekty;
				$stmt->close();

				if ($result_oceny AND $result_projekty) return "{\"status\":200,\"result\":\"Evaluation grade of the project has been added\"}";
				else return "{\"status\": 400, \"result\":\"Bad params\"}";
			}
			else return "{\"status\": 400, \"result\":\"No such project\"}";
		} else return "{\"status\": 400, \"result\":\"Bad params\"}";
	}

	public function getProjects()
	{
        $stmt = $this->conn->prepare("SELECT `id_projekt`, `nazwa` FROM `projekty`");
        $result = $stmt->execute();
        $projekts = $stmt->get_result();
        $stmt->close();
        if ($projekts) 
        {
        	$data = array();
        	while($projekt = $projekts->fetch_assoc()) $data [] = $projekt;
        	return "{\"status\":200,\"result\":".json_encode($data)."}";
        }
        else return "{\"status\": 400, \"result\":\"Any projekt doesn't exist\"}";
	}


	public function archiveProject()
	{
		if(isset($_POST['id_projekt']) && isset($_POST['nazwa']))
		{
			$projectJSON = json_decode($this->isProjectExist());
			if(!($projectJSON->status == 200)) return "{\"status\": 400, \"result\":\"Project with id ".$_POST['id_projekt']." doesn't exist \"}";
			else $project = $projectJSON->result;
			$archive['projekt'] = $project;

			$threadsJSON = json_decode($GLOBALS['db']->getThreadController()->getThreads());
			if(!($threadsJSON->status == 200)) return $threadsJSON->result;
			else $threads = $threadsJSON->result;
			$archive['threads'] = $threads->watki;

			$stmt = $this->conn->prepare("INSERT INTO `archiwum`(`id_archiwum`, `id_projekt`, `nazwa`, `dane`, `data`) 
										  VALUES (NULL,?, ?, ?, NULL);");
            $stmt->bind_param("sss", $_POST['id_projekt'], $_POST['nazwa'], json_encode($archive));
            $result = $stmt->execute();
            $error = $stmt->error;
            $stmt->close();
            if ($result) 
            {
				$projectJSON = json_decode($this->deleteProject());
				if(!($projectJSON->status == 200)) return "{\"status\": 400, \"result\":\"Project with id ".$_POST['id_projekt']." doesn't exist \"}";
				return "{\"status\":201,\"result\":\"Project archived successful\"}";
            } else return "{\"status\": 400,\"result\":\"".$error."\"}";
		} else return "{\"status\": 400, \"result\":\"Bad params\"}";
	}

	public function getFromArchiv()
	{
		if(isset($_POST['id_archiwum']) ||isset($_POST['id_projekt']) || isset($_POST['nazwa']))
		{
			$query = isset($_POST['id_archiwum'])? "id_archiwum": (isset($_POST['id_projekt'])? "id_projekt": (isset($_POST['nazwa'])? "nazwa": ""));
			$data = isset($_POST['id_archiwum'])? $_POST['id_archiwum']: (isset($_POST['id_projekt'])? $_POST['id_projekt']: (isset($_POST['nazwa'])? $_POST['nazwa']: ""));

 			$stmt = $this->conn->prepare("SELECT * FROM `archiwum` WHERE `".$query."` = ? ");
            $stmt->bind_param("s", $data);
            $result = $stmt->execute();
            $projekt = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            if ($projekt) 
            {
            	$data = array();
            	$data['archiwum'] = $projekt;
            	$data['archiwum']['dane'] = json_decode($data['archiwum']['dane']);
            	return "{\"status\":200,\"result\":".json_encode($data)."}";
            } else return "{\"status\": 400, \"result\":\"Projekt don't exist\"}";
		} else return "{\"status\": 400, \"result\":\"Bad params\"}";
	}
}
