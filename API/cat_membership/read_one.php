<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
include_once '../config/database.php';
include_once '../objects/cat_membership.php';
 include_once '../token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare cat_membership object
$cat_membership = new Cat_Membership($db);
 
// set ID property of record to read
$cat_membership->id_membership = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of cat_membership to be edited
$cat_membership->readOne();
 
if($cat_membership->id_membership!=null){
    // create array
    $cat_membership_arr = array(
        
"id_membership" => $cat_membership->id_membership,
"membership" => $cat_membership->membership,
"interval_months" => $cat_membership->interval_months,
"price" => $cat_membership->price,
"active" => $cat_membership->active
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "cat_membership found","document"=> $cat_membership_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user cat_membership does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "cat_membership does not exist.","document"=> ""));
}
?>
