<?php
$pageName="Match Giving";
require('/var/www/valve/views/header.php');
?>
<content>
<span class='full'>
<?php
// Include DB Connect
include('/home/access/valve/dbconnect.php');

//Include Helper Class
include('FellowshipOne.php');

// Get F1 Credentials
require('/home/access/valve/f1keys.php');
echo "<script>console.log('Status: Retreived F1 Credentials');</script>";
// GET person ID
$entry = $_POST['resubmit'];

// Get GLOBAL TEAMS Fund
$funds = $f1->getGivingFunds();
$f = array_search('GLOBAL TEAMS', array_column($funds['funds']['fund'], 'name'));
$fund = $funds['funds']['fund'][$f];


// Get Credit Card giving type
$types = $f1->getGivingContributionTypes();
$t = array_search('Credit Card', array_column($types['contributionTypes']['contributionType'], 'name'));
$type = $types['contributionTypes']['contributionType'][$t];

// Get SubType
$subTypes = $f1->getGivingContributionSubTypes($type['@id']);
$st = array_search( 'Managed Missions', array_column($subTypes['contributionSubTypes']['contributionSubType'], 'name'));
$subType = $subTypes['contributionSubTypes']['contributionSubType'][$st];

// Get People with Status Code "unMatched" from Valve2_0
$sql = "SELECT * FROM Contributions WHERE id='{$entry}' AND type='Credit Card' ORDER BY epochDate LIMIT 1";
$results = $conn->query($sql);

if($results->num_rows == 1){ // If there are search results
    while($person = $results->fetch_assoc()){

      $r = $f1->searchPeople(array(//search attributes
          "searchFor"=>$person['name'], // Name
          "include"=>$person['phone'].','.$person['email'], // Phone Number Search for Phone Number
          //"address"=>$person['address']
          ));      
		  date_default_timezone_set('UTC');
		  
// Create Receipt Variables
	// Get subFund aka Trip Name
        $subFunds = $f1->getGivingSubFunds($fund['@id']);
        $sf = array_search( $person['tripName'], array_column($subFunds['subFunds']['subFund'], 'name'));
        $subFund = $subFunds['subFunds']['subFund'][$sf];


	// Create Received Date & Transmit Date
		$contribDate = $person['epochDate']/1000; // Get this math out of the way. MM adds Milliseconds for some god awful reason 
		$addDay = strtotime('+1 day', $contribDate); // Add day to fix Conversion issue between MM and F1
        $cd = date("Y-m-d\TH:i:sP", $addDay);
        $receivedDate = new DateTime($cd);
        $transmitDate = new DateTime();
        
// SINGLE MATCH
    if($r['results']['@totalRecords'] == 1){
        echo "Congrats! We found only 1 match for {$person['name']}.<br>";
	// Create foreach
        foreach($r['results']['person'] as $result){

    // Add Contribution to person
        $receipt = $f1->contributionReceiptModel;
        $receipt['contributionReceipt']['accountReference']= $person['id'];
	$receipt['contributionReceipt']['amount'] = (float) $person['amount'];
        $receipt['contributionReceipt']['fund']['@id'] = $fund['@id'];
        $receipt['contributionReceipt']['fund']['name'] = $fund['name'];
        $receipt['contributionReceipt']['subFund']['@id'] = $subFund['@id'];
        $receipt['contributionReceipt']['household']['@id'] = (int) $result['@householdID'];
        $receipt['contributionReceipt']['person']['@id'] = $result['@id'];
        $receipt['contributionReceipt']['contributionType']['@id'] = $type['@id'];
        $receipt['contributionReceipt']['contributionSubType']['@id'] = $subType['@id'];
        $receipt['contributionReceipt']['receivedDate'] = $receivedDate->format(DATE_ATOM);
        $receipt['contributionReceipt']['transmitDate'] = $transmitDate->format(DATE_ATOM);
        $receipt['contributionReceipt']['memo'] = "MM Contribution ID:{$person['id']}\nDonated To: {$person['donatedTo']}\nMatch Forced By User";

        	// Create variable to send the receipt
                $addContribution = $f1->createContributionReceipt($receipt);

        	// If Contribution Receipt was Created
        	unset($conn);
        	if($addContribution){
        	// Update our Database to reflect that the person was "MATCHED"
                    echo "Added contribution from {$person['name']}!<br>";
        	    $conn = new mysqli($DB_host, $DB_user, $DB_pass, $DB_name);
                    $update = "UPDATE Contributions SET status='Matched', note='{$transmitDate->format(DATE_ATOM)}' WHERE id='{$person['id']}'";
        	    $conn->query($update);
        	    $conn->close();
        	    unset($update);
        	} else { // If Contribution Receipt was NOT created
                    echo "Could not add {$person['name']}<br>";
        	    $conn = new mysqli($DB_host, $DB_user, $DB_pass, $DB_hame);
        	    $update = "UPDATE Contributions SET status='ERROR', note='Contribution could not be added to F1!' WHERE id='{$person['id']}'";
        	    $conn->query($update);
        	    $conn->close();
        	    unset($update);
        	}} // End "If only one match" && End Foreach Loop

              } elseif($r['results']['@totalRecords'] > 1){ // If there is more than one match
              // Get All Results and Assign Variable to them
              // Assign Variable to "unMatched"
            	   echo "Found multiple matches for {$person['name']}!<br>";
        	   $conn = new mysqli($DB_host, $DB_user, $DB_pass, $DB_name);
        	   $update = "UPDATE Contributions SET status='e2x{$r['results']['@totalRecords']}', note='Multiple Matches Found!' WHERE id='{$person['id']}'";
        	   $conn->query($update);
        	   $conn->close();
        	   unset($update);
              } else { // If there are NO results
              // Write code to say "No Results"
        	   echo "Found NO matches for {$person['name']}<br>";
              	   $conn = new mysqli($DB_host, $DB_user, $DB_pass, $DB_name);
        	   $update = "UPDATE Contributions SET status='e1', note='No matches found in Fellowship One! You may need to create a contributor!' WHERE id='{$person['id']}'";
        	   $conn->query($update);
        	   $conn->close();
         	   unset($update);
        	}
          } // End WHILE Loop
        } else {
          echo "<span class='full'><p>There are no new unMatched Contributions right now, try again later!</p></span>";
        }
        ?>
        <span class='half'><a class='button' href='../../index.php'>Back Home</a></span><span class='half'><a class='button' href='matchGiving.php'>Run Again</a></span>
        </content>
        </body>
