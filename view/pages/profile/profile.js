// Load the entrypoint of the page script
init();



function init() {
  mostrarDatosPersona();
  setTriggers();
}





function setTriggers(){
  trigger('#save_profile','click', updateProfile);
}












function mostrarDatosPersona() {

    findone("#imp_name").value = G__SESSION.name;
    findone("#imp_lastname").value = G__SESSION.lastname;
    findone("#imp_email").value = G__SESSION.main_email;


    let status = '';
    switch (G__SESSION.member) {
      case "1":
        status = '<h5><span class="badge badge-success">Activated!</span></h5>';
      break;
      case "3":
        status = '<h5><span class="badge badge-warning text-dark">Pending activation...</span></h5>';
      break;
      case "0":
        status = '<h5><span class="badge badge-danger">Membership not Activated!</span></h5>';
      break;
    };

    findone("#membership_status-label").innerHTML = status;


    let active = '';
    switch (G__SESSION.active) {
      case "1":
        active = '<h5><span class="badge badge-success">Activated!</span></h5>';
      break;
      case "0":
        active = '<h5><span class="badge badge-danger">Membership not Activated!</span></h5>';
      break;
    };
    findone("#account_status-label").innerHTML = active;

}








//------------------Update al perfil------------------
function registerProfileUpdate(){
  let profile_error = [];
  let validation = [];
  const remove_class = findall('.form-control');


  // Quita las clases de error.
  remove_class.forEach(elem => {
    elem.classList.remove('is-invalid');
    elem.classList.remove('border');
    elem.classList.remove('border-danger');
  })


  // Crea la estructura del JSON
  let profile_obj = {
    id_person:G__SESSION.id_person,
    name:findone('#imp_name').value,
    lastname:findone('#imp_lastname').value,
    main_email:findone('#imp_email').value
  }


  // Valida los inputs necesarios
  if (profile_obj.name == "") {
    profile_error.push('#imp_name');
  }
  if (profile_obj.lastname == "") {
    profile_error.push('#imp_lastname');
  }
  if (profile_obj.main_email == "" || !validateEmail(profile_obj.main_email)) {
    profile_error.push('#imp_email');
  }


  //valida si hay errores.
  if (profile_error.length > 0) {

    profile_error.forEach(elem => {
      console.log(elem)
      elem = findone(elem);
      elem.classList.add('is-invalid');
      elem.classList.add('border');
      elem.classList.add('border-danger');
    })

    Fnon.Hint.Danger('Please check your inputs.', {position:'center-center'});
    return false
  }


  //retorna el objeto
  return profile_obj;
}
















// Funciones que ejecuten request se ponen al final.



function updateProfile(){
  const CONST_PROFILE = registerProfileUpdate();
  if(CONST_PROFILE){
    request( '<?=PATH_API?>person/update.php', function(r){
      if(r.code){
        Fnon.Hint.Success(r.message, {position:'center-center'});
        return;
      }
      Fnon.Hint.Danger('Error!', {position:'center-center'});
    }, { data:CONST_PROFILE} )
  }
}
