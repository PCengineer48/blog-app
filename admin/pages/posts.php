<?php
error_reporting(E_ALL ^ E_DEPRECATED ^ E_NOTICE);

// Check logged in
if($isAdmin == false) {die('Invalid access.');}

$getList = "SELECT * FROM `blogtopics`";

// Check for topic update
$showTitle = 'Add new';
if($_GET['topicID'] != NULL)
{
	list($topicID) = cleanInputs($_GET['topicID']);
	$getTopic = mysql_fetch_assoc(mysql_query("SELECT * FROM `blogtopics` WHERE `topicID`='$topicID'"));
	$_POST = $getTopic;
	if($_POST['topicID'] == $_GET['topicID']){ $showTitle = 'Edit';}
	$getList .= " WHERE `topicID`='$topicID'";
}

// topic listing
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
$getFinalList = mysql_query($getList . " ORDER BY `topicID` DESC LIMIT $start,$displayLimit");

// Get Categories
$getCats = mysql_query("SELECT `catID`,`catTitle` FROM `catlist`");
while($eachCat = @mysql_fetch_assoc($getCats))
{
	$catList[$eachCat['catID']] = $eachCat['catTitle'];
}

// Get users
$getUsers = mysql_query("SELECT `userID`,`userName` FROM `userlist`");
while($eachUser = @mysql_fetch_assoc($getUsers))
{
	$userList[$eachUser['userID']] = $eachUser['userName'];
}

