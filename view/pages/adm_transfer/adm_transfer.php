<?php _component('admin/validateIsAdmin'); ?>


<?php if (isset($_GET['id_horse'])) { ?>


    <div class="container p-4">
    
        <a href="?p=adm_horses" class="btn btn-dark text-white">Back to the list</a>
        <div class="card">
            <div class="card-header">
                <h3>Transfering horse ID: <?=$_GET['id_horse']?></h3>
            </div>
            <div class="card-body">

                <div class="form-group">
                    <label for="">Horse Name <small>(Non editable)</small>:</label>
                    <input type="text" class="disabled" readonly id="horse_name">
                </div>

                <div class="form-group">
                    <label for="">Current Owner <small>(Non editable)</small>:</label>
                    <input type="text" class="disabled" readonly id="current_owner_input">
                </div>

                <div class="form-group">
                    <label for="">New Owner:</label>
                    <select name="" id="new_owner_select"></select>
                </div>

                <div class="form-group d-none">
                    <label for=""> Owner since <small>(Non editable)</small>:</label>
                    <input type="date" id="from_date" readonly>
                </div>


                <div class="form-group d-none">
                    <label for=""> Owned until <small>(Non editable)</small>:</label>
                    <input type="date" id="to_date" readonly>
                </div>


                <div class="form-group">
                    <button class="btn btn-dark text-white" id="execute_transfer">Transfer Horse</button>
                </div>

            </div>
        </div>
    </div>



<?php } else { ?>



    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0">Transfer History</h2>
                    </div>
                    <div class="card-body">
                        <table id="transfer_list_datatable" style="width:100%" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID Transfer</th>
                                    <th>Old Owner</th>
                                    <th>Horse Name</th>
                                    <th>Owned From Date:</th>
                                    <th>Owned To Date:</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>




<?php } ?>





