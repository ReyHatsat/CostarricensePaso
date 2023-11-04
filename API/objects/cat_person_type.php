<?php
class Cat_Person_Type{
 
    // database connection and table name
    private $conn;
    private $table_name = "cat_person_type";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
    // object properties
	
public $id_person_type;
public $type;
public $data;
public $active;
    
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

	function total_record_count() {
	$query = "select count(1) as total from ". $this->table_name ."";
	$stmt = $this->conn->prepare($query);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	return $row['total'];
	}
	
	// read cat_person_type
	function read(){
		if(isset($_GET["pageNo"])){
		$this->pageNo=$_GET["pageNo"];
		}
		$offset = ($this->pageNo-1) * $this->no_of_records_per_page; 
		// select all query
		$query = "SELECT  t.* FROM ". $this->table_name ." t  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);
	 
		// execute query
		$stmt->execute();
	 
		return $stmt;
	}
	//Search table
	function search($searchKey){
		if(isset($_GET["pageNo"])){
		$this->pageNo=$_GET["pageNo"];
		}
		$offset = ($this->pageNo-1) * $this->no_of_records_per_page; 

		// select all query
		$query = "SELECT  t.* FROM ". $this->table_name ." t  WHERE t.id_person_type LIKE ? OR t.type LIKE ?  OR t.data LIKE ?  OR t.active LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// bind searchKey
		
$stmt->bindParam(1, $searchKey);
$stmt->bindParam(2, $searchKey);
$stmt->bindParam(3, $searchKey);
$stmt->bindParam(4, $searchKey);
	 
		// execute query
		$stmt->execute();
	 
		return $stmt;
	}

	function readOne(){
	 
		// query to read single record
		$query = "SELECT  t.* FROM ". $this->table_name ." t  WHERE t.id_person_type = ? LIMIT 0,1";
	 
		// prepare query statement
		$stmt = $this->conn->prepare( $query );
	 
		// bind id
		$stmt->bindParam(1, $this->id_person_type);
	 
		// execute query
		$stmt->execute();
	 
		// get retrieved row
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
	 
		// set values to object properties
		
$this->id_person_type = $row['id_person_type'];
$this->type = $row['type'];
$this->data = $row['data'];
$this->active = $row['active'];
	}
	
	// create cat_person_type
	function create(){
	 
		// query to insert record
		$query ="INSERT INTO ".$this->table_name." SET type=:type,data=:data,active=:active";

		// prepare query
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->type=htmlspecialchars(strip_tags($this->type));
$this->data=htmlspecialchars(strip_tags($this->data));
$this->active=htmlspecialchars(strip_tags($this->active));
	 
		// bind values
		
$stmt->bindParam(":type", $this->type);
$stmt->bindParam(":data", $this->data);
$stmt->bindParam(":active", $this->active);
	 
		// execute query
		if($stmt->execute()){
			return  $this->conn->lastInsertId();
		}
	 
		return 0;
		 
	}
	
	
	
	// update the cat_person_type
	function update(){
	 
		// update query
		$query ="UPDATE ".$this->table_name." SET type=:type,data=:data,active=:active WHERE id_person_type = :id_person_type";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->type=htmlspecialchars(strip_tags($this->type));
$this->data=htmlspecialchars(strip_tags($this->data));
$this->active=htmlspecialchars(strip_tags($this->active));
$this->id_person_type=htmlspecialchars(strip_tags($this->id_person_type));
	 
		// bind new values
		
$stmt->bindParam(":type", $this->type);
$stmt->bindParam(":data", $this->data);
$stmt->bindParam(":active", $this->active);
$stmt->bindParam(":id_person_type", $this->id_person_type);
	 
		// execute the query
		if($stmt->execute()){
			return true;
		}
	 
		return false;
	}
	
	// delete the cat_person_type
	function delete(){
	 
		// delete query
		$query = "DELETE FROM " . $this->table_name . " WHERE id_person_type = ? ";
	 
		// prepare query
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		$this->id_person_type=htmlspecialchars(strip_tags($this->id_person_type));
	 
		// bind id of record to delete
		$stmt->bindParam(1, $this->id_person_type);
	 
		// execute query
		if($stmt->execute()){
			return true;
		}
	 
		return false;
		 
	}

	
	//extra function will be generated for one to many relations
}
?>
