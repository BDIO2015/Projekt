<?php
class otherController{
	private $conn;
	public function __construct($conn) 
	{
		$this->conn = $conn;
	}


	public function getDepartments()
	{
         $stmt = $this->conn->prepare("SELECT * from `wydzialy`");
         $result = $stmt->execute();
         $error = $stmt->error;
         $departments = $stmt->get_result();
         $stmt->close();

         $data = array();
         while($department = $departments->fetch_assoc()) 
         	$data[] = $department;
		 
         if ($result) return "{\"status\":200,\"result\":{\"wydzialy\":".json_encode($data)."}}";
         else return "{\"status\": 400,\"result\":\"".$error."\"}";
	}

	public function getSpecializations()
	{
		$stmt = $this->conn->prepare("SELECT * from `specjalizacje`");
         $result = $stmt->execute();
         $error = $stmt->error;
         $specializations = $stmt->get_result();
         $stmt->close();

         $data = array();
         while($specialization = $specializations->fetch_assoc()) 
         	$data[] = $specialization;
		 
         if ($result) return "{\"status\":200,\"result\":{\"specjalizacje\":".json_encode($data)."}}";
         else return "{\"status\": 400,\"result\":\"".$error."\"}";
	}

	public function getCathedral()
	{
		$stmt = $this->conn->prepare("SELECT * from `katedry`");
         $result = $stmt->execute();
         $error = $stmt->error;
         $csthedrals = $stmt->get_result();
         $stmt->close();

         $data = array();
         while($csthedral = $csthedrals->fetch_assoc()) 
         	$data[] = $csthedral;
		 
         if ($result) return "{\"status\":200,\"result\":{\"katedry\":".json_encode($data)."}}";
         else return "{\"status\": 400,\"result\":\"".$error."\"}";
	}
	
}
?>