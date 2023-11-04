const G__ID_CABALLO = retrieveGET('id_horse');


// Variables Globales
let g__users_list = [];
let g__horse = false;
let g__current_owner = false;
let g__transfer_list = [];
let g__datatable = null;


if(G__ID_CABALLO){
    transferHorseCode()
}else{
    transferListCode()
}



//******************************************************************
//******************************************************************
//******************************************************************
async function transferHorseCode(){
    //carga datos
    let x = await cargaCaballo();
    let y = await cargaPersonas();
    let z = await cargarTransferenciasCaballo();

    //despliega informacion
    renderHorseTransfer()
    setTriggers()
}


function renderHorseTransfer(){
    //encuentra los datos
    g__current_owner = g__users_list.find( x => x.id_person == g__horse.id_current_owner)
    g__users_list = g__users_list.filter( x => x.id_person != g__horse.id_current_owner)

    // establece la fecha de inicio
    let from_date = (g__transfer_list.length > 0 ) 
        ? g__transfer_list.pop().to_date 
        : g__horse.inscription_date;


    // Establece los datos
    findone('#horse_name').value = g__horse.horse_name
    findone('#current_owner_input').value = `${g__current_owner.name} ${g__current_owner.lastname} - ${g__current_owner.main_email}`;
    LoadSelect('#new_owner_select', g__users_list, ['id_person', 'name', 'lastname', 'main_email'], 'id_person')
    findone('#from_date').value = from_date;
    findone('#to_date').value = getDate();
}


function setTriggers(){
    trigger('#execute_transfer', 'click', function(){

        let data = {
            id_horse:G__ID_CABALLO,
            old_owner:g__current_owner.id_person,
            id_person:findone('#new_owner_select').value,
            from_date:findone('#from_date').value,
            to_date:getDate()
        }

        Fnon.Dialogue.Info(
            `If you proceed now, the owner of the horse will no longer be: ${g__horse.name}.`,
            'Transfer Horse?',
            'Confirm transfer!', 
            'Cancel', 
            function(){
                executeTransfer(data)
            }
        );
    })
}






//******************************************************************
//******************************************************************
//******************************************************************
async function transferListCode(){

    let x = await cargarTransferencias();
    renderTransfersTable();

}



function renderTransfersTable(){
    g__datatable = $('#transfer_list_datatable').DataTable( {
        data: g__transfer_list,
        responsive: true,
        destroy: true,
        columns: [
            { data: "id_old_owner" },
            { data: "name" },
            { data: "horse_name" },
            { data: "from_date" },
            { data: "to_date" }
        ]
    });
}































//******************************************************************
//******************************************************************
//******************************************************************



// REQUESTS AQUI ABAJO
function cargaCaballo(){
    return new Promise( resolve => {
        request(`<?=PATH_API?>horse/read_one.php?id=${G__ID_CABALLO}`, function(r){
            if(r.code){
                g__horse = r.document
            }else{
                Fnon.Hint.Danger( 'Selected horse, does not exist')
                setTimeout( () => { location.replace( '?p=adm_horses' ) }, 2000 )
            }
            resolve(true)
        })
    })
}


function cargaPersonas(){
    return new Promise( resolve => {
        request(`<?=PATH_API?>person/read.php?pagesize=999999`, function(r){
            g__users_list = (r.code) ? r.document.records : [];
            resolve(true)
        })
    })
}


function cargarTransferenciasCaballo(){
    return new Promise( resolve => {
        request(`<?=PATH_API?>horse_old_owner/read_by_id_horse.php?pagesize=99999&id_horse=${G__ID_CABALLO}`, function(r){
            g__transfer_list = (r.code) ? r.document.records : [];
            resolve(true)
        })
    })
}

// Funcion para ejecutar el request que crea la transferencia del caballo.
function executeTransfer( data ){

    request( '<?=PATH_API?>horse_old_owner/new_transfer.php', function(r){

        if(r.code){
            Fnon.Hint.Success('Horse transfered succesfully, redirecting...', {position:'center-center'})
            setTimeout( ()=> {location.replace('?p=adm_transfer')}, 1500)
        }

    }, { data:data })

}




//******************************************************************
//******************************************************************
//******************************************************************


function cargarTransferencias(){
    return new Promise( resolve => {
        request('<?=PATH_API?>horse_old_owner/read.php?pagesize=99999', function(r){
            g__transfer_list = ( r.code ) ? r.document.records : [];
            resolve(true)
        })
    })
}