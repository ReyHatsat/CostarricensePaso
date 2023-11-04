<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once '../config/database.php';

// instantiate horse object
include_once '../objects/horse.php';
include_once '../objects/horse_parents.php';
 include_once '../token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();

$horse = new horse($db);



// get posted data
$data = json_decode(file_get_contents("php://input"));


// make sure data is not empty
if(!empty($data->id_current_owner)
&&!empty($data->horse->id_encaste)
&&!empty($data->horse->id_horse_sex)
&&!empty($data->horse->name)
&&!empty($data->horse->birth)
&&!empty($data->first_owner)
&&!empty($data->breeder_data)
&&!empty($data->other_information)
&&!empty($data->horse->observations)
&&!empty($data->horse->microchip_no)
&&!empty($data->horse->inscription_date)
&&!empty($data->inspector_reference)){

    // set horse property values


if(!empty($data->id_current_owner)) {
$horse->id_current_owner = $data->id_current_owner;
} else {
$horse->id_current_owner = '';
}
if(!empty($data->horse->id_encaste)) {
$horse->id_encaste = $data->horse->id_encaste;
} else {
$horse->id_encaste = '';
}
if(!empty($data->horse->id_horse_sex)) {
$horse->id_horse_sex = $data->horse->id_horse_sex;
} else {
$horse->id_horse_sex = '';
}
if(!empty($data->horse->name)) {
$horse->horse_name = $data->horse->name;
} else {
$horse->horse_name = '';
}
if(!empty($data->horse->birth)) {
$horse->birth_date = $data->horse->birth;
} else {
$horse->birth_date = '';
}
if(!empty($data->first_owner_data)) {
$horse->first_owner_data = $data->first_owner_data;
} else {
$horse->first_owner_data = '';
}
if(!empty($data->breeder_data)) {
$horse->breeder_data = $data->breeder_data;
} else {
$horse->breeder_data = '';
}
if(!empty($data->other_information)) {
$horse->other_information = $data->other_information;
} else {
$horse->other_information = '';
}
if(!empty($data->horse->observations)) {
$horse->observations = $data->horse->observations;
} else {
$horse->observations = '';
}
if(!empty($data->horse->microchip_no)) {
$horse->microchip_no = $data->horse->microchip_no;
} else {
$horse->microchip_no = '';
}
if(!empty($data->horse->inscription_date)) {
$horse->inscription_date = $data->horse->inscription_date;
} else {
$horse->inscription_date = '';
}
if(!empty($data->inspector_reference)) {
$horse->inspector_reference = $data->inspector_reference;
} else {
$horse->inspector_reference = '';
}
$horse->active = 3;




 	$lastInsertedId=$horse->create();
    // create the horse
    if($lastInsertedId!=0){

        $horse_parents = new Horse_Parents($db);
        $horse_parents->id_horse = $lastInsertedId;
        $horse_parents->mother_data = $data->mother;
        $horse_parents->father_data = $data->father;


        $horse_parents->create();

        $horse->no_of_records_per_page = 9999999;
        $stmt = $horse->read();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","document"=> [ "id" => $lastInsertedId, "records" => $rows ]));
    }

    // if unable to create the horse, tell the user
    else{

        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create horse","document"=> ""));
    }
}

// tell the user data is incomplete
else{

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create horse. Data is incomplete.","document"=> $data));
}
?>
