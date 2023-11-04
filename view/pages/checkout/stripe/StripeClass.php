<?php
class StripeClass {

  private $stripe;

  //TESTING KEY
  private $testClientSec = 'sk_test_zoF4KPLIHO2YkaiggoDI4vuL';
  private $liveClientSec = 'sk_live_Fg1vRRBXYROgMr9HNPCmHbRZ';

  // ENV to use.
  private $clientSec = '';


  function __construct( $env = false ) {

      $this->clientSec = ( $env ) ? $this->liveClientSec : $this->testClientSec;
      $this->stripe = new \Stripe\StripeClient(
        $this->clientSec
      );
  }

  public function addplan($amount,$currency,$freq_intv,$product){
    $Plan=$this->stripe->plans->create([
      'amount' => $amount,
      'currency' => $currency,
      'interval' => $freq_intv,
      'product' => $product,
    ]);

    //print_r($vret);
    return $Plan->id;
  }

  public function addprod($productname){
    $Product=$this->stripe->products->create([
      'name' => $productname,
      'type' => 'service'
    ]);

    //print_r($vret);
    return $Product->id;
  }
  public function addprodsku($vProductId, $vProductPrice, $cur){
      $SKU=$this->stripe->skus->create([
        'attributes' => [],
          'price' => $vProductPrice,
          'currency' => $cur,
          'inventory' => [
            'type'=>'infinite'
          ],
          'product' => $vProductId,
      ]);
    return $SKU->id;

  }
  public function getcustomer($custid)
  {
    $Customer=  $this->stripe->customers->retrieve(
      $custid,
      []
    );
    return $Customer;
  }


  public function createcharge($custname,$email,$card_num,$exp_month,$exp_year,$cvc,$amount,$product,$cur){
    $token = $this->stripe->tokens->create([
      "card" => [
        "number" => $card_num,
        "exp_month" => $exp_month,
        "exp_year" => $exp_year,
        "cvc" => $cvc,
      ],
    ]);
    $tokenId= $token->id;
    /*$Customer=$this->stripe->customers->create([
        'description' => $custname,
        'email' => $email,
        "source" => $tokenId,
    ]);*/
    $charge = $this->stripe->charges->create([
      'amount' => $amount,
      'currency' => $cur,
      'source' => $tokenId,
      'description' => $product,
    ]);
    return $charge;
  }

  // CREATE CUSTOMER
  public function createcust($custname,$email,$card_num='',$exp_month='',$exp_year='',$cvc='',$custid=''){
    $tokenId='';
    if(!empty($custid))
    {
      try{
        $Customer=  $this->stripe->customers->retrieve(
          $custid,
          []
        );
        if($Customer!==null)
        {
          $custid=$Customer->id;
        }
        else {
          $custid='';
        }
      }
      catch(Exception $e)
      {
        $custid='';
      }
    }
    if(empty($custid))
    {
      if(!empty($card_num) and !empty($exp_month) and !empty($exp_year) and !empty($cvc))
      {
        $token = $this->stripe->tokens->create(["card" => [
                                                "number" => $card_num,
                                                "exp_month" => $exp_month,
                                                "exp_year" => $exp_year,
                                                "cvc" => $cvc,
                                              ],
                                            ]);
        $tokenId= $token->id;
        $Customer=$this->stripe->customers->create([
            'description' => $custname,
            'email' => $email,
            "source" => $tokenId,
        ]);
      }
      else {
        $Customer=$this->stripe->customers->create([
            'description' => $custname,
            'email' => $email
        ]);
      }
      $custid=$Customer->id;
    }


    //print_r($vret);
    return $custid;
  }

  public function addsubscription($vPlanId,$custid)
  {
    $vSubscription=$this->stripe->subscriptions->create([
        'customer' => $custid,
        'items' => [
          ['price' => $vPlanId],
        ],
      ]);
      return $vSubscription;
  }

