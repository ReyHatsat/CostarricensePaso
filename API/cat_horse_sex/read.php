<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/cat_horse_sex.php';
 include_once '../token/validatetoken.php';
// instantiate database and cat_horse_sex object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$cat_horse_sex = new Cat_Horse_Sex($db);

$cat_horse_sex->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$cat_horse_sex->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
// read cat_horse_sex will be here

// query cat_horse_sex
$stmt = $cat_horse_sex->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //cat_horse_sex array
    $cat_horse_sex_arr=array();
	$cat_horse_sex_arr["pageno"]=$cat_horse_sex->pageNo;
	$cat_horse_sex_arr["pagesize"]=$cat_horse_sex->no_of_records_per_page;
    $cat_horse_sex_arr["total_count"]=$cat_horse_sex->total_record_count();
    $cat_horse_sex_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $cat_horse_sex_item=array(
            
"id_horse_sex" => $id_horse_sex,
"sex" => $sex,
"active" => $active
        );
 
        array_push($cat_horse_sex_arr["records"], $cat_horse_sex_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show cat_horse_sex data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "cat_horse_sex found","document"=> $cat_horse_sex_arr));
    
}else{
 // no cat_horse_sex found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no cat_horse_sex found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No cat_horse_sex found.","document"=> ""));
    
}
 


