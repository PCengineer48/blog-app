<?php
error_reporting(E_ALL ^ E_DEPRECATED ^ E_NOTICE);

// Check logged in
if($isAdmin == false) {die('Invalid access.');}

// Check for user update
$showTitle = 'Add new';
if($_GET['userID'] != NULL)
{
	list($userID) = cleanInputs($_GET['userID']);
	$getUser = mysql_fetch_assoc(mysql_query("SELECT * FROM `userlist` WHERE `userID`='$userID'"));
	$_POST = $getUser;
	if($_POST['userID'] == $_GET['userID']){ $showTitle = 'Edit';}
}

// User listing
$getList = "SELECT * FROM `userlist`";
$isCount = mysql_query($getList);
$displayLimit = '50'; 
$itemsCount = @mysql_num_rows($isCount); 
if($itemsCount > $display) { 
	$pageCount = ceil ($itemsCount/$displayLimit); 
} else { 
	$pageCount = 1; 
}
if(is_numeric($_GET['start'])){ 
    $start = $_GET['start']; 
} else { 
    $start = 0; 
} 
$getUsers = mysql_query($getList . " ORDER BY `userID` DESC LIMIT $start,$displayLimit");
?>
<form name="users" id="users" method="post" action="index.php?page=users&userID=<?=$_POST['userID'];?>" style="display:inline;">
    <table width="100%"  border="1" cellpadding="10" cellspacing="0" bordercolor="#00B0B0">
        <tr bgcolor="#E1FFFF">
          <td bgcolor="#E1FFFF"><font size="2"><strong>Users [<a href="index.php?page=users#add">Add</a>] </strong></font></td>
      </tr>
	  <?php
	if($itemsCount > $displayLimit){
	?>
    <tr>
      <td colspan="4"><div align="center"><?php include('includes/pagination.php');?></div></td>
    </tr>
	<?php } ?>
        <tr>
          <td>
             <div align="left"><ul>
			 <?php while($eachUser = mysql_fetch_assoc($getUsers)){?>
              <li>
                <font size="2"><strong><?php echo $eachUser['userName'];?><font color="#999999">, registered on</font> <?php echo formatDate($eachUser['registerDate']);?></strong>
<a href="index.php?page=users&userID=<?php echo $eachUser['userID'];?>"><img src="images/edit.png" border="0"></a> - <a style="cursor:pointer;"onClick="if(window.confirm('Are you sure you want to delete user: <?php echo $eachUser['userName'];?>?')){this.href='index.php?page=users&submitName=users&do=remove&userID=<?php echo $eachUser['userID'];?>';}"><img src="images/delete.gif"></a></font></li>
 			<?php } ?>
            </ul></div>
			 </td>
        </tr>	
		<?php
	if($itemsCount > $displayLimit){
	?>
    <tr>
      <td colspan="4"><div align="center"><?php include('includes/pagination.php');?></div></td>
    </tr>
	<?php } ?>	
        <tr>
          <td bgcolor="#E1FFFF"><font size="2"><strong><?php echo $showTitle;?> user: <a name="add"></a></strong></font></td>
        </tr>
        <tr>
          <td><table width="100%"  border="1" cellpadding="5" cellspacing="0" bordercolor="#990000">
            <tr>
              <td width="40%"><font size="2">User name: </font></td>
              <td width="60%"><font size="2">
                <input name="userName" type="text" id="userName" value="<?php echo $_POST['userName'];?>">
              </font></td>
            </tr>
            <tr>
              <td width="40%"><font size="2">User email: </font></td>
              <td width="60%"><font size="2">
                <input name="userEmail" type="text" id="userEmail" value="<?php echo $_POST['userEmail'];?>">
              </font></td>
            </tr>
            <tr>
              <td width="40%"><font size="2">User password: </font></td>
              <td width="60%">
                <input name="userPassword" type="text" id="userPassword" value="<?php echo $_POST['userPassword'];?>"> 
  				</td>
            </tr>
            <tr>
              <td width="40%"><font size="2">User status: </font></td>
              <td width="60%"><font size="2">
			  <?php if($_POST['userStatus'] == '1' || $_POST['userStatus'] == NULL){ $sel= ' checked';}else{$sel=NULL;}?>
                <input name="userStatus" type="radio" value="1"<?php echo $sel;?>>
Enabled
<?php if($_POST['userStatus'] == '0'){ $sel= ' checked';}else{$sel=NULL;}?>
<input name="userStatus" type="radio" value="0"<?php echo $sel;?>>
Disabled 
<?php
$valueRandom = generate(10);
 if($_POST['userStatus'] != '1' && $_POST['userStatus'] != '0'){ $sel= ' checked';}else{$sel=NULL;}?>
<input name="userStatus" type="radio" value="<?php echo $valueRandom;?>"<?php echo $sel;?>>
Requires email verification </font></td>
            </tr>
            <tr>
              <td colspan="2"><div align="center">
                <input name="oldUserEmail" type="hidden" id="oldUserEmail" value="<?php echo $_POST['userEmail'];?>">
                <input name="userID" type="hidden" id="userID" value="<?php echo $_POST['userID'];?>">
                <input name="submitName" type="hidden" id="submitName" value="users">
                <input type="submit" name="Submit" value="Submit">
              </div></td>
            </tr>
          </table></td>
        </tr>
  </table>  
</form>