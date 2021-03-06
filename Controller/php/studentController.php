<?php
require_once "./View/Gui.php";
require_once "./Model/Student.php";
class studentController{
	private $student;
	private $gui;
	public function __construct()
	{
		$this->student=new Student();
		$this->gui=new Gui();
		$this->gui->setHead("View/student/head/headStudent.html");
		$this->gui->setMenu("View/student/menu/menuStudent.html");
		$this->gui->setContainer("View/student/container/containerStudent.html");															
		$this->gui->setFooter("View/student/footer/footer.html");
		
	}
	public function studentLoad()
	{
		if(isset($_POST['przyciskStudent']) || isset($_GET['przyciskStudent']))
		{
			isset($_POST['przyciskStudent'])?$method=$_POST['przyciskStudent']:$method=$_GET['przyciskStudent'];
			switch($method)
			{
				
				case "Wybierz":
					switch($_GET['menuOpcjaStudent'])
					{
						case "dostepne":
							$wynik=$this->student->pobierzListeProjektow();
							$wynik=$this->student->podmien("View/student/container/containerProjekty.html",$wynik,"{projekty}");
							$this->gui->setContainer($wynik);
							break;
						case "moje":
							$wynik=$this->student->pobierzMojaListeProjektow($_SESSION['userId']);
							$wynik=$this->student->podmien("View/student/container/containerProjekty.html",$wynik,"{projekty}");
							$this->gui->setContainer($wynik);
							break;								
						break;
						case "zakonczone":
							$wynik=$this->student->wyswietlZarchiwizowane();
							$wynik=$this->student->podmien("View/prowadzacy/container/containerZakonczoneProjektyProwadzacy.html",$wynik,"{zarchiwizowane}");
							$this->gui->setContainer($wynik);										
						break;
					}
				break;
				case "pobierzProjekt":
					$_SESSION['idProjektu']=$_GET['idProjekt'];
					$wynik=$this->student->pokazProjekt($_GET['idProjekt'],"View/student/container/containerProjektStudent.html");
					$wynik=$this->student->podmien($wynik,$this->student->wyswietlStudentow($_GET['idProjekt'],1),'{uczestnicy}');
					$wynik=$wynik.$this->student->pobierzRaporty($_GET['idProjekt']).$this->student->pobierzWatki($_GET['idProjekt'],'Student');
					$this->gui->setContainer($wynik);
				break;
				case "pobierzZarchiwizowanyProjekt":	
					$_SESSION['idProjektu']=$_GET['idProjekt'];
					$wynik=$this->student->pokazZarchiwizowanyProjekt($_GET['idProjekt'],"View/prowadzacy/container/containerProjektZarchiwizowanyProwadzacy.html");
					$wynik=$this->student->podmien($wynik,"Brak dostepu",'{uczestnicy}');
					$this->gui->setContainer($wynik);
				break;
				
				case "Dodaj mnie do projektu":
					$wynik=$this->student->dolaczenieDoProjektu($_SESSION['idProjektu']);
				
				break;
				case "pobierzKomentarz":
					$_SESSION['idWatek']=$_GET['idWatek'];
					$wynik=$this->student->pobierzWatek($_SESSION['idWatek']);
					$this->gui->setContainer($wynik);
				break;
				case "Nowy Komentarz":
					$this->gui->setContainer("View/prowadzacy/container/containerNowyKomentarz.html");
				break;
				case "Wyślij plik":
					$wynik=$this->student->wyslijPlik();
					$this->gui->setContainer($wynik);
				break;
				case "Nowy raport":
					$this->gui->setContainer("View/student/container/containerNowyRaport.html");
				break;
				
				case "Stwórz raport":
					return $this->student->nowyRaport($_SESSION['idProjektu']);
				break;
				
				case "Wyloguj":															
					$this->student->wyloguj();
				break;
				case "Edytuj konto":
					$wynik=$this->student->uzupelnienieFormularza();
					$this->gui->setContainer($wynik);															
				break;
				
				case "Wiadomości":															
					$this->gui->setContainer("View/student/container/containerMenuWiadomosciStudent.html");
				break;
				
				case "Napisz wiadomość":
					$this->gui->setContainer("View/student/container/containerNowaWiadomoscStudent.html");
				break;
				
				case "Otrzymane":
					$wynik=$this->student->pobierzWiadomosci("przyciskStudent");
					$wynik=$this->student->podmien("View/student/container/containerOdebraneStudent.html",$wynik,"{wiadomosci}");
					$this->gui->setContainer($wynik);
				break;
				
				case "Wysłane":
					$this->gui->setContainer("View/student/container/containerWyslaneStudent.html");
				break;
				case "pobierzWiadomosc":
					$wynik=$this->student->pokazWiadomosc($_GET['idWiadomosc']);
					$this->gui->setContainer($wynik);
				break;
				case "wyslijWiadomosc":
					return $this->student->wiadomoscWyslij();
				break;
				case "sprawdzCzyIstnieje":
				 	return $this->student->sprawdzCzyIstnieje();
				break;
				
				case "przeslijZmiany":
				 	return $this->student->edytujKontoStudenta();
				break;
				
				case "zmien":
				 	return $this->student->zmienHaslo();
				break;
			}
		}
		$this->gui->showGui();

		
	}
}
?>