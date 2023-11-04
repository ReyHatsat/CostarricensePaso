<?php
session_start();



include_once '../../config.php';
// importar conexion a Base de datos
include_once '../config/database.php';
include_once '../objects/person.php';
 include_once '../token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare person object
$person = new Person($db);

// trae los datos del front end del objeto
$data = json_decode(file_get_contents("php://input"));




$person->main_email = $data->main_email;




if(!$person->readEmail()){
    response(false, "No existe");
}


//  1  ACTIVO  //  status = 3 PENDIENTE CONFIRMAR
if($person->active != 1){
    response(false, "Está inactivo");
}



$hp = $person->HashPassword($data->login_password, $person->login_salt);
if($hp != $person->login_password){
    response(false, "Contraseña no coincide");
}



//Person array
$per_arr = array(
  "id_person" => $person->id_person,
  "type" => $person->type,
  "id_person_type" => $person->id_person_type,
  "name" => $person->name,
  "lastname" => $person->lastname,
  "main_email" => html_entity_decode($person->main_email),
  "member" => $person->member,
  "active" => $person->active,
  'is_admin' => ($person->id_person_type == 0)
);




$_SESSION[SES_OBJ] = $per_arr;
response(true, "Login Exitoso", $person->main_email);








// funcion de retorno
function response($s = false, $m = 'Uknown error', $d = []){
  $r = [ "status" => $s, "msg" => $m, "data" => $d ];
  echo json_encode( $r );
  exit();
}





?>
