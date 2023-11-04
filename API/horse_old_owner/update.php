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
 
// get id of horse_old_owner to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of horse_old_owner to be edited
$horse_old_owner->id_old_owner = $data->id_old_owner;

if(
!empty($data->id_person)
&&!empty($data->id_horse)
&&!empty($data->from_date)
&&!empty($data->to_date)
&&!empty($data->active)
){
// set horse_old_owner property values

if(!empty($data->id_person)) { 
$horse_old_owner->id_person = $data->id_person;
} else { 
$horse_old_owner->id_person = '';
}
if(!empty($data->id_horse)) { 
$horse_old_owner->id_horse = $data->id_horse;
} else { 
$horse_old_owner->id_horse = '';
}
if(!empty($data->from_date)) { 
$horse_old_owner->from_date = $data->from_date;
} else { 
$horse_old_owner->from_date = '';
}
if(!empty($data->to_date)) { 
$horse_old_owner->to_date = $data->to_date;
} else { 
$horse_old_owner->to_date = '';
}
if(!empty($data->active)) { 
$horse_old_owner->active = $data->active;
} else { 
$horse_old_owner->active = '1';
}
 
// update the horse_old_owner
if($horse_old_owner->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}
 
// if unable to update the horse_old_owner, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update horse_old_owner","document"=> ""));
    
}
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update horse_old_owner. Data is incomplete.","document"=> ""));
}
?>
