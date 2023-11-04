<?php
class Horse_Parents{
 
    // database connection and table name
    private $conn;
    private $table_name = "horse_parents";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
    // object properties
	
public $id_horse_parents;
public $id_horse;
public $mother_data;
public $father_data;
    
 
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
	
	// read horse_parents
	function read(){
		if(isset($_GET["pageNo"])){
		$this->pageNo=$_GET["pageNo"];
		}
		$offset = ($this->pageNo-1) * $this->no_of_records_per_page; 
		// select all query
		$query = "SELECT  d.horse_name, t.* FROM ". $this->table_name ." t  join horse d on t.id_horse = d.id_horse  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
	 
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
		$query = "SELECT  d.horse_name, t.* FROM ". $this->table_name ." t  join horse d on t.id_horse = d.id_horse  WHERE t.id_horse_parents LIKE ? OR t.id_horse LIKE ?  OR d.horse_name LIKE ?  OR t.mother_data LIKE ?  OR t.father_data LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// bind searchKey
		
$stmt->bindParam(1, $searchKey);
$stmt->bindParam(2, $searchKey);
$stmt->bindParam(3, $searchKey);
$stmt->bindParam(4, $searchKey);
$stmt->bindParam(5, $searchKey);
	 
		// execute query
		$stmt->execute();
	 
		return $stmt;
	}

	function readOne(){
	 
		// query to read single record
		$query = "SELECT  d.horse_name, t.* FROM ". $this->table_name ." t  join horse d on t.id_horse = d.id_horse  WHERE t.id_horse_parents = ? LIMIT 0,1";
	 
		// prepare query statement
		$stmt = $this->conn->prepare( $query );
	 
		// bind id
		$stmt->bindParam(1, $this->id_horse_parents);
	 
		// execute query
		$stmt->execute();
	 
		// get retrieved row
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
	 
		// set values to object properties
		
$this->id_horse_parents = $row['id_horse_parents'];
$this->id_horse = $row['id_horse'];
$this->horse_name = $row['horse_name'];
$this->mother_data = $row['mother_data'];
$this->father_data = $row['father_data'];
	}
	
	// create horse_parents
	function create(){
	 
		// query to insert record
		$query ="INSERT INTO ".$this->table_name." SET id_horse=:id_horse,mother_data=:mother_data,father_data=:father_data";

		// prepare query
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->id_horse=htmlspecialchars(strip_tags($this->id_horse));
$this->mother_data=htmlspecialchars(strip_tags($this->mother_data));
$this->father_data=htmlspecialchars(strip_tags($this->father_data));
	 
		// bind values
		
$stmt->bindParam(":id_horse", $this->id_horse);
$stmt->bindParam(":mother_data", $this->mother_data);
$stmt->bindParam(":father_data", $this->father_data);
	 
		// execute query
		if($stmt->execute()){
			return  $this->conn->lastInsertId();
		}
	 
		return 0;
		 
	}
	
	
	
	// update the horse_parents
	function update(){
	 
		// update query
		$query ="UPDATE ".$this->table_name." SET id_horse=:id_horse,mother_data=:mother_data,father_data=:father_data WHERE id_horse_parents = :id_horse_parents";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->id_horse=htmlspecialchars(strip_tags($this->id_horse));
$this->mother_data=htmlspecialchars(strip_tags($this->mother_data));
$this->father_data=htmlspecialchars(strip_tags($this->father_data));
$this->id_horse_parents=htmlspecialchars(strip_tags($this->id_horse_parents));
	 
		// bind new values
		
$stmt->bindParam(":id_horse", $this->id_horse);
$stmt->bindParam(":mother_data", $this->mother_data);
$stmt->bindParam(":father_data", $this->father_data);
$stmt->bindParam(":id_horse_parents", $this->id_horse_parents);
	 
		// execute the query
		if($stmt->execute()){
			return true;
		}
	 
		return false;
	}
	
	// delete the horse_parents
	function delete(){
	 
		// delete query
		$query = "DELETE FROM " . $this->table_name . " WHERE id_horse_parents = ? ";
	 
		// prepare query
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		$this->id_horse_parents=htmlspecialchars(strip_tags($this->id_horse_parents));
	 
		// bind id of record to delete
		$stmt->bindParam(1, $this->id_horse_parents);
	 
		// execute query
		if($stmt->execute()){
			return true;
		}
	 
		return false;
		 
	}

	
function readByid_horse(){

if (isset($_GET["pageNo"]))
{
$this->pageNo =$_GET["pageNo"]; } 
$offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
$query = "SELECT  d.horse_name, t.* FROM ". $this->table_name ." t  join horse d on t.id_horse = d.id_horse  WHERE t.id_horse = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->id_horse);

$stmt->execute();
return $stmt;
}

	//extra function will be generated for one to many relations
}
?>
