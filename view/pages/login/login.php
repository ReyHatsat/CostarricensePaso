<div class="container mt-4 py-5">

  <?php if (isset($_GET['pending'])): ?>
    <div class="alert alert-warning bg-warning text-dark">
      Please log in first.
    </div>
  <?php endif; ?>

  <div class="row justify-content-center">

    <div class="col-md-6 text-center">

      <h3>Welcome once again!</h3>

      <div class="form-content">

        <div class="form-group">
          <label for="">Enter your email</label>
          <input autocomplete="on" class="form-control" type="email" id="id_log_email">
        </div>

        <div class="form-group">
          <label for="">Enter your password</label>
          <input autocomplete="on" class="form-control" type="password" id="log_password">
        </div>

        <button class="btn-lg btn-block btn btn-dark" id="btn_log_in_now"> Log in now </button>
        <button class="btn btn-outline-danger"> Forgot password? </button>


      </div>


    </div>

  </div>

</div>
