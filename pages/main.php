<?php
error_reporting(E_ALL ^ E_DEPRECATED ^ E_NOTICE);

// topics listing
$getList = "SELECT * FROM `blogtopics`";

// Are we viewing a topic
if($_POST['topicID'] != NULL){$_GET['topicID'] = $_POST['topicID'];}
list($topicID) = cleanInputs($_GET['topicID']);
$doAdd = false;
if($topicID != NULL)
{
	$getList .= " WHERE `topicID`='$topicID'";
	$doAdd = true;
}

// Are we searching
list($searchTerm,$searchIn) = cleanInputs($_GET['searchTerm'],$_GET['searchIn']);

if($searchTerm != NULL)
{
	if($doAdd == true){ $getList .= " AND ";}else{$getList .= " WHERE ";}
	switch($searchIn)
	{
		case 1: $getList .= " `topicTitle` LIKE '%$searchTerm%'";break;
		case 2: $getList .= " `topicText` LIKE '%$searchTerm%'";break;
		case 3: $getList .= " `topicTitle` LIKE '%$searchTerm%' OR `topicText` LIKE '%$searchTerm%'";break;
	}
}

// Showing by category
settype($_GET['catID'],"integer");
if($_GET['catID'] > 0)
{
	if($doAdd == true || $searchTerm != NULL){
		$getList .= " AND `catID`='$_GET[catID]'";
	}else{
		$getList .= " WHERE `catID`='$_GET[catID]'";
	}
}

// Showing by date
list($date) = cleanInputs($_GET['date']);
if($date != NULL)
{
	if($doAdd == true || $searchTerm != NULL || $_GET['catID'] > 0){
		$getList .= " AND (`topicCreated` >= '$date' AND `topicCreated` <= '".str_replace('-00','-31',$date)."')";
	}else{
		$getList .= " WHERE (`topicCreated` >= '$date' AND `topicCreated` <= '".str_replace('-00','-31',$date)."')";
	}
}

