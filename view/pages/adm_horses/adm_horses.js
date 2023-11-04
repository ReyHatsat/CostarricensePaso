// Variables here
let g__horse_list = [];
let g__personas = [];
let g__horse_table;
let g__horseUpdateData = [];
let g__edit_other_information_obj = [];
let g__horse_parents = [];
let g__father_data = [];
let g__mother_data = [];
const phrase = 'U2FsdGVkX19Yy+aHiBtIz8zvJr0NZoNruSTVPrxo2q4=';

// Initialize the script
init();





async function init(){

  let a = await loadHorses();
  let b = await cargarPersonas();
  renderHorseTable();

  // After rendering the list, add the triggers.
  setTriggers();
  setLimitDate();

}



function setLimitDate(){
  findone('#horse_birth_date').max = getDate();
  findone('#date_of_evaluation').max = getDate();
}



function toggleView(){
  findone('#table_view').classList.toggle('d-none')
  findone('#add_horse_view').classList.toggle('d-none')
}



function setTriggers(){



  //Import / Export triggers.
  trigger('#btn_gen_file', 'click', async function() {

    //validar que el JSON tenga al menos 1 valor no vacio.
    let form_string = JSON.stringify( generateFormJSON() );

    if (form_string.split('""').length - 1 < 19) {
      const encrypted = await enc( form_string );
      const downloadBtn = document.getElementById('download_button');
      downloadBtn.href = 'data:text/plain;charset=utf-8,'+encodeURIComponent( encrypted );
      downloadBtn.setAttribute('download',  findone('#name_of_the_horse').value + '-' + getDate() + '.crdp' );
      downloadBtn.click();
      return;
    }else{
      Fnon.Hint.Danger('Cannot export an empty form');
    }

  });

  trigger('#btn-loadFile', 'click', function(){
    Fnon.Alert.Dark(`
      <h4> Select a file to load </h4>
      <div class="custom-file">
        <input type="file" accept=".crdp" class="custom-file-input" id="file_selector">
        <label class="custom-file-label" id="lbl-file_selector" for="file_selector">Choose file</label>
      </div>
      </br>
      <br>
      <button class="btn btn-outline-dark" id="btn-load_file_data"> Load File </button>
    `, 'Select Import File', 'Close');

    trigger('#file_selector', 'change', function(e){
      var fileName = e.target.value.split("\\").pop();
      findone("#lbl-file_selector").innerHTML = fileName;
    })

    trigger('#btn-load_file_data', 'click', function(){
      LoadFileData()
    })

  });




  trigger('.toggle_horse_view-btn', 'click', function(){
    toggleView();
  })



  trigger('#i_am_current_owner', 'change', function(e){
      const container = findone('#first_owner_information-container');
      //console.log(this.checked);
      container.classList.toggle('d-none');
  });

  trigger('#father_registration_status', 'change', function(e){
      const container = findone('#father_registration-container');
      container.classList.toggle('d-none');
  });

  trigger('#mother_registration_status', 'change', function(e){
      const container = findone('#mother_registration-container');
      container.classList.toggle('d-none');
  });

  trigger('#btn_register', 'click', function(){triggerDBHorse()});

  trigger('#typesize', 'change', function(e){
    const medida = findone('#horse_size');
    medida.value = conversion( medida.value, this.dataset.ini, this.value );
    this.dataset.ini = this.value;
  });

  trigger('#father_typesize', 'change', function(e){
    const medida = findone('#father_size');
    medida.value = conversion( medida.value, this.dataset.ini, this.value );
    this.dataset.ini = this.value;
  });

  trigger('#mother_typesize', 'change', function(e){
    const medida = findone('#mother_size');
    medida.value = conversion( medida.value, this.dataset.ini, this.value );
    this.dataset.ini = this.value;
  });
}







