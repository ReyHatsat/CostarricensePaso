// DEFINICION DE VARIABLES Y CONSTANTES GLOBALES
let g__users_table;
let g__personas = [];
let g__cat_person_type = [];
let g__person_status = [
  { status:'Active', active:1 },
  { status:'Pending Activation', active:3 },
  { status:'Inactive', active:0 }
];
let selected_person = {};


//Punto de entrada del script
init();




async function init() {
    let a = await cargarPersonas();
    let b = await cargarTiposPersona();
    setTriggers();
}






function setTriggers(){
    trigger('#btn-create-person', 'click', function(){
      createPersonEvent();
    });
}






function renderPersonas(personasObj){
  g__users_table = $('#tableusers').DataTable( {
      data: personasObj,
      responsive: true,
      destroy: true,
      columns: [
          { data: "id_person"},
          { data: "name" },
          { data: "lastname" },
          { data: "main_email" },
          {
            data: "id_person_type",
            render:function(data){
              return (parseInt(data))
                ? '<h5><span class="text-primary">Customer</span></h5>'
                : '<h5><span class="text-success">Admin</span></h5>';
            }
          },
          { data: "member",
            render:function(data){
              return (parseInt(data))
                ? '<h5 style="font-size:20px;"><span class="badge badge-success">Membership Active</span></h5>'
                : '<h5 style="font-size:20px;"><span class="badge badge-danger">No membership</span></h5>';
            }

          },
          { data: "active",
            render:function(data){
              switch (data) {
                case "1":
                  return '<h5 style="font-size:20px;"><span class="badge badge-success">Active</span></h5>';
                break;
                case "3":
                  return '<h5 style="font-size:20px;"><span class="badge badge-warning text-dark">Pending Activation</span></h5>';
                break;
                case "0":
                  return '<h5 style="font-size:20px;"><span class="badge badge-danger">Inactive</span></h5>';
                break;
              };
            }
          },
          {
            data:'id_person',
            render:function(data, type, row){
              return`
                <button class="btn btn-primary btn-sm" onclick="editUser(${data})">
                  <i class="fas fa-pencil-alt"></i>
                </button>
                <button class="btn btn-info btn-sm d-none" onclick="editUser(${data})">
                  <i class="fas fa-eye"></i>
                </button>
              `
            }
          }
      ]
  });
}










// CREATE USER *****************************************************************
function createPersonEvent(){

  let content = getPersonContent(false, {});
  Fnon.Alert.Dark( content, 'Add Person', 'Close' );


  //set the event for the button to create the person.
  trigger('#submit_form', 'click', function(){
    createUser()
  });

}



function createUser(){
  const respuesta = register_form_validation();
  if(respuesta){
    executeCreatePersona(respuesta)
  }
}


function handleCreatePersonCallback(r){

  // Cerrar el modal
  fireEvent('.f__btn', 'click');

  // Mostrar mensaje
  let msg = (parseInt(r.code)) ? 'Person Created!' : r.document.errorInfo[2];
  let type = (parseInt(r.code)) ? 'Success' : 'Danger';
  Fnon.Hint[type](msg, {position:'center-center'});


  //recargar tabla de personas
  let personasObj = ( parseInt(r.code) ) ? r.document.records : false;
  if (personasObj) {
    g__personas = personasObj;
    renderPersonas(personasObj)
  }
}



// END CREATE USER *****************************************************************
















// UPDATE USER -----------------------------------------------------------------
function editUser(id){
  selected_person = g__personas.find( x => x.id_person == id);
  let content = getPersonContent(true, selected_person);
  Fnon.Alert.Dark( content, `Editing ${selected_person.name}`, 'Close' );

  trigger('#submit_form', 'click', function(){
    updateUserValidate()
  })

}



function updateUserValidate(){
  let person = register_form_validation(true);
  if (person) {
    executeUpdatePersona(person)
  }
}



function handleUpdatePersonCallback(r){
  //cerrar el modal
  fireEvent('.f__btn', 'click');

  //mostrar mensaje de error/exito
  let msg = ( parseInt(r.code) ) ? 'User updated succesfully' : 'Error updating the user';
  let type = ( parseInt(r.code) ) ? 'Success' : 'Danger';
  Fnon.Hint[type]( msg , { position:'center-center' });

  //recargar la tabla de ser necesario.
  let personasObj = ( parseInt(r.code) ) ? r.document : false;
  if (personasObj) {
    g__personas = personasObj;
    renderPersonas(personasObj)
  }
}
// END UPDATE USER -----------------------------------------------------------------















