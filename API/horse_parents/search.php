<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/horse_parents.php';
 include_once '../token/validatetoken.php';
// instantiate database and horse_parents object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$horse_parents = new Horse_Parents($db);

$searchKey = isset($_GET['key']) ? $_GET['key'] : die();
$horse_parents->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$horse_parents->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

// query horse_parents
$stmt = $horse_parents->search($searchKey);
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //horse_parents array
    $horse_parents_arr=array();
	$horse_parents_arr["pageno"]=$horse_parents->pageNo;
	$horse_parents_arr["pagesize"]=$horse_parents->no_of_records_per_page;
    $horse_parents_arr["total_count"]=$horse_parents->total_record_count();
    $horse_parents_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $horse_parents_item=array(
            
"id_horse_parents" => $id_horse_parents,
"horse_name" => $horse_name,
"id_horse" => $id_horse,
"mother_data" => html_entity_decode($mother_data),
"father_data" => html_entity_decode($father_data)
        );
 
        array_push($horse_parents_arr["records"], $horse_parents_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show horse_parents data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "horse_parents found","document"=> $horse_parents_arr));
    
}else{
 // no horse_parents found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no horse_parents found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No horse_parents found.","document"=> ""));
    
}
 


