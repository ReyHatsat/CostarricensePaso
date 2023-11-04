<?php
//start the PHP session.
session_start();

//include the configuration file
include('config.php');

//include the functions for the application
include('functions.php');

//include the session validation functions and proccess.
include(SESSION_VALIDATION);

//includes the view validation to show a specific file or path.
include(VIEW_VALIDATION);
?>
