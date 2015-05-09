<?php
error_reporting(E_ALL ^ E_DEPRECATED ^ E_NOTICE);

// Check logged in
if($isAdmin == false) {die('Invalid access.');}

// Check for reply update
$showTitle = 'Add new';
if($_GET['replyID'] != NULL)
{
	list($replyID) = cleanInputs($_GET['replyID']);
	$getReply = mysql_fetch_assoc(mysql_query("SELECT * FROM `blogreplies` WHERE `replyID`='$replyID'"));
	$_POST = $getReply;
	if($_POST['replyID'] == $_GET['replyID']){ $showTitle = 'Edit';}
}

// Get users
$getUsers = mysql_query("SELECT `userID`,`userName` FROM `userlist`");
while($eachUser = @mysql_fetch_assoc($getUsers))
{
	$userList[$eachUser['userID']] = $eachUser['userName'];
}

?>
<form name="replies" id="replies" method="post" action="index.php?page=replies&replyID=<?=$_POST['replyID'];?>" style="display:inline;">
    <table width="100%"  border="1" cellpadding="10" cellspacing="0" bordercolor="#00B0B0">
        <tr>
          <td bgcolor="#E1FFFF"><font size="2"><strong><?php echo $showTitle;?> reply: [<a href="?page=replies">Add</a>] </strong></font></td>
        </tr>
        <tr>
          <td><table width="100%"  border="1" cellpadding="5" cellspacing="0" bordercolor="#990000">
            <tr>
              <td width="40%"><font size="2">User name: </font></td>
            <td width="60%"><font size="2">
              <select name="userID" id="userID">
                <?php
				foreach($userList as $userID => $userName)
				{
					if($userID == $_POST['userID']){ $sel = ' selected';}else{$sel=NULL;}
					echo '<option value="'.$userID.'"'.$sel.'>'.$userName.'</option>';
				}
				?>
                            </select>
            </font></td>
            </tr>
            <tr>
              <td><font size="2">Reply topic:</font></td>
            <td width="60%"><font size="2">
              <select name="topicID" id="topicID">
                <?php
				$getTopics = mysql_query("SELECT `topicID`,`topicTitle` FROM `blogtopics`");
				while($eachTopic = @mysql_fetch_assoc($getTopics))
				{
					if($eachTopic['topicID'] == $_POST['userID']){ $sel = ' selected';}else{$sel=NULL;}
					echo '<option value="'.$eachTopic['topicID'].'"'.$sel.'>'.$eachTopic['topicTitle'].'</option>';
				}
				?>
              </select>
            </font></td>
            </tr>
            <tr>
              <td><font size="2">Reply title: </font></td>
            <td width="60%"><font size="2">
                <input name="replyTitle" type="text" id="replyTitle" value="<?php echo $_POST['replyTitle'];?>">
              </font></td>
            </tr>
            <tr>
              <td colspan="2"><div align="left">
                <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><font size="2">Reply text:</font></td>
                    <td><div align="right">
					<img src="images/arrowDown.gif" onClick="changeRows('5','replyText');" style="border:1px solid gray;cursor:pointer;">
					<img src="images/arrowUp.gif" onClick="changeRows('-5','replyText');" style="border:1px solid gray;cursor:pointer;">
					</div></td>
                  </tr>
                </table>
                </div></td>
            </tr>
            <tr>
              <td colspan="2"><div align="center"><font size="2">
                  <textarea name="replyText" style="width:100%;" rows="10" id="replyText"><?php echo $_POST['replyText'];?></textarea>
                  <strong>Allowed BBCode: b,i,u,img,url,align,mail,font,size,color,code,br.</strong></font></div></td>
            </tr>
            <tr>
              <td colspan="2"><div align="center">
                <input name="replyID" type="hidden" id="replyID" value="<?php echo $_POST['replyID'];?>">                
                <input name="submitName" type="hidden" id="submitName" value="replies">
                <input type="submit" name="Submit" value="Submit">
              </div></td>
            </tr>
          </table></td>
        </tr>
  </table>  
</form>
<script language="javascript">

// Increase or decrease rows for text box
function changeRows(changeBy,inputID)
{
	document.getElementById(inputID).rows = document.getElementById(inputID).rows+parseInt(changeBy);
}

</script>