function renderHorseTable(){
  g__horse_table = $('#tablehorses').DataTable( {
      data: g__horse_list,
      responsive: true,
      destroy: true,
      columns: [
          {
            data: "name",
            render:function(data, type, row){
              return `<a href="?p=adm_person&id=${row.id_current_owner}">${row.id_current_owner} - ${data}</a>`
            }
          },
          { data: "horse_name" },
          { data: "birth_date" },
          { data: "encaste" },
          { data: "sex" },
          { data: "microchip_no" },
          { data: "inscription_date" },
          { data: "observations" },
          {
            data: "id_horse",
            render:function(data){
              return `
                <button class="btn btn-primary btn-sm" onclick="viewInformationEvent(${data})">
                  <i class="fa fa-eye"></i>
                </button>
                <button class="btn btn-primary btn-sm" onclick="goToTransfer(${data})">
                  <i class="fas fa-exchange-alt"></i>
                </button>
                  <button class="btn btn-primary btn-sm" onclick="editHorseEvent(${data})">
                    <i class="fas fa-pencil-alt"></i>
                  </button>
              `;
            }
          },

      ]
  });
}




function goToTransfer(id){
  Fnon.Dialogue.Info(
    'If you continue, you will be redirected to the transfers page.',
    'Transfer Horse',
    'Yes',
    'Cancel',
    function(){
      location.replace(`?p=adm_transfer&id_horse=${id}`)
    }
  );
}


async function viewInformationEvent(id_horse_datatables){
  g__horseUpdateData = g__horse_list.find(x => x.id_horse == id_horse_datatables);
  g__horse_parents = await loadHorsesParents(g__horseUpdateData.id_horse);
  g__horse_parents = g__horse_parents.document.records.find(x => x.id_horse == id_horse_datatables);
  g__father_data = JSON.parse(g__horse_parents.father_data);
  g__mother_data = JSON.parse(g__horse_parents.mother_data);
  console.log(g__mother_data);
  g__edit_other_information_obj = JSON.parse(g__horseUpdateData.other_information);
  g__edit_other_information_obj = JSON.parse(g__horseUpdateData.other_information);
  viewInformation();
}


