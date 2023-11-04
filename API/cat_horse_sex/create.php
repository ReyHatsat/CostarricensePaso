<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// get database connection
include_once '../config/database.php';
 
// instantiate cat_horse_sex object
include_once '../objects/cat_horse_sex.php';
 include_once '../token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();
 
$cat_horse_sex = new Cat_Horse_Sex($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(!empty($data->sex)
&&!empty($data->active)){
 
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
 	$lastInsertedId=$cat_horse_sex->create();
    // create the cat_horse_sex
    if($lastInsertedId!=0){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","document"=> $lastInsertedId));
    }
 
    // if unable to create the cat_horse_sex, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create cat_horse_sex","document"=> ""));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create cat_horse_sex. Data is incomplete.","document"=> ""));
}
?>
