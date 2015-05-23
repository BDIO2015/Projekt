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
				if($zatwierdzony==0)
				{
					$lista=$lista.'<tr><td>'.$odbior->login.'<a href="?przyciskProwadzacy=akceptujStudentaPrzycisk&idStudenta='.$odbior->id_uzytkownik.'"> Akceptuj</tr></td>';
				}
				else
				{
					$lista=$lista.'<tr><td>'.$odbior->login.'</tr></td>';
				}
			}
			$lista='<table>'.$lista.'</table>';
			$_SESSION['result']=$wynik->result;
			return $lista;
		}
	}
	
	public function akceptujStudenta($idProjektu,$idStudent)
	{
		$wiadomosc='id_projekt='.$idProjektu.'&id_uzytkownik='.$idStudent;
		$adres=$this->api->activateUser;
		$wynik=$this->requestApi($wiadomosc,$adres);
		$wynik=json_decode($wynik);
		if($wynik->status=200)
		{
			
			$wynik=json_encode($wynik);
			return $wynik;
		}
	}
	public function wstawOcene()
	{
		$idProjektu=$_SESSION['idProjektu'];
		$idKoordynator=$_SESSION['userId'];
		$ocena=$_POST['ocena'];
		$komentarz=$_POST['komentarz'];
		$wiadomosc='id_projekt='.$idProjektu.'&id_koordynator='.$idKoordynator.'&ocena'.$ocena.'&komentarz'.$komentarz;
		$adres=$this->api->evaluateProject;
		$wynik=$this->requestApi($wiadomosc,$adres);
		$wynik=json_decode($wynik);
		if($wynik->status=200)
		{
			
			$wynik=json_encode($wynik);
			return $wynik;
		}
	}
	
	public function wyswietlWszystkichStudentow()
	{
		
		$idKoordynator=$_SESSION['userId'];
		$poz=1;
		$wiadomosc='id_uzytkownik='.$idKoordynator.'&poziom='.$poz;
		$adres=$this->api->showAllUsers;
		$wynik=$this->requestApi($wiadomosc,$adres);
		$wynik=json_decode($wynik);
		if($wynik->status=200)
		{
			$lista="";
			foreach($wynik->result as $odbior)
			{
					$lista=$lista.'<tr><td>'.$odbior->login.'<a href="?przyciskProwadzacy=akceptujStudentaPrzycisk&idStudenta='.$odbior->id_uzytkownik.'"> Dodaj</tr></td>';
			}
			$lista='<table>'.$lista.'</table>';
			$_SESSION['result']=$wynik->result;
			return $lista;
		}
	}
	
public function dolaczenieStudentaDoProjektu($idProjektu,$idStudent)
	{
		$wiadomosc='id_uzytkownik='.$idStudent.'&id_projekt='.$idProjektu;
		$adres=$this->api->addUser;
		$wynik=$this->requestApi($wiadomosc,$adres);
		$wynik=json_decode($wynik);
		return $wynik;
	}
}
?>