// Desplegar la informacion del caballo seleccionado
function viewInformation(){
  Fnon.Alert.Dark(`
    <table class="table">
    <thead>
      <tr>
      <td class="font-weight-bold">
        Horse:
      </td>
      </tr>
      <tr>
        <td class="border">Name:</td>
        <td class="border">${g__horseUpdateData.horse_name}</td>
      </tr>
      <tr>
        <td class="border">Birth Date:</td>
        <td class="border">${g__horseUpdateData.birth_date}</td>
      </tr>
      <tr>
        <td class="border">Horse sex:</td>
        <td class="border">${g__edit_other_information_obj.sex == 1 ? "Male" : "Female"}</td>
      </tr>
      <tr>
        <td class="border">Date of evaluation:</td>
        <td class="border">${g__horseUpdateData.inscription_date}</td>
      </tr>
      <tr>
        <td class="border">Purity of Breed:</td>
        <td class="border">${g__horseUpdateData.encaste}</td>
      </tr>
      <tr>
        <td class="border">Microchip Number:</td>
        <td class="border">${g__horseUpdateData.microchip_no}</td>
      </tr>
      <tr class="d-none">
        <td class="border">ID Current Owner:</td>
        <td class="border">${g__horseUpdateData.id_current_owner}</td>
      </tr>
      <tr class="d-none">
        <td class="border">ID horse:</td>
        <td class="border">${g__horseUpdateData.id_horse}</td>
      </tr>
      <tr>
        <td class="border">Size:</td>
        <td class="border">${g__edit_other_information_obj.size} ${g__edit_other_information_obj.typesize}${g__edit_other_information_obj.typesize == "hand" && g__edit_other_information_obj.size > 1 ? "s" : ""}</td>
      </tr>
      <tr>
        <td class="border">ID horse:</td>
        <td class="border">${g__horseUpdateData.id_horse}</td>
      </tr>
      <tr>
        <td class="border">Observations:</td>
        <td class="border">${g__horseUpdateData.observations}</td>
      </tr>
    </thead>
    </table>

    <table class="table">
    <thead>
      <tr>
      <td class="font-weight-bold">
        Father Data:
      </td>
      </tr>
      <tr>
        <td class="border">Name:</td>
        <td class="border">${g__father_data.name}</td>
      </tr>
      <tr>
        <td class="border">Father Registered in Ascacopa / Costarricense de Paso:</td>
        <td class="border">${g__father_data.registration}</td>
      </tr>
      <tr>
        <td class="border">Size:</td>
        <td class="border">${g__father_data.size} ${g__father_data.typesize}${g__father_data.typesize == "hand" && g__father_data.size > 1 ? "s" : ""}</td>
      </tr>
      <tr class="d-none">
        <td class="border">Status:</td>
        <td class="border">${g__father_data.status}</td>
      </tr>
    </thead>
    </table>

    <table class="table">
      <thead>
        <tr>
          <td class="font-weight-bold">
            Mother Data:
          </td>
        </tr>
        <tr>
          <td class="border">Name:</td>
          <td class="border">${g__mother_data.name}</td>
        </tr>
        <tr>
          <td class="border">Microcapsule of the Mother:</td>
          <td class="border">${g__mother_data.microcapsule}</td>
        </tr>
        <tr>
          <td class="border">Father Registered in Ascacopa / Costarricense de Paso:</td>
          <td class="border">${g__mother_data.registration}</td>
        </tr>
        <tr>
        <td class="border">Size:</td>
          <td class="border">${g__mother_data.size} ${g__mother_data.typesize}${g__father_data.typesize == "hand" && g__mother_data.size > 1 ? "s" : ""}</td>
        </tr>
        <tr class="d-none">
          <td class="border">Status:</td>
          <td class="border">${g__mother_data.status}</td>
        </tr>
      </thead>
      </table>

    `,'View Information','Close');

}





function getViewInformationContent(caballo){
  var breeder = JSON.parse(caballo.breeder_data);
  var other_information = JSON.parse(caballo.other_information);
  return `<div class="table">
    <div>Breeder Data</div>
    <div class="container">
       <div class="row">
         <div class="col">
           Name
          </div>
          <div class="col">
            Identification
          </div>
      </div>
       <div class="row">
          <div class="col">
            ${breeder.name}
          </div>
          <div class="col">
           ${breeder.identification}
          </div>
       </div>
    </div>

   <div>Horse Information</div>
   <div class="container">
      <div class="row">
        <div class="col">
          Size
         </div>
         <div class="col">
           ID horse
         </div>
     </div>
      <div class="row">
         <div class="col">
           ${other_information.size} ${other_information.typesize}s
         </div>
         <div class="col">
          ${caballo.id_horse}
         </div>
      </div>
   </div>
 </div>`;
}




// Add a horse Event -----------------------------------------------------------
function triggerDBHorse() {

  const respuesta = form_validation();
  if(respuesta){

    respuesta.mother = JSON.stringify(respuesta.mother);
    respuesta.father = JSON.stringify(respuesta.father);
    respuesta.breeder_data = JSON.stringify(respuesta.breeder_data);
    respuesta.other_information = JSON.stringify(respuesta.other_information);
    if (respuesta.horse.observations == "") {
      respuesta.horse.observations = "The owner did not put any observations*";
    }


    request( '<?=PATH_API?>horse/create.php', function(r){
      if(r.code){
        Fnon.Hint.Success(r.message);
        toggleView();
        setTimeout(function(){ init(); }, 10);
      }else{
        Fnon.Hint.Danger(r.message);
      }

    }, { data:respuesta })
  }

}



function form_validation(){
  let form_data = generateFormJSON();
  return checkError(form_data);
}




