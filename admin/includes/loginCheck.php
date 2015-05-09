<?php
error_reporting(E_ALL ^ E_DEPRECATED ^ E_NOTICE ^ E_WARNING);

//Check admin sessions (set it to NO at first)
$isAdmin = false;

// Start session
session_start();

if($_SESSION['usersession'] == NULL){$_SESSION['usersession'] = $_COOKIE['usersession'];}

$checkUser = mysql_fetch_assoc(mysql_query("SELECT * FROM `usersessions` WHERE `sessionID`='$_SESSION[usersession]'"));
$userID = $checkUser['userID'];

// check for any sessions in database
if($userID === '1')
{ 
	$isAdmin = true;
}

?>