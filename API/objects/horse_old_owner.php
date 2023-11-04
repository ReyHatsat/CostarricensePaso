<?php
class Horse_Old_Owner{
 
    // database connection and table name
    private $conn;
    private $table_name = "horse_old_owner";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
    // object properties
	
public $id_old_owner;
public $id_person;
public $id_horse;
public $from_date;
public $to_date;
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
	
	// read horse_old_owner
	function read(){
		if(isset($_GET["pageNo"])){
		$this->pageNo=$_GET["pageNo"];
		}
		$offset = ($this->pageNo-1) * $this->no_of_records_per_page; 
		// select all query
		$query = "SELECT  v.name, s.horse_name, t.* FROM ". $this->table_name ." t  join person v on t.id_person = v.id_person  join horse s on t.id_horse = s.id_horse  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
	 
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
		$query = "SELECT  v.name, s.horse_name, t.* FROM ". $this->table_name ." t  join person v on t.id_person = v.id_person  join horse s on t.id_horse = s.id_horse  WHERE t.id_old_owner LIKE ? OR t.id_person LIKE ?  OR v.name LIKE ?  OR t.id_horse LIKE ?  OR s.horse_name LIKE ?  OR t.from_date LIKE ?  OR t.to_date LIKE ?  OR t.active LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
	 
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
		$query = "SELECT  v.name, s.horse_name, t.* FROM ". $this->table_name ." t  join person v on t.id_person = v.id_person  join horse s on t.id_horse = s.id_horse  WHERE t.id_old_owner = ? LIMIT 0,1";
	 
		// prepare query statement
		$stmt = $this->conn->prepare( $query );
	 
		// bind id
		$stmt->bindParam(1, $this->id_old_owner);
	 
		// execute query
		$stmt->execute();
	 
		// get retrieved row
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
	 
		// set values to object properties
		
$this->id_old_owner = $row['id_old_owner'];
$this->id_person = $row['id_person'];
$this->name = $row['name'];
$this->id_horse = $row['id_horse'];
$this->horse_name = $row['horse_name'];
$this->from_date = $row['from_date'];
$this->to_date = $row['to_date'];
$this->active = $row['active'];
	}
	
	// create horse_old_owner
	function create(){
	 
		// query to insert record
		$query ="INSERT INTO ".$this->table_name." SET id_person=:id_person,id_horse=:id_horse,from_date=:from_date,to_date=:to_date,active=:active";

		// prepare query
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->id_person=htmlspecialchars(strip_tags($this->id_person));
$this->id_horse=htmlspecialchars(strip_tags($this->id_horse));
$this->from_date=htmlspecialchars(strip_tags($this->from_date));
$this->to_date=htmlspecialchars(strip_tags($this->to_date));
$this->active=htmlspecialchars(strip_tags($this->active));
	 
		// bind values
		
$stmt->bindParam(":id_person", $this->id_person);
$stmt->bindParam(":id_horse", $this->id_horse);
$stmt->bindParam(":from_date", $this->from_date);
$stmt->bindParam(":to_date", $this->to_date);
$stmt->bindParam(":active", $this->active);
	 
		// execute query
		if($stmt->execute()){
			return  $this->conn->lastInsertId();
		}
	 
		return 0;
		 
	}
	
	
	
	// update the horse_old_owner
	function update(){
	 
		// update query
		$query ="UPDATE ".$this->table_name." SET id_person=:id_person,id_horse=:id_horse,from_date=:from_date,to_date=:to_date,active=:active WHERE id_old_owner = :id_old_owner";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		$this->id_person=htmlspecialchars(strip_tags($this->id_person));
		$this->id_horse=htmlspecialchars(strip_tags($this->id_horse));
		$this->from_date=htmlspecialchars(strip_tags($this->from_date));
		$this->to_date=htmlspecialchars(strip_tags($this->to_date));
		$this->active=htmlspecialchars(strip_tags($this->active));
		$this->id_old_owner=htmlspecialchars(strip_tags($this->id_old_owner));
	 
		// bind new values
		$stmt->bindParam(":id_person", $this->id_person);
		$stmt->bindParam(":id_horse", $this->id_horse);
		$stmt->bindParam(":from_date", $this->from_date);
		$stmt->bindParam(":to_date", $this->to_date);
		$stmt->bindParam(":active", $this->active);
		$stmt->bindParam(":id_old_owner", $this->id_old_owner);
	 
		// execute the query
		if($stmt->execute()){
			return true;
		}
	 
		return false;
	}
	
	// delete the horse_old_owner
	function delete(){
	 
		// delete query
		$query = "DELETE FROM " . $this->table_name . " WHERE id_old_owner = ? ";
	 
		// prepare query
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		$this->id_old_owner=htmlspecialchars(strip_tags($this->id_old_owner));
	 
		// bind id of record to delete
		$stmt->bindParam(1, $this->id_old_owner);
	 
		// execute query
		if($stmt->execute()){
			return true;
		}
	 
		return false;
		 
	}

	
function readByid_person(){

if (isset($_GET["pageNo"]))
{
$this->pageNo =$_GET["pageNo"]; } 
$offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
$query = "SELECT  v.name, s.horse_name, t.* FROM ". $this->table_name ." t  join person v on t.id_person = v.id_person  join horse s on t.id_horse = s.id_horse  WHERE t.id_person = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->id_person);

$stmt->execute();
return $stmt;
}

function readByid_horse(){

if (isset($_GET["pageNo"]))
{
$this->pageNo =$_GET["pageNo"]; } 
$offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
$query = "SELECT  v.name, s.horse_name, t.* FROM ". $this->table_name ." t  join person v on t.id_person = v.id_person  join horse s on t.id_horse = s.id_horse  WHERE t.id_horse = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->id_horse);

$stmt->execute();
return $stmt;
}

	









function newTransfer($id, $new){
	 
	// update query
	$query ="UPDATE ".$this->table_name." SET active = 0 WHERE id_horse = :id_horse AND id_old_owner <> :id_old_owner";
 
	// prepare query statement
	$stmt = $this->conn->prepare($query);
 
	// sanitize
	$id = htmlspecialchars(strip_tags($id));
	$new = htmlspecialchars(strip_tags($new));
 
	// bind new values
	$stmt->bindParam(":id_horse", $id);
	$stmt->bindParam(":id_old_owner", $new);

	// execute the query
	if($stmt->execute()){
		return true;
	}
 
	return false;
}



}
?>