function generateFormJSON(){

  const PERSONA = g__personas.find( x => x.id_person == findone('#current_owner').value);

  let container = findone('#i_am_current_owner').checked;
  let first_owner = (container) ?{
    name:PERSONA.name,
    id_person:PERSONA.id_person
  }:{
    name:findone("#name_first_owner").value,
    id:findone("#id_first_owner").value,
    address:findone("#address_first_owner").value,
    phone:findone("#phone_number_first_owner").value,
  };

  return {
    id_current_owner:PERSONA.id_person,
    inspector_reference:"NONE",
    first_owner:first_owner,
    breeder_data:{
      name:findone("#breeder_name").value,
      identification:findone("#breeder_identification").value,
    },
    horse:{
      name:findone("#name_of_the_horse").value,
      birth:findone("#horse_birth_date").value,
      observations:findone("#observations").value,
      microchip_no:findone("#microchip_number").value,
      inscription_date:findone("#date_of_evaluation").value,
      id_horse_sex:findone("#horse_sex").value,
      id_encaste : findone("#purity_of_breed").value,
    },
    other_information:{
      purity:findone("#purity_of_breed").value,
      sex:findone("#horse_sex").value,
      size:findone("#horse_size").value,
      typesize:findone("#typesize").value,
    },
    father:{
      name:findone("#father_name").value,
      father:findone("#dna_father").value,
      size:findone("#father_size").value,
      typesize:findone("#father_typesize").value,
      status:findone("#father_registration_status").value,
      registration:findone("#father_registration").value,
    },
    mother:{
      name:findone("#mother_name").value,
      microcapsule:findone("#mother_microcapsule").value,
      size:findone("#mother_size").value,
      typesize:findone("#mother_typesize").value,
      status:findone("#mother_registration_status").value,
      registration:findone("#mother_registration").value,
    }
  }
}


function fillFormFromJSON(obj){
  findone("#name_first_owner").value = obj.first_owner.name;
  findone("#id_first_owner").value = obj.first_owner.id;
  findone("#address_first_owner").value = obj.first_owner.address;
  findone("#phone_number_first_owner").value = obj.first_owner.phone;
  findone("#breeder_name").value = obj.breeder_data.name;
  findone("#breeder_identification").value = obj.breeder_data.identification;
  findone("#name_of_the_horse").value = obj.horse.name;
  findone("#horse_birth_date").value = obj.horse.birth;
  findone("#purity_of_breed").value = obj.other_information.purity;
  findone("#horse_sex").value = obj.other_information.sex;
  findone("#microchip_number").value = obj.horse.microchip_no;
  findone("#date_of_evaluation").value = obj.horse.inscription_date;
  findone("#horse_size").value = obj.other_information.size;
  findone("#typesize").value = obj.other_information.typesize;
  findone("#observations").value = obj.horse.observations;
  findone("#father_name").value = obj.father.name;
  findone("#dna_father").value = obj.father.father;
  findone("#father_size").value = obj.father.size;
  findone("#father_typesize").value = obj.father.typesize;
  findone("#father_registration_status").value = obj.father.status;
  findone("#father_registration").value = obj.father.registration;
  findone("#mother_name").value = obj.mother.name;
  findone("#mother_microcapsule").value = obj.mother.microcapsule;
  findone("#mother_size").value = obj.mother.size;
  findone("#mother_typesize").value = obj.mother.typesize;
  findone("#mother_registration_status").value = obj.mother.status;
  findone("#mother_registration").value = obj.mother.registration;
}



