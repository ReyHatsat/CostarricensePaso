<?php
require('stripe/init.php');
$payload = @file_get_contents('php://input');
$event = null;
$from_email_address = "Test <test@gmail.com>";
$emailtosend='test@t.com';
try {
    $event = \Stripe\Event::constructFrom(
        json_decode($payload, true)
    );
    $data_text='';
    foreach ($event->jsonSerialize() as $key => $value) {
        if(!is_array($value))
        {
          $data_text .= $key . " = " . $value . "\r\n";
        }
    }

    mail($emailtosend, "IPN call", "IPN call: ".$data_text, "From: " . $from_email_address);
} catch(\UnexpectedValueException $e) {
    // Invalid payload
    http_response_code(400);
    exit();
}

// Handle the event
switch ($event->type) {
    case 'payment_intent.succeeded':
        $paymentIntent = $event->data->object;


        $data_text='';
        foreach ($paymentIntent->jsonSerialize() as $key => $value) {
            if(!is_array($value))
            {
              $data_text .= $key . " = " . $value . "\r\n";
            }
        }

        mail($emailtosend, "IPN call", "IPN call: ".$data_text, "From: " . $from_email_address);
        break;
    case 'order.payment_failed':
        $order = $event->data->object;


        $data_text='';
        foreach ($order->jsonSerialize() as $key => $value) {
            if(!is_array($value))
            {
              $data_text .= $key . " = " . $value . "\r\n";
            }
        }

        mail($emailtosend, "IPN call", "IPN call: ".$data_text, "From: " . $from_email_address);
        break;
    case 'order.payment_succeeded':
        $order = $event->data->object;


        $data_text='';
        foreach ($order->jsonSerialize() as $key => $value) {
            if(!is_array($value))
            {
              $data_text .= $key . " = " . $value . "\r\n";
            }
        }

        mail($emailtosend, "IPN call", "IPN call: ".$data_text, "From: " . $from_email_address);
        break;
    case 'payout.failed':
        $payout = $event->data->object;


        $data_text='';
        foreach ($payout->jsonSerialize() as $key => $value) {
            if(!is_array($value))
            {
              $data_text .= $key . " = " . $value . "\r\n";
            }
        }

        mail($emailtosend, "IPN call", "IPN call: ".$data_text, "From: " . $from_email_address);
        break;
    case 'payment_intent.payment_failed':
        $paymentIntent = $event->data->object;


        $data_text='';
        foreach ($paymentIntent->jsonSerialize() as $key => $value) {
            if(!is_array($value))
            {
              $data_text .= $key . " = " . $value . "\r\n";
            }
        }

        mail($emailtosend, "IPN call", "IPN call: ".$data_text, "From: " . $from_email_address);
        break;
    case 'charge.failed':
        $charge = $event->data->object;

        
        $data_text='';
        foreach ($charge->jsonSerialize() as $key => $value) {
            if(!is_array($value))
            {
              $data_text .= $key . " = " . $value . "\r\n";
            }
        }

        mail($emailtosend, "IPN call", "IPN call: ".$data_text, "From: " . $from_email_address);
        break;
    case 'charge.succeeded':
        $paymentIntent = $event->data->object;


        $paymentIntent=$paymentIntent->jsonSerialize();
        $data_text='';
        foreach ($paymentIntent as $key => $value) {
            if(!is_array($value))
            {
              $data_text .= $key . " = " . $value . "\r\n";
            }
        }
        $balance_transaction=$paymentIntent['balance_transaction'];
        mail($emailtosend, "IPN call", "IPN call: ".$data_text, "From: " . $from_email_address);
        break;
    case 'checkout.session.async_payment_failed':
        $checkout = $event->data->object;
        $data_text='';
        foreach ($checkout->jsonSerialize() as $key => $value) {
            if(!is_array($value))
            {
              $data_text .= $key . " = " . $value . "\r\n";
            }
        }

        mail($emailtosend, "IPN call", "IPN call: ".$data_text, "From: " . $from_email_address);
        break;
    case 'checkout.session.async_payment_succeeded':
        $checkout = $event->data->object;


        $data_text='';
        foreach ($checkout->jsonSerialize() as $key => $value) {
            if(!is_array($value))
            {
              $data_text .= $key . " = " . $value . "\r\n";
            }
        }

        mail($emailtosend, "IPN call", "IPN call: ".$data_text, "From: " . $from_email_address);
        break;
    case 'customer.subscription.created':
        $subscription = $event->data->object;
        $subscription=$subscription->jsonSerialize();
          $data_text='';
          foreach ($subscription as $key => $value) {
              if(!is_array($value))
              {
                $data_text .= $key . " = " . $value . "\r\n";
              }
          }
          $payment_intent =$subscription['payment_intent'];
          $invoice = $subscription['invoice'];
          mail($emailtosend, "IPN call", "IPN call: ".$data_text, "From: " . $from_email_address);
        break;
    case 'customer.subscription.trial_will_end':
        $subscription = $event->data->object;
          $data_text='';
          foreach ($subscription->jsonSerialize() as $key => $value) {
              if(!is_array($value))
              {
                $data_text .= $key . " = " . $value . "\r\n";
              }
          }

          mail($emailtosend, "IPN call", "IPN call: ".$data_text, "From: " . $from_email_address);
        break;
    case 'customer.created':
        $customer = $event->data->object;
         $data_text='';
        $vCustomer = $customer->jsonSerialize();

        $data_text='';
        foreach ($vCustomer as $key => $value) {
            if(is_array($value))
            {
              foreach ($value as $key1 => $value1) {
                //$data_text .= $key1 . " = " . $value1 . "\r\n";
                if(is_array($value1))
                {
                  foreach ($value1 as $key2 => $value2) {
                    if(is_array($value2))
                    {
                      foreach ($value2 as $key3 => $value3) {

                        if(is_array($value3))
                        {
                          foreach ($value3 as $key4 => $value4) {
                            $data_text .= $key4 . " = " . $value4 . "\r\n";
                          }
                        }
                        else {
                          $data_text .= $key3 . " = " . $value3 . "\r\n";
                        }
                      }
                    }
                    else {
                      $data_text .= $key2 . " = " . $value2 . "\r\n";
                    }
                  }
                }
                else {
                  $data_text .= $key1 . " = " . $value1 . "\r\n";
                }
              }
            }
            else {
              $data_text .= $key . " = " . $value . "\r\n";
            }
        }

        mail($emailtosend, "Stripe IPN Custromer ", "IPN call: ".$data_text, "From: " . $from_email_address);


        break;
    // ... handle other event types
    default:
        // Unexpected event type
        http_response_code(200);
        exit();
}

http_response_code(200);

 ?>
