<?php
class Horse{

    // database connection and table name
    private $conn;
    private $table_name = "horse";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
    // object properties

public $id_horse;
public $id_current_owner;
public $id_encaste;
public $id_horse_sex;
public $horse_name;
public $birth_date;
public $first_owner_data;
public $breeder_data;
public $other_information;
public $observations;
public $microchip_no;
public $inscription_date;
public $inspector_reference;
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

	// read horse
	function read(){
		if(isset($_GET["pageNo"])){
		$this->pageNo=$_GET["pageNo"];
		}
		$offset = ($this->pageNo-1) * $this->no_of_records_per_page;
		// select all query
		$query = "SELECT  r.name, q.encaste, x.sex, t.* FROM ". $this->table_name ." t  join person r on t.id_current_owner = r.id_person  join cat_encaste q on t.id_encaste = q.id_encaste  join cat_horse_sex x on t.id_horse_sex = x.id_horse_sex  LIMIT ".$offset." , ". $this->no_of_records_per_page."";

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
		$query = "SELECT  r.name, q.encaste, x.sex, t.* FROM ". $this->table_name ." t  join person r on t.id_current_owner = r.id_person  join cat_encaste q on t.id_encaste = q.id_encaste  join cat_horse_sex x on t.id_horse_sex = x.id_horse_sex  WHERE t.id_horse LIKE ? OR t.id_current_owner LIKE ?  OR r.name LIKE ?  OR t.id_encaste LIKE ?  OR q.encaste LIKE ?  OR t.id_horse_sex LIKE ?  OR x.sex LIKE ?  OR t.horse_name LIKE ?  OR t.birth_date LIKE ?  OR t.first_owner_data LIKE ?  OR t.breeder_data LIKE ?  OR t.other_information LIKE ?  OR t.observations LIKE ?  OR t.microchip_no LIKE ?  OR t.inscription_date LIKE ?  OR t.inspector_reference LIKE ?  OR t.active LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";

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
$stmt->bindParam(9, $searchKey);
$stmt->bindParam(10, $searchKey);
$stmt->bindParam(11, $searchKey);
$stmt->bindParam(12, $searchKey);
$stmt->bindParam(13, $searchKey);
$stmt->bindParam(14, $searchKey);
$stmt->bindParam(15, $searchKey);
$stmt->bindParam(16, $searchKey);
$stmt->bindParam(17, $searchKey);

		// execute query
		$stmt->execute();

		return $stmt;
	}

	function readOne(){

		// query to read single record
		$query = "SELECT  r.name, q.encaste, x.sex, t.* FROM ". $this->table_name ." t  join person r on t.id_current_owner = r.id_person  join cat_encaste q on t.id_encaste = q.id_encaste  join cat_horse_sex x on t.id_horse_sex = x.id_horse_sex  WHERE t.id_horse = ? LIMIT 0,1";

		// prepare query statement
		$stmt = $this->conn->prepare( $query );

		// bind id
		$stmt->bindParam(1, $this->id_horse);

		// execute query
		$stmt->execute();

		// get retrieved row
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		// set values to object properties

$this->id_horse = $row['id_horse'];
$this->id_current_owner = $row['id_current_owner'];
$this->name = $row['name'];
$this->id_encaste = $row['id_encaste'];
$this->encaste = $row['encaste'];
$this->id_horse_sex = $row['id_horse_sex'];
$this->sex = $row['sex'];
$this->horse_name = $row['horse_name'];
$this->birth_date = $row['birth_date'];
$this->first_owner_data = $row['first_owner_data'];
$this->breeder_data = $row['breeder_data'];
$this->other_information = $row['other_information'];
$this->observations = $row['observations'];
$this->microchip_no = $row['microchip_no'];
$this->inscription_date = $row['inscription_date'];
$this->inspector_reference = $row['inspector_reference'];
$this->active = $row['active'];
	}

