<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/database.php';
include_once '../objects/cat_horse_sex.php';
 include_once '../token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare cat_horse_sex object
$cat_horse_sex = new Cat_Horse_Sex($db);
 
// get id of cat_horse_sex to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of cat_horse_sex to be edited
$cat_horse_sex->id_horse_sex = $data->id_horse_sex;

if(
!empty($data->sex)
&&!empty($data->active)
){
// set cat_horse_sex property values

if(!empty($data->sex)) { 
$cat_horse_sex->sex = $data->sex;
} else { 
$cat_horse_sex->sex = '';
}
if(!empty($data->active)) { 
$cat_horse_sex->active = $data->active;
} else { 
$cat_horse_sex->active = '1';
}
 
// update the cat_horse_sex
if($cat_horse_sex->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}
 
// if unable to update the cat_horse_sex, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update cat_horse_sex","document"=> ""));
    
}
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update cat_horse_sex. Data is incomplete.","document"=> ""));
}
?>
