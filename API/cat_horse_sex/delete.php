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
 
// get cat_horse_sex id
$data = json_decode(file_get_contents("php://input"));
 
// set cat_horse_sex id to be deleted
$cat_horse_sex->id_horse_sex = $data->id_horse_sex;
 
// delete the cat_horse_sex
if($cat_horse_sex->delete()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Cat_Horse_Sex was deleted","document"=> ""));
    
}
 
// if unable to delete the cat_horse_sex
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to delete cat_horse_sex.","document"=> ""));
}
?>
