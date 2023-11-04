<?php
session_start();
include_once '../../config.php';

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/database.php';
include_once '../objects/person.php';
 include_once '../token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare person object
$person = new Person($db);

// get id of person to be edited
$data = json_decode(file_get_contents("php://input"));


if(
  !empty($data->id_person)
  &&!empty($data->name)
  &&!empty($data->lastname)
  &&!empty($data->main_email)
){

  // set person property values
  $person->id_person = $data->id_person;
  $person->name = $data->name;
  $person->lastname = $data->lastname;
  $person->main_email = $data->main_email;
  $person->active = $data->active;
  $person->id_person_type = $data->id_person_type;

  // update the person
  if($person->updateBE()){

      $person->no_of_records_per_page = 9999999;
      $stmt = $person->read();

      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // set response code - 200 ok
      http_response_code(200);

      // tell the user
  	  echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> $rows));
  }

  // if unable to update the person, tell the user
  else{
    // set response code - 503 service unavailable
    http_response_code(503);
    // tell the user
	  echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update person","document"=> ""));
   }



// tell the user data is incomplete
}else{


    // set response code - 400 bad request
    http_response_code(400);
    // tell the user
	  echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update person. Data is incomplete.","document"=> ""));


}
?>
