<?php
class Gui{
	public function setHead($adres)
	{
		if(file_exists($adres))
		$_SESSION['contentHead']=file_get_contents($adres);
		else
		$_SESSION['contentHead']=$adres;
	}
	public function setMenu($adres)
	{
		if(file_exists($adres))
		$_SESSION['contentMenu']=file_get_contents($adres);
		else
		$_SESSION['contentMenu']=$adres;
	}
	public function setContainer($adres)
	{
		if(file_exists($adres))
		$_SESSION['contentContainer']=file_get_contents($adres);
		else
		$_SESSION['contentContainer']=$adres;
	}
	public function setFooter($adres)
	{
		if(file_exists($adres))
		$_SESSION['contentFooter']=file_get_contents($adres);
		else
		$_SESSION['contentFooter']=$adres;
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