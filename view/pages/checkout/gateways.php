<!-- Paypal -->
<div class="col-lg-4 col-md-6 col-sm-12 mb-4">
  <div class="card">
    <div class="card-header">
      Checkout with paypal
    </div>
    <div class="card-body" id="paypal-button-container">
      <!-- <button class="btn btn-warning btn-block">Pay with Paypal</button> -->
    </div>
  </div>
</div>



<!-- Stripe -->
<div class="col-lg-4 col-md-6 col-sm-12 mb-4">
  <div class="card">
    <div class="card-header">
      Checkout with Credit/Debit Card
    </div>
    <div class="card-body">
      <button class="btn btn-success btn-block btn-lg" id="doCheckout-stripe">Pay with Card</button>
    </div>
  </div>
</div>



<!-- Alipay -->
<!-- <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
  <div class="card">
    <div class="card-header">
      Pay with Alipay (will appear as donation)
    </div>
    <div class="card-body">
      <button class="btn btn-info btn-block btn-lg"> Pay via Alipay </button>
    </div>
  </div>
</div> -->


<?php if ($btnRefPage == 'loan_bond'): ?>
  <!-- Wire Transfer -->
  <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
    <div class="card">
      <div class="card-header">
        Pay wire Transfer
      </div>
      <div class="card-body">
        <button class="btn btn-primary btn-block btn-lg"> Pay via Bank/Wire transfer </button>
      </div>
    </div>
  </div>
<?php endif; ?>
