<?php
class userController{
	private $conn;
	public function __construct($conn) 
	{
		$this->conn = $conn;
	}

    public function isUserActive() {
        if(isset($_POST['login']) || isset($_POST['id_uzytkownik']))
        {
            $login = isset($_POST['login'])? $_POST['login']:"";
            $id = isset($_POST['id_uzytkownik'])? $_POST['id_uzytkownik']: -1;
            $stmt = $this->conn->prepare("SELECT * FROM `uzytkownicy` WHERE (`login` = ? or `id_uzytkownik` = ?) and `aktywny` = 1");
            $stmt->bind_param("ss", $login, $id);
            $result = $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            if ($user) return "{\"status\":200,\"result\":".json_encode($user)."}";
            else return "{\"status\": 400, \"result\":\"User don't active\"}";
       } else return "{\"status\": 400, \"result\":\"Bad params\"}";
    }

	public function isUserExist() {
        if(isset($_POST['login']) || isset($_POST['id_uzytkownik']))
        {
            $login = isset($_POST['login'])? $_POST['login']:"";
            $id = isset($_POST['id_uzytkownik'])? $_POST['id_uzytkownik']: -1;
            $stmt = $this->conn->prepare("SELECT * FROM `uzytkownicy` WHERE `login` = ? or `id_uzytkownik` = ?");
            $stmt->bind_param("ss", $login, $id);
            $result = $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            if ($user) return "{\"status\":200,\"result\":".json_encode($user)."}";
            else return "{\"status\": 400, \"result\":\"User don't exist\"}";
       } else return "{\"status\": 400, \"result\":\"Bad params\"}";
    }

     public function login() {
       if(isset($_POST['login']) && isset($_POST['haslo']))
       {
            $stmt = $this->conn->prepare("SELECT * FROM `uzytkownicy` WHERE `login` = ? and `haslo` = ?");
            $pass = md5($_POST['haslo']);
            $stmt->bind_param("ss", $_POST['login'], $pass);
            $result = $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            if ($user) return "{\"status\":200,\"result\":{\"user\":".json_encode($user)."}}";
            else return "{\"status\": 400, \"result\":\"User don't exist\"}";
       } else return "{\"status\": 400, \"result\":\"Bad params\"}";
    }

