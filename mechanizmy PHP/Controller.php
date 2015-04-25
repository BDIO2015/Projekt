<?php
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
		$gui= new Gui();
		switch($user)
		{
			case self::quest:
			$gui->setHead("View/head.html");
			$gui->setMenu("View/menu.html");
			if(!empty($_POST['nowekonto'])=="nowekonto")               //ło tutej
			{															  //ło tutej	
				$gui->setContenier("View/contenierRejestracji.html");        //ło tutej
			} 
			else{
				$gui->setContenier("View/contenierLogowania.html");
			}																  //ło tutej
			$gui->setFooter("View/footer.html"); 
			
			
			break;
			case self::userStudent:
			$gui->setHead("View/zalogowanie.html");
			if(isset($_POST['wyloguj']))               //ło tutej
			{															  //ło tutej	
				session_destroy();
				header('refresh: 0.01;');
			} 
			break;
			
		}
		
		$gui->showGui();
	}
}
?>