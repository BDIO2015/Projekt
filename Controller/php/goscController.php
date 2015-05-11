<?php
require_once "./View/Gui.php";
require_once "./Model/gosc.php";
class goscController{
	private $gosc;
	private $gui;
	public function __construct()
	{
		$this->gosc=new Gosc();
		$this->gui=new Gui();
		$this->gui->setHead("View/gosc/head/headGosc.html");
		$this->gui->setMenu("View/gosc/menu/menuGosc.html");
		$this->gui->setContainer("View/gosc/container/containerLogowaniaGosc.html");															
		$this->gui->setFooter("View/gosc/footer/footer.html");
		
	}
	
	public function goscLoad()
	{
		if(isset($_POST['przyciskGosc']) || isset($_GET['przyciskGosc']))
		{
			isset($_POST['przyciskGosc'])?$method=$_POST['przyciskGosc']:$method=$_GET['przyciskGosc'];
			switch($method)
			{
				case "login":													
						return $this->gosc->login();
				break;
				case "register":												
						return $this->gosc->register();
				case "Nowe konto":
					$this->gui->setContainer("View/gosc/container/containerRejestracjiGosc.html");
				break;
				break;
			}
		}
		$this->gui->showGui();
	}
}
?>