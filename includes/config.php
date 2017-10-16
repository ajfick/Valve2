<?php
//Include Header
$pageName = "Configuration";
include('../views/header.php');

echo "<content>";

// Include DB Credentials
include('/home/access/valve/dbconnect.php');

    //Create Connection
    $conn = new mysqli($server, $db_user, $db_pass);

    //Create Sql statement
    $sql = 'CREATE DATABASE IF NOT EXISTS valve2_0';
    if($conn->query($sql)){
      echo 'Connected To Server Successfully<br>';
    } else {
      echo 'Could not Connect to Database, or Database already exists!<br>';
    }
    unset($conn);
    unset($sql);
    //Create Tables
    $conn = new mysqli($server, $db_user, $db_pass, $db_name);
    // Contributions Table
    $contrib = "CREATE TABLE IF NOT EXISTS Contributions (
        id INT(100) UNIQUE,
        oldName VARCHAR(50) NOT NULL,
        name VARCHAR(50),
        tripName VARCHAR(100),
        donatedTo VARCHAR(100),
        epochDate VARCHAR(30),
        contribDate VARCHAR(30),
        pullDate TIMESTAMP,
        type VARCHAR(25),
        chkNum INT(8) DEFAULT NULL,
        amount INT(10) NOT NULL,
        address VARCHAR(255),
        address2 VARCHAR(255),
        city VARCHAR(50),
        state VARCHAR(30),
        zip VARCHAR(11),
        email VARCHAR(50),
        phone VARCHAR(20),
        status VARCHAR(12),
        note  VARCHAR(255)
      )";
    // Users Query
    $users = "CREATE TABLE IF NOT EXISTS Users (
        id INT(9) AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(60),
        password VARCHAR(255),
        name VARCHAR(60),
        role VARCHAR(20))";
    // Config Query
    $config = "CREATE TABLE IF NOT EXISTS Config (
 `valveURL` varchar(255) DEFAULT NULL,
 `mmKey` varchar(255) DEFAULT NULL,
 `f1URL` varchar(255) DEFAULT NULL,
 `f1Key` varchar(255) DEFAULT NULL,
 `f1Secret` varchar(255) DEFAULT NULL,
 `f1User` varchar(255) DEFAULT NULL,
 `f1Pass` varchar(255) DEFAULT NULL,
 `ldapOn` bit(1) DEFAULT NULL,
 `ldapHost` varchar(255) DEFAULT NULL,
 `ldapDN` varchar(255) DEFAULT NULL,
 `ldapUserGroup` varchar(255) DEFAULT NULL,
 `ldapManagerGroup` varchar(255) DEFAULT NULL,
 `ldapUserDomain` varchar(255) DEFAULT NULL,
 `matchGivingLimit` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1";

    // Run Contrib Query
    if($conn->query($contrib)){
     echo 'Contributions table created successfully!<br>';
    } else {
     echo 'Failed to create Contributions table, or Table already exists!<br>';
    }
    unset($contrib);

    // Run Users Query
    if($conn->query($users)){
     echo 'Users table created Successfully!<br>';
    } else {
     echo 'Failed to create Users table, or Table already exists!<br>';
    }
    unset($users);

    // Run Config Query
    if($conn->query($users)){
        echo 'Config table created Successfully!<br>';
    } else {
        echo 'Failed to create Config table, or Table already exists!<br>';
    }
    unset($users);
?>

<form action="updateSettings.php" method="post">
    <h1>Settings:</h1>
    <div class="row">
        <h2><small>Valve Operation Settings</small></h2>
        <div class="col-md-6">
            <div class="input-group">
                <label class="input-group-addon">Match Giving Limit: </label>
                <input class="form-control" type="number" name="matchGivingLimit" value="" placeholder="15">
            </div><!-- end .input-group -->
        </div><!-- end col-md -->
        <div class="col-md-6">
            <div class="input-group">
                <label class="input-group-addon">Valve URL: </label>
                <input class="form-control" type="text" name="valveURL" value="">
            </div><!-- end .input-group -->
        </div><!-- end col-md -->
    </div><!-- End .row -->
    <div class="row">
        <h2><small>API Settings</small></h2>
        <div class="col-md-12">
            <div class="input-group">
                <label class="input-group-addon">Managed Missions API Key: </label>
                <input class="form-control" type="text" name="mmKey" value="">
            </div>
        </div>
        <div class="col-md-12">
            <div class="input-group">
                <label class="input-group-addon">F1 URL http://www.</label>
                <input class="form-control" type="text" name="f1URL" value="">
                <label class="input-group-addon">.fellowshiponeapi.com</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group">
                <label class="input-group-addon">F1 Key: </label>
                <input class="form-control" type="text" name="f1Key" value="">
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group">
                <label class="input-group-addon">F1 Secret: </label>
                <input class="form-control" type="text" name="f1Secret" value="">
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group">
                <label class="input-group-addon">F1 User: </label>
                <input class="form-control" type="text" name="f1User" value="">
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group">
                <label class="input-group-addon">F1 Pass: </label>
                <input class="form-control" type="text" name="f1Pass" value="">
            </div>
        </div>
    </div><!-- End .row -->
    <div class="row">
        <h2><small>LDAP Settings</small></h2>
        <div class="col-md-12">
            <label for="ldapOn">I would like to use LDAP for user Login.</label>
            <input class="" type="checkbox" name="ldapOn">
        </div>
        <div class="col-md-6">
            <div class="input-group">
                <label class="input-group-addon">LDAP Host: </label>
                <input class="form-control" type="text" name="ldapHost" value="">
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group">
                <label class="input-group-addon">LDAP Domain: </label>
                <input class="form-control" type="text" name="ldapUserDomain" value="">
            </div>
        </div>
        <div class="col-md-12">
            <div class="input-group">
                <label class="input-group-addon">LDAP DN: </label>
                <input class="form-control" type="text" name="ldapDN" value="">
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group">
                <label class="input-group-addon">User Group: </label>
                <input class="form-control" type="text" name="ldapUserGroup" value="">
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group">
                <label class="input-group-addon">Manager Group: </label>
                <input class="form-control" type="text" name="ldapManagerGroup" value="">
            </div>
        </div>
    </div><!-- End .row -->
    <div class="row">
        <h2><small>Add Local User</small></h2>
        <div class="col-md-6">
            <div class="input-group">
                <label class="input-group-addon">User Name:</label>
                <input class="form-control" type="text" name="userName" value="">
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group">
                <label class="input-group-addon">User Email:</label>
                <input class="form-control" type="email" name="userEmail" value="">
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group">
                <label class="input-group-addon">User Password:</label>
                <input class="form-control" type="password" name="userPassword" value="">
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group">
                <select name="userRole">
                    <option value="1">User</option>
                    <option value="2">Manager</option>
                </select>
            </div>
        </div>
    </div><!-- End .row -->
    <div class="row">
        <button class="btn btn-primary text-center align-center" type="submit">Save Changes</button>
    </div>
</form>
</content>
</body>
</html>