    public function createUser() {
        if(isset($_POST['email']) && isset($_POST['login']) && isset($_POST['haslo']) && isset($_POST['poziom'])&& isset($_POST['imie']) && isset($_POST['nazwisko']))
        {
            $stmt = $this->conn->prepare("INSERT INTO `uzytkownicy` (`id_uzytkownik`, `login`, `haslo`, `poziom`, `imie`, `nazwisko`, `email`, `telefon`, `id_wydzial`, `kierunek`, `rok`, `id_specjalizacja`, `przedmioty`, `projekty`, `id_katedra`, `stanowisko`, `tytul`, `id_sieciowy`, `studenci`) 
                                                         VALUES (NULL, ?, ?, ?, ?, ?, ?, '', '', '', '', '', '', '', '', '', '','','')");
            $stmt->bind_param("ssssss", $_POST['login'], md5($_POST['haslo']), $_POST['poziom'], $_POST['imie'], $_POST['nazwisko'], $_POST['email']);
            $result = $stmt->execute();
            $error = $stmt->error;
            $stmt->close();
            if ($result) return "{\"status\":201,\"result\":\"Account created\"}";
            else return "{\"status\": 400,\"result\":\"".$error."\"}";
        } else return "{\"status\": 400,\"result\":\"Bad params\"}";
    }

    public function updateUser() {
        if(isset($_POST['id_uzytkownik']))
        {
            $i = 0;
            if(isset($_POST['imie'])){$imie = $i > 0 ? ", " : "";$imie .= " `imie` = \"".$_POST['imie']."\"";$i++;}else $imie = "";
            if(isset($_POST['nazwisko'])){$nazwisko = $i > 0 ? ", " : "";$nazwisko .= " `nazwisko` = \"".$_POST['nazwisko']."\"";$i++;}else $nazwisko = "";
            if(isset($_POST['telefon'])){$telefon = $i > 0 ? ", " : "";$telefon .= " `telefon` = \"".$_POST['telefon']."\"";$i++;}else $telefon = "";
            if(isset($_POST['id_wydzial'])){$id_wydzial = $i > 0 ? ", " : "";$id_wydzial .= " `id_wydzial` = \"".$_POST['id_wydzial']."\"";$i++;}else $id_wydzial = "";
            if(isset($_POST['kierunek'])){$kierunek = $i > 0 ? ", " : "";$kierunek .= " `kierunek` = \"".$_POST['kierunek']."\"";$i++;}else $kierunek = "";
            if(isset($_POST['rok'])){$rok = $i > 0 ? ", " : "";$rok .= " `rok` = \"".$_POST['rok']."\"";$i++;}else $rok = "";
            if(isset($_POST['id_specjalizacja'])){$id_specjalizacja = $i > 0 ? ", " : "";$id_specjalizacja .= " `id_specjalizacja` = \"".$_POST['id_specjalizacja']."\"";$i++;}else $id_specjalizacja = "";
            if(isset($_POST['przedmioty'])){$przedmioty = $i > 0 ? ", " : "";$przedmioty .= " `przedmioty` = \"".$_POST['przedmioty']."\"";$i++;}else $przedmioty = "";
            if(isset($_POST['projekty'])){$projekty = $i > 0 ? ", " : "";$projekty .= " `projekty` = \"".$_POST['projekty']."\"";$i++;}else $projekty = "";
            if(isset($_POST['id_katedra'])){$id_katedra = $i > 0 ? ", " : "";$id_katedra .= " `id_katedra` = \"".$_POST['id_katedra']."\"";$i++;}else $id_katedra = "";
            if(isset($_POST['stanowisko'])){$stanowisko = $i > 0 ? ", " : "";$stanowisko .= " `stanowisko` = \"".$_POST['stanowisko']."\"";$i++;}else $stanowisko = "";
            if(isset($_POST['tytul'])){$tytul = $i > 0 ? ", " : "";$tytul .= " `tytul` = \"".$_POST['tytul']."\"";$i++;}else $tytul = "";
            if(isset($_POST['id_sieciowy'])){$id_sieciowy = $i > 0 ? ", " : "";$id_sieciowy .= " `id_sieciowy` = \"".$_POST['id_sieciowy']."\"";$i++;}else $id_sieciowy = "";
            if(isset($_POST['studenci'])){$studenci = $i > 0 ? ", " : "";$studenci .= " `studenci` = \"".$_POST['studenci']."\"";$i++;}else $studenci = "";
            if($i > 0)
            {
                $query = "UPDATE `uzytkownicy` SET";
                $query .= $imie.$nazwisko.$telefon.$id_wydzial.$kierunek.$rok.$id_specjalizacja.$przedmioty.$projekty.$id_katedra.$stanowisko.$tytul.$id_sieciowy.$studenci;
                $query .= " WHERE `id_uzytkownik` = ".$_POST['id_uzytkownik'];   
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $result = $stmt->execute();
                $error = $stmt->error;
                $stmt->close();
                if ($result) return "{\"status\":200,\"result\":\"User updated successful\"}";
                 else return "{\"status\": 400,\"result\":\"".$error."\"}";
            } else return "{\"status\": 400,\"result\":\"Set some params\"}";
        } else return "{\"status\": 400,\"result\":\"Bad params\"}";
    }

    public function changePass() {
        if(isset($_POST['id_uzytkownik']) && isset($_POST['haslo']) && isset($_POST['nowe_haslo']) && isset($_POST['login']))
        {
            if ($_POST['haslo'] == $_POST['nowe_haslo']) return "{\"status\": 400,\"result\":\"New and old passwords are the same\"}";
            $query = "SELECT `login`, `haslo` FROM `uzytkownicy` WHERE id_uzytkownik = ".$_POST['id_uzytkownik'];
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->execute();
            $error = $stmt->error;
            $table = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            if ($result) 
            {
                $haslo = md5($_POST['haslo']);
                if(($table['login'] == $_POST['login']) && ($table['haslo'] == $haslo))
                {
                    $query = "UPDATE `uzytkownicy` SET `haslo` = \"".md5($_POST['nowe_haslo'])."\" WHERE `id_uzytkownik` = ".$_POST['id_uzytkownik'];
                    $stmt = $this->conn->prepare($query);
                    $result = $stmt->execute();
                    $error = $stmt->error;
                    if ($result) return "{\"status\":200,\"result\":\"Change password successful\"}";
                     else return "{\"status\": 400,\"result\":\"".$error."\"}";
                } else return "{\"status\":400,\"result\":\"Wrong password or login\"}";
            } else return "{\"status\": 400,\"result\":\"".$error."\"}";
        } else return "{\"status\": 400,\"result\":\"Bad params\"}";
    }
<<<<<<< HEAD

    public function changeActivity() {
        if(isset($_POST['id_uzytkownik']) && isset($_POST['aktywnosc']))
        {
            if($_POST['aktywnosc'] == 0) $aktywny = 0;
            else $aktywny = 1;
            $query = "UPDATE `uzytkownicy` SET `aktywny` = ".$aktywny." WHERE `id_uzytkownik` = ".$_POST['id_uzytkownik'];
            $stmt = $this->conn->prepare($query);
            $result = $stmt->execute();
            $error = $stmt->error;
            if ($result) return "{\"status\":200,\"result\":\"Change activity successful\"}";
             else return "{\"status\": 400,\"result\":\"".$error."\"}";
       } else return "{\"status\": 400, \"result\":\"Bad params\"}";
    }

    public function showAllUsers() {
        if(isset($_POST['id_uzytkownik']))
        {
            $json = json_decode($this->isUserExist());
            if(!($json->status == 200)) return "{\"status\": 400, \"result\":\"User with id ".$_POST['id_uzytkownik']." doesn't exist \"}";
            if($json->result->poziom != 3) return "{\"status\": 400, \"result\":\"Only admin can execute this method \"}";

            $stmt = $this->conn->prepare("SELECT * FROM `uzytkownicy`");
            $result = $stmt->execute();
            $users = $stmt->get_result();
            $error = $stmt->error;
            if($result)
            {
                $data = array();
                foreach ($users as $user) $data[] = $user;

                return "{\"status\":200,\"result\":".json_encode($data)."}";
            }else return "{\"status\": 400, \"result\":\"Users don't exist\"}";
       } else return "{\"status\": 400, \"result\":\"Bad params\"}";
   }
}
=======
    
