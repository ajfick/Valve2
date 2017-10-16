<?php
//Start Session
session_start();
$pageName = 'Settings';

//Check if logged in
if (!isset($_SESSION['email']) && !isset($_SESSION['role'])){
  header('Location: login.php');
}
include('header.php'); 
require('/home/access/valve/dbconnect.php');
$sql = "SELECT * FROM Config";
$results = $conn->query($sql);
$setting = $results->fetch_assoc();
?>
    <content class="container-fluid">
    <form action="../includes/updateSettings.php" method="post">
    	<h1>Settings:</h1>
    	<?php 
		if($_GET['updateCode'] == 200){
			echo "<div class='col-md-12 bg-success' style='padding: 1em 1em; margin: 0px 0px 1em 0px;'><i class='fa fa-thumbs-o-up fa-lg'></i> Updated Successfully!</div>";
		}
	?>
    	<div class="row">
    		<h2><small>Valve Operation Settings</small></h2>
    		<div class="col-md-6">
	    		<div class="input-group">
		    		<label class="input-group-addon">Match Giving Limit: </label>
	    			<input class="form-control" type="number" name="matchGivingLimit" value="<?php echo $setting['matchGivingLimit'];?>">
	    		</div><!-- end .input-group -->
			</div><!-- end col-md -->
			<div class="col-md-6">
	    		<div class="input-group">
		    		<label class="input-group-addon">Valve URL: </label>
	    			<input class="form-control" type="text" name="valveURL" value="<?php echo $setting['valveURL'];?>">
	    		</div><!-- end .input-group -->
			</div><!-- end col-md -->
    	</div><!-- End .row -->
    	<div class="row">
    		<h2><small>API Settings</small></h2>
    		<div class="col-md-12">
	    		<div class="input-group">
		    		<label class="input-group-addon">Managed Missions API Key: </label>
	    			<input class="form-control" type="text" name="mmKey" value="<?php echo $setting['mmKey'];?>">
	    		</div>
			</div>
			<div class="col-md-12">
	    		<div class="input-group">
		    		<label class="input-group-addon">F1 URL http://www.</label>
	    			<input class="form-control" type="text" name="f1URL" value="<?php echo $setting['f1URL'];?>">
	    			<label class="input-group-addon">.fellowshiponeapi.com</label>
	    		</div>
			</div>
			<div class="col-md-6">
	    		<div class="input-group">
		    		<label class="input-group-addon">F1 Key: </label>
	    			<input class="form-control" type="text" name="f1Key" value="<?php echo $setting['f1Key'];?>">
	    		</div>
			</div>
			<div class="col-md-6">
	    		<div class="input-group">
		    		<label class="input-group-addon">F1 Secret: </label>
	    			<input class="form-control" type="text" name="f1Secret" value="<?php echo $setting['f1Secret'];?>">
	    		</div>
			</div>
		<div class="col-md-6">
    		<div class="input-group">
	    		<label class="input-group-addon">F1 User: </label>
    			<input class="form-control" type="text" name="f1User" value="<?php echo $setting['f1User'];?>">
    		</div>
		</div>
		<div class="col-md-6">
    		<div class="input-group">
	    		<label class="input-group-addon">F1 Pass: </label>
    			<input class="form-control" type="password" name="f1Pass" value="<?php echo $setting['f1Pass'];?>">
    		</div>
		</div>
    	</div><!-- End .row -->
    	<div class="row">
	    	<h2><small>LDAP Settings</small></h2>
	    	<div class="col-md-12">
	    			<label for="ldapOn">I would like to use LDAP for user Login.</label>
					<input class="" type="checkbox" name="ldapOn" <?php if($setting['ldapOn'] == 1){echo "checked";}?> >
			</div>
			<div class="col-md-6">
    			<div class="input-group">
	    			<label class="input-group-addon">LDAP Host: </label>
    				<input class="form-control" type="text" name="ldapHost" value="<?php echo $setting['ldapHost'];?>">
    			</div>
			</div>
			<div class="col-md-6">
    			<div class="input-group">
	    			<label class="input-group-addon">LDAP Domain: </label>
    				<input class="form-control" type="text" name="ldapUserDomain" value="<?php echo $setting['ldapUserDomain'];?>">
    			</div>
			</div>
			<div class="col-md-12">
    			<div class="input-group">
	    			<label class="input-group-addon">LDAP DN: </label>
    				<input class="form-control" type="text" name="ldapDN" value="<?php echo $setting['ldapDN'];?>">
    			</div>
			</div>
			<div class="col-md-6">
    			<div class="input-group">
	    			<label class="input-group-addon">User Group: </label>
    				<input class="form-control" type="text" name="ldapUserGroup" value="<?php echo $setting['ldapUserGroup'];?>">
    			</div>
			</div>
			<div class="col-md-6">
    			<div class="input-group">
	    			<label class="input-group-addon">Manager Group: </label>
    				<input class="form-control" type="text" name="ldapManagerGroup" value="<?php echo $setting['ldapManagerGroup'];?>">
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
