<?php
error_reporting(E_ALL ^ E_DEPRECATED ^ E_NOTICE);

// Check logged in
if($isAdmin == false) {die('Invalid access.');}

// Check for category update
$showTitle = 'Add new';
if($_GET['catID'] != NULL)
{
	list($catID) = cleanInputs($_GET['catID']);
	$getUser = mysql_fetch_assoc(mysql_query("SELECT * FROM `catlist` WHERE `catID`='$catID'"));
	$_POST = $getUser;
	if($_POST['catID'] == $_GET['catID']){ $showTitle = 'Edit';}
}

// Category listing
$getList = "SELECT * FROM `catlist`";
$isCount = mysql_query($getList);
$displayLimit = '10'; 
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
$getFinalList = mysql_query($getList . " ORDER BY `catID` DESC LIMIT $start,$displayLimit");
?>
<form name="cats" id="cats" method="post" action="index.php?page=categories&catID=<?=$_POST['catID'];?>" style="display:inline;">
    <table width="100%"  border="1" cellpadding="10" cellspacing="0" bordercolor="#00B0B0">
        <tr bgcolor="#E1FFFF">
          <td bgcolor="#E1FFFF"><font size="2"><strong>Categories [<a href="index.php?page=categories#add">Add</a>] </strong></font></td>
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
            <div align="left">
			 <?php while($eachCat = mysql_fetch_assoc($getFinalList)){?>
			 <fieldset style="font-size:14px;"><legend style="font-weight:bold;color:#0000FF;"><?php echo $eachCat['catTitle'];?></legend>
			 <?php echo $eachCat['catDesc'];?>.
			 
			 <strong><a href="index.php?page=categories&catID=<?php echo $eachCat['catID'];?>"><img src="images/edit.png" border="0"></a> - <a style="cursor:pointer;"onClick="if(window.confirm('Are you sure you want to delete category: <?php echo $eachCat['catTitle'];?>?')){this.href='index.php?page=categories&submitName=categories&do=remove&catID=<?php echo $eachCat['catID'];?>';}"><img src="images/delete.gif"></a>
           </strong> 
			</fieldset>
			<?php } ?>
			</div>
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
          <td bgcolor="#E1FFFF"><font size="2"><strong><?php echo $showTitle;?> category: <a name="add"></a></strong></font></td>
        </tr>
        <tr>
          <td><table width="100%"  border="1" cellpadding="5" cellspacing="0" bordercolor="#990000">
            <tr>
              <td width="40%"><font size="2">Category name: </font></td>
              <td width="60%"><font size="2">
                <input name="catTitle" type="text" id="catTitle" value="<?php echo $_POST['catTitle'];?>">
              </font></td>
            </tr>
            <tr>
              <td width="40%"><font size="2">Category description: </font></td>
              <td width="60%"><font size="2">
                <textarea name="catDesc" id="catDesc"><?php echo $_POST['catDesc'];?></textarea>
              </font></td>
            </tr>
            <tr>
              <td colspan="2"><div align="center">
                <input name="catID" type="hidden" id="catID" value="<?php echo $_POST['catID'];?>">
                <input name="submitName" type="hidden" id="submitName" value="categories">
                <input type="submit" name="Submit" value="Submit">
              </div></td>
            </tr>
          </table></td>
        </tr>
  </table>  
</form>