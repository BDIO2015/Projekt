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
				
				case "Studenci":
				
				$wynik=$this->admin->wyswietlStud();
				$wynik=$this->admin->podmien("View/admin/container/containerStudenci.html",$wynik,"{studenci}");
				$this->gui->setContainer($wynik);
				break;		

				case "Prowadzący":
				
				$wynik=$this->admin->wyswietlProwadzacych();
				$wynik=$this->admin->podmien("View/admin/container/containerProwadzacy.html",$wynik,"{prowadzacy}");
				$this->gui->setContainer($wynik);
				break;	
				
				case "Resetuj Hasło":
				$this->admin->passReset();
				$wynik=$this->admin->pobierzUzytkownikow();
				$wynik=$this->admin->podmien("View/admin/container/containerAdministracji.html",$wynik,"{opc}");
				$this->gui->setContainer($wynik);
				break;
				
				case "Projekty":
				$wynik=$this->admin->wyswietlProjekty();
				$wynik=$this->admin->podmien("View/admin/container/containerProjekty.html",$wynik,"{projekty}");
				$this->gui->setContainer($wynik);
				break;	
				
				case "Edytuj konto":
				$wynik=$this->admin->uzupelnienieFormularzaAdmin();
				$this->gui->setContainer($wynik);
				break;
								
				case "Zmień Hasło":
				$this->gui->setContainer("View/admin/container/containerZmianyHasla.html");
				break;	
				
				case "Modyfikuj":
				$wynik=$this->admin->modyfikujHaslo();
				$wynik=$this->admin->uzupelnienieFormularzaAdmin();
				$this->gui->setContainer($wynik);
				break;
				
				case "Wiadomości":
				$this->gui->setContainer("View/admin/container/containerMenuWiadomosciAdmin.html");
				break;
				
				case "Otrzymane":
				$wynik=$this->admin->pobierzWiadomosci("przyciskAdmin");
				$wynik=$this->admin->podmien("View/admin/container/containerOdebraneAdmin.html",$wynik,"{wiadomosci}");
				$this->gui->setContainer($wynik);
				break;
				
				case "pobierzWiadomosc":
					$wynik=$this->admin->pokazWiadomosc($_GET['idWiadomosc']);
					$this->gui->setContainer($wynik);
				break;
				
				case "Napisz wiadomość":
					$this->gui->setContainer("View/admin/container/containerNowaWiadomoscAdmin.html");
				break;
				
				case "wyslijWiadomoscAdmin":
				return $this->student->wiadomoscWyslij();
				break;
				
			
			}		
				
		}
			$this->gui->showGui();
			
	}
				
	
	
	
	
}


?>