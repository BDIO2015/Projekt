<?php
require_once "./View/Gui.php";
require_once "./Model/Prowadzacy.php";
class prowadzacyController{
	private $prowadzacy;
	private $gui;
	public function __construct()
	{
		$this->prowadzacy=new Prowadzacy();
		$this->gui=new Gui();
		$this->gui->setHead("View/prowadzacy/head/headProwadzacy.html");
		$this->gui->setMenu("View/prowadzacy/menu/menuProwadzacy.html");
		$this->gui->setContainer("View/prowadzacy/container/containerProwadzacy.html");															
		$this->gui->setFooter("View/prowadzacy/footer/footer.html");
		
	}
	public function prowadzacyLoad()
	{
		if(isset($_POST['przyciskProwadzacy']) || isset($_GET['przyciskProwadzacy']))
		{
			isset($_POST['przyciskProwadzacy'])?$method=$_POST['przyciskProwadzacy']:$method=$_GET['przyciskProwadzacy'];
			switch($method)
			{
				case "Wyloguj":													
					return $this->prowadzacy->wyloguj();
				break;
				case "Wybierz":
					switch($_GET['menuOpcjaProwadzacy'])
					{
						case "przeprowadzane":
							$wynik=$this->prowadzacy->pobierzListeProjektow();
							$wynik=$this->prowadzacy->podmien("View/prowadzacy/container/containerProjekty.html",$wynik,"{projekty}");
							$this->gui->setContainer($wynik);
							break;
						case "stworz":
							$this->gui->setContainer("View/prowadzacy/container/containerNowyProjektProwadzacy.html");										
						break;
						case "zakonczone":
							$wynik=$this->prowadzacy->wyswietlZarchiwizowane();
							$wynik=$this->prowadzacy->podmien("View/prowadzacy/container/containerZakonczoneProjektyProwadzacy.html",$wynik,"{zarchiwizowane}");
							$this->gui->setContainer($wynik);										
						break;
					}
				break;
				case "pobierzProjekt":
					$_SESSION['idProjektu']=$_GET['idProjekt'];
					$wynik=$this->prowadzacy->pokazProjekt($_GET['idProjekt'],"View/prowadzacy/container/containerProjektProwadzacy.html");
					$wynik=$wynik.$this->prowadzacy->pobierzRaporty($_GET['idProjekt']).$this->prowadzacy->pobierzWatki($_GET['idProjekt'],'Prowadzacy');
					$wynik=$this->prowadzacy->podmien($wynik,$this->prowadzacy->wyswietlStudentow($_GET['idProjekt'],1),'{uczestnicy}');
					$wynik=$this->prowadzacy->podmien($wynik,$this->prowadzacy->wyswietlStudentow($_GET['idProjekt'],0),'{oczekujacy}');
					$wynik=$this->prowadzacy->podmien($wynik,$this->prowadzacy->wyswietlWszystkichStudentow(),'{wszyscy}');
					$this->gui->setContainer($wynik);
				break;
				
				case "Edytuj konto":
					ob_start();
					include __DIR__ . '/../../View/prowadzacy/container/containerEdytujKonto.phtml';
					$body = ob_get_clean();
					$this->gui->setContainer($body);															
				break;
				
				case "zmienHaslo":
				 	return $this->prowadzacy->zmienHaslo();
				break;
				
				case "przeslijZmiany":
				 	return $this->prowadzacy->edytujKonto();
				break;
				
				case "Wiadomości":															
					$this->gui->setContainer("View/prowadzacy/container/containerMenuWiadomosci.html");
				break;
				
				case "Napisz wiadomość":
					$this->gui->setContainer("View/prowadzacy/container/containerNowaWiadomosc.html");
				break;
				
				case "Otrzymane":
					$wynik=$this->prowadzacy->pobierzWiadomosci();
					$wynik=$this->prowadzacy->podmien("View/prowadzacy/container/containerOdebrane.html",$wynik,"{wiadomosci}");
					$this->gui->setContainer($wynik);
				break;
				
				case "Wysłane":
					$this->gui->setContainer("View/prowadzacy/container/containerWyslane.html");
				break;
				
				case "pobierzWiadomosc":
					$wynik=$this->prowadzacy->pokazWiadomosc($_GET['idWiadomosc']);
					$this->gui->setContainer($wynik);
				break;
				
				case "wyslijWiadomosc":
					return $this->prowadzacy->wiadomoscWyslij();
				break;
				
				case "sprawdzCzyIstnieje":
				 	return $this->prowadzacy->sprawdzCzyIstnieje();
				break;
				
				case "pobierzZarchiwizowanyProjekt":	
					$_SESSION['idProjektu']=$_GET['idProjekt'];
					$wynik=$this->prowadzacy->pokazZarchiwizowanyProjekt($_GET['idProjekt'],"View/prowadzacy/container/containerProjektZarchiwizowanyProwadzacy.html");
					$wynik=$wynik.$this->prowadzacy->pobierzWatki($_GET['idProjekt']);
					$wynik=$this->prowadzacy->podmien($wynik,$this->prowadzacy->wyswietlStudentowZarchiwizowanych($_GET['idProjekt'],1),'{uczestnicy}');
					$this->gui->setContainer($wynik);
				break;
				case "stworz":
                  return $this->prowadzacy->stworzNowyProjekt();
				break;
				case "Archiwizuj":
					return $this->prowadzacy->archiwizujProjekt($_SESSION['idProjektu'],$_SESSION['nazwaProjektu']);
				break;
				case "Usuń projekt":
					return $this->prowadzacy->usunProjekt($_SESSION['idProjektu']);
				break;
				case "Nowy Wątek":
					$this->gui->setContainer("View/prowadzacy/container/containerNowyWatek.html");										
				break;
				case "Stwórz Wątek":
					return $this->prowadzacy->nowyWatek($_SESSION['idProjektu']);
				break;
				
				case "akceptujStudentaPrzycisk":
					 $_SESSION['idStudent']=$_GET['idStudenta'];
					 $this->prowadzacy->dolaczenieStudentaDoProjektu($_SESSION['idProjektu'],$_SESSION['idStudent']);
				   	return $this->prowadzacy->akceptujStudenta($_SESSION['idProjektu'],$_SESSION['idStudent']);
				break;
				
				case "usunStudentaPrzycisk":
					 $_SESSION['idStudent']=$_GET['idStudenta'];
					 $this->prowadzacy->usuniecieStudentaZProjektu($_SESSION['idProjektu'],$_SESSION['idStudent']);
				break;
				case "Ocena":
					$this->gui->setContainer("View/prowadzacy/container/containerOcenaProwadzacy.html");	
				break;
				case "wstawOcena":
					return $this->prowadzacy->wstawOcene();
				break;
				case "pobierzKomentarz":
					$_SESSION['idWatek']=$_GET['idWatek'];
					$wynik=$this->prowadzacy->pobierzWatek($_SESSION['idWatek']);
					$this->gui->setContainer($wynik);
				break;
				case "Nowy Komentarz":
					$this->gui->setContainer("View/prowadzacy/container/containerNowyKomentarz.html");
				break;
				case "Dodaj komentarz":
					return $this->prowadzacy->nowyKomentarz($_SESSION['idProjektu'],$_SESSION['idWatek']);
				break;
				case "usunKomentarz":
					$this->prowadzacy->usunKomentarz();
					$wynik=$this->prowadzacy->pobierzWatek($_SESSION['idWatek']);
					$this->gui->setContainer($wynik);
				break;
				case "Usuń Wątek":
					$this->prowadzacy->usunWatek($_SESSION['idWatek']);
				break;
				case "Wyślij plik":
					$wynik=$this->prowadzacy->wyslijPlik();
					$this->gui->setContainer($wynik);
				break;
				case "Edytuj projekt":
					$wynik=$this->prowadzacy->uzupelnienieEdycjiProjektu($_SESSION['idProjektu']);
					$this->gui->setContainer($wynik);
					
				break;
				case "aktualizujProjekt":
					$wynik=$this->prowadzacy->edytujProjekt($_SESSION['idProjektu']);
					return $wynik;
				break;
				case "pobierzZalacznik":
					$this->prowadzacy->pobierzPlik($_GET['idZalacznik']);
				break;
			}
		}
		$this->gui->showGui();
	}
}
?>