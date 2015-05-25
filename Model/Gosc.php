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
			
			$_SESSION['userImie']=$wynik->result->user->imie;
			$_SESSION['userNazwisko']=$wynik->result->user->nazwisko;
			$_SESSION['userTelefon']=$wynik->result->user->telefon;
			$_SESSION['userWydzial']=$wynik->result->user->id_wydzial;
			$_SESSION['userKierunek']=$wynik->result->user->kierunek;
			$_SESSION['userSpecjalizacja']=$wynik->result->user->id_specjalizacja;
			$_SESSION['userKatedra']=$wynik->result->user->id_katedra;
			$_SESSION['userSieciowe']=$wynik->result->user->id_sieciowy;
		
		
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
		$wynik=$this->requestApi($wiadomosc,$adres);
		return $wynik;
	}
	public function podmien($adres,$tresc,$slowoKlucz)
	{
		if(file_exists($adres))
		{
			$adres=file_get_contents($adres);
			return str_replace($slowoKlucz,$tresc,$adres);
		}
		else
		{
			return str_replace($slowoKlucz,$tresc,$adres);
		}
		return "Blad";
	}
	
	public function resetPass()
	{
		$username=$_POST['username'];
		$email=$_POST['email'];
		
		$wiadomosc='&login='.$username.'&email='.$email;
		$adres=$this->api->resetPass;															
		$wynik=$this->requestApi($wiadomosc,$adres);

		return $wynik;
		
	}
	
 }
?>