//Initialize the JS for the page.
InitForms();

function InitForms(){
    // Set the triggers for the form
    setTriggers();
}

function setTriggers(){
  trigger('#btn_log_in_now', 'click', function(){
    const const_login = register_login_validation();
    console.log(const_login);
    if(const_login){
      request( '<?=PATH_API?>person/attemptLogin.php', function(r){
        if(r.status){
          Fnon.Hint.Success( 'Login successful, redirecting...' , {position:'center-center'});
          setTimeout(function(){
            location.replace('?p=profile');
          }, 2000)
        }else{
          Fnon.Hint.Danger(r.msg, {position:'center-center'});
        }
      }, { data:const_login})
    }

  });


}



function register_login_validation(){


 const remove_class = findall('.form-control');
 for (let index = 0; index < remove_class.length; index++) {
   const elem = remove_class[index];
   elem.classList.remove('is-invalid');
   elem.classList.remove('border');
   elem.classList.remove('border-danger');
 }

   login_obj = {
     main_email:findone('#id_log_email').value,
     login_password:findone('#log_password').value,
   }

   let log_error = [];

   let validation = [];

      if (login_obj.main_email == "" || !validateEmail(login_obj.main_email)) {
        log_error.push('#id_log_email');
      }
      if (login_obj.login_password == "") {
        log_error.push('#log_password');
      }
      if (log_error.length > 0) {
        for (var i = 0; i < log_error.length; i++) {
          const elem = findone(log_error[i]);
          elem.classList.add('is-invalid');
          elem.classList.add('border');
          elem.classList.add('border-danger');
        }
        Fnon.Hint.Danger('Please check your inputs.', {position:'center-center'});
        return false
      }
   return login_obj;
}
