<?php

$data = json_decode(file_get_contents("php://input"));
//Validates that the execute charge is not set.
if (!empty($data->executeCharge)) {

    session_start();
    $card = [
      'num' => $data->executeCharge->cardNum,
      'month' => $data->executeCharge->cardMonth,
      'year' => $data->executeCharge->cardYear,
      'cvc' => $data->executeCharge->cardCvc
    ];
    executeCharge($card);


}else{ // If the card is not being charged... prepare the information.

  // require('controller/stripe/StripeClass.php');
  // $StrpClass = new StripeClass();
  $_SESSION['authorize'] = false;
  $_SESSION['checkout'] = json_decode($checkoutArr);


}





// Function to charge the credit card in Stripe
function executeCharge($card){

  include('stripe/stripe/init.php');
  include('stripe/StripeClass.php');
  $Stripe = new StripeClass(false);

  $cust = $_SESSION['person'];

  try {

    //charge the Customer
    $charge = $Stripe->createcharge(

      //Attributes required
      $cust['name'].' '.$cust['lastname'], //Customer Name
      $cust['main_email'], //Customer Email
      $card['num'], //Card Number
      $card['month'], //Card Expiration Month
      $card['year'], //Card Expiration Year
      $card['cvc'], // Card CVC
      $_SESSION['checkout']->data->checkoutAmount*100, // Transaction Amount
      $_SESSION['checkout']->data->checkoutDescription, // Product Description
      'usd' // Currency

    );

    if ($charge->captured) {
      $_SESSION['authorize'] = true;
    }

    echo json_encode( [ "status" => true, 'res' => 'Processing' ]);


  } catch (\Exception $e) {
    //$text = $e->error->message;
    //echo json_encode( [ "status" => false, 'res' => $text ]);
    echo '{ "status":false, "res":"'.$e.'" }';
  }




  /**
  highlight_string("<?php\n\$data =\n" . var_export($charge, true) . ";\n?>");
  **/


}


?>