function checkError(form_data){
  let hor_dat = Date.parse(form_data.birth_date);
  let eva_dat = Date.parse(form_data.inscription_date);
  let ima_dat = Date.parse(getDate());
  //
  let container = findone('#i_am_current_owner').checked;
  let f_check = findone('#father_registration_status').value;
  let m_check = findone('#mother_registration_status').value;

  //remove error classes.
  const remove_class = findall('.form-control');
  let forms_error = [];
  for (let index = 0; index < remove_class.length; index++) {
    const elem = remove_class[index];
    elem.classList.remove('is-invalid');
    elem.classList.remove('border');
    elem.classList.remove('border-danger');
  }

  // identificadores
  if ( !container  && !form_data.first_owner.name) {
    forms_error.push('#name_first_owner');
  }
  if ( !container && !form_data.first_owner.id) {
    forms_error.push('#id_first_owner');
  }
  if ( !container && !form_data.first_owner.address) {
    forms_error.push('#address_first_owner');
  }
  if ( !container && !form_data.first_owner.phone) {
    forms_error.push('#phone_number_first_owner');
  }
  if (!form_data.breeder_data.name) {
    forms_error.push('#breeder_name');
  }
  if (!form_data.breeder_data.identification) {
    forms_error.push('#breeder_identification');
  }
  //
  if (!form_data.horse.name) {
    forms_error.push('#name_of_the_horse');
  }
  if (!form_data.horse.birth || hor_dat > ima_dat) {
    forms_error.push('#horse_birth_date');
  }

  if (!form_data.horse.microchip_no) {
    forms_error.push('#microchip_number');
  }
  if (!form_data.horse.inscription_date || eva_dat > ima_dat) {
    forms_error.push('#date_of_evaluation');
  }
  if (!form_data.other_information.size) {
    forms_error.push('#horse_size');
  }
  //
  if (!form_data.father.name) {
    forms_error.push('#father_name');
  }
  if (!form_data.father.size) {
    forms_error.push('#father_size');
  }
  if (f_check == "1" && !form_data.father.registration) {
    forms_error.push('#father_registration');
  }
  //
  if (!form_data.mother.name) {
    forms_error.push('#mother_name');
  }
  if (!form_data.mother.size) {
    forms_error.push('#mother_size');
  }
  if (!form_data.mother.microcapsule) {
    forms_error.push('#mother_microcapsule');
  }
  if (m_check == "1"  && !form_data.mother.registration) {
    forms_error.push('#mother_registration');
  }

  if (forms_error.length > 0) {
    for (var i = 0; i < forms_error.length; i++) {
      const elem = findone(forms_error[i]);
      elem.classList.add('is-invalid');
      elem.classList.add('border');
      elem.classList.add('border-danger');
    }
    Fnon.Hint.Danger('Please check your inputs.');
    return false;
  }
    return form_data;
}





// Convertir 15 pulgadas a centimetros.
// 15 - inches - centimeters
function conversion(m, i, f){
  const hm = {
    "centimeters":10.16,
    "inches":4
  };
  if (i == 'hand') {
    return rnd(m * hm[f]);
  }else if(f == 'hand'){
    return rnd(m / hm[i]);
  }else{
    return rnd((m/hm[i]) * hm[f]);
  }
}


