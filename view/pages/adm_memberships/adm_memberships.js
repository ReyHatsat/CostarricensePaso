//-----------------------Variables here-----------------------//
let g__membership_lst = [];
let g__membership_lst_datatable = [];
let g__membershipUpdateData = [];

//-----------------------End Variables here-----------------------//





//-----------------------Initialize the script-----------------------//
init();
async function init(){

  let a = await loadMembershipList();
  let b = await renderMembershipTable();
  // After rendering the list, add the triggers.
  setTriggers();

}
//-----------------------End Initialize the script-----------------------//


//-----------------------Triggers-----------------------//
function setTriggers(){
  trigger('#btn-create-membership', 'click', async function(){
    createMembershipEvent();
  });

}
//-----------------------End Triggers-----------------------//


//-----------------------Data Tables-----------------------//
function renderMembershipTable(){
  // if (g__membership_lst_datatable) {
  //   g__membership_lst_datatable.destroy();
  // }

  g__membership_lst_datatable = $('#tablemembership').DataTable( {
      data: g__membership_lst,
      responsive: true,
      destroy: true,
      columns: [
          { data: "id_membership" },
          { data: "membership" },
          { data: "interval_months" },
          {
            data: "price",
            render:function(data){
              return `$${formatNum(data)}`
            }
          },
          { data: "active",
          render:function(data){
            return (parseInt(data))
              ? '<h5><span class="text-success">Active</span></h5>'
              : '<h5><span class="text-danger">Inactive</span></h5>';
          }
          },
          {
            data:'id_membership',
            render:function(data, type, row){
              return`
                <button class="btn btn-primary btn-sm" onclick="editMembershipEvent(${data})">
                  <i class="fas fa-pencil-alt"></i>
                </button>
              `;
            }
          }
      ]
  });
}
//-------------------END Data Tables-----------------------//





//-------------------CREATE MEMBERSHIP --------------------//
function createMembershipEvent(){
  let membershipForm = `
  <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 container py-2">
          <div class="justify-content-center text-left">
            <div class="" id="create_membership_form" style="background-color: #ffffff">
              <div class="user">

                <div class="form-group">
                  <label class="col-form-label-sm">Membership:</label>
                  <input autocomplete="on" class="form-control" type="text" id="p_membership">
                </div>

                <div class="form-group">
                  <label class="col-form-label-sm">Interval Months</label>
                  <input autocomplete="on" class="form-control" type="text" id="p_interval_months">
                </div>

                <div class="form-group">
                  <label class="col-form-label-sm">Price</label>
                  <input autocomplete="on" class="form-control" type="text" id="p_price">
                </div>
                </div>

                <button class="btn-lg btn-block btn btn-dark" id="submit_form_membership">Create</button>
              </div>
            </div>
          </div>
        </div>
    </div>
  `

    Fnon.Alert.Dark(membershipForm,'Create Membership','Close');
    trigger('#submit_form_membership','click',function(){
      createMembership(createObj());
    });
};


function editMembershipEvent(data_mem_datatables){
  g__membershipUpdateData = g__membership_lst.find(x => x.id_membership == data_mem_datatables);
  let membershipFormEdit = `
  <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 container py-2">
          <div class="justify-content-center text-left">
            <div class="" id="create_membership_form" style="background-color: #ffffff">
              <div class="user">

                <div class="form-group">
                  <label class="col-form-label-sm">Membership:</label>
                  <input autocomplete="on" class="form-control" type="text" id="u_p_membership" value="${g__membershipUpdateData.membership}">
                </div>

                <div class="form-group">
                  <label class="col-form-label-sm">Interval Months</label>
                  <input autocomplete="on" class="form-control" type="text" id="u_p_interval_months" value="${g__membershipUpdateData.interval_months}">
                </div>

                <div class="form-group">
                  <label class="col-form-label-sm">Price</label>
                  <input autocomplete="on" class="form-control" type="text" id="u_p_price" value="${g__membershipUpdateData.price}">
                </div>

                <div class="form-group">
                  <select id="u_p_active" class="form-control">
                    ${LoadSelect(g__membershipUpdateData.active, [{v:0, label:'inactive'}, {v:1, label:'active'}], 'label', 'v', false)}
                  </select>
                </div>
                </div>

                <button class="btn-lg btn-block btn btn-dark" id="submit_form_membership" onclick="updateMembership(g__membershipUpdateData)">Update</button>
              </div>
            </div>
          </div>
        </div>
    </div>
  `
  Fnon.Alert.Dark(membershipFormEdit,'Edit Membership','Close');
}


