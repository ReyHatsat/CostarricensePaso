<?php

//start the session to get the $_SESSION variables.
session_start();


include_once '../../../El_Lugar_API/config/database.php';


//get the request Data
$data = json_decode(file_get_contents("php://input"));
//var_dump($data);
// echo 'bd249a12f: '.$data->bd249a12f.'</br>';
// echo 'checkoutAmount: '.$data->checkoutAmount.'</br>';
// echo 'checkoutItemPrice: '.$data->checkoutItemPrice.'</br>';
// echo 'tscQty: '.$data->tscQty.'</br>';
// echo 'tscReference: '.$data->tscReference.'</br>';
// echo 'tsctype: '.$data->tsctype.'</br>';


if (!$_SESSION['authorize']) {

  echo json_encode( [
    "status" => "error",
    "code" => 2,
    "title" => 'Payment not captured',
    "message" => 'There was an error processing your request, please try again.'
  ] );

}else{

  //Add tickets for the user
  if ($data->tscType == 1) { AddTicketforUser($data); }

  //Add membership for the user
  if ($data->tscType == 2) { AddMembershipforUser($data); }

  //Add loan bonds for the user
  if ($data->tscType == 3) { AddLoanBondforUser($data); }


  //Add the Investment Payment
  if ($data->tscType == 4) { InvestmentPayment($data); }


  echo json_encode( [
    "status" => "success",
    "code" => 1,
    "title" => 'Purchase successful!',
    "message" => 'Thank you for your purchase, we will redirect you in a second, please wait...'
  ] );

}







// FUNCTIONS
//This functions Adds Tickets to users
function AddTicketforUser($data){

  include_once '../../../El_Lugar_API/objects/per_ticket.php';

  // get database connection
  $database = new Database();
  $db = $database->getConnection();

  // prepare cat_ticket object
  $per_ticket = new Per_Ticket($db);


  $per_ticket->id_ticket_type = $data->tscReference;
  $per_ticket->id_person = $_SESSION['person']['id_person'];
  $per_ticket->purchase_price = $data->checkoutItemPrice;
  $per_ticket->date = date('Y-m-d H:i:s');
  $per_ticket->active = 1;


  for ($i=0; $i < $data->tscQty; $i++) {
    $per_ticket->create();
  }


  //add a single payment for all the tickets
  $amount = $data->checkoutAmount;
  $remarks = 'Purchase of '.$data->tscQty.' tickets at the price of: $'.number_format($data->checkoutItemPrice, 2, ',', '.').' each.';
  AddPerPayment($amount, 4, 'ticket_purchase', $remarks, $data->bd249a12f);

}



function AddMembershipforUser($data){

  include_once '../../../El_Lugar_API/objects/per_membership.php';

  // get database connection
  $database = new Database();
  $db = $database->getConnection();

  $per_membership = new Per_Membership($db);


  // set per_membership property values
  $per_membership->id_person = $_SESSION['person']['id_person'];
  $per_membership->id_membership = $data->tscReference;
  $per_membership->start_date = date('Y-m-d H:i:s');
  $per_membership->active = 1;


  $lastInsertedId = $per_membership->create();
  if ($lastInsertedId != 0) {

    //get the variables out of the session
    extract($_SESSION['person']);

    //Update the Person Object in the DB
    include_once '../../../El_Lugar_API/objects/person.php';

    // get database connection
    $database = new Database();
    $db = $database->getConnection();

    $person = new Person($db);


    $person->id_person = $_SESSION['person']['id_person'];
    $person->user_type = 2;

    $person->updateUserType();
    $person->readOne();

    //Update the Session Object
    $person_arr = array(
      "id_person" => $person->id_person,
      "name" => html_entity_decode($person->name),
      "id_admin" => html_entity_decode($person->id_admin),
      "lastname" => html_entity_decode($person->lastname),
      "main_email" => html_entity_decode($person->main_email),
      "salt" => html_entity_decode($person->salt),
      "password" => html_entity_decode($person->password),
      "type" => html_entity_decode($person->type),
      "user_type" => $person->user_type,
      "loan_agreement" => $person->loan_agreement,
      "country" => html_entity_decode($person->country),
      "id_country" => $person->id_country,
      "income" => html_entity_decode($person->income),
      "id_income" => $person->id_income,
      "occupation" => html_entity_decode($person->occupation),
      "id_occupation" => $person->id_occupation,
      "status" => $person->status,
      "id_status" => $person->id_status
    );

    //start the session.
    $_SESSION['person'] = $person_arr;

  }
  //Add the membershp payment into the DB
  $remarks = 'Membership purchase at the price of: $'.number_format($data->checkoutItemPrice, 2, ',', '.');
  AddPerPayment($data->checkoutItemPrice, 2, 'membership_purchase', $remarks, $data->bd249a12f);

}




//This function Adds Loan Bonds to users
function AddLoanBondforUser($data){

  include_once '../../../controller/mailer/class.Mail.php';
  include_once '../../../El_Lugar_API/objects/per_loan_bond.php';

  // get database connection
  $database = new Database();
  $db = $database->getConnection();

  $per_loan_bond = new Per_Loan_Bond($db);


  // set per_loan_bond property values
  $per_loan_bond->id_person = $_SESSION['person']['id_person'];
  $per_loan_bond->id_loan_bond_type = $data->tscReference;
  $per_loan_bond->date_purchased = date('Y-m-d H:i:s');
  $per_loan_bond->active = 1;


  for ($i=0; $i < $data->tscQty; $i++) {
    $per_loan_bond->create();
  }


  //add the payment
  $remarks = $data->tscQty.' Loan Bonds purchased at the price of: $'.number_format($data->checkoutItemPrice, 2, ',', '.').' each';
  AddPerPayment($data->checkoutItemPrice, 3, 'loan_bond_purchase', $remarks, $data->bd249a12f);


  //Notify the user that the purchase was succesful

  // $order = [
  //   "name"      => $_SESSION['person']['name'],
  //   "lastname"  => $_SESSION['person']['lastname'],
  // ];
  //
  //
  // $Mail = new Mail();
  // $Mail->LoanBondPurchased();

}



function InvestmentPayment($data){
  //get the person_share reference
  $id_per_share = $data->tscReference;
  $amount = $data->checkoutItemPrice;
  $remarks = 'Paid from the Application';

  //add the payment
  AddPerPayment($amount, 1, $id_per_share, $remarks, $data->bd249a12f);
}




function AddPerPayment($amount, $type, $reference, $remarks, $method){

    //get the request data initialized inside the function
    include_once '../../../El_Lugar_API/objects/per_payment.php';

    $database = new Database();
    $db = $database->getConnection();
    $per_payment = new Per_Payment($db);


    $per_payment->id_person = $_SESSION['person']['id_person'];
    $per_payment->id_payment_type = $type;
    $per_payment->id_payment_method = $method;
    $per_payment->reference = $reference;
    $per_payment->amount = $amount;
    $per_payment->date = date('Y-m-d H:i:s');
    $per_payment->remarks = $remarks;
    $per_payment->active = 1;



 	  $per_payment->create();

}



?>
