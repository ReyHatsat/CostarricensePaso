<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
include_once '../config/database.php';
include_once '../objects/horse.php';
 include_once '../token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare horse object
$horse = new Horse($db);
 
// set ID property of record to read
$horse->id_horse = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of horse to be edited
$horse->readOne();
 
if($horse->id_horse!=null){
    // create array
    $horse_arr = array(
        "id_horse" => $horse->id_horse,
        "name" => $horse->name,
        "id_current_owner" => $horse->id_current_owner,
        "encaste" => $horse->encaste,
        "id_encaste" => $horse->id_encaste,
        "sex" => $horse->sex,
        "id_horse_sex" => $horse->id_horse_sex,
        "horse_name" => $horse->horse_name,
        "birth_date" => $horse->birth_date,
        "first_owner_data" => html_entity_decode($horse->first_owner_data),
        "breeder_data" => html_entity_decode($horse->breeder_data),
        "other_information" => html_entity_decode($horse->other_information),
        "observations" => html_entity_decode($horse->observations),
        "microchip_no" => html_entity_decode($horse->microchip_no),
        "inscription_date" => $horse->inscription_date,
        "inspector_reference" => html_entity_decode($horse->inspector_reference),
        "active" => $horse->active
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "horse found","document"=> $horse_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user horse does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "horse does not exist.","document"=> ""));
}
?>
