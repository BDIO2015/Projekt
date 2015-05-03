<?php
class Gui{
	private $contentHead;
	private $contentMenu;
	private $contentContainer;
	private $contentFooter;
	public function setHead($adres)
	{
		$this->contentHead=file_get_contents($adres);
	}
	public function setMenu($adres)
	{
		$this->contentMenu=file_get_contents($adres);
	}
	public function setContainer($adres)
	{
		$this->contentContainer=file_get_contents($adres);
		
	}
	public function setFooter($adres)
	{
		$this->contentFooter=file_get_contents($adres,-1);
	}
	public function showGui()
	{
		
		$html=file_get_contents("View/Gui.html");
		
		$search='{contentHead}';
		$replace=$this->contentHead;
		$html=str_replace($search,$replace,$html);
		
		$search='{contentMenu}';
		$replace=$this->contentMenu;
		$html=str_replace($search,$replace,$html);
		
		$search='{contentContainer}';
		$replace=$this->contentContainer;
		$html=str_replace($search,$replace,$html);
		
		$search='{contentFooter}';
		$replace=$this->contentFooter;
		$html=str_replace($search,$replace,$html);
	
		print_r($html);
	}
}
?>