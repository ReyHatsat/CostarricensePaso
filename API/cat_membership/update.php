<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/database.php';
include_once '../objects/cat_membership.php';
 include_once '../token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare cat_membership object
$cat_membership = new Cat_Membership($db);

// get id of cat_membership to be edited
$data = json_decode(file_get_contents("php://input"));

// set ID property of cat_membership to be edited
$cat_membership->id_membership = $data->id_membership;

if(
!empty($data->membership)
&&!empty($data->interval_months)
&&!empty($data->price)
){
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
$cat_membership->active = $data->active;


// update the cat_membership
if($cat_membership->update()){

    // set response code - 200 ok
    http_response_code(200);

    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}

// if unable to update the cat_membership, tell the user
else{

    // set response code - 503 service unavailable
    http_response_code(503);

    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update cat_membership","document"=> ""));

}
}
// tell the user data is incomplete
else{

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update cat_membership. Data is incomplete.","document"=> ""));
}
?>
