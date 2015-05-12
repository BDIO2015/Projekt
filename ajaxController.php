<?php
session_start();
require_once "./Controller/php/goscController.php";
require_once "./Controller/php/studentController.php";
require_once "./Controller/php/prowadzacyController.php";
require_once "./Controller/php/adminController.php";
if(isset($_SESSION['userProjekt']))
	{
		switch($_SESSION['poziom'])
		{
			case '1':
				$studentController= new studentController();
				echo $studentController->studentLoad();
			break;
			case '2':
				$prowadzacyController= new prowadzacyController();
				echo $prowadzacyController->prowadzacyLoad();
			break;
			case '3':
				$adminController= new adminController();
				echo $adminController->adminLoad();
			break;
			}
		}
		else 
		{
			$goscController= new goscController();
			echo $goscController->goscLoad();
		}
?>