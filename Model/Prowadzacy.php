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
	
	public function edytujKonto()
	{
		$idUz=$_SESSION['userId'];
		$imieUz=$_POST['imie'];
		$nazwiskoUz=$_POST['nazwisko'];
		$telefonUz=$_POST['nrtel'];
		$idWydzialuUz=$_POST['idwydz'];
		$idSpecjalizacjiUz=$_POST['idspec'];
		$idKatedryUz=$_POST['idkat'];
		$idSiecioweUz=$_POST['idsiec'];
		$tytul = $_POST['tytul'];
		
		$wiadomosc='id_uzytkownik='.$idUz.'&imie='.$imieUz.'&nazwisko ='.$nazwiskoUz.'&telefon='.$telefonUz.'&id_wydzial='.$idWydzialuUz.'&id_specjalizacja='.$idSpecjalizacjiUz.'&id_katedra='.$idKatedryUz.'&id_sieciowy='.$idSiecioweUz.'&tytul='.$tytul;
		$adres=$this->api->updateUser;
		$wynik=$this->requestApi($wiadomosc,$adres);
		$wynik=json_decode($wynik);
		if($wynik->status==200)
		{
			$wynik=json_encode($wynik);
			$_SESSION['userImie']=$imieUz;
			$_SESSION['userNazwisko']=$nazwiskoUz;
			$_SESSION['userTelefon']=$telefonUz;
			$_SESSION['userWydzial']=$idWydzialuUz;
			$_SESSION['userSpecjalizacja']=$idSpecjalizacjiUz;
			$_SESSION['userKatedra']=$idKatedryUz;
			$_SESSION['userSieciowe']=$idSiecioweUz;
			$_SESSION['userTytul']=$tytul;
			return $wynik;
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
	
		public function pobierzListeProjektow()
	{
		$adres=$this->api->getProjects;
		$wynik=$this->requestApi("",$adres);
		$wynik=json_decode($wynik);
		if($wynik->status==200)
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
	public function wyswietlStudentow($idProjektu,$zatwierdzony)
	{
		$wiadomosc='id_projekt='.$idProjektu.'&zatwierdzony='.$zatwierdzony;
		$adres=$this->api->getUsers;
		$wynik=$this->requestApi($wiadomosc,$adres);
		$wynik=json_decode($wynik);
		if($wynik->status==200)
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
					$lista=$lista.'<tr><td>'.$odbior->login.'<a href="?przyciskProwadzacy=usunStudentaPrzycisk&idStudenta='.$odbior->id_uzytkownik.'"> Usuń</tr></td>';
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
		if($wynik->status==200)
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
		$wiadomosc='id_projekt='.$idProjektu.'&id_koordynator='.$idKoordynator.'&ocena='.$ocena.'&komentarz='.$komentarz;
		$adres=$this->api->evaluateProject;
		$wynik=$this->requestApi($wiadomosc,$adres);
		return $wynik;
	}
	public function pobierzWatek($idWatku)
	{
		$wiadomosc='id_watek='.$idWatku;
		$adres=$this->api->getThread;
		$wynik=$this->requestApi($wiadomosc,$adres);
		$wynik=json_decode($wynik);
		if($wynik->status==200)
		{
			$lista='<tr><td style="width: 10%;">'.$wynik->result->date.'</td><td colspan="2">'.$wynik->result->text.'</td></tr>';
			$pliki="";
			foreach($wynik->result->komentarze as $odbierz)
			{
				if($odbierz->id_zalacznik==NULL)
				$lista=$lista.'<tr><td>'.$odbierz->date.'</td><td>'.$odbierz->text.'</td><td style="width: 18px;"><a href=?przyciskProwadzacy=usunKomentarz&idKomentarz='.$odbierz->id_watek.'">X</a></td></tr>';
				else
				$lista=$lista.'<tr><td>'.$odbierz->date.'</td><td><p>Plik: <a href="ajaxController.php?przyciskProwadzacy=pobierzZalacznik&idZalacznik='.$odbierz->id_zalacznik.'">'.$odbierz->text.'</a></p></td><td style="width: 18px;"><a href=?przyciskProwadzacy=usunKomentarz&idKomentarz='.$odbierz->id_watek.'">X</a></td></tr>';
			}
				$pliki=$pliki.$wynik->result->id_zalacznik;
			$lista='<table border="1" cellspacing="0" style="width:100%;">'.$lista.'</table><br />
							<form method="get" id="obslugaProjektu">
							<input type="submit" name="przyciskProwadzacy" id="nowyKomentarz" value="Nowy Komentarz">
							<input type="submit" name="przyciskProwadzacy" id="usunWatek" value="Usuń Wątek">
							</form>';
			$lista=$lista.'<form action="index.php" method="POST" ENCTYPE="multipart/form-data">
   <input type="file" name="plik"/><br/>
   <input type="submit" name="przyciskProwadzacy" value="Wyślij plik"/>
  </form>';
			$lista=$lista.$pliki;
			$_SESSION['result']=$wynik->result;
			return $lista;
		}
	}
	
	public function nowyWatek($idProjektu)
	{
		$tresc=$_POST['tresc'];
		$adres=$this->api->addThread;
		$wiadomosc='id_projekt='.$idProjektu.'&text='.$tresc.'&id_zalacznik=""';
		$wynik=$this->requestApi($wiadomosc,$adres);
		return $wynik;
	}
	public function nowyKomentarz($idProjektu,$idWatku){
		$trescKomentarza=$_POST['trescKomentarza'];
		$wiadomosc='id_projekt='.$idProjektu.'&id_nadrzedny='.$idWatku.'&text='.$trescKomentarza;
		$adres=$this->api->addComment;
		$wynik=$this->requestApi($wiadomosc,$adres);
		return $wynik;
	}
	public function usunKomentarz(){
		$idKomentarza=$_GET['idKomentarz'];
		$wiadomosc='id_watek='.$idKomentarza;
		$adres=$this->api->deleteComment;
		$wynik=$this->requestApi($wiadomosc,$adres);
	}
	public function usunWatek($idWatku){
		$wiadomosc='id_watek='.$idWatku;
		$adres=$this->api->deleteThread;
		$wynik=$this->requestApi($wiadomosc,$adres);
	}
	
	
		public function wyswietlWszystkichStudentow()
	{
		
		$idKoordynator=$_SESSION['userId'];
		$poz=1;
		$wiadomosc='id_uzytkownik='.$idKoordynator.'&poziom='.$poz;
		$adres=$this->api->showAllUsers;
		$wynik=$this->requestApi($wiadomosc,$adres);
		$wynik=json_decode($wynik);
		if($wynik->status==200)
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
	
	public function usuniecieStudentaZProjektu($idProjektu,$idStudent)
	{
		$wiadomosc='id_projekt='.$idProjektu.'&id_uzytkownik='.$idStudent;
		$adres=$this->api->removeUser;
		$wynik=$this->requestApi($wiadomosc,$adres);
		$wynik=json_decode($wynik);
		return $wynik;
	}
	public function uzupelnienieEdycjiProjektu($idProjektu)
	{
		$lista='<form id="edycjaProjektu">				
				Nazwa: <input type="text" name="nazwaProjektu" id="nazwaProjektu" value="'.$_SESSION['nazwaProjektu'].'" /><br />
				Opis: <input type="text" name="opisProjektu" id="opisProjektu" value="'.$_SESSION['opisProjektu'].'" /><br />
				Termin: <input type="text" name="terminProjektu" id="terminProjektu" value="'.$_SESSION['terminProjektu'].'" /><br />
				Miejsce: <input type="text" name="miejsceProjektu" id="miejsceProjektu" value="'.$_SESSION['miejsceProjektu'].'" /><br />
				Wytyczne: <input type="text" name="wytyczneProjektu" id="wytyczneProjektu" value="'.$_SESSION['wytyczneProjektu'].'" /><br />
				<input type="button" value="Aktualizuj projekt" name="przyciskProwadzacy" id="aktualizujProjekt" /></form>';
				
		return $lista;
	}
	public function edytujProjekt($idProjektu)
	{
		$nazwaProjektu=$_POST['nazwaProjektu'];
		$opisProjektu=$_POST['opisProjektu'];
		$terminProjektu=$_POST['terminProjektu'];
		$miejsceProjektu=$_POST['miejsceProjektu'];
		$wytyczneProjektu=$_POST['wytyczneProjektu'];
		$wiadomosc='id_projekt='.$idProjektu.'&nazwa='.$nazwaProjektu.'&opis='.$opisProjektu.'&termin='.$terminProjektu.'&miejsce='.$miejsceProjektu.'&wytyczne='.$wytyczneProjektu;
		$adres=$this->api->updateProject;
		$wynik=$this->requestApi($wiadomosc,$adres);	
		return $wynik;
	}
	public function wyswietlZarchiwizowane()
	{
		$adres=$this->api->getArchivedProjects;
		$wynik=$this->requestApi("",$adres);
		$wynik=json_decode($wynik);
		if($wynik->status==200)
		{
			
			$lista="";
			foreach($wynik->result as $odbior)
			{
				$lista='<tr><td><a href="?przyciskProwadzacy=pobierzZarchiwizowanyProjekt&idProjekt='.$odbior->id_projekt.'">'.$odbior->nazwa.'</a></td></tr>'.$lista;
			}
			$lista='<table>'.$lista.'</table>';
			$_SESSION['result']=$wynik->result;
			return $lista;
		}
	}
	
	public function wyswietlStudentowZarchiwizowanych($idProjektu,$zatwierdzony)
	{
		$wiadomosc='id_projekt='.$idProjektu.'&zatwierdzony='.$zatwierdzony;
		$adres=$this->api->getUsers;
		$wynik=$this->requestApi($wiadomosc,$adres);
		$wynik=json_decode($wynik);
		if($wynik->status==200)
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