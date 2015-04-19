<?php

include_once __DIR__.'/include/DbHandler.php';
$method = substr($_SERVER['REQUEST_URI'], strlen(substr($_SERVER['SCRIPT_NAME'], 0, strlen($_SERVER['SCRIPT_NAME']) - 9)));
$db = new DbHandler();

$result = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') 
switch($method)
{
	default:
	case "api": 
		$path = "public/index.html";
		$file = fopen($path, "r");
		$dane = fread($file, filesize($path));
		echo $dane;
		break;
}else
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
switch($method)
{
	case "login": $result = $db->login(); break;
	case "createUser": $result = $db->createUser(); break;
	default: $result = "{\"status\":400,\"result\":\"Can't find method: '".getPOST()."'\"}"; break;
}

echo $result;
?>