//Initialize the JS for the page.
InitForms();
const phrase = 'U2FsdGVkX19Yy+aHiBtIz8zvJr0NZoNruSTVPrxo2q4=';


function InitForms(){
    // Set the triggers for the form
    setTriggers();
    setLimitDate();
}

function setLimitDate() {
  findone('#horse_birth_date').max = getDate();
  findone('#date_of_evaluation').max = getDate();
}


function setTriggers(){
    trigger('#i_am_current_owner', 'change', function(e){
        const container = findone('#first_owner_information-container');
        console.log(this.checked);
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
}




function triggerDBHorse() {

  if (G__SESSION == 1) {

    Fnon.Ask.Warning(
      'You are not registered, do you want to register now?','You are not registered!',
      'Yes', 'No', (result)=>{
        if (result) {
          //validar que el JSON tenga al menos 1 valor no vacio.
          let form_string = JSON.stringify( generateFormJSON() );
          if (form_string.split('""').length - 1 < 19) {
            Fnon.Ask.Warning(
              'do you want to save the form inputs?','Form Inputs',
              'Yes',
              'No',
              (result)=>{
                if (result) {
                  fireEvent('#btn_gen_file', 'click');
                } else {
                  window.location.href = "?p=register"
                }
              }
            );
          }else {window.location.href = "?p=register"}
        }
    });
    return;

  }


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
        setTimeout(function(){
        window.location.href = "?p=profile";
      },2500);
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

  let container = findone('#i_am_current_owner').checked;
  let first_owner = (container) ?{
    name:G__SESSION.name,
    id_person:G_SESSION.id_person
  }:{
    name:findone("#name_first_owner").value,
    id:findone("#id_first_owner").value,
    address:findone("#address_first_owner").value,
    phone:findone("#phone_number_first_owner").value,
  };

  return {
    id_current_owner:G__SESSION.id_person,
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





if ($(window).width() > 992) {
  $(window).scroll(function(){
     if ($(this).scrollTop() > 400) {
        $('.sticky').addClass("fixed-top");
        // add padding top to show content behind navbar
        $('body').css('padding-top', $('.navbar').outerHeight() + 'px');
      }else{
        $('.sticky').removeClass("fixed-top");
         // remove padding top from body
        $('body').css('padding-top', '0');
      }
  });
} // end if




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




function enc(val){
  return CryptoJS.AES.encrypt(val, phrase);
}

function dec(val){
  const d = CryptoJS.AES.decrypt(val, phrase);
  return d.toString(CryptoJS.enc.Utf8);
}