function createObj(){

  const remove_class = findall('.form-control');
  for (let index = 0; index < remove_class.length; index++) {
    const elem = remove_class[index];
    elem.classList.remove('is-invalid');
    elem.classList.remove('border');
    elem.classList.remove('border-danger');
  }


  let membership_from_obj = {
    membership:findone('#p_membership').value,
    interval_months:findone('#p_interval_months').value,
    price:findone('#p_price').value,
    active:1
  };

  let error = [];


  if (membership_from_obj.membership == '') {
    error.push('#p_membership');
  }
  if(membership_from_obj.interval_months == '' || Number.isInteger(membership_from_obj.interval_months)){
    Fnon.Hint.Danger('In Interval Months: Only numbers please.', {position:'center-center'})
    error.push('#p_interval_months');
  }
  if(membership_from_obj.price == '' || isNaN(membership_from_obj.price)) {
    Fnon.Hint.Danger('In Price: Only numbers please.', {position:'center-center'})
    error.push('#p_price');
  }


  if(error.length > 0){
    for (let index = 0; index < error.length; index++) {
      const elem = findone(error[index]);
      elem.classList.add('is-invalid');
      elem.classList.add('border');
      elem.classList.add('border-danger');
    }
    Fnon.Hint.Danger('Please check your inputs.', {position:'center-center'});
    return false;
  }
  return membership_from_obj;
}



function updateObj(){

  const remove_class = findall('.form-control');
  for (let index = 0; index < remove_class.length; index++) {
    const elem = remove_class[index];
    elem.classList.remove('is-invalid');
    elem.classList.remove('border');
    elem.classList.remove('border-danger');
  }


  let membership_from_obj = {
    id_membership:g__membershipUpdateData.id_membership,
    membership:findone('#u_p_membership').value,
    interval_months:findone('#u_p_interval_months').value,
    price:findone('#u_p_price').value,
    active:findone('#u_p_active').value
  };

  let error = [];


  if (membership_from_obj.membership == '') {
    error.push('#u_p_membership');
  }
  if(membership_from_obj.interval_months == '' || isNaN(membership_from_obj.interval_months)){
    Fnon.Hint.Danger('In Interval Months: Only numers please.', {position:'center-center'})
    error.push('#u_p_interval_months');
  }
  if(membership_from_obj.price == '' || isNaN(membership_from_obj.price)) {
    Fnon.Hint.Danger('In Price: Only numers please.', {position:'center-center'})
    error.push('#u_p_price');
  }


  if(error.length > 0){
    for (let index = 0; index < error.length; index++) {
      const elem = findone(error[index]);
      elem.classList.add('is-invalid');
      elem.classList.add('border');
      elem.classList.add('border-danger');
    }
    Fnon.Hint.Danger('Please check your inputs.', {position:'center-center'});
    return false;
  }
  return membership_from_obj;
}

//-----------------------EndCreate/Update-----------------------//







//-----------------------Requests here-----------------------//
function loadMembershipList(){
  return new Promise( resolve => {
    request('<?=PATH_API?>cat_membership/read.php?pagesize=99999999', function(r){
      g__membership_lst = (r.code) ? r.document.records : [];
      resolve(r);
    })
  })
}


// ------------------------------------------------

function createMembership(membership_from_obj){
  console.log(membership_from_obj);
  request( '<?=PATH_API?>cat_membership/create.php', function(r){
    if(r.code){
      Fnon.Hint.Success('Creation successful', {position:'center-center'});
      fireEvent('.f__btn', 'click');
      init();
    }else{
      Fnon.Hint.Danger('Error creating your membership', {position:'center-center'});
    }
  }, { data:membership_from_obj})
}


// ------------------------------------------------
function updateMembership(g__membershipUpdateData){
  let value = updateObj()
  if(!value){ return };

  request( '<?=PATH_API?>cat_membership/update.php?id_membership='+g__membershipUpdateData.id_membership, function(r){
    if(r.code){
      Fnon.Hint.Success('Update successful', {position:'center-center'});
      fireEvent('.f__btn', 'click');
      init();
    }else{
      Fnon.Hint.Danger('Error updating your membership', {position:'center-center'});
    }
  }, { data:value })
}
//-----------------------End Requests here-----------------------//
