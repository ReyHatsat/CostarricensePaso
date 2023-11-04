<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/cat_membership.php';
 include_once '../token/validatetoken.php';
// instantiate database and cat_membership object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$cat_membership = new Cat_Membership($db);

$searchKey = isset($_GET['key']) ? $_GET['key'] : die();
$cat_membership->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$cat_membership->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

// query cat_membership
$stmt = $cat_membership->search($searchKey);
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //cat_membership array
    $cat_membership_arr=array();
	$cat_membership_arr["pageno"]=$cat_membership->pageNo;
	$cat_membership_arr["pagesize"]=$cat_membership->no_of_records_per_page;
    $cat_membership_arr["total_count"]=$cat_membership->total_record_count();
    $cat_membership_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $cat_membership_item=array(
            
"id_membership" => $id_membership,
"membership" => $membership,
"interval_months" => $interval_months,
"price" => $price,
"active" => $active
        );
 
        array_push($cat_membership_arr["records"], $cat_membership_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show cat_membership data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "cat_membership found","document"=> $cat_membership_arr));
    
}else{
 // no cat_membership found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no cat_membership found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No cat_membership found.","document"=> ""));
    
}
 


