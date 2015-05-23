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
							$this->gui->setContainer("View/prowadzacy/container/containerZakonczoneProjektyProwadzacy.html");										
						break;
					}
				break;
				case "pobierzProjekt":
					$_SESSION['idProjektu']=$_GET['idProjekt'];
					$wynik=$this->prowadzacy->pokazProjekt($_GET['idProjekt']);
					$wynik2=$this->prowadzacy->pobierzWatki($_GET['idProjekt']);
					$wynik3=$this->prowadzacy->wyswietlStudentow($_GET['idProjekt'],1);
					$wynik4=$this->prowadzacy->wyswietlStudentow($_GET['idProjekt'],0);
					
					$wynik=$wynik.'<br /><br /><form method="get" id="obslugaProjektu"><input type="submit" name="przyciskProwadzacy" id="archiwizuj" value="Archiwizuj" />
																			<input type="submit" name="przyciskProwadzacy" id="usunProjekt" value="Usuń projekt" /><br />
																			<br />Uczestnicy projektu:<br />'.$wynik3.'<br />Oczekujący na zatwierdzenie:<br />'.$wynik4.
																			'<br /><input type="submit" name="przyciskProwadzacy" id="nowyWatek" value="Nowy Wątek" />
																			</form>';
					
					$wynik=$wynik.'<br />'.$wynik2;
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
				case "pobierzKomentarz":
					$_SESSION['idWatek']=$_GET['idWatek'];
					$wynik=$this->prowadzacy->pobierzWatek($_SESSION['idWatek']);
					$this->gui->setContainer($wynik);
				break;
				case "Nowy Komentarz":
					$this->gui->setContainer("View/prowadzacy/container/containerNowyKomentarz.html");
				break;
				case "Dodaj komentarz":
					$this->prowadzacy->nowyKomentarz($_SESSION['idProjektu'],$_SESSION['idWatek']);
				break;
				case "usunKomentarz":
					$this->prowadzacy->usunKomentarz();
					$wynik=$this->prowadzacy->pobierzWatek($_SESSION['idWatek']);
					$this->gui->setContainer($wynik);
				break;
				case "Usuń Wątek":
					$this->prowadzacy->usunWatek($_SESSION['idWatek']);
				break;
			}
		}
		$this->gui->showGui();
	}
}
?>