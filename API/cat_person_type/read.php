<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/cat_person_type.php';
 include_once '../token/validatetoken.php';
// instantiate database and cat_person_type object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$cat_person_type = new Cat_Person_Type($db);

$cat_person_type->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$cat_person_type->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
// read cat_person_type will be here

// query cat_person_type
$stmt = $cat_person_type->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //cat_person_type array
    $cat_person_type_arr=array();
	$cat_person_type_arr["pageno"]=$cat_person_type->pageNo;
	$cat_person_type_arr["pagesize"]=$cat_person_type->no_of_records_per_page;
    $cat_person_type_arr["total_count"]=$cat_person_type->total_record_count();
    $cat_person_type_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $cat_person_type_item=array(
            
"id_person_type" => $id_person_type,
"type" => $type,
"data" => html_entity_decode($data),
"active" => $active
        );
 
        array_push($cat_person_type_arr["records"], $cat_person_type_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show cat_person_type data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "cat_person_type found","document"=> $cat_person_type_arr));
    
}else{
 // no cat_person_type found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no cat_person_type found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No cat_person_type found.","document"=> ""));
    
}
 


