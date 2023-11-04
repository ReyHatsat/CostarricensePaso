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
$stmt = $person->read();
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){

    //person array
    $person_arr=array();
	$person_arr["pageno"]=$person->pageNo;
	$person_arr["pagesize"]=$person->no_of_records_per_page;
    $person_arr["total_count"]=$person->total_record_count();
    $person_arr["records"]=array();

    // retrieve our table contents

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        $person_item=array(
          "id_person" => $id_person,
          "type" => $type,
          "id_person_type" => $id_person_type,
          "name" => $name,
          "lastname" => $lastname,
          "main_email" => html_entity_decode($main_email),
          "login_salt" => html_entity_decode($login_salt),
          "login_password" => html_entity_decode($login_password),
          "member" => $member,
          "active" => $active
        );

        array_push($person_arr["records"], $person_item);
    }

    // set response code - 200 OK
    http_response_code(200);

    // show person data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "person found","document"=> $person_arr));

}else{
 // no person found will be here

    // set response code - 404 Not found
    http_response_code(404);

    // tell the user no person found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No person found.","document"=> ""));

}
