<?php
$pageName = 'Login';
include('header.php');
include("../includes/authenticate.php");

// check to see if user is logging out
if(isset($_GET['out'])) {
	// destroy session
	session_unset();
	$_SESSION = array();
	unset($_SESSION['user'],$_SESSION['access']);
	session_destroy();
}
 
// check to see if login form has been submitted
if(isset($_POST['userLogin'])){
	// run information through authenticator
	if(authenticate($_POST['userLogin'],$_POST['userPassword']))
	{
		// authentication passed
		header("Location: ../index.php");
		die();
	} else {
		// authentication failed
		$error = 1;
	}
}

echo "<content>";
 
// output error to user
if(isset($error)) echo "<span class='full'>Login failed: Incorrect user name, password, or rights</span>";
 
// output logout success
if(isset($_GET['out'])) echo "<span class='full'>Logout successful</span>";
?>
      <div class="formContainer">
        <h3>Log In</h3>
      <form class="login" action="login.php" method="post">
        <label>User Name:</label><br>
        <input type="text" name="userLogin" size="35" required><br>
        <label>Password:</label><br>
        <input type="password" name="userPassword" required><br>
        <button class="submit" type="submit">Submit</button>
      </form>
    </div>
    </content>
  </body>
</html>
