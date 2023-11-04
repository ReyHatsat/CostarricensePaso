<?php _component('admin/validateIsAdmin'); ?>
<div class="container-fluid p-4">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
          <div class="card-header">
            <h2 class="mb-0">Membership</h2>
          </div>
          <div class="card-body">
            <button class="btn btn-dark text-light" id="btn-create-membership">
              Add Membership
            </button>
            <hr>
            <table id="tablemembership" style="width:100%" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID Membership</th>
                        <th>Membership</th>
                        <th>Interval Months</th>
                        <th>Price</th>
                        <th>Active</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
          </div>
      </div>
    </div>
  </div>
</div>
