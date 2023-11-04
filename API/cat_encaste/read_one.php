<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
include_once '../config/database.php';
include_once '../objects/cat_encaste.php';
 include_once '../token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare cat_encaste object
$cat_encaste = new Cat_Encaste($db);
 
// set ID property of record to read
$cat_encaste->id_encaste = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of cat_encaste to be edited
$cat_encaste->readOne();
 
if($cat_encaste->id_encaste!=null){
    // create array
    $cat_encaste_arr = array(
        
"id_encaste" => $cat_encaste->id_encaste,
"encaste" => $cat_encaste->encaste,
"active" => $cat_encaste->active
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "cat_encaste found","document"=> $cat_encaste_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user cat_encaste does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "cat_encaste does not exist.","document"=> ""));
}
?>
