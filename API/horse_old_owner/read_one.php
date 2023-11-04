<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
include_once '../config/database.php';
include_once '../objects/horse_old_owner.php';
 include_once '../token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare horse_old_owner object
$horse_old_owner = new Horse_Old_Owner($db);
 
// set ID property of record to read
$horse_old_owner->id_old_owner = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of horse_old_owner to be edited
$horse_old_owner->readOne();
 
if($horse_old_owner->id_old_owner!=null){
    // create array
    $horse_old_owner_arr = array(
        
"id_old_owner" => $horse_old_owner->id_old_owner,
"name" => $horse_old_owner->name,
"id_person" => $horse_old_owner->id_person,
"horse_name" => $horse_old_owner->horse_name,
"id_horse" => $horse_old_owner->id_horse,
"from_date" => $horse_old_owner->from_date,
"to_date" => $horse_old_owner->to_date,
"active" => $horse_old_owner->active
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "horse_old_owner found","document"=> $horse_old_owner_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user horse_old_owner does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "horse_old_owner does not exist.","document"=> ""));
}
?>
