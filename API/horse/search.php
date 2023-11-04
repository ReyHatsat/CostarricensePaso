<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/horse.php';
 include_once '../token/validatetoken.php';
// instantiate database and horse object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$horse = new Horse($db);

$searchKey = isset($_GET['key']) ? $_GET['key'] : die();
$horse->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$horse->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

// query horse
$stmt = $horse->search($searchKey);
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //horse array
    $horse_arr=array();
	$horse_arr["pageno"]=$horse->pageNo;
	$horse_arr["pagesize"]=$horse->no_of_records_per_page;
    $horse_arr["total_count"]=$horse->total_record_count();
    $horse_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $horse_item=array(
            
"id_horse" => $id_horse,
"name" => $name,
"id_current_owner" => $id_current_owner,
"encaste" => $encaste,
"id_encaste" => $id_encaste,
"sex" => $sex,
"id_horse_sex" => $id_horse_sex,
"horse_name" => $horse_name,
"birth_date" => $birth_date,
"first_owner_data" => html_entity_decode($first_owner_data),
"breeder_data" => html_entity_decode($breeder_data),
"other_information" => html_entity_decode($other_information),
"observations" => html_entity_decode($observations),
"microchip_no" => html_entity_decode($microchip_no),
"inscription_date" => $inscription_date,
"inspector_reference" => html_entity_decode($inspector_reference),
"active" => $active
        );
 
        array_push($horse_arr["records"], $horse_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show horse data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "horse found","document"=> $horse_arr));
    
}else{
 // no horse found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no horse found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No horse found.","document"=> ""));
    
}
 


