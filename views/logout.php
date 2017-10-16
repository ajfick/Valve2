<?php
//Erase all data associated with Session
$_SESSION = array();

// Kill the session
session_destroy();

//Relocate to Login.php
header('Location: login.php');
?>
