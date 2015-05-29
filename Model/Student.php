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
				$tytul='<tr><td><a href="?przyciskStudent=pobierzWiadomosc&idWiadomosc='.$odebrana->id_wiadomosc.'">'.$odebrana->tytul.'</a></td>';
				$data='<td>Data: '.$odebrana->data.'</td></tr>';
				$lista=$tytul.$data.$lista;
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
	public function czyProjektIstnieje($idProjektu)
	{
		$adres=$this->api->isProjectExist;
		$wiadomosc='id_projekt='.$idProjektu;
		$wynik=$this->requestApi($wiadomosc,$adres);
		return $wynik;
	}
	public function uzupelnienieFormularza()
	{
		return $wynik='Edycja danych osobowych:
		<form id="edytujKonto">
		Imię: <input type="text" name="imie"id="imie" value="'.$_SESSION['userImie'].'" /><br />
		Nazwisko: <input type="text" name="nazwisko"id="nazwisko" value="'.$_SESSION['userNazwisko'].'"/><br />
		Nr telefonu: <input type="text" name="nrtel"id="nrtel" value="'.$_SESSION['userTelefon'].'"/><br />
		ID wydziału: <input type="text" name="idwydz"id="idwydz"value="'.$_SESSION['userWydzial'].'" /><br />
		Kierunek: <input type="text" name="kierunek"id="kierunek" value="'.$_SESSION['userKierunek'].'"/><br />
		ID specjalizacji: <input type="text" name="idspec"id="idspec" value="'.$_SESSION['userSpecjalizacja'].'"/><br />
		ID katedry: <input type="text" name="idkat"id="idkat" value="'.$_SESSION['userKatedra'].'"/><br />
		ID sieciowe: <input type="text" name="idsiec"id="idsiec"value="'.$_SESSION['userSieciowe'].'" /><br />
		<input type="submit" value="Prześlij" name="przyciskStudent" id="przeslijZmiany" /><br />
		</form>
		Zmień hasło:
		<form id="zmienHaslo">
		Login: <input type="text" name="username"id="username" /><br />
		Hasło: <input type="password" name="password" id="password"/><br />
		Nowe hasło: <input type="password" name="newpassword" id="newpassword"/><br />
		<input type="submit" value="Zmień" name="przyciskStudent" id="zmien" /></form>';
		
	}
	public function zmienHaslo()
	{
		$idUz=$_SESSION['userId'];
		$nazwaUz=$_POST['username'];
		$hasloUz=$_POST['password'];
		$noweUz=$_POST['newpassword'];
		$wiadomosc='id_uzytkownik='.$idUz.'&login='.$nazwaUz.'&haslo='.$hasloUz.'&nowe_haslo='.$noweUz;
		$adres=$this->api->changePass;
		$wynik=$this->requestApi($wiadomosc,$adres);
		$wynik=json_decode($wynik);
		if($wynik->status==200)
		{
			$wynik=json_encode($wynik);
			return $wynik;
		}
		return 0;
	}
	public function edytujKontoStudenta()
	{
		$idUz=$_SESSION['userId'];
		$imieUz=$_POST['imie'];
		$nazwiskoUz=$_POST['nazwisko'];
		$telefonUz=$_POST['nrtel'];
		$idWydzialuUz=$_POST['idwydz'];
		$kierunekUz=$_POST['kierunek'];
		$idSpecjalizacjiUz=$_POST['idspec'];
		$idKatedryUz=$_POST['idkat'];
		$idSiecioweUz=$_POST['idsiec'];
		
		$wiadomosc='id_uzytkownik='.$idUz.'&imie='.$imieUz.'&nazwisko ='.$nazwiskoUz.'&telefon='.$telefonUz.'&id_wydzial='.$idWydzialuUz.'&kierunek='.$kierunekUz.'&id_specjalizacja='.$idSpecjalizacjiUz.'&id_katedra='.$idKatedryUz.'&id_sieciowy='.$idSiecioweUz;
		$adres=$this->api->updateUser;
		$wynik=$this->requestApi($wiadomosc,$adres);
		$wynik=json_decode($wynik);
		if($wynik->status==200)
		{
			$_SESSION['userId']=$idUz;
			$_SESSION['userImie']=$imieUz;
			$_SESSION['userNazwisko']=$nazwiskoUz;
			$_SESSION['userTelefon']=$telefonUz;
			$_SESSION['userWydzial']=$idWydzialuUz;
			$_SESSION['userKierunek']=$kierunekUz;
			$_SESSION['userSpecjalizacja']=$idSpecjalizacjiUz;
			$_SESSION['userKatedra']=$idKatedryUz;
			$_SESSION['userSieciowe']=$idSiecioweUz;
			$wynik=json_encode($wynik);
			return $wynik;
		}
		return 0;
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
				$lista='<tr><td><a href="?przyciskStudent=pobierzProjekt&idProjekt='.$odbior->id_projekt.'">'.$odbior->nazwa.'</a></td></tr>'.$lista;
			}
			$lista='<table>'.$lista.'</table>';
			$_SESSION['result']=$wynik->result;
			return $lista;
		}
	}
	
	public function pobierzMojaListeProjektow($idUzytkownik)
	{
		$adres=$this->api->getProjectsWithUser;
		$wynik=$this->requestApi("id_uzytkownik=$idUzytkownik",$adres);
		$wynik=json_decode($wynik);
		if($wynik->status==200)
		{
			$lista="";
			foreach($wynik->result as $odbior)
			{
				$lista='<tr><td><a href="?przyciskStudent=pobierzProjekt&idProjekt='.$odbior->id_projekt.'">'.$odbior->nazwa.'</a></td></tr>'.$lista;
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
					$lista=$lista.'<tr><td>'.$odbior->login.'</td></tr>';
				}
				else
				{
					$lista=$lista.'<tr><td>'.$odbior->login.'</td></tr>';
				}
			}
			$lista='<table>'.$lista.'</table>';
			$_SESSION['result']=$wynik->result;
			return $lista;
		}
	}
	public function pokazProjekt($idProjektu,$adresFormularza)
	{				
				$wiadomosc='id_projekt='.$idProjektu;
				$adres=$this->api->getProject;
				$wynikProjekt=$this->requestApi($wiadomosc,$adres);
				$adres=$this->api->getGrade;
				$wynikOcena=$this->requestApi($wiadomosc,$adres);
				$wynikProjekt=json_decode($wynikProjekt);
				$wynikOcena=json_decode($wynikOcena);
				if($wynikProjekt->status==200)
				{
					if(file_exists($adresFormularza)){
						$html=file_get_contents($adresFormularza);
						$_SESSION['nazwaProjektu']=$wynikProjekt->result->nazwa;
						$_SESSION['opisProjektu']=$wynikProjekt->result->opis;
						$_SESSION['terminProjektu']=$wynikProjekt->result->termin;
						$_SESSION['miejsceProjektu']=$wynikProjekt->result->miejsce;
						$_SESSION['wytyczneProjektu']=$wynikProjekt->result->wytyczne;
						$replace='<p>Nazwa='.$wynikProjekt->result->nazwa.'</p>';
						$replace=$replace.'<p>Opis='.$wynikProjekt->result->opis.'</p>';
						$replace=$replace.'<p>Termin='.$wynikProjekt->result->termin.'</p>';
						$replace=$replace.'<p>Miejsce='.$wynikProjekt->result->miejsce.'</p>';
						$replace=$replace.'<p>Wytyczne='.$wynikProjekt->result->wytyczne.'</p>';
						if($wynikOcena->status==200)
						{
							$replace=$replace.'<p>Ocena='.$wynikOcena->result[0]->ocena.'</p>';
							$replace=$replace.'<p>Komentarz do oceny='.$wynikOcena->result[0]->komentarz.'</p>';
						}
						else
						{
							$replace=$replace.'<p>Ocena= Brak</p>';
							$replace=$replace.'<p>Komentarz do oceny= Brak</p>';
						}
						$html=str_replace('{wynik}',$replace,$html);
					}
					return $html;
				}
		return 0;
	}
	
	public function nowyRaport($idProjektu)
	{
		$tresc=$_POST['tresc'];
		$adres=$this->api->addRaport;
		$wiadomosc='id_projekt='.$idProjektu.'&text='.$tresc;
		$wynik=$this->requestApi($wiadomosc,$adres);
		return $wynik;
	}
	
	public function pobierzRaporty($idProjektu)
	{
		$wiadomosc='id_projekt='.$idProjektu;
		$adres=$this->api->getRaports;
		$wynik=$this->requestApi($wiadomosc,$adres);
		$wynik=json_decode($wynik);
		$lista="";
		if($wynik->status==200)
		{
			foreach($wynik->result as $odbierz)
			{
				$lista .='<div>'.$odbierz->date.'<br />'.$odbierz->text.'</div>';
			}
			$lista='<div id="raports">'.$lista.'</div>';
			$_SESSION['result']=$wynik->result;
		}
		
		return $lista;
	}
	
	public function pobierzWatki($idProjektu)
	{
		$wiadomosc='id_projekt='.$idProjektu;
		$adres=$this->api->getThreads;
		$wynik=$this->requestApi($wiadomosc,$adres);
		$wynik=json_decode($wynik);
		if($wynik->status==200)
		{
			$lista="";
			foreach($wynik->result->watki as $odbierz)
			{
				$lista='<tr><td><a href="?przyciskStudent=pobierzKomentarz&idWatek='.$odbierz->id_watek.'">'.$odbierz->text.' </a>'.$odbierz->date.'</a></td></tr>'.$lista;
			}
			$lista='<table>'.$lista.'</table>';
			$_SESSION['result']=$wynik->result;
			return $lista;
		}
	}
	public function dolaczenieDoProjektu($idProjektu)
	{
		$idUz=$_SESSION['userId'];
		$wiadomosc='id_uzytkownik='.$idUz.'&id_projekt='.$idProjektu;
		$adres=$this->api->addUser;
		$wynik=$this->requestApi($wiadomosc,$adres);
		return $wynik;
	}
	public function wyslijPlik()
	{
		$max_rozmiar = 1024*1024;
	if (is_uploaded_file($_FILES['plik']['tmp_name'])) {
    if ($_FILES['plik']['size'] > $max_rozmiar) {
        return 'Błąd! Plik jest za duży!';
    } else {
        $wynik= '<p>Odebrano plik. Początkowa nazwa: '.$_FILES['plik']['name'].'</p>';
        if (isset($_FILES['plik']['type'])) {
            $wynik=$wynik. '<p>Typ: '.$_FILES['plik']['type'].'</p>';
        }
		$sciezka = str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']).'/Files/';
		$file_name=$_FILES['plik']['name'];
		$i=0;
		$file_name2=$file_name;
		while(1){
			if(!file_exists($sciezka.$file_name2)){
				move_uploaded_file($_FILES['plik']['tmp_name'], $sciezka.$file_name2);
				$idWatek=$_SESSION['idWatek'];
				$idProjekt=$_SESSION['idProjektu'];
				$wiadomosc='sciezka='.$file_name2.'&id_watek='.$idWatek;
				$adres=$this->api->addAttachment;
				$wynik=$this->requestApi($wiadomosc,$adres);
				$wynik=json_decode($wynik);
				$wiadomosc='id_projekt='.$idProjekt.'&id_nadrzedny='.$idWatek.'&text=Plik '.$file_name2.'&id_zalacznik='.$wynik->sciezka;
				$adres=$this->api->addComment;
				$wynik=$this->requestApi($wiadomosc,$adres);
				$wynik=json_decode($wynik);
				if($wynik->status==201)
					return 'Plik został wysłany';
				else
					return 'Pliku nie udało się wysłać';
			}
			$i++;
			$file_name2=str_replace('.', '_'.$i.'.', $file_name);
		}
    }
	} else {
		return 'Błąd przy przesyłaniu danych!';
		}
	}

}
?>
