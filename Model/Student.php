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
	public function wiadomoscWyslij()
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
	public function pobierzWiadomosci()
	{
		$idOdbiorcy=$_SESSION['userId'];
		$wiadomosc='id_uzytkownik='.$idOdbiorcy;
		$adres=$this->api->getReceivedMessages;
		$wynik=$this->requestApi($wiadomosc,$adres);
		$wynik=json_decode($wynik);
		if($wynik->status==200)
		{
			$lista="";
			foreach ($wynik->result->wiadomosci as $odebrana)
			{
				$lista='<tr><td><a href="?przyciskStudent=pobierzWiadomosc&idWiadomosc='.$odebrana->id_wiadomosc.'">'.$odebrana->tytul.'</a></td></tr>'.$lista;
			}
			$lista='<table>'.$lista.'</table>';
			$_SESSION['wiadomosci']=$wynik->result->wiadomosci;
			return $lista;
		}
		else
		return 'Blad';
	}
	public function pokazWiadomosc($idWiadomosci)
	{
		$wiadomosci=$_SESSION['wiadomosci'];
		foreach($wiadomosci as $odebrana)
		{
			if($odebrana->id_wiadomosc==$idWiadomosci)
			 return $odebrana->tresc;
		}
		return 0;
	}
}
?>
