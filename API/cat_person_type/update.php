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
 
// get id of cat_person_type to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of cat_person_type to be edited
$cat_person_type->id_person_type = $data->id_person_type;

if(
!empty($data->type)
&&!empty($data->data)
&&!empty($data->active)
){
// set cat_person_type property values

if(!empty($data->type)) { 
$cat_person_type->type = $data->type;
} else { 
$cat_person_type->type = '';
}
if(!empty($data->data)) { 
$cat_person_type->data = $data->data;
} else { 
$cat_person_type->data = '';
}
if(!empty($data->active)) { 
$cat_person_type->active = $data->active;
} else { 
$cat_person_type->active = '1';
}
 
// update the cat_person_type
if($cat_person_type->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}
 
// if unable to update the cat_person_type, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update cat_person_type","document"=> ""));
    
}
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update cat_person_type. Data is incomplete.","document"=> ""));
}
?>
