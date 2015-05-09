<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php error_reporting(E_ALL ^ E_DEPRECATED ^ E_NOTICE); ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Simple Blog</title>
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
    <td colspan="2" bgcolor="#666666"><div align="center"><a href="index.php"><strong><font color="#FFFF00" size="5">Simple Blog</font></strong></a></div></td>
  </tr>
  <?php
  if($allowSearch == 1)
  {
  ?>
  <tr>
    <td colspan="2"><div align="center">
      <form name="search" id="search" method="get" action="index.php" style="display:inline;">
        <div align="right"><font size="2"><strong>Search
            <input name="searchTerm" type="text" id="searchTerm" value="<?php echo $searchTerm;?>">
              <select name="searchIn" id="searchIn">
			  <?php if($searchIn == 1 || $searchIn == NULL){$sel=' selected';}else{$sel = NULL;}?>
                <option value="1"<?php echo $sel;?>>Topic Title</option>
				<?php if($searchIn == 2){$sel=' selected';}else{$sel = NULL;}?>
                <option value="2"<?php echo $sel;?>>Topic Body</option>
				<?php if($searchIn == 3){$sel=' selected';}else{$sel = NULL;}?>
                <option value="3"<?php echo $sel;?>>Both</option>
              </select>
            <input type="submit" name="Submit" value="Submit">
        </strong></font>     </div>
      </form>
      </div></td>
  </tr>
  <?php  
  }
  // System message from submits
  if($message != NULL ) { ?>
  <tr bgcolor="#FFE1E1">
    <td colspan="3"><div align="left">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="40"><img src="admin/images/system.jpg"></td>
          <td valign="top"><strong><font color="#FF0000" size="2"><u>System Message:</u><br />
                <?php echo $message;?> </font></strong></td>
        </tr>
      </table>
      </div></td>
  </tr>
  <?php } ?>
  <tr>
    <td valign="top">
	<?php
	echo $MAINBODY
	?>
	</td>
	<?php
	if($showCats == 1 || $showDates == 1) { ?>
  <td width="20%" valign="top"><div align="center">
  <?php
  	// do we need to show categories
	if($showCats == 1)
	{
		include('pages/showcats.php');	
	} 
	if($showDates == 1)
	{
		// Dates listings
		include('pages/showdates.php');	
	}
	
  ?></div></td>
  <?php } ?>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#666666"><div align="center"><strong><a href="admin/"><font color="#FFFFFF" size="2">Admin Panel</font></a></strong><br />
    <br /><a href="http://localhost/simple-blog/index.php" target="_blank"><font color="#FFFF00" size="2">Designed by Sedat Can Uygur</font></a></div></td>
  </tr>
</table>
</body>
</html>