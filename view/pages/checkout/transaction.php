<?php

  $liveEnv = false;

  $paypalID = '';
  $paypalID = ($liveEnv)
  ? 'AdfoQtPEtQv27PsYCcWoOAK8sdKG5Jv8BnVB86upipYHZk95ovAXT9Vl2GEcQoeSB77Z8rm0FLEJppCR'
  : 'AaIgteV9sthHRJ1_g06pYgtMorqKBSVJEjP_P0XH9LX7qpEiHTuJ1R3YdwmFyXKxL8cxAd_YloGDglwM';

?>
<?php include('tscTypes.php'); //process and update of values. ?>



<!-- Include PAYPAL Processing-->
<script
data-page-type="checkout"
src="https://www.paypal.com/sdk/js?client-id=<?=$paypalID?>&disable-funding=credit,card">
</script>

<!-- Include Stripe Processing -->
<?php include('tscStripe.php'); ?>

<div class="jumbotron">
  <a href="?p=<?=$btnRefPage?>" class="btn btn-outline-dark">Go Back</a>
  <h2 class="text-center">Checkout</h2>
  <hr>
  <div class="row">
    <div class="col-sm-12 col-md-10 col-lg-8 offset-sm-0 offset-md-1 offset-lg-2">
      <div class="card">
        <div class="card-header">
          Verify your checkout information.
        </div>
        <div class="card-body">
          <table class="table table-bordered">

            <tr>
              <td>Item Name:</td>
              <th> <?= $checkoutitemName ?> </th>
            </tr>
            <tr>
              <td>Item Description:</td>
              <th> <?= $checkoutDescription ?> </th>
            </tr>
            <tr>
              <td>Quantity:</td>
              <th> <?= $checkoutItemQty ?><?= ( ( $checkoutItemQty == 1) ? ' item' : ' items') ?></th>
            </tr>
            <tr>
              <td>Item Price:</td>
              <th> $ <?= number_format($checkoutItemPrice, 2, '.', ','); ?> </th>
            </tr>
            <tr>
              <td>Total to pay:</td>
              <th> $ <?= number_format($checkoutAmount, 2, '.', ','); ?> </th>
            </tr>


          </table>

          <div class="row">
            <div class="col-md-12">
              <div class="alert alert-warning">
                Selecting Paypal will add a <strong> +5% additional fee ( $ <?= number_format($checkoutAmount * 0.05, 2, '.', ','); ?> ) </strong>
              </div>
            </div>


            <?php include('gateways.php') ?>


          <!-- END OF PAYMENT METHODS -->
          </div>


          <!-- END OF CHECKOUT CARD -->
        </div>

      </div>
    </div>



  </div>


</div>
