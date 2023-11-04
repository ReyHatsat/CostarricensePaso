<?php
require_once('../../../module/class.Session.php');

require_once('../../../module/class.Conexion.php');
require_once('../../../module/class.Metodos.php');
require_once('../../../module/class.User.php');



//Response variables
$response = [
  'status' => false,
  'msg' => ''
];


// Get the file ext
$f_ext = pathinfo( strtolower($_FILES['passport']['name']), PATHINFO_EXTENSION );


// Accepted file exts
$accepted = ['jpeg', 'jpg', 'png'];


if (in_array($f_ext, $accepted)) {

  //Set the Session variables
  $Session = new Session();
  $ID = $Session->getID();
  //File vars
  $filename = 'passport_'.$ID.'.png';
  $directory = 'Docs/'.$ID.'/';
  $location = $directory.$filename;

  if (!file_exists($directory)) {
    mkdir($directory);
  }


  //Move the uploaded file to the folder. and Change the format to PNG
  if (imagepng( imagecreatefromstring( file_get_contents($_FILES['passport']['tmp_name']) ), $location)) {

    // INIT User Class
    $User = new User();

    //Upload succesful, Update DATABASE
    $response['status'] = true;
    $User->updateUserCol($ID, '2', 'passport_status');
    $Session->setUser( $User->getUser($ID)[0] );
    $response['msg'] = 'Your passport has been uploaded and submited for revision, an administrator will check it. Thank you!';

  }else{

    //Upload failed
    $response['msg'] = 'Error while uploading the file, try again later.';

  }

}else{

  $response['msg'] = 'The file type is not accepted, please upload a file in any of these formats, ( png, jpeg, jpg, pdf )';

}

echo json_encode($response);


?>
