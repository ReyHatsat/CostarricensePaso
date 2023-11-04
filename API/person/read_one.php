<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

include_once '../config/database.php';
include_once '../objects/person.php';
 include_once '../token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare person object
$person = new Person($db);

// set ID property of record to read
$person->id_person = isset($_GET['id']) ? $_GET['id'] : die();

// read the details of person to be edited
$person->readOne();

if($person->id_person!=null){

    // create array
    $person_arr = array(
      "id_person" => $person->id_person,
      "type" => $person->type,
      "id_person_type" => $person->id_person_type,
      "name" => $person->name,
      "lastname" => $person->lastname,
      "main_email" => html_entity_decode($person->main_email),
      "login_salt" => html_entity_decode($person->login_salt),
      "login_password" => html_entity_decode($person->login_password),
      "member" => $person->member,
      "active" => $person->active
    );

    // set response code - 200 OK
    http_response_code(200);

    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "person found","document"=> $person_arr));
}

else{
    // set response code - 404 Not found
    http_response_code(404);

    // tell the user person does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "person does not exist.","document"=> ""));
}
?>
