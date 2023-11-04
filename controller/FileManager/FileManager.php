<?php
require_once('../../../module/class.Session.php');
require_once('../../../module/class.Conexion.php');
require_once('../../../module/class.Metodos.php');
require_once('../../../module/class.User.php');
$Session = new Session();

$r = [
  'status' => false
];

if (!isset($_POST)) {


  $r['msg'] = 'Error, no request recieved';


}else{



  $nodes = [
    [
      'find' => "{{name}}",
      'replace' => $_POST['data']['name']
    ],
    [
      'find' => "{{born}}",
      'replace' => $_POST['data']['born']
    ],
    [
      'find' => "{{address}}",
      'replace' => $_POST['data']['address']
    ],
    [
      'find' => "{{city}}",
      'replace' => $_POST['data']['city']
    ],
    [
      'find' => "{{zipcode}}",
      'replace' => $_POST['data']['zipcode']
    ],
    [
      'find' => "{{country}}",
      'replace' => $_POST['data']['country']
    ]
  ];


  //Saves the user file
  if (SaveFile($nodes)) {
    $User = new User();

    //Updates the user
    $User->updateUserCol(
      $Session->getID(),     //id
      1,                     //val
      'loan_bond_agreed'     //col
    );

    $User->updateUserCol(
      $Session->getID(),     //id
      $_POST['data']['income'], //val
      'id_income'     //col
    );

    $User->updateUserCol(
      $Session->getID(),     //id
      $_POST['data']['occupation'], //val
      'id_occupation'     //col
    );

    //updates the Session Variable
    $Session->setUser( $User->getUser($Session->getID())[0] );
    $r['status'] = true;

  }else{

    $r['msg'] = 'Error savig the file';

  }



}


echo json_encode($r);
























function SaveFile($nodes, $ext = 'html'){

  $ID = $GLOBALS['Session']->getID();
  $file = 'lba_template.php';


  $filename = 'lba-'.$ID.'.'.$ext;
  $directory = 'Docs/'.$ID.'/';
  $location = $directory.$filename;

  //create Directory if it doesnt exists.
  if (!file_exists($directory)) {
    mkdir($directory);
  }

  // Open and Replace the string tenplate
  $content = file_get_contents($file);
  $content = ReplaceStrings($content, $nodes);

  // Save the new file
  return file_put_contents($location, $content);

}










function ReplaceStrings($html, $nodes){
  foreach ($nodes as $node) {
    $html = str_replace($node['find'], $node['replace'], $html);
  }
  return $html;
}




?>
