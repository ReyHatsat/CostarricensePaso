<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once '../config/database.php';

// instantiate cat_membership object
include_once '../objects/cat_membership.php';
 include_once '../token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();

$cat_membership = new Cat_Membership($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if(!empty($data->membership)
&&!empty($data->interval_months)
&&!empty($data->price)
&&!empty($data->active)){

    // set cat_membership property values

if(!empty($data->membership)) {
$cat_membership->membership = $data->membership;
} else {
$cat_membership->membership = '';
}
if(!empty($data->interval_months)) {
$cat_membership->interval_months = $data->interval_months;
} else {
$cat_membership->interval_months = '';
}
if(!empty($data->price)) {
$cat_membership->price = $data->price;
} else {
$cat_membership->price = '';
}
if(!empty($data->active)) { 
$cat_membership->active = $data->active;
} else {
$cat_membership->active = '1';
}
 	$lastInsertedId=$cat_membership->create();
    // create the cat_membership
    if($lastInsertedId!=0){

        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","document"=> $lastInsertedId));
    }

    // if unable to create the cat_membership, tell the user
    else{

        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create cat_membership","document"=> ""));
    }
}

// tell the user data is incomplete
else{

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create cat_membership. Data is incomplete.","document"=> ""));
}
?>
