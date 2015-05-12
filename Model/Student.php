<?php
require_once "Gosc.php";
class Student extends Gosc{
	public function __construct()
	{
		$this->api=file_get_contents("./Settings/api.json");
		$this->api=json_decode($this->api);		
	}
	public function wyloguj()
	{
		session_destroy();
		header('refresh: 0.01;');
		return;
	}
	public function sprawdzCzyIstnieje()
	{
		$nazwaUzytkownika=$_POST['nazwaUzytkownika'];
		$wiadomosc='login='.$nazwaUzytkownika;
		$adres=$this->api->isUserExist;
		$wynik=$this->requestApi($wiadomosc,$adres);
		return $wynik;
	}
	public function wiadomosc()
	{
		$idNadawcy=$_SESSION['userId'];
		$nazwaUzytkownika=$_POST['nazwaUzytkownika'];
		$tytul=$_POST['tytul'];
		$trescWiadomosci=$_POST['trescWiadomosci'];
		$wiadomosc='id_nadawca='.$idNadawcy.'&id_odbiorca='.$nazwaUzytkownika.'&tytul='.$tytul.'&tresc='.$trescWiadomosci;
		$adres=$this->api->sendMessage;
		$wynik=$this->requestApi($wiadomosc,$adres);
		return $wynik;
	}
}
?>
