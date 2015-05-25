<?php
require_once "./Controller/php/studentController.php";
require_once "./Controller/php/goscController.php";
require_once "./Controller/php/prowadzacyController.php";
require_once "./Controller/php/adminController.php";
require_once "./View/Gui.php";
class Controller{
	const guest=0;
	const userStudent=1;
	const userProwadzacy=2;
	const userAdmin=3;
	protected function user()
	{
		
	    if(isset($_SESSION['userProjekt']))
		{
			switch($_SESSION['poziom'])
			{
				case '1':
					return self::userStudent;
				break;
				case '2':
					return self::userProwadzacy;
				break;
				case '3':
					return self::userAdmin;
			}
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
			case self::userProwadzacy:
				$prowadzacyController= new prowadzacyController();
				$prowadzacyController->prowadzacyLoad();
			break;
			case self::userAdmin:
				$adminController= new adminController();
				$adminController->adminLoad();
			break;
		}
	}
}
?>