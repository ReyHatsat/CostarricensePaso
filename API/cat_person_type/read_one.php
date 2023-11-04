<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
include_once '../config/database.php';
include_once '../objects/cat_person_type.php';
 include_once '../token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare cat_person_type object
$cat_person_type = new Cat_Person_Type($db);
 
// set ID property of record to read
$cat_person_type->id_person_type = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of cat_person_type to be edited
$cat_person_type->readOne();
 
if($cat_person_type->id_person_type!=null){
    // create array
    $cat_person_type_arr = array(
        
"id_person_type" => $cat_person_type->id_person_type,
"type" => $cat_person_type->type,
"data" => html_entity_decode($cat_person_type->data),
"active" => $cat_person_type->active
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "cat_person_type found","document"=> $cat_person_type_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user cat_person_type does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "cat_person_type does not exist.","document"=> ""));
}
?>
