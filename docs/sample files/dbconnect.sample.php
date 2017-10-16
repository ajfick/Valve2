<?php
// MYSQL Credentials
$DB_host = '// YOUR MYSQL HOST';
$DB_user = '// VALVE USER NAME';
$DB_pass = '// VALVE USER PASSWORD';
// If you would like to change the DB Name, you will need to do so in the /includes/config.php file before running it
$DB_name = 'valve2_0';

$conn = new mysqli($DB_host, $DB_user, $DB_pass, $DB_name);
?>

