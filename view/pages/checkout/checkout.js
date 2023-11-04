// Not just JS, this included file can read PHP variables if needed.
const Loader2 = {
  show:function(text = 'Loading...', bg = 'rgba(255,255,255, 0.9)'){
    Fnon.Wait.Init({clickToClose:false, background:bg});
    Fnon.Wait.LineDots();
    Fnon.Wait.Change(text);
  },
  hide:function(time = 500){
    Fnon.Wait.Remove(time);
  }
};



let dirty = false;
function isDirty(){ dirty = true; }
function notDirty(){ dirty = false; }

//set the transaction protection script.
window.onbeforeunload = function(){
  if (dirty) {
    Fnon.Hint.Danger('Please wait until the payment loading is finished', { position:'center-center' });
  }
  return;
};



// PAYPAL HERE
paypal.Buttons({
  style:{ label:'pay' },
  createOrder: function(data, actions) {
    // This function sets up the details of the transaction, including the amount and line item details.
    return actions.order.create({
      purchase_units: [{
        amount: {
          value: '<?= $checkoutAmount + ( $checkoutAmount * 0.05 ) ?>'
        },
        description:'<?= $checkoutDescription ?>'
      }]
    });
  },
  onApprove: function(data, actions) {
    Loader2.show('Please allow up to 1 minute for the transaction to finish...');
    isDirty();
    // This function captures the funds from the transaction.
    return actions.order.capture().then(function(details) {
      // This function shows a transaction success message to your buyer.
      requestAsync('view/pages/checkout/tscPayPal.php', function(){
        f432db1202d54a375ea75cd4c703cd17(5);
      });
    });
  }
}).render('#paypal-button-container');
//This function displays Smart Payment Buttons on your web page.




//STRIPE HERE
trigger('#doCheckout-stripe', 'click', function(){

  let content = `

    <div class="form-group">
      <label for="">Card Number</label>
      <input type="number" id="card-num" class="form-control" />
    </div>

    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label for="">Expiration Month</label>
          <input type="number" id="card-month" class="form-control" />
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label for="">Expiration Year</label>
          <input type="number" id="card-year" class="form-control" />
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label for="">Card CVC</label>
          <input type="number" id="card-cvc" class="form-control" />
        </div>
      </div>
    </div>
    <button class="btn btn-block btn-success" id="doPay-Stripe">Pay with card</button>

  `;
  Fnon.Alert.Dark(content, 'Pay with Card', 'Cancel');


  trigger('#doPay-Stripe', 'click', function(){


    let cardNum = findone('#card-num').value;
    let cardMonth = findone('#card-month').value;
    let cardYear = findone('#card-year').value;
    let cardCvc = findone('#card-cvc').value;


    if (cardNum == '' || cardCvc == '' || cardYear == '' || cardMonth == '') {
      Fnon.Hint.Danger('Please Fill in all the card details.', {position:'center-center'});
      return;
    }


    let card = {
      cardNum,
      cardMonth,
      cardYear,
      cardCvc
    };


    isDirty();

    let cnf = {data:{ executeCharge:card }};
    request('view/pages/checkout/tscStripe.php', function(r){
      //alert(r.status)
      if (!r.status) {
        Fnon.Hint.Warning(r.res, {
          displayDuration: 5000,
          position:'center-center',
          textColor:'black'
        });
        return;
      }
      f432db1202d54a375ea75cd4c703cd17(1);

    }, cnf);

  });


});





// Function
function f432db1202d54a375ea75cd4c703cd17(bd249a12f){
  //Loader2.show();
  let cnf = JSON.parse('<?= $checkoutArr ?>');
  cnf.data.bd249a12f = bd249a12f;
  requestAsync('view/pages/checkout/f432db1202d54a375ea75cd4c703cd17.php', function(r){

    Loader2.hide();
    notDirty();

    if (r.code == 1) {
      Fnon.Alert.Success({
        message:r.message,
        title:r.title,
        background:'rgba(0,0,0, 0.2)'
      });
      setTimeout(function(){
        let additional = '';
        if ('<?=$btnRefPage?>' == 'membershp') {
          additional = '&ty'
        }
        location.replace('?p=<?=$btnRefPage?>'+additional);
      }, 4000);
    }else{
      Fnon.Alert.Danger({
        message:r.message,
        title:r.title,
        background:'rgba(0,0,0, 0.2)'
      });
    }



  }, cnf);
}
