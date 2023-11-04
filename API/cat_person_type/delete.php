<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/database.php';
include_once '../objects/cat_person_type.php';
 include_once '../token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare cat_person_type object
$cat_person_type = new Cat_Person_Type($db);
 
// get cat_person_type id
$data = json_decode(file_get_contents("php://input"));
 
// set cat_person_type id to be deleted
$cat_person_type->id_person_type = $data->id_person_type;
 
// delete the cat_person_type
if($cat_person_type->delete()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Cat_Person_Type was deleted","document"=> ""));
    
}
 
// if unable to delete the cat_person_type
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to delete cat_person_type.","document"=> ""));
}
?>
