<?php
class Database{

    // specify your own database credentials
    private $host = "localhost";
    private $db_name = "costarricense_paso";
    private $username = "root";
    private $password = "";
	  private $port = "3306";
    public $conn;


    //set to true if the enviroment is in the server.
    private $live_env = false;


    function __construct(){
      if($this->live_env){
        $this->db_name = "costarri_crpaso";
        $this->username = "costarri_crpaso";
        $this->password = 'Z%0Hz$eUB5K!DMY55rPq^D%5l#D&h';
      }
    }


    // get the database connection
    public function getConnection(){


        $this->conn = null;

        try{
		        if($this->port){
                $this->conn = new PDO("mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name, $this->username, $this->password);
            }else{
			           $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
			      }
			      $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>