//---------------------Edit functions---------------------//
function editHorseEvent(id_horse_datatables){
  g__horseUpdateData = g__horse_list.find(x => x.id_horse == id_horse_datatables);
  g__edit_other_information_obj = JSON.parse(g__horseUpdateData.other_information);
  let horseFormEdit = `
  <div class="container">


  <div class="col-md-12 pt-5" id="horse_information">

      <h4>Horse Information</h4>
      <hr>
      <div class="row">
          <div class="col-md-6">
              <div class="form-group">
                  <label for=""> Name of the Horse </label>
                  <input type="text" class="form-control" id="u_name_of_the_horse" value="${g__horseUpdateData.horse_name}" />
              </div>
          </div>
          <div class="col-md-6">
              <div class="form-group">
                  <label for=""> Horse Birth Date: </label>
                  <input type="date" class="form-control" id="u_horse_birth_date" value="${g__horseUpdateData.birth_date}"/>
              </div>
          </div>
      </div>

      <div class="row">
          <div class="col-md-6">
              <div class="form-group">
                  <label for=""> Purity of Breed </label>
                  <select class="form-control" id="u_purity_of_breed">
                      ${LoadSelect(g__horseUpdateData.id_encaste, [{v:1, label:'Pure Bred'}, {v:2, label:'YB'}, {v:3, label:'Y1'}, {v:4, label:'Y2'}], 'label', 'v', false)}
                  </select>
              </div>
          </div>
          <div class="col-md-6">
              <div class="form-group">
                  <label for=""> Horse Sex </label>
                  <select class="form-control" id="u_horse_sex" >
                      ${LoadSelect(g__horseUpdateData.id_horse_sex, [{v:1, label:'Male'}, {v:2, label:'Female'}], 'label', 'v', false)}
                  </select>
              </div>
          </div>
      </div>

      <div class="row">
          <div class="col-md-6">
              <div class="form-group">
                  <label for=""> Microchip Number </label>
                  <input type="text" class="form-control" id="u_microchip_number" value="${g__horseUpdateData.microchip_no}">
              </div>
          </div>
          <div class="col-md-6">
              <div class="form-group">
                  <label for=""> Date of evaluation </label>
                  <input type="date" class="form-control" id="u_date_of_evaluation"/ value="${g__horseUpdateData.inscription_date}">
              </div>
          </div>
      </div>

      <div class="row">
          <div class="col-md-6">
              <div class="form-group">
                  <label for=""> Horse Size </label>
                  <div class="input-group">
                      <input type="number" class="form-control" placeholder="Ex: 14" min="0" max="" id="u_horse_size" value="${g__edit_other_information_obj.size}">
                      <div class="input-group-append">
                      <select class="form-control bg-secondary text-white" id="u_typesize" data-ini="hand">
                          ${LoadSelect(g__edit_other_information_obj.typesize, [{v:"hand", label:'Hands'}, {v:"inches", label:'Inches (In)'} , {v:"centimeters", label:'Centimeters (Cm)'}], 'label', 'v', false)}
                      </select>
                      </div>
                  </div>
              </div>
          </div>
          <div class="col-md-6">
              <div class="form-group">
                  <label for=""> Observations </label>
                  <textarea class="form-control" id="u_observations">${g__horseUpdateData.observations}</textarea>
              </div>
          </div>
          <button class="btn-lg btn-block btn btn-dark" id="submit_form_membership" onclick="updateHorses(g__horseUpdateData)">Update</button>
      </div>
  </div>
  <!-- END OF HORSE INFORMATION -->
    </div>
  `
  Fnon.Alert.Dark(horseFormEdit,'Edit Horse','Close');
  trigger('#u_typesize', 'change', function(e){
    let medida = findone('#u_horse_size').value;
    findone("#u_horse_size").value = conversion( medida, this.dataset.ini, findone("#u_typesize").value );
    this.dataset.ini = findone("#u_typesize").value;
  });
}








