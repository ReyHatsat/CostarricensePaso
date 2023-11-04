<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
include_once '../config/database.php';
include_once '../objects/cat_horse_sex.php';
 include_once '../token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare cat_horse_sex object
$cat_horse_sex = new Cat_Horse_Sex($db);
 
// set ID property of record to read
$cat_horse_sex->id_horse_sex = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of cat_horse_sex to be edited
$cat_horse_sex->readOne();
 
if($cat_horse_sex->id_horse_sex!=null){
    // create array
    $cat_horse_sex_arr = array(
        
"id_horse_sex" => $cat_horse_sex->id_horse_sex,
"sex" => $cat_horse_sex->sex,
"active" => $cat_horse_sex->active
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "cat_horse_sex found","document"=> $cat_horse_sex_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user cat_horse_sex does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "cat_horse_sex does not exist.","document"=> ""));
}
?>
