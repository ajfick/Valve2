<?php
session_start(); // start session
$pageName = "Get Contributions";
require('../views/header.php');
echo "<content>";
date_default_timezone_set('UTC');
// Credentials
include('/home/access/valve/dbconnect.php');

$mk = "SELECT * FROM Config"; // Get Config
$mresults = $conn->query($mk); // Make the SQL query
$mrow = $mresults->fetch_assoc(); // Get association names

$mmKey = $mrow['mmKey']; //Assign Managed Missions API key from DB to $mmKey


// Check Connection
if ($conn->connect_error){
  die('Error connecting to the Database: ' . $conn->connect_error);
}

  $sql = "SELECT * FROM Contributions WHERE type='Credit Card' ORDER BY epochDate DESC LIMIT 1"; // Get most recent DB entry
  $results = $conn->query($sql); // Make the SQL query
  $row = $results->fetch_assoc(); // Get association names

// Use the most recent contribution date to create a "StartDate" for our Managed Missions query
if($results->num_rows == 1){ // Run this block if the Database only returns one date for "StartDate"
  $apiParameters = array( // Add arguments to the POST call
  'StartDate' => $row['contribDate'] // Set the Start Date to the last date that we have in the database, This will INCLUDE the last date entered
  );
  echo "<span class='full'>Managed Missions query Start Date: {$row['contribDate']}</span>"; // Echo the Contribution Date That was pulled to the Conosole
} elseif($results->num_rows < 1){ // If the Database returns ZERO results for the start date, something is wrong

   echo "No date was provided as a 'Start Date', that means this will pull every contribution to Managed Missions!";

 } else { // If the Database returns MORE THAN ONE result something is wrong!

  echo "Error: More than one date was given for Managed Missions parameter 'StartDate'. This could cause duplicate entries in the GT Database.<br>";

} // End If Else Statement

// Call to MM to get Json Data
$apiSend = curl_init("https://app.managedmissions.com/API/ContributionAPI/List?apiKey=".$mmKey); // Curl the URL and API Key together
curl_setopt($apiSend, CURLOPT_RETURNTRANSFER, true); // Set the request to return something (a JSON doc)
curl_setopt($apiSend, CURLOPT_POSTFIELDS, $apiParameters); // Set the request to look for Contributions made ON or AFTER the day of our last "PULL"
$apiReturned = curl_exec($apiSend); // Execute the curl, and return it as a variable called $result
$apiJson = json_decode($apiReturned, true); // Decode the Json that was returned in the variable $result

// Unset our variables so they can be reused without error.
unset($sql);
unset($results);
unset($row);
unset($data);
$conn->close();

foreach( $apiJson['data'] as $mm ) { // Turn the $json variable into $mm for brevity and namespacing
  $conn = new mysqli($DB_host, $DB_user, $DB_pass, $DB_name); // Create new Mysql Connection

  $sql = "SELECT * FROM Contributions WHERE id='".$mm['Id']."' AND oldName='".$mm['DonorName']."'"; // Check to see if this entry already exists in our DB
  $results = $conn->query($sql); // Run the Database query for each of the new Contributions to check against


  if($results->num_rows == 0){ // If this entry does not already exist run this block

    // Convert Date To Readable Format
    $epochDate = str_replace(array('/Date(', ')/'), '', $mm['DepositDate']);
    $dateTime = gmdate("m/d/Y", str_replace(array('/Date(', ')/'), '', $mm["DepositDate"]) / 1000); // Convert Epoch Time w/ Milliseconds to Readable Format

    // Fix the Laos Trip Name for F1
    if($mm['MissionTripName'] == "Laos Summer Exp."){
      $mm['MissionTripName'] = "Laos";
    }

    // Turn Reference Number into Check #, Card, Or Cash
    if(is_numeric($mm['ReferenceNumber'])){
      $paymentType = 'CHECK';
      $chkNum = $mm['ReferenceNumber'];
    } else {
      $paymentType = $mm['ReferenceNumber'];
      $chkNum = 'NULL';
    }

//    // Fix Names
//    // Constrict name to Alphanumeric ONLY
//    $name = preg_replace( "/[^a-zA-Z- ]/i", "", $mm['DonorName']);
//    $name = str_replace(array("\'", '"', "'s",), '', $name);
//    $name = htmlspecialchars($name);
//    $name = rtrim($name, ',');
//
//    // Explode $name into array
//    $nameArray = explode(' ', $name);
//
//    // Create Familial Names Array
//    $familialNames = array('mom', 'dad', 'mother', 'father', 'aunt', 'auntie', 'uncle', 'cousin', 'grandmother', 'grandma', 'granny', 'grammy', 'nanny', 'grandfather', 'grandpa', 'pop', 'poppop', 'family', 'friends', 'friend', 'and');
//
//    // Create Suffix Array
//    $suffixes = array('Sr', 'senior', 'Jr', 'junior', 'I', 'II', 'III', 'IV', 'V', 'MD', 'Phd',);
//    $suffix = array_intersect(array_map('strtolower', $nameArray), array_map('strtolower', $suffixes));
//    echo implode('', $suffix);
//
//    // Find Differences between The POST and $familialNames
//    $names = array_udiff($nameArray, $familialNames, 'strcasecmp');
//
//    // Check if there are more than 2 things in $nameArray
//    if(count($nameArray) >= 2){
//            $firstName = reset($names);
//            $lastName = end($names);
//            if(strtolower($lastName) == implode('', $suffix) || $lastName == '' || $lastName == ' '){
//                $count = 0;
//                while(is_string($lastName) == FALSE || $lastName == '' || $lastName == ' ' || strtolower($lastName) == implode('', $suffix)){
//                    $index = count($nameArray);
//                    $index = $index - $count;
//                    $lastName = $names[$index];
//                    $count += 1;
//                }
//            }
//        $newName = ucfirst(strtolower($firstName)) . " " . ucfirst(strtolower($lastName));
//    } else {
//      $newName = "";
//    }
    // Create $status
    $status = 'unMatched';

    //Create Array with ALL needed Managed Missions VALUES
    $data1 = $mm['Id']."', '". $mm['DonorName']."', '". $mm['DonorName'] ."', '". $mm['MissionTripName']."', '". $mm['PersonName']."', '". $epochDate."', '". $dateTime."', '". $paymentType."', '". $chkNum."', '";
    $data2 = $mm['ContributionAmount']."', '".$mm['Address1']."', '". $mm['Address2']."', '". $mm['City']."', '". $mm['State']."', '". $mm['PostalCode']."', '". $mm['EmailAddress']."', '";
    $data3 = $mm['PhoneNumber']."', '".$status;

    // Add to Database
    $add = "INSERT IGNORE INTO Contributions (id, oldName, name, tripName, donatedTo, epochDate, contribDate, type, chkNum, amount, address, address2, city, state, zip, email, phone, status) VALUES('" . $data1 . $data2 . $data3 . "')";
    $conn->query($add);
    $conn->close();
    unset($firstName, $lastName, $name, $nameArray, $newName);
  } else {
    continue; // Break out of this iteration of the Foreach loop, move on to the next
  } // End Else
} // End Foreach Loop
echo "<span class='full'>Process Completed! Got all new contributions from Managed Missions.</span>";
echo "<span class='half'><a class='button' href='/'>Go Back Home</a></span>";
echo "<span class='half'><a class='button' href='f1/matchGiving.php'>Match Contributions</a></span>";

//(id, name, epochDate, contribDate, type, chkNum, amount, address, city, state, zip, email, phone, status, error)
?>
</content>
