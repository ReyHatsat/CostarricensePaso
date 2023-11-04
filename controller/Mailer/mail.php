<?php

//include the database files
include_once '../../El_Lugar_API/config/database.php';
include_once '../../El_Lugar_API/objects/person.php';



//include the Mail class and initialize it
require_once('class.Mail.php');




if (isset($_GET['recovery'])) {
  recovery($_GET['recovery']);
}










function recovery($email){

  // get database connection
  $database = new Database();
  $db = $database->getConnection();

  // prepare person object
  $person = new Person($db);
  $person->main_email = $_GET['recovery'];

  $person->readOneEmail();

  $personObj = [
    "name"        => $person->name,
    "lastname"    => $person->lastname,
    "salt"        => $person->salt,
    "main_email"  => $person->main_email,
    "id_person"   => $person->id_person
  ];

  $Mail = new Mail();
  $Mail->resetPassword($personObj);
  echo '{"status":"success", "code":1}';
}


?>
