<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/person.php';
 include_once '../token/validatetoken.php';
// instantiate database and person object
$database = new Database();
$db = $database->getConnection();

// initialize object
$person = new Person($db);

$person->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$person->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
// read person will be here

// query person
$person->main_email = $_GET['main_email'];



// check if more than 0 record found
if(!$person->readEmail()){


    // set response code - 200 OK
    http_response_code(200);

    // show person data in json format
	  echo json_encode(array("status" => "success", "code" => 1, "message"=> "Email not in the database", "document"=> []));

}else{
    // tell the user no person found
	  echo json_encode(array("status" => "error", "code" => 0, "message"=> "No person found.","document"=> ""));
}
