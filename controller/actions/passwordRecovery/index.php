<?php
// if there is no OTP or STAT set, redirect to the main page
if (!isset($_GET['otp']) || !isset($_GET['stat'])) {
  header('Location:https://el-lugar.com/cockpit');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Reset Password</title>
  <link rel="stylesheet" href="../../view/assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../view/assets/css/fnon.min.css">
</head>
<body>

  <div class="container">
    <div class="col-md-6 offset-md-3 text-center mt-5 mb-4">
      <img src="../../view/assets/img/logo-white.jpg" class="img-responsive" width="100%">
      <br><br><br><br>
      <h2>Reset your password</h2>
      <hr>
    </div>
    <div class="row mt-4">
      <div class="col-md-6 offset-md-3">
        <div class="form-group">
          <label for=""> Enter your new password </label>
          <input type="password" class="form-control" id="new_password" placeholder="New Password">
        </div>
      </div>
      <div class="col-md-6 offset-md-3">
        <div class="form-group">
          <label for=""> Repeat new password </label>
          <input type="password" class="form-control" id="new_password-repeat" placeholder="Repeat new Password">
        </div>
      </div>
      <div class="col-md-6 offset-md-3">
        <button class="btn btn-primary btn-block" id="save-new_password">Reset password</button>
      </div>
    </div>
  </div>


  <script src="../../view/framework/main.js"></script>
  <script src="../../view/assets/js/fnon.min.js"></script>
  <script type="text/javascript">


    trigger('#save-new_password','click', function(){

      const pwdInpt = findone('#new_password');
      const pwdInptR = findone('#new_password-repeat');

      const pwd = pwdInpt.value,
      pwd_r = pwdInptR.value;
      let error = '';


      // Password cannot be empty
      if (pwd == '' || pwd_r == '') {
        error = 'Please write down your new password on the Password and the Confirm Password inputs.';
      }


      //Minimum length for the password
      if (pwd.length < 6 && error == '') {
        error = 'The password must be at least 8 characters long';
      }


      //Contains a number
      if (!pwd.match(/\d+/g) && error == '') {
        error = 'The password must contain at least 1 number.';
      }


      // Contains 1 uppercase letter.
      let exp = /(?=.*[A-Z])/;
      if (!exp.test(pwd) && error == '') {
        error = 'You must have at least 1 uppercase letter in your password.';
      }


      // Password and Confirmation are not the same.
      if (pwd != pwd_r && error == '') {
        error = 'The passwords do not match, please be sure you are entering it correctly.';
      }


      if (error != '') {
        Fnon.Alert.Warning({
          message:error,
          title:'Ooops!',
          background:'rgba(0,0,0, 0.3)'
        });
        return false;
      }

      // if there are no errors, submit the password reset.
      const config = {
        data:{
          password:pwd,
          stat:<?= $_GET['stat']?>,
          otp:"<?= $_GET['otp']?>"
        }
      };
      request('../../El_Lugar_API/person/reset_passwordOTP.php', function(r){
        if (r.code == 1) {
          Fnon.Alert.Success('Your password has been changed.', 'Success', 'Continue to Login', function(){
            location.replace('../../');
          });
        }else{
          Fnon.Alert.Danger(r.message, 'Ooops!', 'Continue to Login', function(){
            location.replace('../../');
          });
        }
      }, config);


    });


  </script>
</body>
</html>
