<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/database.php';
include_once '../objects/horse_old_owner.php';
 include_once '../token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare horse_old_owner object
$horse_old_owner = new Horse_Old_Owner($db);
 
// get horse_old_owner id
$data = json_decode(file_get_contents("php://input"));
 
// set horse_old_owner id to be deleted
$horse_old_owner->id_old_owner = $data->id_old_owner;
 
// delete the horse_old_owner
if($horse_old_owner->delete()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Horse_Old_Owner was deleted","document"=> ""));
    
}
 
// if unable to delete the horse_old_owner
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to delete horse_old_owner.","document"=> ""));
}
?>
