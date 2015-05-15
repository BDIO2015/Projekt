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
					isset($_POST['menuOpcjaProwadzacy'])?$method2=$_POST['menuOpcjaProwadzacy']:$method2=$_GET['menuOpcjaProwadzacy'];
					switch($method2)
					{
						case "przeprowadzane":
							$wynik=$this->prowadzacy->pobierzListeProjektow();
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
                case "stworz":
                  return $this->prowadzacy->stworzNowyProjekt();
				break;
			}
		}
		$this->gui->showGui();
	}
}
?>