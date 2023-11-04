<?php
class Person_Membership{
 
    // database connection and table name
    private $conn;
    private $table_name = "person_membership";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
    // object properties
	
public $id_person_membership;
public $id_membership;
public $id_person;
public $start_date;
public $end_date;
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
	
	// read person_membership
	function read(){
		if(isset($_GET["pageNo"])){
		$this->pageNo=$_GET["pageNo"];
		}
		$offset = ($this->pageNo-1) * $this->no_of_records_per_page; 
		// select all query
		$query = "SELECT  i.membership, p.name, t.* FROM ". $this->table_name ." t  join cat_membership i on t.id_membership = i.id_membership  join person p on t.id_person = p.id_person  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
	 
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
		$query = "SELECT  i.membership, p.name, t.* FROM ". $this->table_name ." t  join cat_membership i on t.id_membership = i.id_membership  join person p on t.id_person = p.id_person  WHERE t.id_person_membership LIKE ? OR t.id_membership LIKE ?  OR i.membership LIKE ?  OR t.id_person LIKE ?  OR p.name LIKE ?  OR t.start_date LIKE ?  OR t.end_date LIKE ?  OR t.active LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// bind searchKey
		
$stmt->bindParam(1, $searchKey);
$stmt->bindParam(2, $searchKey);
$stmt->bindParam(3, $searchKey);
$stmt->bindParam(4, $searchKey);
$stmt->bindParam(5, $searchKey);
$stmt->bindParam(6, $searchKey);
$stmt->bindParam(7, $searchKey);
$stmt->bindParam(8, $searchKey);
	 
		// execute query
		$stmt->execute();
	 
		return $stmt;
	}

	function readOne(){
	 
		// query to read single record
		$query = "SELECT  i.membership, p.name, t.* FROM ". $this->table_name ." t  join cat_membership i on t.id_membership = i.id_membership  join person p on t.id_person = p.id_person  WHERE t.id_person_membership = ? LIMIT 0,1";
	 
		// prepare query statement
		$stmt = $this->conn->prepare( $query );
	 
		// bind id
		$stmt->bindParam(1, $this->id_person_membership);
	 
		// execute query
		$stmt->execute();
	 
		// get retrieved row
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
	 
		// set values to object properties
		
$this->id_person_membership = $row['id_person_membership'];
$this->id_membership = $row['id_membership'];
$this->membership = $row['membership'];
$this->id_person = $row['id_person'];
$this->name = $row['name'];
$this->start_date = $row['start_date'];
$this->end_date = $row['end_date'];
$this->active = $row['active'];
	}
	
	// create person_membership
	function create(){
	 
		// query to insert record
		$query ="INSERT INTO ".$this->table_name." SET id_membership=:id_membership,id_person=:id_person,start_date=:start_date,end_date=:end_date,active=:active";

		// prepare query
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->id_membership=htmlspecialchars(strip_tags($this->id_membership));
$this->id_person=htmlspecialchars(strip_tags($this->id_person));
$this->start_date=htmlspecialchars(strip_tags($this->start_date));
$this->end_date=htmlspecialchars(strip_tags($this->end_date));
$this->active=htmlspecialchars(strip_tags($this->active));
	 
		// bind values
		
$stmt->bindParam(":id_membership", $this->id_membership);
$stmt->bindParam(":id_person", $this->id_person);
$stmt->bindParam(":start_date", $this->start_date);
$stmt->bindParam(":end_date", $this->end_date);
$stmt->bindParam(":active", $this->active);
	 
		// execute query
		if($stmt->execute()){
			return  $this->conn->lastInsertId();
		}
	 
		return 0;
		 
	}
	
	
	
	// update the person_membership
	function update(){
	 
		// update query
		$query ="UPDATE ".$this->table_name." SET id_membership=:id_membership,id_person=:id_person,start_date=:start_date,end_date=:end_date,active=:active WHERE id_person_membership = :id_person_membership";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->id_membership=htmlspecialchars(strip_tags($this->id_membership));
$this->id_person=htmlspecialchars(strip_tags($this->id_person));
$this->start_date=htmlspecialchars(strip_tags($this->start_date));
$this->end_date=htmlspecialchars(strip_tags($this->end_date));
$this->active=htmlspecialchars(strip_tags($this->active));
$this->id_person_membership=htmlspecialchars(strip_tags($this->id_person_membership));
	 
		// bind new values
		
$stmt->bindParam(":id_membership", $this->id_membership);
$stmt->bindParam(":id_person", $this->id_person);
$stmt->bindParam(":start_date", $this->start_date);
$stmt->bindParam(":end_date", $this->end_date);
$stmt->bindParam(":active", $this->active);
$stmt->bindParam(":id_person_membership", $this->id_person_membership);
	 
		// execute the query
		if($stmt->execute()){
			return true;
		}
	 
		return false;
	}
	
	// delete the person_membership
	function delete(){
	 
		// delete query
		$query = "DELETE FROM " . $this->table_name . " WHERE id_person_membership = ? ";
	 
		// prepare query
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		$this->id_person_membership=htmlspecialchars(strip_tags($this->id_person_membership));
	 
		// bind id of record to delete
		$stmt->bindParam(1, $this->id_person_membership);
	 
		// execute query
		if($stmt->execute()){
			return true;
		}
	 
		return false;
		 
	}

	
function readByid_membership(){

if (isset($_GET["pageNo"]))
{
$this->pageNo =$_GET["pageNo"]; } 
$offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
$query = "SELECT  i.membership, p.name, t.* FROM ". $this->table_name ." t  join cat_membership i on t.id_membership = i.id_membership  join person p on t.id_person = p.id_person  WHERE t.id_membership = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->id_membership);

$stmt->execute();
return $stmt;
}

function readByid_person(){

if (isset($_GET["pageNo"]))
{
$this->pageNo =$_GET["pageNo"]; } 
$offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
$query = "SELECT  i.membership, p.name, t.* FROM ". $this->table_name ." t  join cat_membership i on t.id_membership = i.id_membership  join person p on t.id_person = p.id_person  WHERE t.id_person = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->id_person);

$stmt->execute();
return $stmt;
}

	//extra function will be generated for one to many relations
}
?>
