<?php
error_reporting(E_ALL ^ E_DEPRECATED ^ E_NOTICE);

// Check logged in
if($isAdmin == false) {die('Invalid access.');}

// If reposting, we need to get the site configurations again
if($_POST['submitName'] != NULL)
{
	// Get configurations
	$getConfigs = mysql_query("SELECT * FROM `siteconfig`");
	while($eachConfig = mysql_fetch_assoc($getConfigs)){${$eachConfig['configName']} = $eachConfig['configValue'];}
}
?>
<form name="config" id="config" method="post" action="index.php?page=configurations" style="display:inline;">
    <table width="100%"  border="1" cellpadding="10" cellspacing="0" bordercolor="#00B0B0">
        <tr bgcolor="#E1FFFF">
          <td colspan="2"><font size="2"><strong>Site configurations</strong></font></td>
      </tr>

	  
	  
        <tr>
          <td width="50%"><font size="2">Site name: </font></td>
          <td width="50%"><font size="2">
            <input name="siteName" type="text" id="siteName" value="<?php echo $siteName;?>">
</font></td>
        </tr>
        <tr>
          <td><font size="2">Site URL: </font></td>
          <td><font size="2">
            <input name="siteURL" type="text" id="siteURL" value="<?php echo $siteURL;?>">
          </font></td>
        </tr>
        <tr>
          <td><font size="2">Site email: </font></td>
          <td><font size="2">
            <input name="siteEmail" type="text" id="siteEmail" value="<?php echo $siteEmail;?>">
          </font></td>
        </tr>
        <tr>
          <td><font size="2">Allow blog search: </font></td>
        <td><font size="2">
        <?php if($allowSearch == 1){$sel = ' checked';}else{$sel=NULL;} ?>
        <input name="allowSearch" type="radio" value="1"<?=$sel;?>>
Yes
<?php if($allowSearch == 0){$sel = ' checked';}else{$sel=NULL;} ?>
<input name="allowSearch" type="radio" value="0"<?=$sel;?>>
No </font></td>
        </tr>
        <tr>
          <td><font size="2">Show category listings: </font></td>
        <td><font size="2">
        <?php if($showCats == 1){$sel = ' checked';}else{$sel=NULL;} ?>
        <input name="showCats" type="radio" value="1"<?=$sel;?>>
Yes
<?php if($showCats == 0){$sel = ' checked';}else{$sel=NULL;} ?>
<input name="showCats" type="radio" value="0"<?=$sel;?>>
No </font></td>
        </tr>
        <tr>
          <td><font size="2">Show listings by date: </font></td>
        <td><font size="2">
        <?php if($showDates == 1){$sel = ' checked';}else{$sel=NULL;} ?>
        <input name="showDates" type="radio" value="1"<?=$sel;?>>
Yes
<?php if($showDates == 0){$sel = ' checked';}else{$sel=NULL;} ?>
<input name="showDates" type="radio" value="0"<?=$sel;?>>
No </font></td>
        </tr>
        <tr>
          <td colspan="2"><div align="center">
            <table  border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td><strong><font color="#0000FF" size="2"><img src="images/notice.jpg"></font></strong></td>
                <td valign="bottom"><strong><font color="#0000FF" size="2">The site name and URL are used for the email templates. </font></strong></td>
              </tr>
            </table>
          </div></td>
        </tr>
        <tr bgcolor="#E1FFFF">
          <td colspan="2"><font size="2"><strong>User management</strong></font></td>
        </tr>
        <tr>
          <td><font size="2">Allow user registration: </font></td>
        <td><font size="2">
		<?php if($allowRegistration == 1){$sel = ' checked';}else{$sel=NULL;} ?>
        <input name="allowRegistration" type="radio" value="1"<?=$sel;?>>
        Yes
		<?php if($allowRegistration == 0){$sel = ' checked';}else{$sel=NULL;} ?>
          <input name="allowRegistration" type="radio" value="0"<?=$sel;?>> 
          No
      </font></td>
        </tr>
        <tr>
          <td><font size="2">Require email verification for new accounts: </font></td>
        <td><font size="2">
		<?php if($requireVerification == 1){$sel = ' checked';}else{$sel=NULL;} ?>
        <input name="requireVerification" type="radio" value="1"<?=$sel;?>>
