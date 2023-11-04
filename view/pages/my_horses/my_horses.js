let g__caballos = [];

init();


async function init(){
    cargarCaballos()
}



//mostrar tabla de caballos
function renderCaballos(caballosObj){
    $('#tablehorses').DataTable( {
        data: caballosObj,
        responsive: true,
        destroy: true,
        columns: [
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
                  <button class = "btn btn-primary" onclick="viewInformation(${data})">
                    <i class="fas fa-eye"></i> View
                  </button>

                `;
              }
            },

        ]
    } );
}




// Desplegar la informacion del caballo seleccionado
function viewInformation(id_horse){
    var caballo = g__caballos.find( x => x.id_horse == id_horse );
    if (!caballo) {
        Fnon.Hint.Danger('The selected horse could not be found');
        return
    }
    Fnon.Alert.Dark( getViewInformationContent(caballo) );
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





function cargarCaballos() {
    return new Promise(resolve => {
      request('<?=PATH_API?>horse/read_by_id_current_owner.php?id_current_owner='+G__SESSION.id_person, function(r) {
        g__caballos = r.document.records;
        if (!r.code) {
          g__caballos = [];
        }
        renderCaballos(r.document.records);
      });
    });
  }
