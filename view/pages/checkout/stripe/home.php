<?php

require('StripeClass.php');

$StrpClass=new StripeClass();


if(isset($_GET['prod']))
{
    $vProdId=$StrpClass->addprod($_GET['prod']);
    //store product id for membership
    echo $vProdId;
}

if(isset($_GET['prodId']))
{
    //amount must be multiply by 100.. for ex.. actual price is 1 then pass 100
    $vPlanId=$StrpClass->addplan(10000,'usd','year',$_GET['prodId']);
    //store plan id for membership
    echo $vPlanId;
}
if(isset($_GET['sku']) && isset($_GET['prodId']))
{
    //amount must be multiply by 100.. for ex.. actual price is 1 then pass 100
    $vSku=$StrpClass->addprodsku($_GET['prodId'],100,'usd',500,$attr);
    //store sku for product
    echo $vSku;
}
if(isset($_GET['cust']))
{
    //pass stripe's custid to avoid duplicate customers in Stripe account like cus_HQPBFdMH6l3bZ2
    $vCustId = $StrpClass->createcust('Test Cust3','test2@t.com','4242424242424242','6','2022','123','');
    //save $vCustId into db for cuetomer reference
    echo $vCustId;
}
//for single product purchase
if(isset($_GET['charge']))
{
  //amount must be multiply by 100.. for ex.. actual price is 1 then pass 100
    $vCharge=$StrpClass->createcharge('Test Cust4','vaishu.khurana@gmail.com','4242424242424242','6','2022','123',10000,'12 Year Subscription','usd');
    //save below details as reference
    $chargeid=$vCharge->id;
    $balance_transaction=$vCharge->balance_transaction;
    print_r($vCharge);
}
//create order into stripe
if(isset($_GET['Order']))
{
  //amount must be multiply by 100.. for ex.. actual price is 1 then pass 100
  //pass stripe's productid and Sku related to that product to avoide duplication of products and sku on stripe Account
  $artarr=array(array("name"=>'art1',"prdid"=>'prod_HS2OTWaUA5coRO','sku'=>'','price'=>200,'qty'=>5),array("name"=>'art2',"prdid"=>'prod_HS2OtCpTwuzAOd','sku'=>'','price'=>100,'qty'=>3));

  $vOrder=$StrpClass->createOrder('vaishu.khurana@gmail.com','','usd',$artarr,'4242424242424242','6','2022','123');
  //store this into db
  $vOrderId=$vOrder->id;//or_1Gt8K5EufoZrcCBmrpG9j5ie
}




if(isset($_GET['planid']) and isset($_GET['custid']))
{
  $vPlanId=$_GET['planid'];
  $vSub=$StrpClass->addsubscription($vPlanId,$_GET['custid']);
  //save this in db while creating subscription
  $invoice_id=$vSub->latest_invoice;
  print_r($vSub);

}
