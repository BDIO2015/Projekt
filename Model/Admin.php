<?php
require_once "Admin.php";
class Admin extends Student{
	public function __construct()
	{
		$this->api=file_get_contents("./Settings/api.json");
		$this->api=json_decode($this->api);		
	}
	
	
	public function pobierzUzytkownikow()
	{
		$userId=$_SESSION['userId'];
		$temp='id_uzytkownik='.$userId;
		$adres=$this->api->showAllUsers;
		$wynik=$this->requestApi($temp,$adres);
	
		$wynik=json_decode($wynik);
		$lista="";
		
		foreach($wynik->result as $odbior)
			{
				$lista='<option>'.'id_uzytkownik='.$odbior->id_uzytkownik.' login:'.$odbior->login.' imie:'.$odbior->imie.' nazwisko:'.$odbior->nazwisko.' aktywnosc='.$odbior->aktywny.'</option>'.$lista;
			}
			$_SESSION['users']=$wynik->result;
			return $lista;
	}
	
public function wyswietlStudentow()
	
	{
		$userId=$_SESSION['userId'];
		$temp='id_uzytkownik='.$userId.'&poziom=1';
		
		$adres=$this->api->showAllUsers;
		$wynik=$this->requestApi($temp,$adres);
		$wynik=json_decode($wynik);
		
		$lista="";
			$x="'";
			foreach($wynik->result as $odbior)
			{
				$lista='<tr onmouseover="toggleClass(this,'.$x.'trOver'.$x.')"<th></th><td>&nbsp;'.$odbior->imie.'</td><td>&nbsp;'.$odbior->nazwisko.'</td><td>&nbsp;'.$odbior->telefon.'</td><td>&nbsp;'.$odbior->id_wydzial.
				'</td><td>&nbsp;'.$odbior->kierunek.'</td><td>&nbsp;'.$odbior->rok.'</td></tr>'.$lista;
			
			}
			
			return $lista;
		
	}
	
	
	public function wyswietlProwadzacych()
	
	{
		$userId=$_SESSION['userId'];
		$temp='id_uzytkownik='.$userId.'&poziom=2';
		
		$adres=$this->api->showAllUsers;
		$wynik=$this->requestApi($temp,$adres);
		$wynik=json_decode($wynik);
		
		$lista="";
		$x="'";
		
			foreach($wynik->result as $odbior)
			{
				$lista='<tr onmouseover="toggleClass(this,'.$x.'trOver'.$x.')"<th></th><td>&nbsp;'.$odbior->imie.'</td><td>'.$odbior->nazwisko.'</td><td>'.$odbior->telefon.'</td><td>'.$odbior->id_wydzial.
				'</td><td>'.$odbior->id_katedra.'</td><td>'.$odbior->stanowisko.'</td><td>'.$odbior->tytul.'</td></tr>'.$lista;
			
			}
			return $lista;
	}
		
	
		public function dezaktywuj()
		{	
			
			$id_uzytkownik=$_POST['uzytkownicy'];
			$d=substr($id_uzytkownik,-1,1);
			$a=strpos($id_uzytkownik, 'login') ;
			$id_uzytkownik=substr($id_uzytkownik, 0, $a);
			
			if($d==1) $wiadomosc=$id_uzytkownik.'&aktywnosc=0';
			if($d==0) $wiadomosc=$id_uzytkownik.'&aktywnosc=1';
			
			$adres=$this->api->changeActivity;															
			$wynik=$this->requestApi($wiadomosc,$adres);
			$wynik=json_decode($wynik);
			if($wynik->status==200)		
				echo("<SCRIPT LANGUAGE='JavaScript'>window.alert('Operacja zmiany aktywności została wykonana poprawnie')</SCRIPT>"); 
			
			
		}
		
public function uzupelnienieFormularza()
	{
		$id_uzytkownik=$_POST['uzytkownicy'];
		$d=substr($id_uzytkownik,-1,1);
		$a=strpos($id_uzytkownik, 'login') ;
		$id_uzytkownik=substr($id_uzytkownik, 0, $a);
		$id_uzytkownik=substr($id_uzytkownik,14);
		$adres=$this->api->showAllUsers;
		$userId=$_SESSION['userId'];
		$temp='id_uzytkownik='.$userId;
		$wynik=$this->requestApi($temp,$adres);
		$wynik=json_decode($wynik);
		$lista="";
		
		foreach($wynik->result as $odbior)
			{
				if($odbior->id_uzytkownik==$id_uzytkownik)
				{
				$_SESSION['edytowanyUser']=$id_uzytkownik;
				$imie=$odbior->imie;
				$nazwisko=$odbior->nazwisko;
				$telefon=$odbior->telefon;
				$id_wydzial=$odbior->id_wydzial;
				$kierunek=$odbior->kierunek;
				$id_specjalizacja=$odbior->id_specjalizacja;
				$id_katedra=$odbior->id_katedra;
				$id_sieciowy=$odbior->id_sieciowy;
				}
			}
			
		
		return $wynik='Edycja danych osobowych:
		<form id="edytujUser">
		
		Imię: <input type="text" name="imie"id="imie" value="'.$imie.'" /><br />
		Nazwisko: <input type="text" name="nazwisko"id="nazwisko" value="'.$nazwisko.'"/><br />
		Nr telefonu: <input type="text" name="nrtel"id="nrtel" value="'.$telefon.'"/><br />
		ID wydziału: <input type="text" name="idwydz"id="idwydz"value="'.$id_wydzial.'" /><br />
		Kierunek: <input type="text" name="kierunek"id="kierunek" value="'.$kierunek.'"/><br />
		ID specjalizacji: <input type="text" name="idspec"id="idspec" value="'.$id_specjalizacja.'"/><br />
		ID katedry: <input type="text" name="idkat"id="idkat" value="'.$id_katedra.'"/><br />
		ID sieciowe: <input type="text" name="idsiec"id="idsiec"value="'.$id_sieciowy.'" /><br />
		
		<input type="submit" value="Wprowadź zmiany" name="przyciskAdmin" id="wprowadzZmiany" /><br />
		</form>';
		
	}
		
		public function edytujKontoUsera()
		{
			
		$idUz=$_SESSION['edytowanyUser'];
		$imieUz=$_POST['imie'];
		$nazwiskoUz=$_POST['nazwisko'];
		$telefonUz=$_POST['nrtel'];
		$idWydzialuUz=$_POST['idwydz'];
		$kierunekUz=$_POST['kierunek'];
		$idSpecjalizacjiUz=$_POST['idspec'];
		$idKatedryUz=$_POST['idkat'];
		$idSiecioweUz=$_POST['idsiec'];
		
		$wiadomosc='id_uzytkownik='.$idUz.'&imie='.$imieUz.'&nazwisko='.$nazwiskoUz.'&telefon='.$telefonUz.'&id_wydzial='.$idWydzialuUz.'&kierunek='.$kierunekUz.'&id_specjalizacja='.$idSpecjalizacjiUz.'&id_katedra='.$idKatedryUz.'&id_sieciowy='.$idSiecioweUz;
		$adres=$this->api->updateUser;
		$wynik=$this->requestApi($wiadomosc,$adres);
		
	}
			
		
		
		
	public function podmien($adres,$tresc,$slowoKlucz)
	{
		if(file_exists($adres))
		{
			$adres=file_get_contents($adres);
			return str_replace($slowoKlucz,$tresc,$adres);
		}
		else
		return "Blad";
	}
}
?>