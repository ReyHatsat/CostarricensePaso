<?php

_loggedIn();

if (!$_SESSION[SES_OBJ]['is_admin']) {
  ?>



  <div class="jumbotron text-center">

    <h3>Hmmm, it looks like you do not belong here.</h3>
    <a href="./" class="btn btn-dark text-light"> Back to the page. </a>

  </div>


  <?php

  _component('footer');
  die();
}


// If the user is admin, include by default the datatables component.
_prepareAsyncExecute(function(){
  _component("utils/datatables");
});


?>
