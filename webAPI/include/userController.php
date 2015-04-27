<?php
class userController{
	private $conn;
	public function __construct($conn) 
	{
		$this->conn = $conn;
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
            $stmt->bind_param("ss", $_POST['login'], md5($_POST['haslo']));
            $result = $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            if ($user) return "{\"status\":200,\"result\":{\"user\":".json_encode($user)."}}";
            else return "{\"status\": 400, \"result\":\"User don't exist\"}";
       } else return "{\"status\": 400, \"result\":\"Bad params\"}";
    }

    public function createUser() {

        if(isset($_POST['email']) && isset($_POST['login']) && isset($_POST['haslo']) && isset($_POST['poziom']))
        {
            $stmt = $this->conn->prepare("INSERT INTO `uzytkownicy` (`id_uzytkownik`, `login`, `haslo`, `poziom`, `imie`, `nazwisko`, `email`, `telefon`, `id_wydzial`, `kierunek`, `rok`, `id_specjalizacja`, `przedmioty`, `projekty`, `id_katedra`, `stanowisko`, `tytul`, `id_sieciowy`, `studenci`) 
                                                         VALUES (NULL, ?, ?, ?, '', '', ?, '', '', '', '', '', '', '', '', '', '','','')");
            $stmt->bind_param("ssss", $_POST['login'], md5($_POST['haslo']), $_POST['poziom'], $_POST['email']);
            $result = $stmt->execute();
            $error = $stmt->error;
            $stmt->close();
            if ($result) return "{\"status\":201,\"result\":\"Account created\"}";
            else return "{\"status\": 400,\"result\":\"".$error."\"}";
        } else return "{\"status\": 400,\"result\":\"Bad params\"}";
    }

    public function updateUser() {
        if(isset($_POST['id_urzytkownik']))
        {
            $i = 0;
            if(isset($_POST['telefon'])){$telefon = $i > 0 ? " AND" : "";$telefon .= " `telefon` = \"".$_POST['telefon']."\"";$i++;}else $telefon = "";
            if(isset($_POST['tworzenie_watkow'])){$tworzenie_watkow = $i > 0 ? " AND" : "";$tworzenie_watkow .= " `tworzenie_watkow` = \"".$_POST['tworzenie_watkow']."\"";$i++;}else $tworzenie_watkow = "";
            if(isset($_POST['przedmiot'])){$przedmiot = $i > 0 ? " AND" : "";$przedmiot .= " `przedmiot` = \"".$_POST['przedmiot']."\"";$i++;}else $przedmiot = "";
            if(isset($_POST['projekty'])){$projekty = $i > 0 ? " AND" : "";$projekty .= " `projekty` = \"".$_POST['projekty']."\"";$i++;}else $projekty = "";
            if(isset($_POST['imie'])){$imie = $i > 0 ? " AND" : "";$imie .= " `imie` = \"".$_POST['imie']."\"";$i++;}else $imie = "";
            if(isset($_POST['nazwisko'])){$nazwisko = $i > 0 ? " AND" : "";$nazwisko .= " `nazwisko` = \"".$_POST['nazwisko']."\"";$i++;}else $nazwisko = "";
            if(isset($_POST['nr_albumu'])){$nr_albumu = $i > 0 ? " AND" : "";$nr_albumu .= " `nr_albumu` = \"".$_POST['nr_albumu']."\"";$i++;}else $nr_albumu = "";
            if(isset($_POST['wydzial'])){$wydzial = $i > 0 ? " AND" : "";$wydzial .= " `wydzial` = \"".$_POST['wydzial']."\"";$i++;}else $wydzial = "";
            if(isset($_POST['kierunek'])){$kierunek = $i > 0 ? " AND" : "";$kierunek .= " `kierunek` = \"".$_POST['kierunek']."\"";$i++;}else $kierunek = "";
            if(isset($_POST['rok'])){$rok = $i > 0 ? " AND" : "";$rok .= " `rok` = \"".$_POST['rok']."\"";$i++;}else $rok = "";
            if(isset($_POST['specjalizacja'])){$specjalizacja = $i > 0 ? " AND" : "";$specjalizacja .= " `specjalizacja` = \"".$_POST['specjalizacja']."\"";$i++;}else $specjalizacja = "";
            if($i > 0)
            {
                $query = "UPDATE `uzytkownicy` SET";
                $query .= $telefon.$tworzenie_watkow.$przedmiot.$projekty.$imie.$nazwisko.$nr_albumu.$wydzial.$kierunek.$rok.$specjalizacja;
                $query .= " WHERE `ID_student` = ".$_POST['ID_student'];   
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $result = $stmt->execute();
                $error = $stmt->error;
                $stmt->close();
                if ($result) return "{\"status\":200,\"result\":\"Update successful\"}";
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
}