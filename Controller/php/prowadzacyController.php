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
			}
		}
		$this->gui->showGui();
	}
}
?>