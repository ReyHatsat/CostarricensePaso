<?php
session_start();


// if there is no OTP set, redirect to the main page
if (!isset($_GET['otp'])) {
  header('Location:https://el-lugar.com/cockpit');
}



//include the database files
include_once '../../El_Lugar_API/config/database.php';
include_once '../../El_Lugar_API/objects/person.php';


// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare person object
$person = new Person($db);

// set ID property of record to read
$person->salt = isset($_GET['otp']) ? $_GET['otp'] : die();
$person->id_person = isset($_GET['stat']) ? $_GET['stat'] : die();

// read the details of person to be edited
$person->readOneSalt();

if($person->id_person!=null){

    $person->id_status = 1;
    $person->update();

    // create array
    $person_arr = array(
      "id_person" => $person->id_person,
      "name" => html_entity_decode($person->name),
      "lastname" => html_entity_decode($person->lastname),
      "main_email" => html_entity_decode($person->main_email),
      "salt" => html_entity_decode($person->salt),
      "password" => html_entity_decode($person->password),
      "type" => html_entity_decode($person->type),
      "user_type" => $person->user_type,
      "loan_agreement" => $person->loan_agreement,
      "code" => html_entity_decode($person->code),
      "id_country" => $person->id_country,
      "income" => html_entity_decode($person->income),
      "id_income" => $person->id_income,
      "occupation" => html_entity_decode($person->occupation),
      "id_occupation" => $person->id_occupation,
      "status" => $person->status,
      "id_status" => $person->id_status
    );

    //$_SESSION['person'] = $person_arr;
    header('Location:../../?e-confirmed');


}else{
    // set response code - 404 Not found
    header('Location:../../?invalid');
}




?>
