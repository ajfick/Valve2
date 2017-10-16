<?php
//Start Session
session_start();
$pageName = 'Settings';

//Check if logged in
if (!isset($_SESSION['email']) && !isset($_SESSION['role'])){
  header('Location: login.php');
}
include('header.php');
// Include DB Credentials
require('/home/access/valve/dbconnect.php');

if(isset($_POST['ldapOn'])){
    $ldapOn = 1;
} else {
    $ldapOn = 0;
}

if(!empty($_POST['mmKey']) && !empty($_POST['f1URL'])){

	$action = "";
	
	$sql = " UPDATE Config SET
	valveURL = '{$_POST['valveURL']}',
	mmKey = '{$_POST['mmKey']}',
	f1URL = '{$_POST['f1URL']}',
	f1Key = '{$_POST['f1Key']}',
	f1Secret = '{$_POST['f1Secret']}',
	f1User = '{$_POST['f1User']}',
	f1Pass = '{$_POST['f1Pass']}',
	ldapOn = {$ldapOn},
	ldapHost = '{$_POST['ldapHost']}',
	ldapDN = '{$_POST['ldapDN']}',
	ldapUserGroup = '{$_POST['ldapUserGroup']}',
	ldapManagerGroup= '{$_POST['ldapManagerGroup']}',
	ldapUserDomain = '{$_POST['ldapUserDomain']}',
	matchGivingLimit = '{$_POST['matchGivingLimit']}'";
	
	$conn->query($sql);
	$updateCode = "200";
} else {
	// Required Fields are missing
}

// Send user back to Settings Page
header("Location: " . "http://valve.gtaog.intra/views/settings.php" . "?updateCode=" . $updateCode);
?>