<?php
class student{
	private $api;
	public function __construct()
	{
		$this->api=file_get_contents("./Settings/api.json");
		$this->api=json_decode($this->api);		
	}
	public function wyloguj()
	{
		session_destroy();
		header('refresh: 0.01;');
		return;
	}
}
?>
