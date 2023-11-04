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
 
// get id of person_membership to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of person_membership to be edited
$person_membership->id_person_membership = $data->id_person_membership;

if(
!empty($data->id_membership)
&&!empty($data->id_person)
&&!empty($data->start_date)
&&!empty($data->end_date)
&&!empty($data->active)
){
// set person_membership property values

if(!empty($data->id_membership)) { 
$person_membership->id_membership = $data->id_membership;
} else { 
$person_membership->id_membership = '';
}
if(!empty($data->id_person)) { 
$person_membership->id_person = $data->id_person;
} else { 
$person_membership->id_person = '';
}
if(!empty($data->start_date)) { 
$person_membership->start_date = $data->start_date;
} else { 
$person_membership->start_date = '';
}
if(!empty($data->end_date)) { 
$person_membership->end_date = $data->end_date;
} else { 
$person_membership->end_date = '';
}
if(!empty($data->active)) { 
$person_membership->active = $data->active;
} else { 
$person_membership->active = '1';
}
 
// update the person_membership
if($person_membership->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}
 
// if unable to update the person_membership, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update person_membership","document"=> ""));
    
}
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update person_membership. Data is incomplete.","document"=> ""));
}
?>
