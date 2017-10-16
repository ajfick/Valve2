<h1>What is Valve</h1>

Valve is a LAMP based web app created by [GT Church](http://www.gtaog.org) to pull contribution data from <b>Managed Missions</b> giving platform, add that information to a <b>mySQL</b> on a linux server, and transmit that data to <b>Fellowship One</b> in the form of a giving receipt. 

The first iteration of the Valve worked but could also create serious issues if something went wrong. So, in Valve 2.0 we moved to a slightly less automated and more manual approach that still saves tons of time and energy compared to entering the data all by hand!

Valve would not have been possible with out the [Fellowship One Helper Class](https://github.com/deboorn/Fellowship-One-API-Helper) created by github user deboorn!! Thank you Thank you!!

<h2>You Will Need</h2>

* Your Fellowship One User account, App code, and secret
* Your Fellowship One User account, TEST App code, and TEST secret (we recommend using the F1 staging portal for testing!)
* Your Managed Misions API key
* A LAMP stack with oAuth installed and configured
* A dedicated mySQL database for Valve

<h2>Setting up your Database</h2>

* As of version 2.0.3 valve stores all api keys in the mysql database in the Config table.

* All database information is stored in a file called ```/dbconnect.php``` and can be located in any location on your system that you would like. We chose to store it in the following location...

```
/home/access/valve
```

* If you wish to change that location you will also need to change references at the top of the following files: 

```
  */views/edit.php
  */views/login.php
  */views/settings.php
  */includes/authenticate.php
  */includes/config.php
  */includes/getmm.php
  */includes/updateSettings.php
  */includes/f1/matchGiving.php
  */includes/f1/createContributor.php
  */includes/f1/forceMatchGiving.php
```

* There is a copy of the ```dbconnect.php``` file in ```/docs/samplefiles``` which can be edited to include your mysql database information, and placed either in ```/home/access/valve/``` or wherever you may like. 

<br>

<h1>How does it work?</h1>

* Valve makes use of the [Fellowship One Helper Class](https://github.com/deboorn/Fellowship-One-API-Helper) to create the giving receipts.

* When the "Get Contributions" button is engaged, Valve will look at your mySQL database created in the config file, and determine the last "pull" date. Meaning, the last date that information was retreived from Managed Missions. 

* Next, Valve will ask Managed Missions for any contributions made between the last pull date and the current date (including the pull date and current date). The Valve will then save information about these contributions to the Valve mySQL database table, so long as the contribution ID does not already exist.

* During the process of looping through the new contributions from Managed Missions, the Valve will add the value "unMatched" to the "status" column of the table to indicate that the contribution has not yet been matched to a contributor in Fellowship One and thus a giving receipt has not yet been created. 

* When the "Match Contributions" button is engaged, The Valve will loop through 10 unmatched contributions and search Fellowship One for any person in the database with a matching Name (If more than one name matches, it will move on to matching either phone number or email address).

* The Valve will then add a giving receipt to that person for their contribution containing information in the following places in the receipt: 
<br>

```
Account Reference: [Managed Missions Contribution ID]

Amount : [Contribution Amount]

Fund : [Fund Name & ID]

SubFund: [SubFund ID]

HouseHold: [Household ID]

Person: [Person ID]

Contribution Type: [Contribution Subtype ID]

Received Date: [Date that contribution was made to MM]

Transmit Date: [Date that the receipt was created in F1]

Memo: "MM Contribution ID: ##Contribution ID## Donated to: Recipient Name"
```

