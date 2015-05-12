<?php
class Student{
	private $api;
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
	public function wiadomosc()
	{
		$idNadawcy=$_SESSION['userId'];
		$nazwaUzytkownika=$_POST['nazwaUzytkownika'];
		$tytul=$_POST['tytul'];
		$trescWiadomosci=$_POST['trescWiadomosci'];
		$wiadomosc='id_nadawca='.$idNadawcy.'&id_odbiorca='.$nazwaUzytkownika.'&tytul='.$tytul.'&tresc='.$trescWiadomosci;
		$zadanie=curl_init('http://deveo.pl/efdi/webAPI/sendMessage');
		curl_setopt($zadanie, CURLOPT_POSTFIELDS, $wiadomosc);
		curl_setopt($zadanie, CURLOPT_RETURNTRANSFER, true);
		$wynik=curl_exec($zadanie);
		curl_close($zadanie);

		return $wynik;
	}
}
?>
