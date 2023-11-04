<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/database.php';
include_once '../objects/person_membership.php';
 include_once '../token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare person_membership object
$person_membership = new Person_Membership($db);
 
// get person_membership id
$data = json_decode(file_get_contents("php://input"));
 
// set person_membership id to be deleted
$person_membership->id_person_membership = $data->id_person_membership;
 
// delete the person_membership
if($person_membership->delete()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Person_Membership was deleted","document"=> ""));
    
}
 
// if unable to delete the person_membership
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to delete person_membership.","document"=> ""));
}
?>