// FUNCIONES DE USOS VARIOS ****************************************************


//Valida los inputs y genera el JSON o retorna falso si hay errores.
function register_form_validation( edit = false ) {

  const remove_class = findall('.form-control');
  remove_class.forEach( elem => {
    elem.classList.remove('is-invalid');
    elem.classList.remove('border');
    elem.classList.remove('border-danger');
  });


  let password = (!edit) ? findone('#p_password').value : 'no_value';
  let personID = (selected_person) ? selected_person.id_person : false;

  obj = {
    id_person:personID,
    name:findone('#p_name').value,
    lastname:findone('#p_lastname').value,
    main_email:findone('#p_email').value,
    login_password:password,
    id_person_type:findone('#p_permissions').value,
    active:findone('#p_status').value
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

  if( !edit && g__personas.find( x => x.main_email == obj.main_email )  ){
    Fnon.Hint.Danger(
      `The email: ${obj.main_email}, is already registered, please use another one.`,
      {position:'center-center'}
    );
    error.push('#p_email');
  }

  if(obj.login_password == '') {
    error.push('#p_password');
  }


  if(error.length > 0){
    error.forEach( elem => {
      elem = findone(elem)
      elem.classList.add('is-invalid');
      elem.classList.add('border');
      elem.classList.add('border-danger');
    });



    Fnon.Hint.Danger('Please check your inputs.', {position:'center-center'});
    return false;
  }

  return obj;

}




// retorna el contenido HTML para mostrar en el modal.
function getPersonContent( setvals = false, data = {} ){

  let c = {
    name:'',
    lastname:'',
    main_email:'',
    password:'',
    id_person_type:1,
    status:1
  }

  let btn_text = 'Create Person';
  let password_content = `
    <div class="form-group">
      <label class="col-form-label-sm">Password</label>
      <input autocomplete="on" class="form-control" type="password" id="p_password">
    </div>
  `;


  if (setvals) {
    Object.assign( c, data );
    password_content = '';
    btn_text = 'Update Person'
  }

  return`
  <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 container py-2">
          <div class="justify-content-center text-left">
            <div class="" id="register_form" style="background-color: #ffffff">
              <div class="user">

                <div class="form-group">
                  <label class="col-form-label-sm">Name:</label>
                  <input autocomplete="on" class="form-control" type="text" id="p_name" value="${c.name}">
                </div>

                <div class="form-group">
                  <label class="col-form-label-sm">Last name</label>
                  <input autocomplete="on" class="form-control" type="text" id="p_lastname" value="${c.lastname}">
                </div>

                <div class="form-group">
                  <label class="col-form-label-sm">E-Mail</label>
                  <input autocomplete="on" class="form-control" type="text" id="p_email" value="${c.main_email}">
                </div>

                ${password_content}

                <div class="form-group">
                  <label class="col-form-label-sm">Person Type</label>
                  <select class="form-control" id="p_permissions">
                      ${LoadSelect(c.id_person_type, g__cat_person_type, 'type', 'id_person_type', false)}
                  </select>
                </div>

                <div class="form-group">
                  <label class="col-form-label-sm">Person Status</label>
                  <select class="form-control" id="p_status">
                      ${LoadSelect(c.active, g__person_status, 'status', 'active', false)}
                  </select>
                </div>

                <button class="btn-lg btn-block btn btn-dark" id="submit_form">${btn_text}</button>
              </div>
            </div>
          </div>
        </div>
    </div>
  </div>
`;
}



// END FUNCIONES DE USOS VARIOS ************************************************
















// REQUESTS AQUI ABAJO

function cargarPersonas() {
  return new Promise(resolve => {
    request('<?=PATH_API?>person/read.php?pagesize=99999999', function(r) {
      g__personas = (parseInt(r.code)) ? r.document.records : [];
      renderPersonas(g__personas);
      resolve(true);
    });
  });
}


function cargarTiposPersona(){
  return new Promise(resolve => {
    request('<?=PATH_API?>cat_person_type/read.php?pagesize=99999999', function(r) {
      g__cat_person_type = (!r.code) ? [] : r.document.records;
      resolve(true);
    });
  });
}




function executeCreatePersona(persona){
  request( '<?=PATH_API?>person/createBE.php', function(r){
    handleCreatePersonCallback(r)
  }, { data:persona })
}



function executeUpdatePersona(person){
  request('<?=PATH_API?>person/updateBE.php', function(r){
    handleUpdatePersonCallback(r)
  }, { data:person });
}
