<?php _loggedIn(); ?>

<?php
_prepareAsyncExecute(function(){
  _component("utils/datatables");
});
?>


<div class="container rounded bg-white mt-5 mb-5">
    <div class="row">
        <div class="col-md-8">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-right">Profile Settings</h4>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <label class="labels">Name</label>
                        <input type="text" class="form-control" id="imp_name">
                    </div>
                    <div class="col-md-6">
                        <label class="labels">Last Name</label>
                        <input type="text" class="form-control" id="imp_lastname">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <label class="labels">Email</label>
                        <input type="text" class="form-control" id="imp_email"></div>
                </div>
                <div class="mt-5 text-center">
                    <button class="btn btn-dark profile-button" type="button" id="save_profile">
                        Save Changes
                    </button>
                </div>
            </div>
        </div>

            <div class="p-3 py-5 border-left">
                <h4 class="text-right">Membership and status</h4>
                  <label class="col-md-6 control-label center">Membership status:</label>
                  <div class="col">
                    <a id="membership_status-label"/>
                  </div>
                  <label class="col-md-6 control-label center">Account Status:</label>
                  <div class="col">
                    <a id="account_status-label"/>
                  </div>

            </div>

    </div>
</div>
