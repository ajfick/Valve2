<?php
//Start Session
session_start();
$pageName = 'Home';

//Check if logged in
if (!isset($_SESSION['email']) && !isset($_SESSION['role'])){
  header('Location: views/login.php');
}

include('views/header.php'); ?>
    <content>
      <span class="full">
        <a class="button" href="/includes/getMM.php">Get Contributions</a>
        <a class="button" href="/includes/f1/matchGiving.php">Match Contributions</a>
      </span>
      <span class="full">
        <form action="/" method="post">
        <select name="mainPageView">
          <option value="all">All</option>
          <option value="unMatched">unMatched</option>
          <option value="Matched">Matched</option>
          <option value="e1">Add Contributor</option>
          <option value="e2">Multiple Matches</option>
        </select>
        <input type="search" placeholder="Search ... " name="search" size="35">
        <input type="submit" value="Go!">
      </form>
      </span>
      <!-- pager -->
	<span class="full">
	<div class="pager">
	    <img src="http://mottie.github.com/tablesorter/addons/pager/icons/first.png" class="first" />
	    <img src="http://mottie.github.com/tablesorter/addons/pager/icons/prev.png" class="prev" /> <span class="pagedisplay"></span>
	    <!-- this can be any element, including an input -->
	    <img src="http://mottie.github.com/tablesorter/addons/pager/icons/next.png" class="next" />
	    <img src="http://mottie.github.com/tablesorter/addons/pager/icons/last.png" class="last" />
	    <select class="pagesize" title="Select page size">
	        <option selected="selected" value="10">10</option>
	        <option value="20">20</option>
	        <option value="30">30</option>
	        <option value="40">40</option>
	    </select>
	    <select class="gotoPage" title="Select page number"></select>
	</div></span>
      <table id="mainTable" class="exceptionList">
        <thead class="header">
            <tr>
            <th>#</th>
            <th>Donor Name:</th>
            <th>Trip Name:</th>
            <th>Donated To:</th>
            <th>Contribution Date:</th>
            <th>Amount:</th>
            <th>Payment Type:</th>
            <th>Status:</th>
            <th>Actions:</th>
            </tr>
        </thead>
        <tbody>
          <?php
          include('/home/access/valve/dbconnect.php');
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                if(!empty($_POST['search']) && $_POST['mainPageView'] == 'all'){
                  $sql = "SELECT * FROM Contributions WHERE name LIKE '%{$_POST['search']}%' AND type='Credit Card'";
                } elseif(!empty($_POST['search']) && $_POST['mainPageView']!== 'all') {
                  $sql = "SELECT * FROM Contributions WHERE name LIKE '%{$_POST['search']}%' AND status='{$_POST['mainPageView']}' AND type='Credit Card'";
                }
              } else {
                $sql = 'SELECT * FROM Contributions WHERE type="Credit Card"';
              }
            $results = $conn->query($sql);

            if($results->num_rows > 0){
                while($row = $results->fetch_assoc()){
                  echo "<tr>";
                  echo "<td>{$row['id']}</td>";
                  echo "<td>{$row['name']}</td>";
                  echo "<td>{$row['tripName']}</td>";
                  echo "<td>{$row['donatedTo']}</td>";
                  echo "<td>{$row['contribDate']}</td>";
                  echo "<td>\${$row['amount']}</td>";

		  // Use Icons for contribution Type
		  if($row["type"] == 'Credit Card'){
		    echo "<td>Credit Card</td>";
          } elseif ($row["type"] == 'Cash'){
		    echo "<td> <i class='fa fa-money fa-2x'></i> </td>";
		  }
		  // Set Status tooltips and Links
		  if($row["status"] == 'e1'){
                    echo "<td><form name='addContrib' action='/includes/f1/createContributor.php' method='get'><button name='name' type='submit' value='{$row['name']}'>Add Contributor</button></form></td>";
                  } elseif($row["status"] == 'Matched') {
                      echo "<td>{$row['status']}</td>";
                  } elseif($row["status"] == 'unMatched') {
                      echo "<td>{$row['status']}</td>";
		  } else {
		      echo "<td><div class='tooltip href='#'>{$row['status']}<span class='tooltiptext'>This contributor was matched to multiple people in the F1 Databse. Please check to see if there is an error in F1, or in the entry here.The number after the 'x' indicates how many results were found in the search.</span></div></td>";
                  }
                  echo "<td><form name='actions' method='get' action='views/edit.php'><button name='edit' class='edit' type='submit' value='{$row['id']}'> Edit</button></form></td>";
                  echo '</tr>';
                }
            } else {
              echo '<tr><td colspan="9">0 results to show!</td><tr>';
            }
          ?>
	</tbody>
      </table>
      <span class="full">
        <div class="pager">
            <img src="http://mottie.github.com/tablesorter/addons/pager/icons/first.png" class="first" />
            <img src="http://mottie.github.com/tablesorter/addons/pager/icons/prev.png" class="prev" /> <span class="pagedisplay"></span>
            <!-- this can be any element, including an input -->
            <img src="http://mottie.github.com/tablesorter/addons/pager/icons/next.png" class="next" />
            <img src="http://mottie.github.com/tablesorter/addons/pager/icons/last.png" class="last" />
            <select class="pagesize" title="Select page size">
                <option selected="selected" value="10">10</option>
                <option value="20">20</option>
                <option value="30">30</option>
                <option value="40">40</option>
            </select>
            <select class="gotoPage" title="Select page number"></select>
        </div></span>
    </content>
  </body>
</html>
