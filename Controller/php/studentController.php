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
		if(isset($_POST['przyciskStudent']))
		{
			switch($_POST['przyciskStudent'])
			{
				case "Wyloguj":															
						session_destroy();
						header('refresh: 0.01;');
						return;
				break;
			}
		}
		else
			{
					$gui->setHead("View/student/head/headStudent.html");
					$gui->setMenu("View/student/menu/menuStudent.html");
					$gui->setContainer("View/student/container/containerStudent.html");     							
					$gui->setFooter("View/student/footer/footer.html");
					$gui->showGui();
			}
		
	}
}
?>