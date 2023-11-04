<?php


//Validates if user is not logging out.
if (isset($_GET[LOGOUT]) && _session()) {
  session_destroy();
  header("Refresh:0");
}



//Validate if the user is admin and give access to the view
if (isset($_SESSION[SES_OBJ]) && !is_null($_SESSION[SES_OBJ][ADM_VALID])) {
  define(ADM_VARIABLE, true);
}



//The user is admin and is accessing into the admin view
if (isset($_GET[ADM_ACC]) && !is_null($_SESSION[SES_OBJ][ADM_VALID])){
  $_SESSION[ADM_ACC] = true;
  header(ADM_REDIR);
  die();
}


//the user is exitting the admin view
if (isset($_GET[EXIT_ADM])) {
  unset($_SESSION[ADM_ACC]);
  header(ADM_REDIR);
  die();
}



//Function to validate the admin view access.
function validateAdminPage(){
    return(
        isset($_SESSION[SES_OBJ])
        && !empty($_SESSION[SES_OBJ])
        && !isset($_GET[LOGOUT])
        && isset($_SESSION[ADM_ACC])
        && !is_null($_SESSION[SES_OBJ][ADM_VALID])
        && isset($_GET[ADM_PAGE])
    );
}



//function to validate the client view access.
function validatePage(){
    return(
        isset($_SESSION[SES_OBJ])
        && !empty($_SESSION[SES_OBJ])
        && !isset($_GET[LOGOUT])
    );
}


?>
