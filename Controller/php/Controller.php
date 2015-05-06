<?php
require_once "./Controller/php/studentController.php";
require_once "./Controller/php/goscController.php";
require_once "./View/Gui.php";
class Controller{
	const guest=1;
	const userStudent=2;
	protected function user()
	{
		
	    if(isset($_SESSION['userProjekt']))
		{
			return self::userStudent;
		}
		else 
		{
			return self::guest;
		}
	}
	public function load()
	{
		
		$user=$this->user();
		switch($user)
		{
			case self::guest:
				$goscController= new goscController();
				$goscController->goscLoad();
			break;
			case self::userStudent:
				$studentController= new studentController();
				$studentController->studentLoad();
			break;
		}
		
		
	}
}
?>