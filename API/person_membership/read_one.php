<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
include_once '../config/database.php';
include_once '../objects/person_membership.php';
 include_once '../token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare person_membership object
$person_membership = new Person_Membership($db);
 
// set ID property of record to read
$person_membership->id_person_membership = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of person_membership to be edited
$person_membership->readOne();
 
if($person_membership->id_person_membership!=null){
    // create array
    $person_membership_arr = array(
        
"id_person_membership" => $person_membership->id_person_membership,
"membership" => $person_membership->membership,
"id_membership" => $person_membership->id_membership,
"name" => $person_membership->name,
"id_person" => $person_membership->id_person,
"start_date" => $person_membership->start_date,
"end_date" => $person_membership->end_date,
"active" => $person_membership->active
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "person_membership found","document"=> $person_membership_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user person_membership does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "person_membership does not exist.","document"=> ""));
}
?>
