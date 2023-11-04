<?php

include('../../config.php');

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once '../config/database.php';

// instantiate person object
include_once '../objects/person.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$person = new Person($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));


// make sure data is not empty
if(!empty($data->name)
&&!empty($data->lastname)
&&!empty($data->main_email)
&&!empty($data->login_password)){



    // set person property values
    if(!empty($data->name)) {
        $person->name = $data->name;
    } else {
        $person->name = '';
    }
    if(!empty($data->lastname)) {
        $person->lastname = $data->lastname;
    } else {
        $person->lastname = '';
    }
    if(!empty($data->main_email)) {
        $person->main_email = $data->main_email;
    } else {
        $person->main_email = '';
    }
    if(!empty($data->login_salt)) {
        $person->login_salt = $data->login_salt;
    } else {
        $person->login_salt = $person->gensalt();
    }
    if(!empty($data->login_password)) {
        $person->login_password = $person->HashPassword( $data->login_password, $person->login_salt );
    } else {
        $person->login_password = $person->HashPassword( $data->login_password, $person->login_salt );
    }
    if(!empty($data->member)) {
        $person->member = $data->member;
    } else {
        $person->member = 0;
    }
    if(!empty($data->active)) {
        $person->active = $data->active;
    } else {
        $person->active = 3;
    }



    try {
        $lastInsertedId = $person->create();
    } catch (\Exception $e) {
        http_response_code(503);
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create person","document"=> $e));
        exit();
    }




    // create the person
    if($lastInsertedId != 0){

        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","document"=> $lastInsertedId));
    }
    // if unable to create the person, tell the user
    else{

        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create person","document"=> ""));
    }
}

// tell the user data is incomplete
else{

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create person. Data is incomplete.", "document"=> $data));
}
?>
