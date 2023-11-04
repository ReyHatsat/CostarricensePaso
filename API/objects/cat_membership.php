<?php
class Cat_Membership{

    // database connection and table name
    private $conn;
    private $table_name = "cat_membership";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
    // object properties

public $id_membership;
public $membership;
public $interval_months;
public $price;
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

	// read cat_membership
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
		$query = "SELECT  t.* FROM ". $this->table_name ." t  WHERE t.id_membership LIKE ? OR t.membership LIKE ?  OR t.interval_months LIKE ?  OR t.price LIKE ?  OR t.active LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";

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
		$query = "SELECT  t.* FROM ". $this->table_name ." t  WHERE t.id_membership = ? LIMIT 0,1";

		// prepare query statement
		$stmt = $this->conn->prepare( $query );

		// bind id
		$stmt->bindParam(1, $this->id_membership);

		// execute query
		$stmt->execute();

		// get retrieved row
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		// set values to object properties

$this->id_membership = $row['id_membership'];
$this->membership = $row['membership'];
$this->interval_months = $row['interval_months'];
$this->price = $row['price'];
$this->active = $row['active'];
	}

	// create cat_membership
	function create(){

		// query to insert record
		$query ="INSERT INTO ".$this->table_name." SET membership=:membership,interval_months=:interval_months,price=:price,active=:active";

		// prepare query
		$stmt = $this->conn->prepare($query);

		// sanitize

$this->membership=htmlspecialchars(strip_tags($this->membership));
$this->interval_months=htmlspecialchars(strip_tags($this->interval_months));
$this->price=htmlspecialchars(strip_tags($this->price));
$this->active=htmlspecialchars(strip_tags($this->active));

		// bind values

$stmt->bindParam(":membership", $this->membership);
$stmt->bindParam(":interval_months", $this->interval_months);
$stmt->bindParam(":price", $this->price);
$stmt->bindParam(":active", $this->active);

		// execute query
		if($stmt->execute()){
			return  $this->conn->lastInsertId();
		}

		return 0;

	}



	// update the cat_membership
	function update(){

		// update query
		$query ="UPDATE ".$this->table_name." SET membership=:membership,interval_months=:interval_months,price=:price,active=:active WHERE id_membership = :id_membership";

		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// sanitize

$this->membership=htmlspecialchars(strip_tags($this->membership));
$this->interval_months=htmlspecialchars(strip_tags($this->interval_months));
$this->price=htmlspecialchars(strip_tags($this->price));
$this->active=htmlspecialchars(strip_tags($this->active));
$this->id_membership=htmlspecialchars(strip_tags($this->id_membership));

		// bind new values

$stmt->bindParam(":membership", $this->membership);
$stmt->bindParam(":interval_months", $this->interval_months);
$stmt->bindParam(":price", $this->price);
$stmt->bindParam(":active", $this->active);
$stmt->bindParam(":id_membership", $this->id_membership);

		// execute the query
		if($stmt->execute()){
			return true;
		}

		return false;
	}

	// delete the cat_membership
	function delete(){

		// delete query
		$query = "DELETE FROM " . $this->table_name . " WHERE id_membership = ? ";

		// prepare query
		$stmt = $this->conn->prepare($query);

		// sanitize
		$this->id_membership=htmlspecialchars(strip_tags($this->id_membership));

		// bind id of record to delete
		$stmt->bindParam(1, $this->id_membership);

		// execute query
		if($stmt->execute()){
			return true;
		}

		return false;

	}


	//extra function will be generated for one to many relations
}
?>
