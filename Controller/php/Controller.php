<?php
require_once "./Controller/php/studentController.php";
require_once "./View/Gui.php";
class Controller{
	const quest=1;
	const userStudent=2;
	protected function user()
	{
		
	    if(isset($_SESSION['userProjekt']))
		{
			return self::userStudent;
		}
		else 
		{
			return self::quest;
		}
	}
	public function load()
	{
		
		$user=$this->user();
		switch($user)
		{
			case self::quest:
			$gui= new Gui();
			$gui->setHead("View/head.html");
			$gui->setMenu("View/menu.html");
			if(!empty($_POST['nowekonto'])=="nowekonto")              
			{															 
				$gui->setContainer("View/contenierRejestracji.html");      
			} 
			else{
				$gui->setContainer("View/contenierLogowania.html");
			}																
			$gui->setFooter("View/footer.html"); 
			$gui->showGui();
			
			break;
			case self::userStudent:
				$studentController= new studentController();
				$studentController->studentLoad();
			break;
		}
		
		
	}
}
?>