?>
<form action="index.php?page=posts&topicID=<?=$_POST['topicID'];?>" method="post" enctype="multipart/form-data" name="posts" id="posts" style="display:inline;">
    <table width="100%"  border="1" cellpadding="10" cellspacing="0" bordercolor="#00B0B0">
        <tr bgcolor="#E1FFFF">
          <td bgcolor="#E1FFFF"><font size="2"><strong>Topics [<a href="index.php?page=posts#add">Add</a>] </strong></font></td>
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
			 <?php while($eachTopic = mysql_fetch_assoc($getFinalList)){
			 		
					 $countWords = number_format(count(explode(' ',$eachTopic['topicText'])));
					 $getFiles = mysql_query("SELECT * FROM `blogattachments` WHERE `topicID`='$eachTopic[topicID]'");
			  		 $countFiles = mysql_num_rows($getFiles);
			 ?>
			 <fieldset style="font-size:14px;border:2px solid green;"><legend style="font-weight:bold;color:#0000FF;">
			 <img onClick="doChange('<?php echo $eachTopic['topicID'];?>','<?php echo $countWords;?>',1);" style="cursor:pointer;" src="images/iconExpand.gif" id="doChanger1<?php echo $eachTopic['topicID'];?>">
			 <a href="index.php?page=posts&topicID=<?php echo $eachTopic['topicID'];?>"><?php echo $eachTopic['topicTitle'];?></a> [<?php echo $catList[$eachTopic['catID']];?>]</legend>
			  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
  			<tr>
   			 <td> <span id="doShow1<?php echo $eachTopic['topicID'];?>"><font color="#FF00FF"><?php echo $countWords;?> words</font></span></td>
			 <?php
			 if($countFiles > 0){
			 ?>
   			 <td valign="top"><div align="right"><img src="images/download.jpg"></div></td>
			 <?php } ?>
 			 </tr>
			</table>
			<br />
			 <div align="right">
			     <strong><font size="2"><a href="index.php?page=posts&topicID=<?php echo $eachTopic['topicID'];?>"><img src="images/edit.png" border="0"></a> - <a style="cursor:pointer;"onClick="if(window.confirm('Are you sure you want to delete topic: <?php echo $eachTopic['topicTitle'];?>?')){this.href='index.php?page=posts&submitName=posts&do=remove&topicID=<?php echo $eachTopic['topicID'];?>';}"><img src="images/delete.gif"></a></font>                 </strong> 
		     </div>
			  <?php
			  // topic replies
			  $getReplies = mysql_query("SELECT * FROM `blogreplies` WHERE `topicID`='$eachTopic[topicID]' ORDER BY `replyDate` DESC");
			  $replyCount = mysql_num_rows($getReplies);
			  if($replyCount > 0 && $_GET['topicID'] == $eachTopic['topicID'])
			  {
			  	echo '</fieldset><br />';
			  while($eachReply = mysql_fetch_assoc($getReplies))
			  {
			  	$countWordsReply = number_format(count(explode(' ',$eachReply['replyText'])));

			  ?>
			  	<fieldset style="margin-left:50px;font-size:14px;border:2px dashed darkgreen;"><legend style="font-weight:bold;color:#0000FF;">
			 <img onClick="doChange('<?php echo $eachReply['replyID'];?>','<?php echo $countWords;?>',2);" style="cursor:pointer;" src="images/iconExpand.gif" id="doChanger2<?php echo $eachReply['replyID'];?>">
			 <?php echo $eachReply['replyTitle'];?> [By: <?php echo $userList[$eachReply['userID']];?>] on <?php echo formatDate($eachReply['replyDate']);?></legend>
			 <span id="doShow2<?php echo $eachReply['replyID'];?>"><font color="#FF00FF"><?php echo $countWordsReply;?> words</font></span>
			 </fieldset>
			 <div align="right">
			     <strong><font size="2"><a href="index.php?page=replies&replyID=<?php echo $eachReply['replyID'];?>"><img src="images/edit.png" border="0"></a> - <a style="cursor:pointer;"onClick="if(window.confirm('Are you sure you want to delete reply: <?php echo $eachReply['replyTitle'];?>?')){this.href='index.php?page=posts&submitName=posts&do=remove2&replyID=<?php echo $eachReply['replyID'];?>';}"><img src="images/delete.gif"></a></font>                 </strong> 
		     </div>			  
			   <?php 
			   } 
			    } else {
			   	echo $replyCount.' replies</fieldset><br />';
			   }
			   ?>
		      
			   <?php
			   // end replies loop
			   }  // end topics loop
			   
			   ?>
			   
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
          <td bgcolor="#E1FFFF"><font size="2"><strong><?php echo $showTitle;?> topics: <a name="add"></a></strong></font></td>
        </tr>
        <tr>
          <td><table width="100%"  border="1" cellpadding="5" cellspacing="0" bordercolor="#990000">
            <tr>
              <td width="40%"><font size="2">Category name: </font></td>
              <td width="60%"><font size="2">
                <select name="catID" id="catID">
				<?php
				foreach($catList as $catID => $catTitle)
				{
					if($catID == $_POST['catID']){ $sel = ' selected';}else{$sel=NULL;}
					echo '<option value="'.$catID.'"'.$sel.'>'.$catTitle.'</option>';
				}
				?>
              </select>
              </font></td>
            </tr>
            <tr>
              <td><font size="2">User name: </font></td>
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
              <td><font size="2">Topic title: </font></td>
            <td width="60%"><font size="2">
                <input name="topicTitle" type="text" id="topicTitle" value="<?php echo $_POST['topicTitle'];?>">
              </font></td>
            </tr>
            <tr>
              <td colspan="2"><div align="left">
                <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><font size="2">Topic text:</font></td>
                    <td><div align="right">
					<img src="images/arrowDown.gif" onClick="changeRows('5','topicText');" style="border:1px solid gray;cursor:pointer;">
					<img src="images/arrowUp.gif" onClick="changeRows('-5','topicText');" style="border:1px solid gray;cursor:pointer;">
					</div></td>
                  </tr>
                </table>
                </div></td>
            </tr>
            <tr>
              <td colspan="2"><div align="center"><font size="2">
                  <textarea name="topicText" style="width:100%;" rows="10" id="topicText"><?php echo $_POST['topicText'];?></textarea>
                  <strong>Allowed BBCode: b,i,u,img,url,align,mail,font,size,color,code,br.</strong></font></div></td>
            </tr>
			<?php		
			if($_POST['topicID'] != NULL)
			{
				 $getFiles = mysql_query("SELECT * FROM `blogattachments` WHERE `topicID`='$_POST[topicID]'");
				 $numFiles = mysql_num_rows($getFiles);?>
            <tr bgcolor="#E1FFFF">
              <td colspan="2"><strong><font size="2">Topics attachments [<a href="#addfile">add</a>] <a name="addfile"></a></font></strong></td>
            </tr>
			<?php if($numFiles > 0){ ?>
            <tr>
              <td colspan="2"><ul>
			  <?php
			
			  while($eachFile = mysql_fetch_assoc($getFiles)) {?>
                <li><font size="2">File: <font color="#0000FF"><?php echo $eachFile['fileTitle'];?></font> Filename: <a href="download.php?fileID=<?php echo $eachFile['fileID'];?>"><?php echo $eachFile['fileName'];?></a> - <strong><?php echo number_format(filesize($fileLocation.$eachFile['fileNameIs']));?> Bytes</strong> [<?php echo number_format($eachFile['fileHits']);?> downloads] <a style="cursor:pointer;"onClick="if(window.confirm('Are you sure you want to delete file: <?php echo $eachFile['fileName'];?>?')){this.href='index.php?page=posts&submitName=posts&do=remove3&fileID=<?php echo $eachFile['fileID'];?>&topicID=<?php echo $_POST['topicID'];?>';}"><img src="images/delete.gif"></a></font></li>
				<?php } ?>
              </ul></td>
            </tr>
			<?php } 
			}?>
            <tr>
              <td><font size="2">Add new attachment: </font></td>
            <td><font size="2">
              <input name="fileName" type="file" id="fileName">
            </font></td>
            </tr>
            <tr>
              <td><font size="2">Attachment title: </font></td>
            <td><font size="2">
              <input name="fileTitle" type="text" id="fileTitle">
            </font></td>
            </tr>
            <tr>
              <td colspan="2"><div align="center">
                <input name="topicID" type="hidden" id="topicID" value="<?php echo $_POST['topicID'];?>">
                <input name="submitName" type="hidden" id="submitName" value="posts">
                <input type="submit" name="Submit" value="Submit">
              </div></td>
            </tr>
          </table></td>
        </tr>
  </table>  
