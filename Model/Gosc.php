<?php
 class Gosc{
	 private $api;
	 public function __construct()
	{
		$this->api=file_get_contents("./Settings/api.json");
		$this->api=json_decode($this->api);		
	}
	protected function requestApi($wiadomosc,$adres)
	{
		$zadanie=curl_init($adres);
		curl_setopt($zadanie, CURLOPT_POSTFIELDS, $wiadomosc);
		curl_setopt($zadanie, CURLOPT_RETURNTRANSFER, true);
		$wynik=curl_exec($zadanie);
		curl_close($zadanie);
		return $wynik;
	}
	public function login()
	{
		$nazwaUzytkownika=$_POST['username'];
		$haslo=$_POST['haslo'];
		$wiadomosc='login='.$nazwaUzytkownika.'&haslo='.$haslo;
		$adres=$this->api->login;
		$wynik=$this->requestApi($wiadomosc,$adres);
		$wynik=json_decode($wynik);
		if($wynik->status==200)
		{
			$_SESSION['userProjekt']=$nazwaUzytkownika;
			$_SESSION['poziom']=$wynik->result->user->poziom;
			$_SESSION['userId']=$wynik->result->user->id_uzytkownik;
		}
		$wynik=json_encode($wynik);
		return $wynik;
	}
	public function register()
	{
		$username=$_POST['username'];
		$password=$_POST['password'];
		$email=$_POST['email'];
		$name=$_POST['imie'];
		$surname=$_POST['nazwisko'];
		$poziom=$_POST['poziom'];
		$wiadomosc='email='.$email.'&login='.$username.'&haslo='.$password.'&poziom='.$poziom.'&imie='.$name.'&nazwisko='.$surname;
		$adres=$this->api->createUser;															
		$wynik=$this->requestApi($wiadomosc,$adres,201);
		$wynik=json_decode($wynik);
		if($wynik->status==201)
		{
			$_SESSION['userProjekt']=$username;
			$_SESSION['poziom']=$poziom;
		}
		$wynik=json_encode($wynik);
		return $wynik;
	}
 }
?>