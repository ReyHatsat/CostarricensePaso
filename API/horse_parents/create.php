<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once '../config/database.php';

// instantiate horse_parents object
include_once '../objects/horse_parents.php';
 include_once '../token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();

$horse_parents = new Horse_Parents($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if(!empty($data->id_horse)
&&!empty($data->mother_data)
&&!empty($data->father_data)){

    // set horse_parents property values


    $data->father_data = json_encode($data->father_data);


    $data->mother_data = json_encode($data->mother_data);

if(!empty($data->id_horse)) {
$horse_parents->id_horse = $data->id_horse;
} else {
$horse_parents->id_horse = '';
}
if(!empty($data->mother_data)) {
$horse_parents->mother_data = $data->mother_data;
} else {
$horse_parents->mother_data = '';
}
if(!empty($data->father_data)) {
$horse_parents->father_data = $data->father_data;
} else {
$horse_parents->father_data = '';
}
 	$lastInsertedId=$horse_parents->create();
    // create the horse_parents
    if($lastInsertedId!=0){

        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","document"=> $lastInsertedId));
    }

    // if unable to create the horse_parents, tell the user
    else{

        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create horse_parents","document"=> ""));
    }
}

// tell the user data is incomplete
else{

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create horse_parents. Data is incomplete.","document"=> ""));
}
?>
