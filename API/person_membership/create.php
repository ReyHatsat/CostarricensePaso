<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// get database connection
include_once '../config/database.php';
 
// instantiate person_membership object
include_once '../objects/person_membership.php';
 include_once '../token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();
 
$person_membership = new Person_Membership($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(!empty($data->id_membership)
&&!empty($data->id_person)
&&!empty($data->start_date)
&&!empty($data->end_date)
&&!empty($data->active)){
 
    // set person_membership property values
	 
if(!empty($data->id_membership)) { 
$person_membership->id_membership = $data->id_membership;
} else { 
$person_membership->id_membership = '';
}
if(!empty($data->id_person)) { 
$person_membership->id_person = $data->id_person;
} else { 
$person_membership->id_person = '';
}
if(!empty($data->start_date)) { 
$person_membership->start_date = $data->start_date;
} else { 
$person_membership->start_date = '';
}
if(!empty($data->end_date)) { 
$person_membership->end_date = $data->end_date;
} else { 
$person_membership->end_date = '';
}
if(!empty($data->active)) { 
$person_membership->active = $data->active;
} else { 
$person_membership->active = '1';
}
 	$lastInsertedId=$person_membership->create();
    // create the person_membership
    if($lastInsertedId!=0){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","document"=> $lastInsertedId));
    }
 
    // if unable to create the person_membership, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create person_membership","document"=> ""));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create person_membership. Data is incomplete.","document"=> ""));
}
?>
