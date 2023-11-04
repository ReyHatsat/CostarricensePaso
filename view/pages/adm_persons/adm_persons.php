<?php _component('admin/validateIsAdmin'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/aes.js" integrity="sha256-/H4YS+7aYb9kJ5OKhFYPUjSJdrtV6AeyJOtTkw6X72o=" crossorigin="anonymous"></script>
<div class="container-fluid p-4">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
          <div class="card-header">
            <h2 class="mb-0">Users</h2>
          </div>
          <div class="card-body">
            <button class="btn btn-dark text-light" id="btn-create-person">
              Add Users
            </button>
            <hr>
            <table id="tableusers" style="width:100%" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID Person</th>
                        <th>Name</th>
                        <th>Last Name</th>
                        <th>E-mail</th>
                        <th>Person Type</th>
                        <th>Membership</th> <!--  (el dato member) -->
                        <th>Status</th><!--  (activado, inactivado o pendiente) -->
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
          </div>
      </div>
    </div>
  </div>
</div>
