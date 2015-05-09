<?php
error_reporting(E_ALL ^ E_DEPRECATED ^ E_NOTICE);
/*
If not logged in, display login form,
otherwise, display welcome message
*/
if($isAdmin == false) { ?>
<form name="login" id="login" method="post" action="index.php" style="display:inline;">
    <table width="100%"  border="1" cellpadding="10" cellspacing="0" bordercolor="#00B0B0">
        <tr bgcolor="#E1FFFF">
          <td colspan="2"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><font size="2"><strong>Admin login </strong></font></td>
              <td><div align="right"><img src="images/login.jpg"></div></td>
            </tr>
          </table>            </td>
      </tr>
        <tr>
          <td width="50%"><font size="2">Admin email: </font></td>
          <td width="50%"><font size="2">
            <input name="adminEmail" type="text" id="adminEmail" value="<?php echo $_POST['adminEmail'];?>">
</font></td>
        </tr>
        <tr>
          <td width="50%"><font size="2">Admin password: </font></td>
          <td width="50%"><font size="2">
            <input name="adminPassword" type="password" id="adminPassword">
</font></td>
        </tr>
        <tr>
          <td colspan="2"><div align="center">
            <table  border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td><strong><font color="#0000FF" size="2"><img src="images/notice.jpg"></font></strong></td>
                <td valign="bottom"><strong><font color="#0000FF" size="2">The default admin email and password are: sedatcan_92@hotmail.com & admin</font></strong></td>
              </tr>
            </table>
            </div></td>
        </tr>
        <tr>
          <td colspan="2"><div align="center">
            <input name="submitName" type="hidden" id="submitName" value="login">
            <input type="submit" name="Submit" value="Submit"> 
            </div></td>
        </tr>
  </table>  
</form>
<?php } else { ?>
<table width="100%"  border="1" cellpadding="10" cellspacing="0" bordercolor="#00B0B0">
  <tr bgcolor="#E1FFFF">
    <td width="50%" colspan="2"><p>&nbsp;</p>
    <p>Welcome to the administration panel. Please select your option from top menu. </p>
    <p>If you  have any questions regarding this application do not hesitate to <?php $to = "sedatcan_92@hotmail.com"; $body="Question about blog system"; echo "<a href='mailto:" . $to . "?body=" . $body . "'>"; ?> ask me</p>
    <?php $to = "sedatcan_92@hotmail.com"; $body="More information about blog system"; echo "<a href='mailto:" . $to . "?body=" . $body . "'>"; ?><p> If you want to see more features implemented, contact me </p></td>
  </tr>
</table>
<?php } ?>