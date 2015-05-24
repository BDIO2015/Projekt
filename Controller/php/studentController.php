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
							$this->gui->setContainer("View/student/container/containerNowyProjektStudent.html");										
						break;
						case "zakonczone":
							$this->gui->setContainer("View/student/container/containerZakonczoneProjektyStudent.html");										
						break;
					}
				break;
				case "pobierzProjekt":
					$_SESSION['idProjektu']=$_GET['idProjekt'];
					$wynik=$this->student->pokazProjekt($_GET['idProjekt'],"View/student/container/containerProjektStudent.html");
					$wynik=$this->student->podmien($wynik,$this->student->wyswietlStudentow($_GET['idProjekt'],1),'{uczestnicy}');
					$wynik=$wynik.$this->student->pobierzWatki($_GET['idProjekt']);
					$this->gui->setContainer($wynik);
				break;
				
				case "Dodaj mnie do projektu":
					$wynik=$this->student->dolaczenieDoProjektu($_SESSION['idProjektu']);
				
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
					$wynik=$this->student->pobierzWiadomosci();
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