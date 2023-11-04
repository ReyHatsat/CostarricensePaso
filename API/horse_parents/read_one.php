<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
include_once '../config/database.php';
include_once '../objects/horse_parents.php';
 include_once '../token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare horse_parents object
$horse_parents = new Horse_Parents($db);
 
// set ID property of record to read
$horse_parents->id_horse_parents = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of horse_parents to be edited
$horse_parents->readOne();
 
if($horse_parents->id_horse_parents!=null){
    // create array
    $horse_parents_arr = array(
        
"id_horse_parents" => $horse_parents->id_horse_parents,
"horse_name" => $horse_parents->horse_name,
"id_horse" => $horse_parents->id_horse,
"mother_data" => html_entity_decode($horse_parents->mother_data),
"father_data" => html_entity_decode($horse_parents->father_data)
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "horse_parents found","document"=> $horse_parents_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user horse_parents does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "horse_parents does not exist.","document"=> ""));
}
?>
