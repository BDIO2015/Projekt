<?php
class threadController{
	private $conn;
	public function __construct($conn) 
	{
		$this->conn = $conn;
	}

	public function addThread()
	{
		if(isset($_POST['text']) && isset($_POST['id_projekt']))
		{
			$stmt = $this->conn->prepare("SELECT id_projekt FROM projekty WHERE id_projekt = ?");
                  $stmt->bind_param("s", $_POST['id_projekt']);
                  $result = $stmt->execute();
                  $error = $stmt->error;
                  $project = $stmt->get_result()->fetch_assoc();
                  $stmt->close();
      
                  if ($project) 
                  {
		    		$stmt = $this->conn->prepare("INSERT INTO `watek` (`id_watek`, `id_projekt`, `text`, `id_nadrzedny`, `id_zalacznik`, `date`) 
                                               	  VALUES (NULL,?, ?, NULL, NULL, NULL)");
                  	$stmt->bind_param("ss", $_POST['id_projekt'], $_POST['text']);
                  	$result = $stmt->execute();
                  	$error = $stmt->error;
                  	$stmt->close();
                  	if ($result) return "{\"status\":201,\"result\":\"Thread created\"}";
                  	else return "{\"status\": 400,\"result\":\"".$error."\"}";
                  } else return "{\"status\": 400,\"result\":\"Project don't exist \"}";
			
		} else return "{\"status\": 400, \"result\":\"Bad params\"}";
	}

	public function deleteThread()
	{
		if(isset($_POST['id_watek']))
		{
			$stmt = $this->conn->prepare( "DELETE FROM `watek` WHERE `id_nadrzedny` = ?");
                  $stmt->bind_param("s", $_POST['id_watek']);
                  $result = $stmt->execute();
                  $error = $stmt->error;
                  $stmt->close();

			$stmt = $this->conn->prepare( "DELETE FROM `watek` WHERE `id_watek` = ?");
                  $stmt->bind_param("s", $_POST['id_watek']);
                  $result = $stmt->execute();
                  $error = $stmt->error;
                  $stmt->close();

            if ($result) return "{\"status\":200,\"result\":\"Thread deleted\"}";
            else return "{\"status\": 400,\"result\":\"".$error."\"}";
		} else return "{\"status\": 400, \"result\":\"Bad params\"}";
	}

      public function deleteThreads()
      {
            if(isset($_POST['id_projekt']))
            {
                  if(!(json_decode($GLOBALS['db']->getProjectController()->isProjectExist())->status == 200)) return "{\"status\": 400, \"result\":\"Project with id ".$_POST['id_projekt']." doesn't exist \"}";

                  $stmt = $this->conn->prepare("SELECT id_watek FROM `watek` WHERE `id_projekt` = ? AND `id_nadrzedny` IS NULL");
                  $stmt->bind_param("s", $_POST['id_projekt']);
                  $result = $stmt->execute();
                  $error = $stmt->error;
                  $threads = $stmt->get_result();
                  $data = array();
                  $stmt->close();
                  while($thread = $threads->fetch_assoc()) 
                  {
                        $_POST['id_watek'] = $thread['id_watek'];
                        $this->deleteThread();
                  }
                  return "{\"status\":200,\"result\":\"Threads has been deleted\"}";
            } else return "{\"status\": 400, \"result\":\"Bad params\"}";
      }

	public function addComment()
	{
		if(isset($_POST['id_nadrzedny']) && isset($_POST['id_projekt']) && isset($_POST['text']))
		{
			$stmt = $this->conn->prepare("INSERT INTO `watek` (`id_watek`, `id_projekt`, `text`, `id_nadrzedny`, `id_zalacznik`, `date`)
										  VALUES (NULL,?, ?, ?, NULL, NULL)");
                  $stmt->bind_param("sss", $_POST['id_projekt'], $_POST['text'], $_POST['id_nadrzedny']);
                  $result = $stmt->execute();
                  $error = $stmt->error;
                  $stmt->close();
                  if ($result) return "{\"status\":201,\"result\":\"Comment added\"}";
                  else return "{\"status\": 400,\"result\":\"".$error."\"}";
		} else return "{\"status\": 400, \"result\":\"Bad params\"}";
	}

	public function deleteComment()
	{
		if(isset($_POST['id_watek']))
		{
			$stmt = $this->conn->prepare( "DELETE FROM `watek` WHERE `id_watek` = ? and id_nadrzedny IS NOT NULL");
                  $stmt->bind_param("s", $_POST['id_watek']);
                  $result = $stmt->execute();
                  $error = $stmt->error;
                  $stmt->close();

                  if ($result) return "{\"status\":200,\"result\":\"Comment deleted\"}";
                  else return "{\"status\": 400,\"result\":\"".$error."\"}";
		} else return "{\"status\": 400, \"result\":\"Bad params\"}";
	}

	public function getThread()
	{
		if(isset($_POST['id_watek']))
		{
			$stmt = $this->conn->prepare( "SELECT * FROM `watek` WHERE `id_watek` = ? AND `id_nadrzedny` IS NULL" );
                  $stmt->bind_param("s", $_POST['id_watek']);
                  $result = $stmt->execute();
                  $error = $stmt->error;
                  $thread = $stmt->get_result()->fetch_assoc();
                  $stmt->close();

                  if ($thread) 
                  {
                  	$stmt = $this->conn->prepare( "SELECT * FROM `watek` WHERE `id_nadrzedny` = ? ORDER BY `date` ASC" );
                  	$stmt->bind_param("s", $_POST['id_watek']);
                  	$result = $stmt->execute();
                  	$error = $stmt->error;
                  	$comments = $stmt->get_result();
      				$data = array();
                  	$stmt->close();
                  	while($comment = $comments->fetch_assoc()) $data[] = $comment;
      				
                  	$thread['komentarze'] = $data;
                  	return "{\"status\":200,\"result\":".json_encode($thread)."}";
                  }
                  else return "{\"status\": 400,\"result\":\"Thread don't exist\"}";
		} else return "{\"status\": 400, \"result\":\"Bad params\"}";
	}

	public function getThreads()
	{
		if(isset($_POST['id_projekt']))
		{
                  if(!(json_decode($GLOBALS['db']->getProjectController()->isProjectExist())->status == 200)) return "{\"status\": 400, \"result\":\"Project with id ".$_POST['id_projekt']." doesn't exist \"}";

            	$stmt = $this->conn->prepare("SELECT id_watek FROM `watek` WHERE `id_projekt` = ? AND `id_nadrzedny` IS NULL");
            	$stmt->bind_param("s", $_POST['id_projekt']);
            	$result = $stmt->execute();
            	$error = $stmt->error;
            	$comments = $stmt->get_result();
			$data = array();
            	$stmt->close();
            	while($comment = $comments->fetch_assoc()) 
            	{
            		$_POST['id_watek'] = $comment['id_watek'];
            		$thread = json_decode($this->getThread());
            		$data[] = $thread->result;
            	}
				
            	$threads['watki'] = $data;
            	return "{\"status\":200,\"result\":".json_encode($threads)."}";
		} else return "{\"status\": 400, \"result\":\"Bad params\"}";
	}
	
	public function addAttachment()
	{
		if(isset($_POST['sciezka']) && isset($_POST['id_watek']))
		{
			$stmt = $this->conn->prepare("INSERT INTO `zalaczniki` (`id_zalacznik`, `id_watek`, `sciezka`, `data`)
										  VALUES (NULL, ?, ?, NULL)");
			$stmt->bind_param("is", $_POST['id_watek'], $_POST['sciezka']);
			$result = $stmt->execute();
			$error = $stmt->error;
			$lastID = $this->conn->insert_id;
			$stmt->close();
			if ($result) return "{\"status\":200,\"result\":\"Attachment added\",\"sciezka\":\"".$lastID."\"}";
			else return "{\"status\": 400,\"result\":\"".$error."\"}";
		}
		else return "{\"status\": 400, \"result\":\"Bad params\"}";
	}
	
	public function deleteAttachment()
	{
		if(isset($_POST['id_zalacznik']))
		{
			$stmt = $this->conn->prepare( "DELETE FROM `zalaczniki` WHERE `id_zalacznik` = ?");
                  $stmt->bind_param("i", $_POST['id_zalacznik']);
                  $result = $stmt->execute();
                  $error = $stmt->error;
                  $stmt->close();

                  if ($result) return "{\"status\":200,\"result\":\"Attachment deleted\"}";
                  else return "{\"status\": 400,\"result\":\"".$error."\"}";
		} else return "{\"status\": 400, \"result\":\"Bad params\"}";
	}
}
