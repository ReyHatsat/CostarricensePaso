<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once '../config/database.php';
 include_once '../token/token.php';
// instantiate product object
include_once '../jwt/BeforeValidException.php';
include_once '../jwt/ExpiredException.php';
include_once '../jwt/SignatureInvalidException.php';
include_once '../jwt/JWT.php';
use \Firebase\JWT\JWT;

$database = new Database();
$db = $database->getConnection();

// get posted data
$data = json_decode(file_get_contents("php://input"));
// make sure data is not empty
if(
!empty($data->general)
&&!empty($data->specific)
){

//validate your username and password from database call (copy the code from other generated table files or user table (if you have user or admin table)
$username = $data->general;
$password = $data->specific;
//Write your own logic here
if(
  $username == "a41737c7b85c8e99ba013e1bce739502" &&
  $password == "98200bb918c81136fe57c60bc488a94f"){
$jwt = JWT::encode($token, SECRET_KEY);
$tokenOutput = array(
			"access_token" => $jwt,
            "expires_in" => $tokenExp,
			"token_type" => "bearer",
			);
  http_response_code(200);
  echo json_encode(array("status" => "success", "code" => 1,"message"=> "Token Generated","document"=> $tokenOutput));
}else{
	http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Invalid login.","document"=> ""));
}
}

// tell the user data is incomplete
else{

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to login.","document"=> ""));
}
?>