function updateObj(){
    let edit_other_information_update = {
      purity:findone("#u_purity_of_breed").value,
      sex:findone("#u_horse_sex").value,
      size:findone("#u_horse_size").value,
      typesize:findone("#u_typesize").value
    }
    let string_edit_other_information = JSON.stringify(edit_other_information_update);
    let edit_obj = {
      horse_name:findone("#u_name_of_the_horse").value,
      birth_date:findone("#u_horse_birth_date").value,
      observations:findone("#u_observations").value,
      microchip_no:findone("#u_microchip_number").value,
      inscription_date:findone("#u_date_of_evaluation").value,
      id_horse_sex:findone("#u_horse_sex").value,
      id_encaste : findone("#u_purity_of_breed").value,
      other_information:string_edit_other_information,
      id_current_owner:g__horseUpdateData.id_current_owner,
      active:g__horseUpdateData.active,
      id_horse:g__horseUpdateData.id_horse
    }





    let hor_dat = Date.parse(g__horseUpdateData.birth_date);
    let eva_dat = Date.parse(g__horseUpdateData.inscription_date);
    let ima_dat = Date.parse(getDate());
    //

    //remove error classes.
    const remove_class = findall('.form-control');
    let forms_error = [];
    for (let index = 0; index < remove_class.length; index++) {
      const elem = remove_class[index];
      elem.classList.remove('is-invalid');
      elem.classList.remove('border');
      elem.classList.remove('border-danger');
    }

    // identificadores

    //
    if (!edit_obj.horse_name) {
      forms_error.push('#u_name_of_the_horse');
    }
    if (!edit_obj.birth_date || hor_dat > ima_dat) {
      forms_error.push('#u_horse_birth_date');
    }

    if (!edit_obj.microchip_no) {
      forms_error.push('#u_microchip_number');
    }
    if (!edit_obj.inscription_date || eva_dat > ima_dat) {
      forms_error.push('#u_date_of_evaluation');
    }
    if (!edit_other_information_update.size) {
      forms_error.push('#u_horse_size');
    }
    if (edit_obj.observations == "") {
      edit_obj.observations = "The owner did not put any observations*";
    }

    if (forms_error.length > 0) {
      for (var i = 0; i < forms_error.length; i++) {
        const elem = findone(forms_error[i]);
        elem.classList.add('is-invalid');
        elem.classList.add('border');
        elem.classList.add('border-danger');
      }
      Fnon.Hint.Danger('Please check your inputs.');
      return false;
    }
      return edit_obj;
}














//---------------------End Edit functions---------------------//






//---------------------Requests here---------------------//

function loadHorses(){
  return new Promise( resolve => {
    request('<?=PATH_API?>horse/read.php?pagesize=99999999', function(r){
      g__horse_list = (r.code) ? r.document.records : [];
      resolve(r)
    })
  })
}



function cargarPersonas() {
  return new Promise(resolve => {
    request('<?=PATH_API?>person/read.php?pagesize=99999999', function(r) {
      g__personas = (parseInt(r.code)) ? r.document.records : [];
      LoadSelect('#current_owner', g__personas, ['id_person', 'name', 'main_email'], 'id_person');
      resolve(true);
    });
  });
}


function updateHorses(){
  let value = updateObj();
  if(!value){ return };
  request('<?=PATH_API?>horse/update_only_horse.php?id_horse='+g__horseUpdateData.id_horse, function(r){
    if(r.code){
      Fnon.Hint.Success('Update successful', {position:'center-center'});
      fireEvent('.f__btn', 'click');
      init();
    }else{
      Fnon.Hint.Danger('Error updating your horse', {position:'center-center'});
    }
  }, { data:value })
}

function loadHorsesParents(id_horse){
  return new Promise( resolve => {
    request('<?=PATH_API?>horse_parents/read_by_id_horse.php?id_horse='+id_horse, function(r){
      g__horse_parents = (r.code) ? r.document.records : [];
      resolve(r)
    })
  })
}
//---------------------End Requests here---------------------//










function LoadFileData(){
  //get the file.
  var fileToLoad = findone("#file_selector").files[0];
  var fileReader = new FileReader();

  try {
    fileReader.readAsText(fileToLoad, "UTF-8");
  } catch (error) {
    Fnon.Hint.Danger('Error reading the file, please try again.');
  }

  //Listen for when the file is fully loaded
  fileReader.onload = function(fileLoadedEvent){

    var file_contents = fileLoadedEvent.target.result;
    var obj = JSON.parse(dec(file_contents));
    fillFormFromJSON(obj);

    fireEvent('.f__btn', 'click');
    Fnon.Hint.Success('File imported correctly');
  };
}





// External Crypto assets.
function enc(val){
  return CryptoJS.AES.encrypt(val, phrase);
}

function dec(val){
  const d = CryptoJS.AES.decrypt(val, phrase);
  return d.toString(CryptoJS.enc.Utf8);
}
