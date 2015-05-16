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
	
	public function pokazProjekt($idProjektu)
	{
		$result=$_SESSION['result'];
		foreach($result as $odbior)
		{
			if($odbior->id_projekt==$idProjektu)
			{		
				
				$wiadomosc='id_projekt='.$idProjektu;
				$adres=$this->api->getProject;
				$wynik=$this->requestApi($wiadomosc,$adres);
				$wynik=json_decode($wynik);
				if($wynik->status=200)
				{
					$_SESSION['nazwaProjektu']=$wynik->result->nazwa;
					return $wynik='ID projektu='.$idProjektu.'<br \>Nazwa='.$wynik->result->nazwa.'<br \>Opis='.$wynik->result->opis.'<br \>Termin='.$wynik->result->termin.'<br \>Miejsce='.$wynik->result->miejsce.'<br \>Wytyczne'.$wynik->result->wytyczne.'<br \>Id koordynatora='.$wynik->result->id_koordynator.'<br \>Id grupy='.$wynik->result->id_grupy.'<br \>Id oceny='.$wynik->result->id_ocena;
				}
			} 
		}
		return 0;
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
}
?>