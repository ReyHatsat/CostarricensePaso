<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/person_membership.php';
 include_once '../token/validatetoken.php';
// instantiate database and person_membership object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$person_membership = new Person_Membership($db);

$person_membership->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$person_membership->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
$person_membership->id_person = isset($_GET['id_person']) ? $_GET['id_person'] : die();
// read person_membership will be here

// query person_membership
$stmt = $person_membership->readByid_person();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //person_membership array
    $person_membership_arr=array();
	$person_membership_arr["pageno"]=$person_membership->pageNo;
	$person_membership_arr["pagesize"]=$person_membership->no_of_records_per_page;
    $person_membership_arr["total_count"]=$person_membership->total_record_count();
    $person_membership_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $person_membership_item=array(
            
"id_person_membership" => $id_person_membership,
"membership" => $membership,
"id_membership" => $id_membership,
"name" => $name,
"id_person" => $id_person,
"start_date" => $start_date,
"end_date" => $end_date,
"active" => $active
        );
 
        array_push($person_membership_arr["records"], $person_membership_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show person_membership data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "person_membership found","document"=> $person_membership_arr));
    
}else{
 // no person_membership found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no person_membership found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No person_membership found.","document"=> ""));
    
}
 


