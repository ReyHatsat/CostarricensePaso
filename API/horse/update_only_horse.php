<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/database.php';
include_once '../objects/horse.php';
 include_once '../token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare horse object
$horse = new Horse($db);

// get id of horse to be edited
$data = json_decode(file_get_contents("php://input"));

// set ID property of horse to be edited
$horse->id_horse = $data->id_horse;

if(
!empty($data->id_current_owner)
&&!empty($data->id_encaste)
&&!empty($data->id_horse_sex)
&&!empty($data->horse_name)
&&!empty($data->birth_date)
&&!empty($data->other_information)
&&!empty($data->observations)
&&!empty($data->microchip_no)
&&!empty($data->inscription_date)
&&!empty($data->active)
){
// set horse property values

if(!empty($data->id_current_owner)) {
$horse->id_current_owner = $data->id_current_owner;
} else {
$horse->id_current_owner = '';
}
if(!empty($data->id_encaste)) {
$horse->id_encaste = $data->id_encaste;
} else {
$horse->id_encaste = '';
}
if(!empty($data->id_horse_sex)) {
$horse->id_horse_sex = $data->id_horse_sex;
} else {
$horse->id_horse_sex = '';
}
if(!empty($data->horse_name)) {
$horse->horse_name = $data->horse_name;
} else {
$horse->horse_name = '';
}
if(!empty($data->birth_date)) {
$horse->birth_date = $data->birth_date;
} else {
$horse->birth_date = '';
}
if(!empty($data->other_information)) {
$horse->other_information = $data->other_information;
} else {
$horse->other_information = '';
}
if(!empty($data->observations)) {
$horse->observations = $data->observations;
} else {
$horse->observations = '';
}
if(!empty($data->microchip_no)) {
$horse->microchip_no = $data->microchip_no;
} else {
$horse->microchip_no = '';
}
if(!empty($data->inscription_date)) {
$horse->inscription_date = $data->inscription_date;
} else {
$horse->inscription_date = '';
}
if(!empty($data->inspector_reference)) {
$horse->inspector_reference = $data->inspector_reference;
} else {
$horse->inspector_reference = '';
}
if(!empty($data->active)) {
$horse->active = $data->active;
} else {
$horse->active = '1';
}

// update the horse
if($horse->update()){

    // set response code - 200 ok
    http_response_code(200);

    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}

// if unable to update the horse, tell the user
else{

    // set response code - 503 service unavailable
    http_response_code(503);

    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update horse","document"=> ""));

}
}
// tell the user data is incomplete
else{

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update horse. Data is incomplete.","document"=> ""));
}
?>
