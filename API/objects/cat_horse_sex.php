<?php
class Cat_Horse_Sex{
 
    // database connection and table name
    private $conn;
    private $table_name = "cat_horse_sex";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
    // object properties
	
public $id_horse_sex;
public $sex;
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
	
	// read cat_horse_sex
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
		$query = "SELECT  t.* FROM ". $this->table_name ." t  WHERE t.id_horse_sex LIKE ? OR t.sex LIKE ?  OR t.active LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// bind searchKey
		
$stmt->bindParam(1, $searchKey);
$stmt->bindParam(2, $searchKey);
$stmt->bindParam(3, $searchKey);
	 
		// execute query
		$stmt->execute();
	 
		return $stmt;
	}

	function readOne(){
	 
		// query to read single record
		$query = "SELECT  t.* FROM ". $this->table_name ." t  WHERE t.id_horse_sex = ? LIMIT 0,1";
	 
		// prepare query statement
		$stmt = $this->conn->prepare( $query );
	 
		// bind id
		$stmt->bindParam(1, $this->id_horse_sex);
	 
		// execute query
		$stmt->execute();
	 
		// get retrieved row
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
	 
		// set values to object properties
		
$this->id_horse_sex = $row['id_horse_sex'];
$this->sex = $row['sex'];
$this->active = $row['active'];
	}
	
	// create cat_horse_sex
	function create(){
	 
		// query to insert record
		$query ="INSERT INTO ".$this->table_name." SET sex=:sex,active=:active";

		// prepare query
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->sex=htmlspecialchars(strip_tags($this->sex));
$this->active=htmlspecialchars(strip_tags($this->active));
	 
		// bind values
		
$stmt->bindParam(":sex", $this->sex);
$stmt->bindParam(":active", $this->active);
	 
		// execute query
		if($stmt->execute()){
			return  $this->conn->lastInsertId();
		}
	 
		return 0;
		 
	}
	
	
	
	// update the cat_horse_sex
	function update(){
	 
		// update query
		$query ="UPDATE ".$this->table_name." SET sex=:sex,active=:active WHERE id_horse_sex = :id_horse_sex";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->sex=htmlspecialchars(strip_tags($this->sex));
$this->active=htmlspecialchars(strip_tags($this->active));
$this->id_horse_sex=htmlspecialchars(strip_tags($this->id_horse_sex));
	 
		// bind new values
		
$stmt->bindParam(":sex", $this->sex);
$stmt->bindParam(":active", $this->active);
$stmt->bindParam(":id_horse_sex", $this->id_horse_sex);
	 
		// execute the query
		if($stmt->execute()){
			return true;
		}
	 
		return false;
	}
	
	// delete the cat_horse_sex
	function delete(){
	 
		// delete query
		$query = "DELETE FROM " . $this->table_name . " WHERE id_horse_sex = ? ";
	 
		// prepare query
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		$this->id_horse_sex=htmlspecialchars(strip_tags($this->id_horse_sex));
	 
		// bind id of record to delete
		$stmt->bindParam(1, $this->id_horse_sex);
	 
		// execute query
		if($stmt->execute()){
			return true;
		}
	 
		return false;
		 
	}

	
	//extra function will be generated for one to many relations
}
?>
