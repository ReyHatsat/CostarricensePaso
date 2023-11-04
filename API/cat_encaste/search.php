<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/cat_encaste.php';
 include_once '../token/validatetoken.php';
// instantiate database and cat_encaste object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$cat_encaste = new Cat_Encaste($db);

$searchKey = isset($_GET['key']) ? $_GET['key'] : die();
$cat_encaste->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$cat_encaste->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

// query cat_encaste
$stmt = $cat_encaste->search($searchKey);
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //cat_encaste array
    $cat_encaste_arr=array();
	$cat_encaste_arr["pageno"]=$cat_encaste->pageNo;
	$cat_encaste_arr["pagesize"]=$cat_encaste->no_of_records_per_page;
    $cat_encaste_arr["total_count"]=$cat_encaste->total_record_count();
    $cat_encaste_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $cat_encaste_item=array(
            
"id_encaste" => $id_encaste,
"encaste" => $encaste,
"active" => $active
        );
 
        array_push($cat_encaste_arr["records"], $cat_encaste_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show cat_encaste data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "cat_encaste found","document"=> $cat_encaste_arr));
    
}else{
 // no cat_encaste found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no cat_encaste found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No cat_encaste found.","document"=> ""));
    
}
 


