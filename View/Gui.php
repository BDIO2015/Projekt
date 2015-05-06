<?php
class Gui{
	public function setHead($adres)
	{
		$_SESSION['contentHead']=file_get_contents($adres);
	}
	public function setMenu($adres)
	{
		$_SESSION['contentMenu']=file_get_contents($adres);
	}
	public function setContainer($adres)
	{
		$_SESSION['contentContainer']=file_get_contents($adres);
		
	}
	public function setFooter($adres)
	{
		$_SESSION['contentFooter']=file_get_contents($adres,-1);
	}
	public function showGui()
	{
		
		$html=file_get_contents("View/Gui.html");
		
		$search='{contentHead}';
		$replace=$_SESSION['contentHead'];
		$html=str_replace($search,$replace,$html);
		
		$search='{contentMenu}';
		$replace=$_SESSION['contentMenu'];
		$html=str_replace($search,$replace,$html);
		
		$search='{contentContainer}';
		$replace=$_SESSION['contentContainer'];
		$html=str_replace($search,$replace,$html);
		
		$search='{contentFooter}';
		$replace=$_SESSION['contentFooter'];
		$html=str_replace($search,$replace,$html);
	
		print_r($html);
	}
}
?>