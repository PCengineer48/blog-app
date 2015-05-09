<?php

// Start session
session_start();

// Load database
include('includes/database.php');
$dbc = connectDatabase();

// Remove admin session
$remove = mysql_query("DELETE FROM `usersessions` WHERE `userID`='1'");

disconnectDatabase($dbc);

unset($_SESSION['adminSession'],$_COOKIE['adminSession']);

header('Location: index.php');
exit();
?>