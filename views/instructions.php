<?php
session_start();
$pageName = 'Instructions';
include('header.php');
?>
<content>
  <h2 class="align-center">How to Use Valve 2.0</h2>
  &nbsp;
  <h3 class="align-center">Step 1: Log In</h3>
  <ul class="instructions">
    <li>For security reasons, this version of the Valve application requires a login. The only people who are able to access the valve are those who are part of the Accounting and Techops departments at GT.</li>
    <li>The username field will be your GT email address but <b>without</b> the '@gtaog.org'. So 'JohnSmith@gtaog.org' becomes just 'JohnSmith'</li>
    <li>The password field, like the username field, is simply the password that you enter to log into your GT email account. Keep in mind that when you change the password to log in to your email, it will also change here!</li>
    <li>If you ever experience trouble logging in please contact AJ or Bill in Techops as soon as possible. If you are having an issue it is likely that everyone is!</li>
  </ul>
  &nbsp;
  <h3 class="align-center">Step 2: Importing Managed Missions Contributions</h3>
  <ul class="instructions">
    <li>To import new Managed Missions contributions, simply go to the "Home" page and click on the <button>Get Contributions</button> button.</li>
    <li>Once this button is clicked the page may take a few minutes to load. That is because the application needs to make a request to Managed Missions for all of the information about new payments.</li>
    <li>While the information comes back to you, you will see information begin to list on the page.</li>
    <li>Once all of the information is added to the Valve, a success message will appear with a button to take you back to the home page!</li>
  </ul>
  <h3 class="align-center">Step 3: Adding contributions to Fellowship One</h3>
  <ul class="instructions">
    <li>Once you have added new contributions to the Valve, click the <button>Match Contributions</button> button on the home page. When we initially launch this version of the Valve, you will be limited to matching 10 people at a time. This is just so that we can't possibly add an incorrect record more than 10 times. Adding an incorrect record ... say 137 times would be a bummer.</li>
    <li>You will see a status bar, as well as some information get prined on the page while the contributions are added to F1. </li>
    <li>The Valve will notify you when people are matched successfuly, Not found, or when multiple matches were found.</li>
    <li>When contributors are not found or multiple matches were found for one contributor, no information will be added to F1. This is because we want to avoid needing to delete a ton of incorrect entries in F1. It also helps in detecting existing errors in F1.</li>
    <li>When no contributors were found in F1, an <button>Add Contributor</button> button will appear in the "Status" column. When this button is clicked the contributor will be added to F1 along with their contribution. NOTE: if this person has made more than one contribution that has been pulled into the valve these contributions will be added too!</li>
    <li>Unfortunately, if the Valve finds multiple matches in F1, you will need to either add the contribution manually or if there is a duplicate person in F1 delete them. You will then need to click on the <button>Edit</button> corresponding to the entry you are trying to match again and then click the <button>Match</button> at the bottom of the page. 
  </ul>
</content>