  public function createpaymenthod($type,$card_num,$exp_month,$exp_year,$cvc)
  {
    $vPayMethod=$this->stripe->paymentMethods->create([
        'type' => $type,
        'card' => [
          'number' => $card_num,
          'exp_month' => $exp_month,
          'exp_year' => $exp_year,
          'cvc' => $cvc,
        ],
      ]);
      return $vPayMethod;
  }



  public function attachcustpaymethod($vPayMethodId,$custid)
  {
      $vPayatt=$this->stripe->paymentMethods->attach(
        $vPayMethodId,
        ['customer' => $custid]
      );
      return $vPayatt;
  }



  public function createsource($type,$currency,$owner)
  {
    $vSource=$this->stripe->sources->create([
          "type" =>$type,
          "currency" => $currency,
          "owner" => [
            "email" => $owner
          ]
        ]);
      return $vSource;
  }



  public function attachcustsource($vSourceId,$custid)
  {
      $vCustSource=$this->stripe->customers->createSource(
        $custid,
        ['source' => $vSourceId]
      );
      return $vCustSource;
  }




  public function getallevents()
  {
    $events=$this->stripe->events->all();
    return $events;

  }



  public function createOrder($custemail='',$custstripeid='',$cur,$items=array(),$card_num,$exp_month,$exp_year,$cvc)
  {
    $vItemForOrder=array();
    foreach ($items as $item)
    {
      $vProductId=$item["prdid"];
      $vProductName=$item["name"];
      $vProductSKU=$item["sku"];
      $vProductPrice=$item["price"];
      $qty=$item['qty'];
      if(!empty($vProductId))
      {
        try{
          $Product=$this->stripe->products->retrieve(
            $vProductId,
            []
          );
          if($Product!==null)
          {
            $vProductId=$Product->id;
          }
          else {
            $vProductId='';
          }
        }
        catch(Exception $e)
        {
          $vProductId='';
        }
      }
      if(empty($vProductId)) {
        $Product=$this->stripe->products->create([
          'name' => $vProductName,
          'type'=> 'good'
        ]);
        $vProductId=$Product->id;
      }

      if(!empty($vProductId)) {
        if(!empty($vProductSKU))
        {
          try{
            $SKU=$this->stripe->skus->retrieve(
              $vProductSKU,
              []
            );
            if($SKU!==null)
            {
              $vProductSKU=$SKU->id;
            }
            else {
              $vProductSKU='';
            }
          }
          catch(Exception $e)
          {
            $vProductSKU='';
          }
        }
        if(empty($vProductSKU)) {
          $SKU=$this->stripe->skus->create([
            'attributes' => [],
              'price' => $vProductPrice,
              'currency' => $cur,
              'inventory' => [
                'type'=>'finite',
                'quantity'=>1000
              ],
              'product' => $vProductId,
          ]);
          $vProductSKU=$SKU->id;
        }
      }

      array_push($vItemForOrder,array("type"=>"sku","parent"=>$vProductSKU,'quantity' => $qty));

    }
    //print_r($vItemForOrder);exit;
      $order=  $this->stripe->orders->create([
      'currency' => $cur,
      'email' => $custemail,
      'items' => [
        $vItemForOrder
      ],
      'shipping' => [ 'name' => '',
            'address' => [
              'line1' => '',
              'city' => '',
              'state' => '',
              'country' => '',
              'postal_code' => '',
            ],]
    ]);
    //$order->id;
    if($order!==null){
            $token = $this->stripe->tokens->create(["card" => [
                                                    "number" => $card_num,
                                                    "exp_month" => $exp_month,
                                                    "exp_year" => $exp_year,
                                                    "cvc" => $cvc,
                                                  ],
                                                ]);
            $tokenId= $token->id;

            $orderpay=$this->stripe->orders->pay(
                  $order->id,
                  ['source' => $tokenId]
              );
                return $orderpay;
      }
    return $order;
  }

}


 ?>
