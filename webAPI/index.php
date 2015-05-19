<?php

include_once __DIR__.'/include/DbHandler.php';
$method = substr($_SERVER['REQUEST_URI'], strlen(substr($_SERVER['SCRIPT_NAME'], 0, strlen($_SERVER['SCRIPT_NAME']) - 9)));
$db = new DbHandler();
$GLOBALS['db'] = $db;
$result = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') 
switch($method)
{
	default:
	case "api": 
		$path = "public/index.php";
		$file = fopen($path, "r");
		$html = fread($file, filesize($path));

		$path = "public/user.html";
		$file = fopen($path, "r");
		$user = fread($file, filesize($path));

		$path = "public/project.html";
		$file = fopen($path, "r");
		$project = fread($file, filesize($path));

		$path = "public/thread.html";
		$file = fopen($path, "r");
		$thread = fread($file, filesize($path));

		$path = "public/other.html";
		$file = fopen($path, "r");
		$other = fread($file, filesize($path));

		$path = "public/message.html";
		$file = fopen($path, "r");
		$message = fread($file, filesize($path));


    	$search = array(":user:", ":project:", ":thread:", ":other:", ":message:"); 
    	$replace = array($user, $project, $thread, $other, $message); 
    	$html = str_replace($search, $replace, $html);

		echo $html;
		break;
}else
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
switch($method)
{
	//userController
	case "login": $result = $db->getUserController()->login(); break;
	case "createUser": $result = $db->getUserController()->createUser(); break;
	case "isUserActive": $result = $db->getUserController()->isUserActive(); break;
	case "isUserExist": $result = $db->getUserController()->isUserExist(); break;
	case "updateUser": $result = $db->getUserController()->updateUser(); break;
	case "changePass": $result = $db->getUserController()->changePass(); break;
	case "changeActivity": $result = $db->getUserController()->changeActivity(); break;
	case "showAllUsers": $result = $db->getUserController()->showAllUsers(); break;
	case "resetPass": $result = $db->getUserController()->resetPass(); break;

	//projektController
	case "isProjectExist": $result = $db->getProjectController()->isProjectExist(); break;
	case "createProject": $result = $db->getProjectController()->createProject(); break;
	case "deleteProject": $result = $db->getProjectController()->deleteProject(); break;
	case "addUser": $result = $db->getProjectController()->addUser(); break;
	case "removeUser": $result = $db->getProjectController()->removeUser(); break;
	case "updateProject": $result = $db->getProjectController()->updateProject(); break;
	case "getProject": $result = $db->getProjectController()->isProjectExist(); break;
	case "getProjects": $result = $db->getProjectController()->getProjects(); break;


	case "archiveProject": $result = $db->getProjectController()->archiveProject(); break;
	case "getFromArchiv": $result = $db->getProjectController()->getFromArchiv(); break;

	//threadController
	case "addThread": $result = $db->getThreadController()->addThread(); break;
	case "deleteThread": $result = $db->getThreadController()->deleteThread(); break;
	case "deleteThreads": $result = $db->getThreadController()->deleteThreads(); break;
	case "addComment": $result = $db->getThreadController()->addComment(); break;
	case "deleteComment": $result = $db->getThreadController()->deleteComment(); break;
	case "getThread": $result = $db->getThreadController()->getThread(); break;
	case "getThreads": $result = $db->getThreadController()->getThreads(); break;

	//otherController
	case "getDepartments": $result = $db->getOtherController()->getDepartments(); break;
	case "getSpecializations": $result = $db->getOtherController()->getSpecializations(); break;
	case "getCathedral": $result = $db->getOtherController()->getCathedral(); break;

	//messagesController
	case "getReceivedMessages": $result = $db->getMessagesController()->getReceivedMessages(); break;
	case "getSendedMessages": $result = $db->getMessagesController()->getSendedMessages(); break;
	case "sendMessage": $result = $db->getMessagesController()->sendMessage(); break;

	default: $result = "{\"status\":400,\"result\":\"Can't find method: '".$method."'\"}"; break;
}

echo $result;
?>
