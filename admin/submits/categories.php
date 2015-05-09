<?php
error_reporting(E_ALL ^ E_DEPRECATED ^ E_NOTICE);

// If already logged in
if($isAdmin != true){ die('Invalid Access');}

// Add Categories
if($_POST['catID'] == NULL && $_GET['do'] == NULL)
{
	// Check new category values
	if($_POST['catTitle'] == NULL){ $message = 'Please enter a title.';return;}
	if($_POST['catDesc'] == NULL){ $message = 'Please enter a description.';return;}
	
	$addCat = mysql_query("INSERT INTO `catlist` (`catTitle`,`catDesc`)
		VALUES ('$_POST[catTitle]','$_POST[catDesc]')");
	if($addCat)
	{
		$message = 'Category added.';
		$_GET['catID'] = mysql_insert_id();
	} else {
		$message = 'Unable to add category.';
	}
}

// Edit category
if($_POST['catID'] != NULL)
{
	// Check new category values
	if($_POST['catTitle'] == NULL){ $message = 'Please enter a title.';return;}
	if($_POST['catDesc'] == NULL){ $message = 'Please enter a description.';return;}
		
	// If everything OK, Update
	$update = mysql_query("UPDATE `catlist` SET `catTitle`='$_POST[catTitle]',`catDesc`='$_POST[catDesc]'
		WHERE `catID`='$_POST[catID]' LIMIT 1");
	if($update)
	{
		$message .= 'Category updated.';
	} else {
		$message .= 'Unable to update category.';
	}
	return;
}

// If removing category
if($_GET['do'] == 'remove')
{
	// Remove category
	$remove = mysql_query("DELETE FROM `catlist` WHERE `catID`='$_GET[catID]' LIMIT 1");
	
	// Remove topics & replies
	$getTopics = mysql_query("SELECT `topicID` FROM `blogtopics` WHERE `catID`='$_GET[catID]'");
	while($eachTopic = @mysql_fetch_assoc($getTopics))
	{
		$removeReplies = mysql_query("DELETE FROM `blogreplies` WHERE `topicID`='$eachTopic[topicID]'");
	}
	$updateReplies = mysql_query("DELETE FROM `blogtopics` WHERE `catID`='$_GET[catID]'");
	
	if($remove)
	{
		$message = 'Category removed';
	} else {
		$message = 'Unable to remove category.';
	}
}

?>