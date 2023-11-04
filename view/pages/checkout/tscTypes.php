<?php


require_once('El_Lugar_API/config/database.php');
$database = new Database();
$db = $database->getConnection();


//split transaction request.
$data = explode('/', $_GET['tsc']);

if (sizeof($data) < 4
|| empty($data[0])
|| empty($data[1])
|| empty($data[2])
|| empty($data[3])) {
  echo 'Missing or Corrupted Parameters';
  die();
}

//transaction information for database update.
$tscType = (is_int(intval($data[0]))) ? $data[0] : 'no_type_defined';
$tscReference = $data[1];
$tscQty = ( $data[2] && is_int(intval($data[2])) ) ? $data[2] : 1;
$btnRefPage = $data[3];


if ($tscType == 'no_type_defined') {
  echo 'Missing or Corrupted Parameters';
  die();
}


//checkout information for payment process.
$checkoutitemName = '';
$checkoutItemPrice = null;
$checkoutItemQty = $tscQty;
$checkoutAmount = null;
$checkoutDescription = 'El Lugar Cockpit - Purchase.';




// Tickets
if ($tscType == 1) {

  // code...
  include_once 'El_Lugar_API/objects/cat_ticket.php';

  // prepare cat_ticket object
  $cat_ticket = new Cat_Ticket($db);
  $cat_ticket->id_ticket_type = $tscReference;

  $cat_ticket->readOne();

  $checkoutitemName = html_entity_decode($cat_ticket->ticket);
  $checkoutDescription = html_entity_decode($cat_ticket->description);
  $checkoutItemPrice = $cat_ticket->price;

}



// Membership
if ($tscType == 2) {


  $tscQty = 1;
  // code...
  include_once 'El_Lugar_API/objects/membership.php';

  // prepare cat_ticket object
  $membership = new Membership($db);
  $membership->id_membership = $tscReference;

  $membership->readOne();

  $checkoutitemName = html_entity_decode($membership->name);
  $checkoutDescription = html_entity_decode($membership->description);
  $checkoutItemPrice = $membership->price;

}


// Loan Bond
if ($tscType == 3) {
  // code...
  include_once 'El_Lugar_API/objects/loan_bond_type.php';

  // prepare cat_ticket object
  $loan_bond_type = new Loan_Bond_Type($db);
  $loan_bond_type->id_loan_bond_type = $tscReference;

  $loan_bond_type->readOne();

  $checkoutitemName = html_entity_decode($loan_bond_type->type);
  $checkoutDescription = 'Purchase of El Lugar Loan Bond: '.html_entity_decode($loan_bond_type->type);
  $checkoutItemPrice = $loan_bond_type->price;

}



// Payment for investment
if ($tscType == 4) {
  // code...
  $checkoutitemName = 'Share Payment';
  $checkoutDescription = 'This will add a payment to your share with the ID of: '.$tscReference;
  $checkoutItemPrice = $data[2]; //For this option, the data index 2 is the amount.
  $checkoutItemQty = 1;
  $tscQty = 1; //set the quantity of payments = 1;

}


//Trim checkout description if needed;
$checkoutDescription = mb_strcut($checkoutDescription, 0, 120).'...';

//After all the values have been set to properly represent the correct transaction
$checkoutAmount = $checkoutItemPrice * $checkoutItemQty;


// SET THE CHECKOUT ARRAY
$checkoutArr = json_encode([
  "data" => [
    'tscReference' => $tscReference,
    'tscType' => $tscType,
    'tscQty' => $checkoutItemQty,
    'checkoutItemPrice' => $checkoutItemPrice,
    'checkoutAmount' => $checkoutAmount,
    'checkoutitemName' => $checkoutitemName,
    'checkoutDescription' => $checkoutDescription
  ]
]);



?>