Yes
<?php if($requireVerification == 0){$sel = ' checked';}else{$sel=NULL;} ?>
<input name="requireVerification" type="radio" value="0"<?=$sel;?>>
No </font></td>
        </tr>
        <tr>
          <td><font size="2">Allow anonymous (guest) replies: </font></td>
        <td><font size="2">
		<?php if($allowGuest == 1){$sel = ' checked';}else{$sel=NULL;} ?>
        <input name="allowGuest" type="radio" value="1"<?=$sel;?>>
Yes
<?php if($allowGuest == 0){$sel = ' checked';}else{$sel=NULL;} ?>
<input name="allowGuest" type="radio" value="0"<?=$sel;?>>
No </font></td>
        </tr>
        <tr>
          <td><font size="2">Allow BBCode in replies: </font></td>
        <td><font size="2">
		<?php if($allowBBCode == 1){$sel = ' checked';}else{$sel=NULL;} ?>
        <input name="allowBBCode" type="radio" value="1"<?=$sel;?>>
Yes
<?php if($allowBBCode == 0){$sel = ' checked';}else{$sel=NULL;} ?>
<input name="allowBBCode" type="radio" value="0"<?=$sel;?>>
No </font></td>
        </tr>
        <tr>
          <td><font size="2">Maximum word count: </font></td>
        <td><font size="2">
          <input name="maxWords" type="text" id="maxWords" value="<?php echo $maxWords;?>">
        </font></td>
        </tr>
        <tr>
          <td><font size="2">File upload directory for attachments: </font></td>
        <td><font size="2">
          <input name="fileLocation" type="text" id="fileLocation" value="<?php echo $fileLocation;?>">
        </font></td>
        </tr>
        <tr>
          <td colspan="2"><div align="center">
            <table  border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td><strong><font color="#0000FF" size="2"><img src="images/notice.jpg"></font></strong></td>
                <td valign="bottom"><strong><font color="#0000FF" size="2">Disabling user registration and anonymous replies only gives administrator access to replies. </font></strong></td>
              </tr>
            </table>
          </div></td>
        </tr>
        <tr bgcolor="#E1FFFF">
          <td colspan="2"><font size="2"><strong>Email template  management</strong></font></td>
        </tr>
        <tr>
          <td><font size="2">Account registration subject </font></td>
        <td><font size="2">
          <input name="registerTemplateSubject" type="text" id="registerTemplateSubject" value="<?php echo $registerTemplateSubject;?>">
        </font></td>
        </tr>
        <tr>
          <td colspan="2"><font size="2">Account registration template </font></td>
        </tr>
        <tr>
          <td colspan="2"><textarea name="registerTemplate" style="width:100%;" rows="8" id="registerTemplate"><?php echo $registerTemplate;?></textarea></td>
        </tr>
        <tr>
          <td><font size="2">Forgot password subject </font></td>
        <td><font size="2">
          <input name="forgotTemplateSubject" type="text" id="forgotTemplateSubject" value="<?php echo $forgotTemplateSubject;?>">
        </font></td>
        </tr>
        <tr>
          <td colspan="2"><font size="2">Forgot password template</font></td>
        </tr>
        <tr>
          <td colspan="2"><textarea name="forgotTemplate" style="width:100%;" rows="8" id="forgotTemplate"><?php echo $forgotTemplate;?></textarea></td>
        </tr>
        <tr>
          <td colspan="2"><div align="center">
            <table  border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td><strong><font color="#0000FF" size="2"><img src="images/notice.jpg"></font></strong></td>
                <td valign="bottom"><strong><font color="#0000FF" size="2">Available template variables [enclosed in percentage sign]: %userName%, %userPassword%, %userEmail, %siteName%, 
                %siteURL%, %userStatus%.</font></strong></td>
              </tr>
            </table>
          </div></td>
        </tr>
        <tr>
          <td colspan="2"><div align="center">
            <input name="submitName" type="hidden" id="submitName" value="configurations">
            <input type="submit" name="Submit" value="Submit"> 
            </div></td>
        </tr>
  </table>  
</form>