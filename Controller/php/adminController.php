<?php
require_once "./View/Gui.php";
require_once "./Model/Admin.php";
class adminController{
	private $admin;
	private $gui;
	public function __construct()
	{
		$this->admin=new Admin();
		$this->gui=new Gui();
		$this->gui->setHead("View/admin/head/headAdmin.html");
		$this->gui->setMenu("View/admin/menu/menuAdmin.html");
		$this->gui->setContainer("View/admin/container/containerAdmin.html");															
		$this->gui->setFooter("View/admin/footer/footer.html");
		
	}
	
	public function adminLoad()
	{
		if(isset($_POST['przyciskAdmin']) || isset($_GET['przyciskAdmin']))
		{
			isset($_POST['przyciskAdmin'])?$method=$_POST['przyciskAdmin']:$method=$_GET['przyciskAdmin'];
			switch($method)
			{
				case "Wyloguj":															
					$this->admin->wyloguj();
				break;
				
				case "Panel administracji":
				$wynik=$this->admin->pobierzUzytkownikow();
				$wynik=$this->admin->podmien("View/admin/container/containerAdministracji.html",$wynik,"{opc}");
				$this->gui->setContainer($wynik);
				break;
					
				case "Aktywuj/Dezaktywuj":
				$this->admin->dezaktywuj();
				$wynik=$this->admin->pobierzUzytkownikow();
				$wynik=$this->admin->podmien("View/admin/container/containerAdministracji.html",$wynik,"{opc}");
				$this->gui->setContainer($wynik);
				break;
				
				case "Edytuj":
			
				$wynik=$this->admin->uzupelnienieFormularza();
				$this->gui->setContainer($wynik);
				break;
			
			
				
				case "Edytuj użytkownika":
				
				$wynik=$this->admin->uzupelnienieFormularza();
				$this->gui->setContainer($wynik);
				break;
				
				case "wprowadzZmiany":
				
				$this->admin->edytujKontoUsera();
				break;
				
				
			
			}		
				
		}
			$this->gui->showGui();
			
	}
				
	
	
	
	
}


?>