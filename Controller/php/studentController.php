<?php
require_once "./View/Gui.php";
require_once "./Model/Student.php";
class studentController{
	private $student;
	private $gui;
	public function __construct()
	{
		$this->student=new Student();
		$this->gui=new Gui();
		$this->gui->setHead("View/student/head/headStudent.html");
		$this->gui->setMenu("View/student/menu/menuStudent.html");
		$this->gui->setContainer("View/student/container/containerStudent.html");															
		$this->gui->setFooter("View/student/footer/footer.html");
		
	}
	public function studentLoad()
	{
		if(isset($_POST['przyciskStudent']) || isset($_GET['przyciskStudent']))
		{
			isset($_POST['przyciskStudent'])?$method=$_POST['przyciskStudent']:$method=$_GET['przyciskStudent'];
			switch($method)
			{
				case "Wyloguj":															
					$this->student->wyloguj();
				break;
			}
		}
		$this->gui->showGui();

		
	}
}
?>