<?php
session_start();
// Create Page Name for Header
$pageName="Create Contributor";
require('/var/www/valve/views/header.php');
echo "<script>console.log('Status: Loaded Header');</script>";
// Include DB Credentials
require('/home/access/valve/dbconnect.php');
echo "<script>console.log('Status: Loaded Database Information');</script>";
// Get FellowshipOne Helper Class
require('FellowshipOne.php');
// Get F1 Credentials
require('/home/access/valve/f1keys.php');
echo "<script>console.log('Status: Retreived F1 Credentials');</script>";

// IF request method == GET
if($_SERVER['REQUEST_METHOD'] === 'GET'){
					  echo "<content>";
	// Create Array full of Contributions that are unmatched
					$c = array();
					$r = $conn->query("SELECT * FROM Contributions WHERE name='{$_GET['name']}' AND type='Credit Card' AND status NOT IN ('Matched')");
					$conn->close();

	// Create table that displays unmatched contribution by person
					echo "<span class='full'>Unmatched Contributions by {$_GET['name']}<br>
						<small>Each contribution listed below will be matched to the new person you create.</small>
								</span>
								<table id='mainTable' class='exceptionList'>
								 <thead class='header'>
										<tr>
											<th>#</th>
											<th>Donor Name:</th>
											<th>Matching To:</th>
											<th>Trip Name:</th>
											<th>Contribution Date: </th>
											<th>Amount: </th>
											<th>Payment Type:</th>
										</tr>
								</thead>
								<tbody>";

					// Loop through each instance of that contributor's giving.
					// And display them in a nice table display.
					if($r->num_rows >= 1){
							while($person = $r->fetch_assoc()){
									array_push($c, $person['id']);
									echo "<tr>";
									echo "<td>{$person['id']}</td>";
									echo "<td>{$person['oldName']}</td>";
									echo "<td>{$person['name']}</td>";
									echo "<td>{$person['tripName']}</td>";
									echo "<td>{$person['contribDate']}</td>";
									echo "<td>{$person['amount']}</td>";
									echo "<td>{$person['type']}</td>";
									echo "</tr>";
							}
					}
					// Close the table tags.
					echo "</tbody></table>";

      // Retreive Household Member Types from F1 for later
      $getHhMemberTypes = $f1->getPeopleHouseholdMemberTypes();
      $householdMemberTypes = $getHhMemberTypes['householdMemberTypes']['householdMemberType'];

      // Retreive People Status Types
      $getMemberStatusTypes = $f1->getPeopleStatuses();
      $getStatusTypes = $getMemberStatusTypes['statuses']['status'];

      // Explode name into an array to get the "Sort" Name
      $nameArray = explode(' ', $_GET['name']);

			if(!$nameArray[2]){
				$suffix = "";
			} else {
				$suffix = $nameArray[2];
			}
			// Implode $c
			$contributions = implode(', ', $c);
      echo "<span class='full'>Please fill out the form below!</span>";

      // Create fields for household creation
      echo "<div class='full'>
              <form name='createHousehold' method='post' action='createContributor.php'>";
          // Create Household Name Field
              echo "<label>Household Name:</label><br>
              <input type='text' value='{$_GET['name']}' name='householdName' size='50'><br><br>";
              // OR
              // echo "<span class='full'><h2>OR</h2></span>";
              // Create Add to Existing Household Field
              // echo "<label>Add to existing household:</label><br>
              // <input type='text' value='' name='existingHousehold' size='50'>";
							// Pass contribution ID as a hidden field
              echo "<input type='hidden' value='{$contributions}' name='contributionArray'>";
							echo "<input type='hidden' value='{$_GET['name']}' name='fullName'>";
      		// Create fields for Person creation
              // Create Person First Name Field
              echo "<label>Person First Name:</label><br>
      	      <input type='text' name='personFirstName' size='50' value='{$nameArray[0]}'><br><br>";
              // Create Person Last Name Field
              echo "<label>Person Last Name:</label><br>
              <input type='text' name='personLastName' size='50' value='{$nameArray[1]}'><br><br>";
              // Create Person Suffix Field
              echo "<label>Suffix:</label><br>
      	      <input type='text' name='personSuffix' size='50' value='{$suffix}'><br><br>";

              // Create Household member type Dropdown
              echo "<label>Household Member Type:</label><br><select name='householdMemberTypeID'>";
              for($i = 0; count($householdMemberTypes) > $i; $i++){ // Loop through array of member types to create <option>s
                echo "<option value='{$householdMemberTypes[$i]['@id']}'>{$householdMemberTypes[$i]['name']}</option>";
              }
              echo "</select><br><br>";

              // Create Member Status Type Dropdown
              echo "<label>Status Type:</label><br><select name='peopleStatusTypes'>";
              for($i = 0; count($getStatusTypes) > $i; $i++){ // Loop through array of Member status types to create <option>s
                echo "<option value='{$getStatusTypes[$i]['@id']}'>{$getStatusTypes[$i]['name']}</option>";
              }
              echo "</select><br><br>";
      echo "</div>"; // End Fields for creating person

              // Close Form and Create Submit!
              echo "<span class='full'><input class='button' type='submit' value='Create This Person!'></span></form>";



}elseif($_SERVER['REQUEST_METHOD'] === 'POST'){ // ELSEIF method POST
	
			echo "<content>";

			$r = $conn->query("SELECT * FROM Contributions WHERE name='{$_POST['fullName']}' AND type='Credit Card' AND status NOT IN ('Matched')");
			$conn->close();

			$row = mysqli_fetch_assoc($r);

			$addressTypes = $f1->getAddressTypes();
			$t = array_search('Primary', array_column($addressTypes['addressTypes']['addressType'], 'name'));
			$addressType = $addressTypes['addressTypes']['addressType'][$t];

			$communicationTypes = $f1->getCommunicationTypes();
			$t = array_search('Home Phone', array_column($communicationTypes['communicationTypes']['communicationType'], 'name'));
			$e = array_search('Email', array_column($communicationTypes['communicationTypes']['communicationType'], 'name'));
			$homePhone = $communicationTypes['communicationTypes']['communicationType'][$t];
			$emailAddress = $communicationTypes['communicationTypes']['communicationType'][$e];

      if (!empty($_POST['existingHousehold'])){
				// Add to existing household
				$householdName = $_POST['existingHousehold'];
			}	else {
				// Create a household
				$householdName = $_POST['householdName'];
      }

			// Create a new household
      $household = $f1->householdModel;
      $household["household"]["householdName"] = $_POST['householdName'];
      $newHouseHold = $f1->createHousehold($household);

			// Get Household ID from the created Household
			$householdID = $newHouseHold['household']['@id'];

      // Create New Person
      $person = $f1->personModel;
      $person["person"]["@householdID"] = $householdID;
      $person["person"]["householdMemberType"]["@id"] = $_POST['householdMemberTypeID'];
      $person["person"]["status"]["@id"] = $_POST['peopleStatusTypes'];
      $person["person"]["firstName"] = ucfirst(strtolower($_POST['personFirstName']));
      $person["person"]["lastName"] = ucfirst(strtolower($_POST['personLastName']));
			$person["person"]["suffix"] = $_POST['personSuffix'];
      $newPerson = $f1->createPerson($person);

			// Get Person ID from the created person
			$personID = $newPerson['person']['@id'];

			// Add Address to Household
			$addressModel = $f1->getAddressModel($newPerson['person']['@id']);
			$addressModel['address']['household']['@id'] = $householdID;
			$addressModel['address']['addressType']['@id'] = $addressType['@id'];
			$addressModel['address']['address1'] = $row['address'];
			$addressModel['address']['address2'] = $row['address2'];
			$addressModel['address']['city'] = $row['city'];
			$addressModel['address']['postalCode'] = $row['zip'];
			$addressModel['address']['stProvince'] = $row['state'];
			$newAddress = $f1->createAddress($addressModel, $personID);

			// Add Phone Number to Person / Household
			$communicationModel = $f1->getPeopleCommunicationModel($personID);
			$communicationModel['communication']['person']['@id'] = $personID;
			$communicationModel['communication']['household']['@id'] = $householdID;
			$communicationModel['communication']['communicationType']['@id'] = $homePhone['@id'];
			$communicationModel['communication']['communicationValue'] = $row['phone'];
			$communicationModel['communication']['preferred'] = true;
			$newHomePhone = $f1->createPeopleCommunication($communicationModel, $personID);

			// Add Email to Person / Household
			// Add Phone Number to Person / Household
			$communicationModel = $f1->getPeopleCommunicationModel($personID);
			$communicationModel['communication']['person']['@id'] = $personID;
			$communicationModel['communication']['household']['@id'] = $householdID;
			$communicationModel['communication']['communicationType']['@id'] = $emailAddress['@id'];
			$communicationModel['communication']['communicationValue'] = $row['email'];
			$communicationModel['communication']['preferred'] = true;
			$newEmail = $f1->createPeopleCommunication($communicationModel, $personID);

			// Now loop through contributions matching this name
			if($r->num_rows > 0){
				date_default_timezone_set('UTC');
				
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

				unset($conn);
				$conn = new mysqli($DB_host, $DB_user, $DB_pass, $DB_name);
				$add = $conn->query("SELECT * FROM Contributions WHERE name='{$_POST['fullName']}' AND type='Credit Card' AND status NOT IN ('Matched')");
				$conn->close();
				while($person = $add->fetch_assoc()){

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


					$receipt = $f1->contributionReceiptModel;
	        $receipt['contributionReceipt']['accountReference']= $person['id'];
					$receipt['contributionReceipt']['amount'] = (float) $person['amount'];
	        $receipt['contributionReceipt']['fund']['@id'] = $fund['@id'];
	        $receipt['contributionReceipt']['fund']['name'] = $fund['name'];
	        $receipt['contributionReceipt']['subFund']['@id'] = $subFund['@id'];
	        $receipt['contributionReceipt']['household']['@id'] = (int) $householdID;
	        $receipt['contributionReceipt']['person']['@id'] = $personID;
	        $receipt['contributionReceipt']['contributionType']['@id'] = $type['@id'];
	        $receipt['contributionReceipt']['contributionSubType']['@id'] = $subType['@id'];
	        $receipt['contributionReceipt']['receivedDate'] = $receivedDate->format(DATE_ATOM);
	        $receipt['contributionReceipt']['transmitDate'] = $transmitDate->format(DATE_ATOM);
	        $receipt['contributionReceipt']['memo'] = "MM Contribution ID:{$person['id']}\nDonated To: {$person['donatedTo']}";

					// Create variable to send the receipt
	        $addContribution = $f1->createContributionReceipt($receipt);
					echo "<br><br>";

					if($addContribution){
					// Update our Database to reflect that the person was "MATCHED"
				            echo "<span class='full'>Added contribution from {$person['name']}!</span>";
					    $conn = new mysqli($DB_host, $DB_user, $DB_pass, $DB_name);
				            $update = "UPDATE Contributions SET status='Matched', note='{$transmitDate->format(DATE_ATOM)}' WHERE id='{$person['id']}'";
					    $conn->query($update);
					    $conn->close();
					    unset($update);
										echo "<span class='full'><a href='/' class='button'>Home</a></span>";
					} else { // If Contribution Receipt was NOT created
				            echo "Could not add {$person['name']}<br>";
					    $conn = new mysqli($DB_host, $DB_user, $DB_pass, $DB_hame);
					    $update = "UPDATE Contributions SET status='ERROR', note='Contribution could not be added to F1!' WHERE id='{$person['id']}'";
					    $conn->query($update);
					    $conn->close();
					    unset($update);
										echo "<span class='full'><a href='/' class='button'>Home</a></span>";
					}} // End "If only one match" && End Foreach Loop
			}

}
?>
</content>
</body>
<script>
var options = document.getElementsByTagName('option');
		for(i=0;options.length > i; i++){
			if(options[i].innerHTML == 'Contributor Only'){
				options[i].selected = 'selected';
			}
}
</script>
