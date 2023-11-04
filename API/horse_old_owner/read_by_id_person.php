<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/horse_old_owner.php';
 include_once '../token/validatetoken.php';
// instantiate database and horse_old_owner object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$horse_old_owner = new Horse_Old_Owner($db);

$horse_old_owner->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$horse_old_owner->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
$horse_old_owner->id_person = isset($_GET['id_person']) ? $_GET['id_person'] : die();
// read horse_old_owner will be here

// query horse_old_owner
$stmt = $horse_old_owner->readByid_person();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //horse_old_owner array
    $horse_old_owner_arr=array();
	$horse_old_owner_arr["pageno"]=$horse_old_owner->pageNo;
	$horse_old_owner_arr["pagesize"]=$horse_old_owner->no_of_records_per_page;
    $horse_old_owner_arr["total_count"]=$horse_old_owner->total_record_count();
    $horse_old_owner_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $horse_old_owner_item=array(
            
"id_old_owner" => $id_old_owner,
"name" => $name,
"id_person" => $id_person,
"horse_name" => $horse_name,
"id_horse" => $id_horse,
"from_date" => $from_date,
"to_date" => $to_date,
"active" => $active
        );
 
        array_push($horse_old_owner_arr["records"], $horse_old_owner_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show horse_old_owner data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "horse_old_owner found","document"=> $horse_old_owner_arr));
    
}else{
 // no horse_old_owner found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no horse_old_owner found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No horse_old_owner found.","document"=> ""));
    
}
 


