<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/database.php';
include_once '../objects/horse_parents.php';
 include_once '../token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare horse_parents object
$horse_parents = new Horse_Parents($db);
 
// get horse_parents id
$data = json_decode(file_get_contents("php://input"));
 
// set horse_parents id to be deleted
$horse_parents->id_horse_parents = $data->id_horse_parents;
 
// delete the horse_parents
if($horse_parents->delete()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Horse_Parents was deleted","document"=> ""));
    
}
 
// if unable to delete the horse_parents
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to delete horse_parents.","document"=> ""));
}
?>