	// create horse
	function create(){

		// query to insert record
		$query ="INSERT INTO ".$this->table_name." SET id_current_owner=:id_current_owner,id_encaste=:id_encaste,id_horse_sex=:id_horse_sex,horse_name=:horse_name,birth_date=:birth_date,first_owner_data=:first_owner_data,breeder_data=:breeder_data,other_information=:other_information,observations=:observations,microchip_no=:microchip_no,inscription_date=:inscription_date,inspector_reference=:inspector_reference,active=:active";

		// prepare query
		$stmt = $this->conn->prepare($query);

		// sanitize

$this->id_current_owner=htmlspecialchars(strip_tags($this->id_current_owner));
$this->id_encaste=htmlspecialchars(strip_tags($this->id_encaste));
$this->id_horse_sex=htmlspecialchars(strip_tags($this->id_horse_sex));
$this->horse_name=htmlspecialchars(strip_tags($this->horse_name));
$this->birth_date=htmlspecialchars(strip_tags($this->birth_date));
$this->first_owner_data=htmlspecialchars(strip_tags($this->first_owner_data));
$this->breeder_data=htmlspecialchars(strip_tags($this->breeder_data));
$this->other_information=htmlspecialchars(strip_tags($this->other_information));
$this->observations=htmlspecialchars(strip_tags($this->observations));
$this->microchip_no=htmlspecialchars(strip_tags($this->microchip_no));
$this->inscription_date=htmlspecialchars(strip_tags($this->inscription_date));
$this->inspector_reference=htmlspecialchars(strip_tags($this->inspector_reference));
$this->active=htmlspecialchars(strip_tags($this->active));

		// bind values

$stmt->bindParam(":id_current_owner", $this->id_current_owner);
$stmt->bindParam(":id_encaste", $this->id_encaste);
$stmt->bindParam(":id_horse_sex", $this->id_horse_sex);
$stmt->bindParam(":horse_name", $this->horse_name);
$stmt->bindParam(":birth_date", $this->birth_date);
$stmt->bindParam(":first_owner_data", $this->first_owner_data);
$stmt->bindParam(":breeder_data", $this->breeder_data);
$stmt->bindParam(":other_information", $this->other_information);
$stmt->bindParam(":observations", $this->observations);
$stmt->bindParam(":microchip_no", $this->microchip_no);
$stmt->bindParam(":inscription_date", $this->inscription_date);
$stmt->bindParam(":inspector_reference", $this->inspector_reference);
$stmt->bindParam(":active", $this->active);

		// execute query
		if($stmt->execute()){
			return  $this->conn->lastInsertId();
		}

		return 0;

	}



	// update the horse
	function update(){

		// update query
		$query ="UPDATE ".$this->table_name." SET id_current_owner=:id_current_owner,id_encaste=:id_encaste,id_horse_sex=:id_horse_sex,horse_name=:horse_name,birth_date=:birth_date,first_owner_data=:first_owner_data,breeder_data=:breeder_data,other_information=:other_information,observations=:observations,microchip_no=:microchip_no,inscription_date=:inscription_date,inspector_reference=:inspector_reference,active=:active WHERE id_horse = :id_horse";

		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// sanitize

$this->id_current_owner=htmlspecialchars(strip_tags($this->id_current_owner));
$this->id_encaste=htmlspecialchars(strip_tags($this->id_encaste));
$this->id_horse_sex=htmlspecialchars(strip_tags($this->id_horse_sex));
$this->horse_name=htmlspecialchars(strip_tags($this->horse_name));
$this->birth_date=htmlspecialchars(strip_tags($this->birth_date));
$this->first_owner_data=htmlspecialchars(strip_tags($this->first_owner_data));
$this->breeder_data=htmlspecialchars(strip_tags($this->breeder_data));
$this->other_information=htmlspecialchars(strip_tags($this->other_information));
$this->observations=htmlspecialchars(strip_tags($this->observations));
$this->microchip_no=htmlspecialchars(strip_tags($this->microchip_no));
$this->inscription_date=htmlspecialchars(strip_tags($this->inscription_date));
$this->inspector_reference=htmlspecialchars(strip_tags($this->inspector_reference));
$this->active=htmlspecialchars(strip_tags($this->active));
$this->id_horse=htmlspecialchars(strip_tags($this->id_horse));

		// bind new values

$stmt->bindParam(":id_current_owner", $this->id_current_owner);
$stmt->bindParam(":id_encaste", $this->id_encaste);
$stmt->bindParam(":id_horse_sex", $this->id_horse_sex);
$stmt->bindParam(":horse_name", $this->horse_name);
$stmt->bindParam(":birth_date", $this->birth_date);
$stmt->bindParam(":first_owner_data", $this->first_owner_data);
$stmt->bindParam(":breeder_data", $this->breeder_data);
$stmt->bindParam(":other_information", $this->other_information);
$stmt->bindParam(":observations", $this->observations);
$stmt->bindParam(":microchip_no", $this->microchip_no);
$stmt->bindParam(":inscription_date", $this->inscription_date);
$stmt->bindParam(":inspector_reference", $this->inspector_reference);
$stmt->bindParam(":active", $this->active);
$stmt->bindParam(":id_horse", $this->id_horse);

		// execute the query
		if($stmt->execute()){
			return true;
		}

		return false;
	}

