<?php
require_once "Student.php";
class Prowadzacy extends Student{
	public function __construct()
	{
		$this->api=file_get_contents("./Settings/api.json");
		$this->api=json_decode($this->api);		
	}
	
	public function stworzNowyProjekt()
	{
		$idKoordynatora=$_SESSION['userId'];
		$nazwaProjektu=$_POST['nazwaProjektu'];
		$opisProjektu=$_POST['opisProjektu'];
		$wiadomosc='nazwa='.$nazwaProjektu.'&opis='.$opisProjektu.'&id_koordynator='.$idKoordynatora;
		$adres=$this->api->createProject;
		$wynik=$this->requestApi($wiadomosc,$adres);
		return $wynik;
	}
	
	
	public function pokazPrzypisanychStudentow()
	{
		$adres=$this->api->getProject;
	}
	
	public function archiwizujProjekt($idProjektu, $nazwaProjektu)
	{	
				$nazwaProjektu=$nazwaProjektu.'_zarchiwizowany';
				$adres=$this->api->archiveProject;
				$wiadomosc='id_projekt='.$idProjektu.'&nazwa='.$nazwaProjektu;
				$wynik=$this->requestApi($wiadomosc,$adres);
				return $wynik;
	}
	
	public function usunProjekt($idProjektu)
	{	
		$adres=$this->api->deleteProject;
		$wiadomosc='id_projekt='.$idProjektu;
		$wynik=$this->requestApi($wiadomosc,$adres);
		return $wynik;
	}
	
		public function pobierzListeProjektow()
	{
		$adres=$this->api->getProjects;
		$wynik=$this->requestApi("",$adres);
		$wynik=json_decode($wynik);
		if($wynik->status=200)
		{
			$lista="";
			foreach($wynik->result as $odbior)
			{
				$lista='<tr><td><a href="?przyciskProwadzacy=pobierzProjekt&idProjekt='.$odbior->id_projekt.'">'.$odbior->nazwa.'</a></td></tr>'.$lista;
			}
			$lista='<table>'.$lista.'</table>';
			$_SESSION['result']=$wynik->result;
			return $lista;
		}
	}
	
	public function pobierzWatki($idProjektu)
	{
		$wiadomosc='id_projekt='.$idProjektu;
		$adres=$this->api->getThreads;
		$wynik=$this->requestApi($wiadomosc,$adres);
		$wynik=json_decode($wynik);
		if($wynik->status=200)
		{
			$lista="";
			foreach($wynik->result->watki as $odbierz)
			{
				$lista='<tr><td><a href="?przyciskProwadzacy=pobierzKomentarz&idWatek='.$odbierz->id_watek.'">'.$odbierz->text.' </a>'.$odbierz->date.'</a></td></tr>'.$lista;
			}
			$lista='<table>'.$lista.'</table>';
			$_SESSION['result']=$wynik->result;
			return $lista;
		}
	}
	
	public function wyswietlStudentow($idProjektu,$zatwierdzony)
	{
		$wiadomosc='id_projekt='.$idProjektu.'&zatwierdzony='.$zatwierdzony;
		$adres=$this->api->getUsers;
		$wynik=$this->requestApi($wiadomosc,$adres);
		$wynik=json_decode($wynik);
		if($wynik->status=200)
		{
			$lista="";
			foreach($wynik->result as $odbior)
			{
				$lista=$lista.'<tr><td>'.$odbior->login.'</tr></td>';
			}
			$lista='<table>'.$lista.'</table>';
			$_SESSION['result']=$wynik->result;
			return $lista;
		}
	}
	
}
?>