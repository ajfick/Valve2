<?php
session_start();
 
function authenticate($user, $password) {
	if(empty($user) || empty($password)) return false;

	// Include DB Credentials
	include('/home/access/valve/dbconnect.php');

    $sql = "SELECT * FROM Config";
    $results = $conn->query($sql);
    $auth = $results->fetch_assoc();

	$ldap_host = $auth['ldapHost'];
    $ldap_dn = $auth['ldapDN'];
    $ldap_user_group = $auth['ldapUserGroup'];
    $ldap_manager_group = $auth['ldapManagerGroup'];
    $ldap_usr_dom = $auth['ldapUserDomain'];
 
	// connect to active directory
	$ldap = ldap_connect($ldap_host);
 
	// verify user and password
	if($bind = ldap_bind($ldap, $user.$ldap_usr_dom, $password)) {
		// valid
		// check presence in groups
		$filter = "(sAMAccountName=".$user.")";
		$attr = array("memberof");
		$result = ldap_search($ldap, $ldap_dn, $filter, $attr) or exit("Unable to search LDAP server");
		$entries = ldap_get_entries($ldap, $result);
		ldap_unbind($ldap);
 
		// check groups
		foreach($entries[0]['memberof'] as $grps) {
			// is manager, break loop
			if(strpos($grps, $ldap_manager_group)) { $role = 2; break; }
 
			// is user
			if(strpos($grps, $ldap_user_group)) $role = 1;
		}
 
		if($role != 0) {
			// establish session variables
			$_SESSION['user'] = $user;
			$_SESSION['role'] = $role;
			return true;
		} else {
			// user has no rights
			return false;
		}
 
	} else {
		//TODO: Add DB Authentication Here!
		// invalid name or password
		return false;
	}
}
?>