	// delete the horse
	function delete(){

		// delete query
		$query = "DELETE FROM " . $this->table_name . " WHERE id_horse = ? ";

		// prepare query
		$stmt = $this->conn->prepare($query);

		// sanitize
		$this->id_horse=htmlspecialchars(strip_tags($this->id_horse));

		// bind id of record to delete
		$stmt->bindParam(1, $this->id_horse);

		// execute query
		if($stmt->execute()){
			return true;
		}

		return false;

	}


function readByid_current_owner(){

if (isset($_GET["pageNo"]))
{
$this->pageNo =$_GET["pageNo"]; }
$offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
$query = "SELECT  r.name, q.encaste, x.sex, t.* FROM ". $this->table_name ." t  join person r on t.id_current_owner = r.id_person  join cat_encaste q on t.id_encaste = q.id_encaste  join cat_horse_sex x on t.id_horse_sex = x.id_horse_sex  WHERE t.id_current_owner = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->id_current_owner);

$stmt->execute();
return $stmt;
}

function readByid_encaste(){

if (isset($_GET["pageNo"]))
{
$this->pageNo =$_GET["pageNo"]; }
$offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
$query = "SELECT  r.name, q.encaste, x.sex, t.* FROM ". $this->table_name ." t  join person r on t.id_current_owner = r.id_person  join cat_encaste q on t.id_encaste = q.id_encaste  join cat_horse_sex x on t.id_horse_sex = x.id_horse_sex  WHERE t.id_encaste = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->id_encaste);

$stmt->execute();
return $stmt;
}

function readByid_horse_sex(){

if (isset($_GET["pageNo"]))
{
$this->pageNo =$_GET["pageNo"]; }
$offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
$query = "SELECT  r.name, q.encaste, x.sex, t.* FROM ". $this->table_name ." t  join person r on t.id_current_owner = r.id_person  join cat_encaste q on t.id_encaste = q.id_encaste  join cat_horse_sex x on t.id_horse_sex = x.id_horse_sex  WHERE t.id_horse_sex = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->id_horse_sex);

$stmt->execute();
return $stmt;
}

	


	// update the horse
	function updateOwner(){

		// update query
		$query ="UPDATE ".$this->table_name." SET id_current_owner=:id_current_owner WHERE id_horse = :id_horse";

		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// sanitize
		$this->id_current_owner=htmlspecialchars(strip_tags($this->id_current_owner));
		$this->id_horse=htmlspecialchars(strip_tags($this->id_horse));

		// bind new values
		$stmt->bindParam(":id_current_owner", $this->id_current_owner);
		$stmt->bindParam(":id_horse", $this->id_horse);

		// execute the query
		if($stmt->execute()){
			return true;
		}

		return false;
	}


}
?>