$isCount = mysql_query($getList);
$displayLimit = '10'; 
$itemsCount = mysql_num_rows($isCount); 
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
$pageOnLoad = false;
?>
<form name="posts" id="posts" method="post" action="?page=main&topicID=<?=$_GET['topicID'];?>" style="display:inline;">
    <table width="100%"  border="1" cellpadding="10" cellspacing="0" bordercolor="#00B0B0">
        <tr bgcolor="#E1FFFF">
          <td bgcolor="#E1FFFF"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><font size="2"><strong>Topics [<a href="#add">Add reply</a>] </strong></font></td>
			  <?php
			  if($_GET['topicID'] != NULL){?>
              <td><div align="right"><strong><a href="index.php"><font size="2">Back to topic list</font></a> </strong></div></td>
			  <?php } ?>
            </tr>
          </table>            </td>
      </tr>
	    <?php
	if($itemsCount <= 0){
	?>
    <tr>
      <td colspan="4"><div align="center">There are no blog posts here<?php if($searchTerm != NULL){ echo ' Matching <strong>'.$searchTerm.'</strong>'; } ?>.</div></td>
    </tr>
	<?php } ?>
	  <?php
	if($itemsCount > $displayLimit){
	?>
    <tr>
      <td colspan="4"><div align="center"><?php include('admin/includes/pagination.php');?></div></td>
    </tr>
	<?php } ?>
        <tr>
          <td>
            <div align="left">
			 <?php while($eachTopic = @mysql_fetch_assoc($getFinalList)){
			 		
					 $countWords = number_format(count(explode(' ',$eachTopic['topicText'])));
					 $javaScript [] = "doChange('$eachTopic[topicID]','$countWords','1')";
					 $pageOnLoad = true;
					  $getFiles = mysql_query("SELECT * FROM `blogattachments` WHERE `topicID`='$eachTopic[topicID]'");
			  		 $countFiles = mysql_num_rows($getFiles);
			   
			 ?>
			 <fieldset style="font-size:14px;border:2px solid green;"><legend style="font-weight:bold;color:#0000FF;">
			 <img onClick="doChange('<?php echo $eachTopic['topicID'];?>','<?php echo $countWords;?>',1);" style="cursor:pointer;" src="admin/images/iconExpand.gif" id="doChanger1<?php echo $eachTopic['topicID'];?>">
			 <a href="?topicID=<?php echo $eachTopic['topicID'];?>"><?php echo $eachTopic['topicTitle'];?> [<?php echo $catList[$eachTopic['catID']];?>]</a></legend>
			 <table width="100%"  border="0" cellspacing="0" cellpadding="0">
  			<tr>
   			 <td> <span id="doShow1<?php echo $eachTopic['topicID'];?>"><font color="#FF00FF"><?php echo $countWords;?> words</font></span></td>
			 <?php
			 if($countFiles > 0){
			 ?>
   			 <td valign="top"><div align="right"><img src="admin/images/download.jpg"></div></td>
			 <?php } ?>
 			 </tr>
			</table>
			
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
				$javaScript[] = "doChange('$eachReply[replyID]','$countWordsReply','2')";
				$pageOnLoad = true;
			  ?>
			  	<fieldset style="margin-left:50px;font-size:14px;border:2px dashed darkgreen;"><legend style="font-weight:bold;color:#0000FF;">
			 <img onClick="doChange('<?php echo $eachReply['replyID'];?>','<?php echo $countWords;?>',2);" style="cursor:pointer;" src="admin/images/iconExpand.gif" id="doChanger2<?php echo $eachReply['replyID'];?>">
			 <?php echo $eachReply['replyTitle'];?> [By: <?php echo $userList[$eachReply['userID']];?>] on <?php echo formatDate($eachReply['replyDate']);?></legend>
			 <span id="doShow2<?php echo $eachReply['replyID'];?>"><font color="#FF00FF"><?php echo $countWordsReply;?> words</font></span>
			 </fieldset>
			   <?php 
			   } 
			   } else {
			   	echo $replyCount;
				?> replies</fieldset><br /><?php
			   }

			   // end replies loop
			   }  // end topics loop
			   
			   // show files
			   if($countFiles > 0 && $_GET['topicID'] != NULL)
			   {
			   ?>
			       <br /><fieldset style="border:1px solid blue;font-size:14px;">
			       <legend>Attachments:</legend>
				   <ul>
				   <?php
				   while($eachFile = mysql_fetch_assoc($getFiles)) {?>
                <li><font size="2">File: <font color="#0000FF"><?php echo $eachFile['fileTitle'];?></font> Filename: <a href="admin/download.php?fileID=<?php echo $eachFile['fileID'];?>"><?php echo $eachFile['fileName'];?></a> - <strong><?php echo number_format(filesize('admin/'.$fileLocation.$eachFile['fileNameIs']));?> Bytes</strong> [<?php echo number_format($eachFile['fileHits']);?> downloads] </font></li>
				<?php } ?>
		     		 </ul>
			       </fieldset>
			   <?php } ?>
            </div>
			 </td>
      </tr>
		<?php
		if($itemsCount > $displayLimit){
	?>
    <tr>
      <td colspan="4"><div align="center"><?php include('admin/includes/pagination.php');?></div></td>
    </tr>
	<?php } 
		if($doAdd == true)
		{
		?>
        <tr>
          <td bgcolor="#E1FFFF"><font size="2"><strong>Add reply <font color="#0000FF"><a name="add"></a></font></strong></font></td>
        </tr>
        <tr>
          <td><div align="center">
		  <table  border="0" cellspacing="0" cellpadding="2">
            <tr>
              <td><strong><font color="#0000FF" size="2"><img src="admin/images/notice.jpg"></font></strong></td>
              <td valign="bottom"><?php if($allowRegistration == 1){
		  			$showRegistration = '<font color="#0000FF">Enabled</font> ';
					$showButton = '<br /><br /><input type="submit" name="Submit2" value="New user? Register for an account then Submit">';
				}else{
					$showRegistration = '<font color="#FF0000">Disabled</font> ';
				}
		  		if($allowGuest == 1){
		  			$showGuest = '<font color="#0000FF">Enabled</font> ';
					// Preset username for guest
					if($_POST['userName'] == NULL){ $_POST['userName'] = 'Guest';}
			  	}else{
					$showGuest = '<font color="#FF0000">Disabled</font> ';
				}?>
                <strong><font size="2">Registration is <?php echo $showRegistration;?> | Guest replies are <?php echo $showGuest;
		  if($requireVerification == 1 && $allowRegistration == 1){
		  ?> | <font color="#0000FF">New accounts require email verification </font>
                <?php } ?>
              </font></strong></td>
            </tr>
          </table>
		  </div></td>
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
              <td><font size="2">Email:</font></td>
            <td width="60%"><font size="2">
              <input name="userEmail" type="text" id="userEmail" value="<?php echo $_POST['userEmail'];?>">
            </font></td>
            </tr>
            <tr>
              <td><font size="2">Password: </font></td>
            <td width="60%"><font size="2">
              <input name="userPassword" type="password" id="userPassword">
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
                      <td><div align="right"> <img src="admin/images/arrowDown.gif" onClick="changeRows('5','replyText');" style="border:1px solid gray;cursor:pointer;"> <img src="admin/images/arrowUp.gif" onClick="changeRows('-5','replyText');" style="border:1px solid gray;cursor:pointer;"> </div></td>
                    </tr>
                  </table>
              </div></td>
            </tr>
            <tr>
              <td colspan="2"><div align="center"><font size="2">
                  <textarea name="replyText" style="width:100%;" rows="10" id="replyText"><?php echo $_POST['replyText'];?></textarea>
               </font>
                  <table  border="0" cellspacing="0" cellpadding="2">
                    <tr>
                      <td><strong><font color="#0000FF" size="2"><img src="admin/images/notice.jpg"></font></strong></td>
                      <td valign="bottom"><font size="2"><strong><font color="#0000FF">
                        <?php
			   if($allowBBCode == 1){
					echo 'Allowed BBCode: b,i,u,img,url,align,mail,font,size,color,code,br.';
				 } else {
				 	echo 'BBCode is not allowed.';
				}
				echo ' Maximum words: '.$maxWords;
				?>
                      </font></strong></font></td>
                    </tr>
                  </table>
                  </div></td>
            </tr>
            <tr>
              <td colspan="2"><div align="center">
                  <input name="topicID" type="hidden" id="topicID" value="<?php echo $_GET['topicID'];?>">
                  <input name="submitName" type="hidden" id="submitName" value="main">
                  <input type="submit" name="Submit" value="Submit">
				  <br /><br />
				  <input type="submit" name="Submit3" value="Forgot password? Click here to get your password.">
              <?php echo $showButton;?>
              </div></td>
            </tr>
          </table></td>
        </tr>	
		<?php
		}
	?>
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
		document.getElementById('doChanger'+isTopic+topicID).src = 'admin/images/iconCollapse.gif';
		xmlhttpPost(topicID,isTopic);
	} else {
		document.getElementById('doChanger'+isTopic+topicID).src = 'admin/images/iconExpand.gif';
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

<?php
// Display posts/replies each every 1 second
	foreach($javaScript as $sub => $curStatement)
	{
		echo 'setTimeout("'.$curStatement.'",'.($sub+1).'000);'.PHP_EOL;
	}
?>	
</script>