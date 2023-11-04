<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/database.php';
include_once '../objects/cat_encaste.php';
 include_once '../token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare cat_encaste object
$cat_encaste = new Cat_Encaste($db);
 
// get id of cat_encaste to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of cat_encaste to be edited
$cat_encaste->id_encaste = $data->id_encaste;

if(
!empty($data->encaste)
&&!empty($data->active)
){
// set cat_encaste property values

if(!empty($data->encaste)) { 
$cat_encaste->encaste = $data->encaste;
} else { 
$cat_encaste->encaste = '';
}
if(!empty($data->active)) { 
$cat_encaste->active = $data->active;
} else { 
$cat_encaste->active = '1';
}
 
// update the cat_encaste
if($cat_encaste->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}
 
// if unable to update the cat_encaste, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update cat_encaste","document"=> ""));
    
}
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update cat_encaste. Data is incomplete.","document"=> ""));
}
?>
