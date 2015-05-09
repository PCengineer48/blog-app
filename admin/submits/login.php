<?php
error_reporting(E_ALL ^ E_DEPRECATED ^ E_NOTICE);
include("../includes/functions.php");
// If already logged in
if($isAdmin == true){ $message = 'You are logged in.'; return;}

// Check that the username and password are not blank
if(checkEmail($_POST['adminEmail']) == false){ $message = 'Please enter a valid email.';return;}
if($_POST['adminPassword'] == NULL){ $message = 'Please enter password.';return;}

list($adminEmail,$adminPassword) = cleanInputs($_POST['adminEmail'],$_POST['adminPassword']);

// If password and username are correct, login
$checkInfo = mysql_fetch_assoc(mysql_query("SELECT * FROM `userlist` WHERE `userID`='1' AND `userEmail`='$adminEmail'
	AND `userPassword`='$adminPassword'"));
	
if($checkInfo['userID'] === '1')
{	
	// Create random admin session
	$usersession = generate(10);	
	$checkInfo = mysql_fetch_assoc(mysql_query("SELECT * FROM `userlist` WHERE `userID`='1' AND `userName`='$adminUserName'
	AND `userPassword`='$adminPassword'"));
	
	// Insert session to database
	$insert = mysql_query("INSERT INTO `usersessions` (`sessionID`,`userID`,`sessionTime`) VALUES ('$usersession','1',NOW())");
	
	// Store admin session
	saveSession('usersession',$usersession);
	$message = 'Login successful.';
	$isAdmin = true;		
}else {
	$message = 'Invalid login.';
}
?>