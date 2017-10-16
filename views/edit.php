<?php
session_start(); // Start session
// Get id# of entry we want to edit.
$entry = $_GET['edit'];

$pageName = "Edit Contribution {$entry}";

//Check if logged in
if (!isset($_SESSION['email']) && !isset($_SESSION['role'])){
  header('Location: login.php');
}

include('header.php');
?>
<content>
  <?php
    if(!empty($_POST['submit']) && !empty($_POST['name'])){

        // Include DB Credentials
      include('/home/access/valve/dbconnect.php');
      $sql = "UPDATE Contributions SET name='{$_POST['name']}', email='{$_POST['email']}', phone='{$_POST['phone']}', address='{$_POST['address']}', address2='{$_POST['address2']}', city='{$_POST['city']}', state='{$_POST['state']}', zip='{$_POST['zip']}', status='{$_POST['status']}' WHERE id='{$_POST['submit']}'";

      if($conn->query($sql)){
        echo "<span class='full'>Entry for {$_POST['name']} updated successfully!</span>";}
    	} else {
	echo "<div class='align-center'>Now Editing: <br>Contribution No. {$entry}</div>";
    include('/home/access/valve/dbconnect.php');
    $sql = "SELECT * FROM Contributions WHERE id='{$entry}'";
    $results = $conn->query($sql);
    if($results->num_rows == 1){
      while($row = $results->fetch_assoc()) {
        echo "<form name='toEdit' method='Post'>";
          echo "<table id='mainTable' class='exceptionList'>
                <thead class='header'>
                  <tr>
                    <th>Name:</th>
                    <th>Email:</th>
                    <th>Phone:</th>
                  </tr>
                </thead>
                <tbody><tr>";
          echo "<td><input required name='name' type='text' size='30' value='{$row['name']}'></td>";
          echo "<td><input name='email' type='email' size='30' value='{$row['email']}'></td>";
          echo "<td><input name='phone' type='tel' size='30' value='{$row['phone']}'></td>";
          echo "</tr></tbody>";
          echo "<thead class='header'>
                  <tr>
                    <th>Address Line 1:</th>
                    <th>Address Line 2:</th>
                    <th>City:</th>
                  </tr>
                </thead>
                <tbody><tr>";
          echo "<td><input name='address' type='text' value='{$row['address']}'></td>";
          echo "<td><input name='address2' type='text' value='{$row['address2']}'></td>";
          echo "<td><input name='city' type='text' value='{$row['city']}'></td>";
          echo "</tr></tbody>";
          echo "<thead class='header'><tr><th colspan=1.5 >State:</th><th>Postal Code:</th><th>Status:</th></tr></thead>";
          echo "<tr><td><input name='state' type='text' value='{$row['state']}'></td>";
          echo "<td><input name='zip' type='text' size='7' value='{$row['zip']}'></td>";
          echo "<td><select name='status'>
          <option value='{$row['status']}' selected>{$row['status']}</option>
          <option value='unMatched'>unMatched</option>
          <option value='Matched'>Matched</option>
          </select></td></tr>";
          echo "</table>";
          echo "<span class='full'>";
	        echo "<button formaction='edit.php' value='{$entry}' class='submit' type='submit' name='submit'>Save Changes</button>";
          echo "<button formaction='../includes/f1/forceMatchGiving.php' value='{$entry}' class='submit' type='submit' name='resubmit'>Match</button>";
	      echo "</span>";
	      echo "</form>";
        }//End While
  }// End If
}// End Else
  ?>
</content>
