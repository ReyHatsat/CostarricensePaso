<?php

session_start();
$_SESSION['authorize'] = true;
echo json_encode( [ "status" => "success", "code" => 1 ] );

?>
