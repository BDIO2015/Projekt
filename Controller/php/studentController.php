<?php
require_once "./View/Gui.php";
class studentController{
	protected function getHead()
	{
	}
	protected function getContainer()
	{
	}
	public function studentLoad()
	{
		$gui= new Gui();
		$gui->setHead("View/headZalogowanego.html");
		$gui->setMenu("View/menuStudenta.html");
		$gui->setContainer("View/contenierZalogowanego.html");       											  
		$gui->setFooter("View/footer.html");
		if(isset($_POST['wyloguj']))              
		{															
			session_destroy();
			header('refresh: 0.01;');
		}
		$gui->showGui();
	}
}
?>