</form>
<script language="javascript">
// Javascript show topic text changer

function doChange(topicID,numberWords,isTopic)
{
	// Get image name - basename
	var currentState = document.getElementById('doChanger'+isTopic+topicID).src;
	currentState = currentState.replace( /.*\//, "" );
	
	// Are we collapsing or expanding
	if(currentState == 'iconExpand.gif')
	{
		document.getElementById('doChanger'+isTopic+topicID).src = 'images/iconCollapse.gif';
		xmlhttpPost(topicID,isTopic);
	} else {
		document.getElementById('doChanger'+isTopic+topicID).src = 'images/iconExpand.gif';
		// remove Text
		document.getElementById('doShow'+isTopic+topicID).innerHTML = '<font color="#FF00FF">'+numberWords+' words</font>';
	}
}

// Ajax method to return post topic
function xmlhttpPost(topicID,isTopic) {
	var strURL = 'getPost.php';
    var xmlHttpReq = false;
    var self = this;
    // Mozilla/Safari
    if (window.XMLHttpRequest) {
        self.xmlHttpReq = new XMLHttpRequest();
    }
    // IE
    else if (window.ActiveXObject) {
        self.xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
    }
    self.xmlHttpReq.open('POST', strURL, true);
    self.xmlHttpReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    self.xmlHttpReq.onreadystatechange = function() {
        if (self.xmlHttpReq.readyState == 4) {
            document.getElementById('doShow'+isTopic+topicID).innerHTML = self.xmlHttpReq.responseText;
        }
    }
	if(isTopic == 1)
	{
	    var doSend = 'topicID='+escape(topicID);
	} else {
		var doSend = 'replyID='+escape(topicID);
	}
	self.xmlHttpReq.send(doSend);
}

// Increase or decrease rows for text box
function changeRows(changeBy,inputID)
{
	document.getElementById(inputID).rows = document.getElementById(inputID).rows+parseInt(changeBy);
}

</script>