<?php

// Load functions & database
include('includes/functions.php');
include('includes/database.php');
$dbc = connectDatabase();

// Check user login - starts session as well
include('includes/loginCheck.php');

// List of allowed pages/submits
$allowedList = array('login','configurations','users','categories','posts','replies');

// Get configurations
$getConfigs = mysql_query("SELECT * FROM `siteconfig`");
while($eachConfig = mysql_fetch_assoc($getConfigs)){${$eachConfig['configName']} = $eachConfig['configValue'];}

// Pass submitName from GET
if($_GET['submitName'] != NULL){$_POST['submitName'] = $_GET['submitName'];}

list($_POST['submitName']) = cleanInputs($_POST['submitName']);

// Check for any submitions we have
if($_POST['submitName'])
{
	if(in_array($_POST['submitName'],$allowedList))
	{
		// Only allow pages on list and that exist
		$fileName = 'submits/'.$_POST['submitName'].'.php';
		if(is_file($fileName)){ include($fileName);}
	}
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Admin Panel</title>
<style type="text/css">
* { font-family:Verdana, Arial, Helvetica, sans-serif; }
a { color:#0000FF; text-decoration:none;}
a:hover { color:#000099; text-decoration:underline;}
</style>
<script language="javascript">

function resize(which, maxsize) {
	var elem = document.getElementById(which);
	if (elem == undefined || elem == null) return false;
	if (maxsize == undefined) maxsize = 500;
	if (elem.width > elem.height) {
	if (elem.width > maxsize) elem.width = maxsize;
	} else {
	if (elem.height > maxsize) elem.height = maxsize;
	}
}
</script>
</head>
<body>
<table width="790"  border="1" align="center" cellpadding="5" cellspacing="0" bordercolor="#666666">
  <tr>
    <td bgcolor="#666666"><div align="center">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><a href="index.php"><strong><font color="#FFFF00" size="5">Simple Blog Admin Panel</font></strong> </a></td>
          <td><div align="right"><a href="../index.php"><font color="#FF00FF"><strong><font color="#00FFFF" size="2">Homepage</font></strong> </font></a></div></td>
        </tr>
      </table>
      </div></td>
  </tr>
  <tr>
    <td><div align="center">
      <table  border="0" cellspacing="0" cellpadding="0">        
        <tr>
		  <td width="100"><div align="center"><img src="images/replies.jpg"></div></td>
          <td width="100"><div align="center"><img src="images/posts.jpg"></div></td>
          <td width="100"><div align="center"><img src="images/categories.jpg"></div></td>
          <td width="100"><div align="center"><img src="images/users.jpg"></div></td>
          <td width="100"><div align="center"><img src="images/configuration.jpg"></div></td>
          <td width="100"><div align="center"><img src="images/logoff.jpg"></div></td>
        </tr>
		<tr>
		  <td><div align="center"><strong><font size="2"><a href="?page=replies">Add reply</a></font></strong></div></td>
          <td><div align="center"><strong><font size="2"><a href="?page=posts">Blog posts</a></font></strong></div></td>
          <td><div align="center"><strong><font size="2"><a href="?page=categories">Categories</a></font></strong></div></td>
          <td><div align="center"><strong><font size="2"><a href="?page=users">Users</a></font></strong></div></td>
          <td><div align="center"><strong><font size="2"><a href="?page=configurations">Configuration</a></font></strong></div></td>
          <td><div align="center"><strong><font size="2"><a href="logoff.php">Log out</a></font></strong></div></td>
        </tr>
      </table>
      </div></td>
  </tr>
  <?php  
  // System message from submits
  if($message != NULL ) { ?>
  <tr bgcolor="#FFE1E1">
    <td colspan="2"><div align="left">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="40"><img src="images/system.jpg"></td>
          <td valign="top"><strong><font color="#FF0000" size="2"><u>System Message:</u><br />
                <?php echo $message;?> </font></strong></td>
        </tr>
      </table>
      </div></td>
  </tr>
  <?php } ?>
  <tr>
    <td>
	<?php
	// Check page to include
	if($isAdmin == false || $_GET['page'] == NULL)
	{
		// Default page
		include('pages/login.php');
	} else {
		if(in_array($_GET['page'],$allowedList))
		{
			// Only include if on list and does exist
			$fileName = 'pages/'.$_GET['page'].'.php';
			if(is_file($fileName)){ include($fileName);}
		} else {
			// Default page
			include('pages/login.php');
		}
	}
	?>
	</td>
  </tr>
  <tr>
    <td bgcolor="#666666"><div align="center"><a href="http://localhost/simple-blog/admin/" target="_blank"><font color="#FFFF00" size="2">Designed by Sedat Can Uygur</font></a></div></td>
  </tr>
</table>
</body>
</html>
<?php
disconnectDatabase($dbc);
?>