<?php
error_reporting(E_ALL ^ E_DEPRECATED ^ E_NOTICE);

// If already logged in
if($isAdmin != true){ die('Invalid Access');}

// Add topic
if($_POST['topicID'] == NULL && $_GET['do'] == NULL)
{
	// Check new topic values
	if($_POST['catID'] == NULL){ $message = 'Please select a category.';return;}
	if($_POST['userID'] == NULL){ $message = 'Please select a user.';return;}
	if($_POST['topicTitle'] == NULL){ $message = 'Please enter a title.';return;}
	if($_POST['topicText'] == NULL){ $message = 'Please enter topic text.';return;}
	
	$addTopic = mysql_query("INSERT INTO `blogtopics` (`userID`,`catID`,`topicTitle`,`topicText`,`topicCreated`,`topicUpdated`)
		VALUES ('$_POST[userID]','$_POST[catID]','$_POST[topicTitle]','$_POST[topicText]',NOW(),NOW())");
	if($addTopic)
	{
		$message = 'Topic added.';
		$_GET['topicID'] = mysql_insert_id();				
	} else {
		$message = 'Unable to add topic.';
	}
}

// Edit topic
if($_POST['topicID'] != NULL)
{
	// Check new topic values
	if($_POST['catID'] == NULL){ $message = 'Please select a category.';return;}
	if($_POST['topicTitle'] == NULL){ $message = 'Please enter a title.';return;}
	if($_POST['topicText'] == NULL){ $message = 'Please enter topic text.';return;}
		
	// If everything OK, Update
	$update = mysql_query("UPDATE `blogtopics` SET `userID`='$_POST[userID]',`catID`='$_POST[catID]',`topicTitle`='$_POST[topicTitle]'
	,`topicText`='$_POST[topicText]',`topicUpdated`=NOW() WHERE `topicID`='$_POST[topicID]' LIMIT 1");
	if($update)
	{
		$message .= 'Topic updated.';
	} else {
		$message .= 'Unable to update topic.';
	}
}

// Add file, if any
if($_FILES['fileName']['name'] != NULL)
{
	list($fileTitle) = cleanInputs($_POST['fileTitle']);
	$fileNameIs = generate(10);
	$fileName = $_FILES['fileName']['name'];
	move_uploaded_file($_FILES['fileName']['tmp_name'],$fileLocation.$fileNameIs);
	$insert = mysql_query("INSERT INTO `blogattachments` (`topicID`,`fileTitle`,`fileName`,`fileNameIs`)
		VALUES ('$_POST[topicID]','$fileTitle','$fileName','$fileNameIs')") or die(mysql_error());
	if($insert)
	{
		$message .= '<br />File added.';
	} else {
		$message .= '<br />Unable to upload file.';
	}
}


// If removing topic
if($_GET['do'] == 'remove')
{
	// Remove topic
	$remove = mysql_query("DELETE FROM `blogtopics` WHERE `topicID`='$_GET[topicID]' LIMIT 1");
	
	// Remove replies
	$removeReplies = mysql_query("DELETE FROM `blogreplies` WHERE `topicID`='$_GET[topicID]'");
	
	if($remove)
	{
		$message = 'Topic removed';
	} else {
		$message = 'Unable to remove topic.';
	}
}

// If removing replies
if($_GET['do'] == 'remove2')
{
	// Remove replies
	$removeReplies = mysql_query("DELETE FROM `blogreplies` WHERE `replyID`='$_GET[replyID]'");
	
	if($removeReplies)
	{
		$message = 'Reply removed';
	} else {
		$message = 'Unable to remove reply.';
	}
}

// if removing attachment
if($_GET['do'] == 'remove3')
{
	// Get file location
	$getFile = mysql_fetch_assoc(mysql_query("SELECT `fileNameIs` FROM `blogattachments` WHERE `fileID`='$_GET[fileID]'"));
	if(is_file($fileLocation.$getFile['fileNameIs']))
	{
		unlink($fileLocation.$getFile['fileNameIs']);
	}
	// Remove file
	$removeFile = mysql_query("DELETE FROM `blogattachments` WHERE `fileID`='$_GET[fileID]'");
	if($removeFile)
	{
		$message = 'File removed';
	} else {
		$message = 'Unable to remove file.';
	}
}

?>