<?php
class Person{

    // database connection and table name
    private $conn;
    private $table_name = "person";
  	public $pageNo = 1;
  	public  $no_of_records_per_page=30;
    // object properties

    public $id_person;
    public $id_person_type;
    public $name;
    public $lastname;
    public $main_email;
    public $login_salt;
    public $login_password;
    public $member;
    public $active;


    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }



    function gensalt(){
      $salt = $this->randval().$this->uniqidReal();
      $salt = str_shuffle($salt);
      return hash('sha1', $salt);
  	}


    /*
    hash_password( lospatitos )

    salt -> gensalt
    salt = 12345Tgas1gh5#$57676 -> shuffle
    salt = 756a1h5#$g21T674g35s -> sha1
    salt = 18760e47cb345d3a980fe70446cf6c38f8f8a1c3 -> sha512

    pwd = -> has_pwd
    hp = 60a148ea0b500dffe315ed5c8994b5a7e682d9ce9b14196e8ec82936651ebe3cc41e29883a1b05542d6285b327f03d848aafe312ba8ac5f182960518136eb3b2
    hs = a7c3690716c5b2de38d82088e4e7fe8e43a4f2bd03c2e98fb8f954f459c01f1839594762a3e0d99c0dbb4f38a2423f07df7628d514a27ec7615ba738bc22b792

    hp = has_pwd(hs + hp)
    5238ffef26d3719bb175f9c163e640bb8869ec45807312f834556a74ba52ea38aec3fad9cc34a2d801e618e700c358fbb4d226e474c314812132e27204671700
    */

    function HashPassword($p, $s){
      $hp = $this->recurrent_hash($p);
      $hs = $this->recurrent_hash($s);
      return $this->recurrent_hash($hs.$hp);
    }



    //returns random value between min - max and fills the empty chars with 0
    function randval($min = 0, $max = 99999999,  $length = 8, $fill = "0"){
      $number = rand($min, $max);
      $number = str_pad($number, $length, $fill, STR_PAD_LEFT);
      return $number;
    }


    //iterates n times hashing every time the result of the previous hash of the string.
    private function recurrent_hash($str, $n = 100, $hash = 'sha512'){
      for ($i=0; $i < $n; $i++) { $str = hash($hash, $str); }
      return $str;
    }


    //generates a random string of characters
    function uniqidReal($lenght = 8) {
      // uniqid gives 13 chars, but you could adjust it to your needs.
      if (function_exists("random_bytes")) {
          $bytes = random_bytes(ceil($lenght / 2));
      } elseif (function_exists("openssl_random_pseudo_bytes")) {
          $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
      } else {
          throw new Exception("no cryptographically secure random function available");
      }
      return substr(bin2hex($bytes), 0, $lenght);
    }




  	function total_record_count() {
    	$query = "select count(1) as total from ". $this->table_name ."";
    	$stmt = $this->conn->prepare($query);
    	$stmt->execute();
    	$row = $stmt->fetch(PDO::FETCH_ASSOC);
    	return $row['total'];
  	}

  	// read person
  	function read(){
  		if(isset($_GET["pageNo"])){
  		$this->pageNo=$_GET["pageNo"];
  		}
  		$offset = ($this->pageNo-1) * $this->no_of_records_per_page;
  		// select all query
  		$query = "SELECT  g.type, t.* FROM ". $this->table_name ." t  join cat_person_type g on t.id_person_type = g.id_person_type  LIMIT ".$offset." , ". $this->no_of_records_per_page."";

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
  		$query = "SELECT  g.type, t.* FROM ". $this->table_name ." t  join cat_person_type g on t.id_person_type = g.id_person_type  WHERE t.id_person LIKE ? OR t.id_person_type LIKE ?  OR g.type LIKE ?  OR t.name LIKE ?  OR t.lastname LIKE ?  OR t.main_email LIKE ?  OR t.login_salt LIKE ?  OR t.login_password LIKE ?  OR t.member LIKE ?  OR t.active LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";

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

  		// execute query
  		$stmt->execute();

  		return $stmt;
  	}

	function readOne(){

		// query to read single record
		$query = "SELECT  g.type, t.* FROM ". $this->table_name ." t  join cat_person_type g on t.id_person_type = g.id_person_type  WHERE t.id_person = ? LIMIT 0,1";

		// prepare query statement
		$stmt = $this->conn->prepare( $query );

		// bind id
		$stmt->bindParam(1, $this->id_person);

		// execute query
		$stmt->execute();

		// get retrieved row
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		// set values to object properties

		$this->id_person = $row['id_person'];
		$this->id_person_type = $row['id_person_type'];
		$this->type = $row['type'];
		$this->name = $row['name'];
		$this->lastname = $row['lastname'];
		$this->main_email = $row['main_email'];
		$this->login_salt = $row['login_salt'];
		$this->login_password = $row['login_password'];
		$this->member = $row['member'];
		$this->active = $row['active'];
	}






	function attemptLogin(){

		// query to read single record
		$query = "SELECT  g.type, t.*
		FROM ". $this->table_name ." t
		join cat_person_type g on t.id_person_type = g.id_person_type
		WHERE t.main_email = ?
		AND t.login_password = ?
		AND t.login_salt = ?
		LIMIT 0,1";

		// prepare query statement
		$stmt = $this->conn->prepare( $query );

		// bind id
		$stmt->bindParam(1, $this->id_person);
		$stmt->bindParam(2, $this->login_password);
		$stmt->bindParam(3, $this->login_salt);

		// execute query
		$stmt->execute();

		// get retrieved row
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		// set values to object properties
		$this->id_person = $row['id_person'];
		$this->id_person_type = $row['id_person_type'];
		$this->type = $row['type'];
		$this->name = $row['name'];
		$this->lastname = $row['lastname'];
		$this->main_email = $row['main_email'];
		$this->login_salt = $row['login_salt'];
		$this->login_password = $row['login_password'];
		$this->member = $row['member'];
		$this->active = $row['active'];

		return $row;
	}





	function readEmail(){

		// query to read single record
		$query = "SELECT  g.type, t.* FROM ". $this->table_name ." t  join cat_person_type g on t.id_person_type = g.id_person_type  WHERE t.main_email = ? LIMIT 0,1";

		// prepare query statement
		$stmt = $this->conn->prepare( $query );

		// bind id
		$stmt->bindParam(1, $this->main_email);

		// execute query
		$stmt->execute();

		// get retrieved row


		// set values to object properties

	   if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
       $this->id_person = $row['id_person'];
       $this->id_person_type = $row['id_person_type'];
       $this->type = $row['type'];
       $this->name = $row['name'];
       $this->lastname = $row['lastname'];
       $this->main_email = $row['main_email'];
       $this->login_salt = $row['login_salt'];
       $this->login_password = $row['login_password'];
       $this->member = $row['member'];
       $this->active = $row['active'];
       return true;
     }
     return false;
	}



	// create person
	function create(){

		// query to insert record
		$query ="INSERT INTO ".$this->table_name." SET id_person_type=:id_person_type,name=:name,lastname=:lastname,main_email=:main_email,login_salt=:login_salt,login_password=:login_password,member=:member,active=:active";

		// prepare query
		$stmt = $this->conn->prepare($query);

		// sanitize

		$this->id_person_type=htmlspecialchars(strip_tags($this->id_person_type));
		$this->name=htmlspecialchars(strip_tags($this->name));
		$this->lastname=htmlspecialchars(strip_tags($this->lastname));
		$this->main_email=htmlspecialchars(strip_tags($this->main_email));
		$this->login_salt=htmlspecialchars(strip_tags($this->login_salt));
		$this->login_password=htmlspecialchars(strip_tags($this->login_password));
		$this->member=htmlspecialchars(strip_tags($this->member));
		$this->active=htmlspecialchars(strip_tags($this->active));

		// bind values

		$stmt->bindParam(":id_person_type", $this->id_person_type);
		$stmt->bindParam(":name", $this->name);
		$stmt->bindParam(":lastname", $this->lastname);
		$stmt->bindParam(":main_email", $this->main_email);
		$stmt->bindParam(":login_salt", $this->login_salt);
		$stmt->bindParam(":login_password", $this->login_password);
		$stmt->bindParam(":member", $this->member);
		$stmt->bindParam(":active", $this->active);

		// execute query
		if($stmt->execute()){
			return  $this->conn->lastInsertId();
		}

		return 0;

	}



	// update the person
	function update(){

		// update query
		$query ="UPDATE ".$this->table_name." SET name=:name,lastname=:lastname,main_email=:main_email WHERE id_person = :id_person";

		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// sanitize
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->lastname=htmlspecialchars(strip_tags($this->lastname));
    $this->main_email=htmlspecialchars(strip_tags($this->main_email));
    $this->id_person=htmlspecialchars(strip_tags($this->id_person));

		// bind new values
    $stmt->bindParam(":name", $this->name);
    $stmt->bindParam(":lastname", $this->lastname);
    $stmt->bindParam(":main_email", $this->main_email);
    $stmt->bindParam(":id_person", $this->id_person);


		// execute the query
		if($stmt->execute()){
			return true;
		}

		return false;
	}


  // update the person
	function updateBE(){

		// update query
		$query ="UPDATE ".$this->table_name."
    SET
      name=:name,
      lastname=:lastname,
      main_email=:main_email,
      id_person_type=:id_person_type,
      active=:active
    WHERE id_person = :id_person";

		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// sanitize
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->lastname=htmlspecialchars(strip_tags($this->lastname));
    $this->main_email=htmlspecialchars(strip_tags($this->main_email));
    $this->id_person=htmlspecialchars(strip_tags($this->id_person));
    $this->id_person_type=htmlspecialchars(strip_tags($this->id_person_type));
    $this->active=htmlspecialchars(strip_tags($this->active));

		// bind new values
    $stmt->bindParam(":name", $this->name);
    $stmt->bindParam(":lastname", $this->lastname);
    $stmt->bindParam(":main_email", $this->main_email);
    $stmt->bindParam(":id_person_type", $this->id_person_type);
    $stmt->bindParam(":active", $this->active);
    $stmt->bindParam(":id_person", $this->id_person);


		// execute the query
		return !!($stmt->execute());
	}




	// delete the person
	function delete(){

		// delete query
		$query = "DELETE FROM " . $this->table_name . " WHERE id_person = ? ";

		// prepare query
		$stmt = $this->conn->prepare($query);

		// sanitize
		$this->id_person=htmlspecialchars(strip_tags($this->id_person));

		// bind id of record to delete
		$stmt->bindParam(1, $this->id_person);

		// execute query
		if($stmt->execute()){
			return true;
		}

		return false;

	}


function readByid_person_type(){

if (isset($_GET["pageNo"]))
{
$this->pageNo =$_GET["pageNo"]; }
$offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
$query = "SELECT  g.type, t.* FROM ". $this->table_name ." t  join cat_person_type g on t.id_person_type = g.id_person_type  WHERE t.id_person_type = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->id_person_type);

$stmt->execute();
return $stmt;
}

	//extra function will be generated for one to many relations
}
?>
