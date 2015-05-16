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
		$this->gui->setHead("View/admin/head/headadmin.html");
		$this->gui->setMenu("View/admin/menu/menuadmin.html");
		$this->gui->setContainer("View/admin/container/containeradmin.html");															
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
			}
		}
		$this->gui->showGui();
	}
}
?>