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
 
// get id of horse_parents to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of horse_parents to be edited
$horse_parents->id_horse_parents = $data->id_horse_parents;

if(
!empty($data->id_horse)
&&!empty($data->mother_data)
&&!empty($data->father_data)
){
// set horse_parents property values

if(!empty($data->id_horse)) { 
$horse_parents->id_horse = $data->id_horse;
} else { 
$horse_parents->id_horse = '';
}
if(!empty($data->mother_data)) { 
$horse_parents->mother_data = $data->mother_data;
} else { 
$horse_parents->mother_data = '';
}
if(!empty($data->father_data)) { 
$horse_parents->father_data = $data->father_data;
} else { 
$horse_parents->father_data = '';
}
 
// update the horse_parents
if($horse_parents->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}
 
// if unable to update the horse_parents, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update horse_parents","document"=> ""));
    
}
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update horse_parents. Data is incomplete.","document"=> ""));
}
?>