    public function randomPassword($length = 8) 
    {
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()";
	$password = substr(str_shuffle($chars), 0, $length);
	return $password;
    }
    
    public function sendMessage($to,$subject,$txt) 
    {
	$headers = "From: Administrator <admin@deveo.pl>";
	mail($to,$subject,$txt,$headers);
    }
    
    public function resetPass() 
    {
	if(isset($_POST['login']) && isset($_POST['email']))
	{
		$password = $this->randomPassword(8);
		$query = "UPDATE `uzytkownicy` SET `haslo` = \"".md5($password)."\" WHERE `login` = \"".$_POST['login']."\" AND `email` = \"".$_POST['email']."\"";
		$stmt = $this->conn->prepare($query); 
		$result = $stmt->execute();
		$error = $stmt->error;
		if ($result) 
		{
			$this->sendMessage($_POST['email'],"Zmiana hasla w systemie zarzadzania projektami studenckimi","Witaj ".$_POST['login']."\r\n"."Haslo dostepu do Twojego konta w systemie zarzadzania projektami studenckimi zostalo zmienione."."\r\n"."Twoje nowe haslo: ".$password); 
			return "{\"status\":200,\"result\":\"Password successfully changed\"}";
		}
		 else return "{\"status\": 400,\"result\":\"".$error."\"}";
	} else return "{\"status\": 400,\"result\":\"Bad params\"}";
    }
}
>>>>>>> 8d54b6d7e53ec1ee7d6488030e4df4428bed6382
