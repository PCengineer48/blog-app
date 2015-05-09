<?php
error_reporting(E_ALL ^ E_DEPRECATED ^ E_NOTICE);
function connectDatabase(){
	$DB_USER =  'root';
	$DB_PASSWORD = 'aliveli48';
	$DB_HOST = 'localhost';
	$DB_NAME = 'simple_blog';
	$dbc = @mysql_connect ($DB_HOST, $DB_USER, $DB_PASSWORD) or $error = mysql_error();
	@mysql_select_db($DB_NAME) or $error = mysql_error();

	if(strlen($error) > 10){
		echo "<!--$error.-->";
		exit();
		die();
	}
	return $dbc;
}

function disconnectDatabase($dbc){
	mysql_close($dbc);
}

?>