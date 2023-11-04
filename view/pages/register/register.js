InitForms();


function InitForms(){

  // Set the triggers for the form
  setTriggers();

}


function setTriggers(){
  trigger('#confirm_register', 'click', function(){
    const respuesta = register_form_validation();
    if(respuesta){
      createAccount(respuesta);
    }
  });
}


function register_form_validation() {

  const remove_class = findall('.form-control');
  for (let index = 0; index < remove_class.length; index++) {
    const elem = remove_class[index];
    elem.classList.remove('is-invalid');
    elem.classList.remove('border');
    elem.classList.remove('border-danger');
  }

  obj = {
    name:findone('#p_name').value, /*0*/
    lastname:findone('#p_lastname').value, /*1*/
    main_email:findone('#p_email').value, /*2*/
    login_password:findone('#p_password').value, /*3*/
    confirm_your_password:findone('#p_confirm_password').value, /*4*/
    id_person_type:1,
    fullname:function(){
      return this.name + ' ' + this.lastname;
    }
  };


  let error = [];


  if (obj.name == '') {
    error.push('#p_name');
  }
  if(obj.lastname == ''){
    error.push('#p_lastname');
  }
  if(obj.main_email == '' || !validateEmail(obj.main_email) ) {
    error.push('#p_email');
  }
  if(obj.login_password == '') {
    error.push('#p_password');
  }
  if(obj.confirm_your_password == '' || obj.confirm_your_password != obj.login_password) {
    error.push('#p_confirm_password');
  }


  if(error.length > 0){
    for (let index = 0; index < error.length; index++) {
      const elem = findone(error[index]);
      elem.classList.add('is-invalid');
      elem.classList.add('border');
      elem.classList.add('border-danger');
    }
    Fnon.Hint.Danger('Please check your inputs.');
    return false;
  }

  return obj;

}









function createAccount(persona){
  request(`<?=PATH_API?>person/validateEmail.php?main_email=${persona.main_email}`, function(r){
   if (!r.code) {
     const elem = findone('#p_email');
     elem.classList.add('is-invalid');
     elem.classList.add('border');
     elem.classList.add('border-danger');
     Fnon.Hint.Danger(`The email: ${persona.main_email}, already exists, please try another one.`, {position:'center-center'})
     return false;
   }
   createAccountFinalize(persona)
  })
}


function createAccountFinalize(persona){
  request( '<?=PATH_API?>person/create.php', function(r){
    if(r.code){
      Fnon.Hint.Success('Registration successful, redirecting...');
      setTimeout( function(){
        location.replace('?p=login')
      }, 1500 )
    }else{
      Fnon.Hint.Danger('Error creating your account');
    }
  }, { data:persona })
}
