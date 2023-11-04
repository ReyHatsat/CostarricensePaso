<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// get database connection
include_once '../config/database.php';
 
// instantiate horse_old_owner object
include_once '../objects/horse_old_owner.php';
include_once '../objects/horse.php';
include_once '../token/validatetoken.php';


$database = new Database();
$db = $database->getConnection();

$horse_old_owner = new Horse_Old_Owner($db);
$horse = new Horse($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(!empty($data->old_owner)
&&!empty($data->id_horse)
&&!empty($data->from_date)
&&!empty($data->to_date)){
 
    // set horse_old_owner property values
	 
if(!empty($data->old_owner)) { 
$horse_old_owner->id_person = $data->old_owner;
} else { 
$horse_old_owner->id_person = '';
}
if(!empty($data->id_horse)) { 
$horse_old_owner->id_horse = $data->id_horse;
} else { 
$horse_old_owner->id_horse = '';
}
if(!empty($data->from_date)) { 
$horse_old_owner->from_date = $data->from_date;
} else { 
$horse_old_owner->from_date = '';
}
if(!empty($data->to_date)) { 
$horse_old_owner->to_date = $data->to_date;
} else { 
$horse_old_owner->to_date = '';
}
if(!empty($data->active)) { 
$horse_old_owner->active = $data->active;
} else { 
$horse_old_owner->active = '1';
}


    //crear la nueva transferencia
 	$lastInsertedId = $horse_old_owner->create();


    // create the horse_old_owner
    if($lastInsertedId != 0){


        //actualizando las transferencias anteriores de este caballo, para que esten desactivadas.
        $horse_old_owner->newTransfer($data->id_horse, $lastInsertedId);


        $horse->id_horse = $data->id_horse;
        $horse->id_current_owner = $data->id_person;
        $horse->updateOwner();

 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","document"=> $lastInsertedId));
    }
 
    // if unable to create the horse_old_owner, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create horse_old_owner","document"=> ""));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create horse_old_owner. Data is incomplete.","document"=> ""));
}
?>
