<?php
class messagesController{
	private $conn;
	public function __construct($conn) 
	{
		$this->conn = $conn;
	}

	public function getReceivedMessages(){
		if(isset($_POST['id_uzytkownik']))
        {
            $stmt = $this->conn->prepare( "SELECT * FROM `wiadomosci` WHERE `id_odbiorca` = ?" );
            $stmt->bind_param("s", $_POST['id_uzytkownik']);
            $result = $stmt->execute();
            $error = $stmt->error;
            $messages = $stmt->get_result();
            $stmt->close();
			if ($messages) 
            {
            	$data = array();
            	while($message = $messages->fetch_assoc()) $data[] = $message;
            	$val["wiadomosci"] = $data;
            	return "{\"status\":200,\"result\":".json_encode($val)."}";
            }
            else return "{\"status\": 400,\"result\":\"Message don't exist\"}";

        } else return "{\"status\": 400,\"result\":\"Bad params\"}";
	}

	public function getSendedMessages(){
		if(isset($_POST['id_uzytkownik']))
        {
            $stmt = $this->conn->prepare( "SELECT * FROM `wiadomosci` WHERE `id_nadawca` = ?" );
            $stmt->bind_param("s", $_POST['id_uzytkownik']);
            $result = $stmt->execute();
            $error = $stmt->error;
            $messages = $stmt->get_result();
            $stmt->close();
			if ($messages) 
            {
            	$data = array();
            	while($message = $messages->fetch_assoc()) $data[] = $message;
            	$val["wiadomosci"] = $data;
            	return "{\"status\":200,\"result\":".json_encode($val)."}";
            }
            else return "{\"status\": 400,\"result\":\"Message don't exist\"}";

        } else return "{\"status\": 400,\"result\":\"Bad params\"}";
	}

	public function sendMessage(){
		if(isset($_POST['id_nadawca']) && isset($_POST['id_odbiorca']) && isset($_POST['tytul']) && isset($_POST['tresc']))
        {
            $stmt = $this->conn->prepare("INSERT INTO `wiadomosci` (`id_wiadomosc`, `id_projekt`, `id_nadawca`, `id_odbiorca`, `tytul`, `tresc`, `data`) 
            							  VALUES (NULL, 0, ?, ?, ?, ?, NULL)");
            $stmt->bind_param("ssss", $_POST['id_nadawca'], $_POST['id_odbiorca'], $_POST['tytul'], $_POST['tresc']);
            $result = $stmt->execute();
            $error = $stmt->error;
            $stmt->close();
            if ($result) return "{\"status\":201,\"result\":\"Message sended\"}";
            else return "{\"status\": 400,\"result\":\"".$error."\"}";
        } else return "{\"status\": 400,\"result\":\"Bad params\"}";
    }
}