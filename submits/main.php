<?php
error_reporting(E_ALL ^ E_DEPRECATED ^ E_NOTICE);


// Check for reply addition
if($_POST['topicID'] != NULL)
{
	list($topicID,$userName,$userEmail,$userPassword,$replyTitle) = cleanInputs(
	$_POST['topicID'],$_POST['userName'],$_POST['userEmail'],$_POST['userPassword'],$_POST['replyTitle']);
	
	// Email needs to be checked no matter what
	if(checkEmail($userEmail) == false){ $message = 'Please enter a valid email.';return;}
	
	// If not forgot pass, check title and text
	if($_POST['Submit3'] == NULL)
	{
		if($replyTitle == NULL){ $message = 'Please enter a reply title.';return;}
		if($_POST['replyText'] == NULL){ $message = 'Please enter reply text.';return;}
	}
	
	// Need password ? not guest
	if($allowGuest == 1 && $_POST['userPassword'] != NULL)
	{
		$message = 'Please enter a password.';return;
	}
	
	if($_POST['Submit2'] != NULL)
	{
		if($allowRegistration != 1){ $message = 'Registration is disabled.';return;}
		
		// new user registration - check email dublicates
		$checkUser = mysql_fetch_assoc(mysql_query("SELECT COUNT(*) FROM `userlist` WHERE `userEmail`='$userEmail'"));
		if($checkUser[0] > 0)
		{
			$message = 'This email is already registered.';return;
		}
		if($requireVerification == 1){ $userStatus = generate(10);}else{$userStatus = 1;}
		$insertUser = mysql_query("INSERT INTO `userlist` (`userName`,`userEmail`,`userPassword`,`userStatus`,`registerDate`)
		VALUES ('$userName','$userEmail','$userPassword','$userStatus',NOW())");
		$userID = mysql_insert_id();
		
		$send = sendVerification($userName,$userEmail,$userPassword,$userStatus,
			$registerDate,$siteName,$siteURL,$siteEmail,$registerTemplate,$registerTemplateSubject);
		
		if($insertUser)
		{
			$message = 'Your account has been created.';
			if($userStatus != 1)
			{
				$message .= '<br />However, you must click on the link sent to your email now before posting.';
				return;
			}
		} else {
			$message = 'Internal error.';
		}
	} else {
		// Forgot password?
		if($_POST['Submit3'] != NULL)
		{
			$getUser = mysql_fetch_assoc(mysql_query("SELECT * FROM `userlist` WHERE `userEmail`='$userEmail'"));
			if($getUser['userEmail'] != NULL)
			{
				$send = sendPassword($getUser['userName'],$userEmail,$getUser['userPassword'],$getUser['userStatus'],
				$getUser['registerDate'],$siteName,$siteURL,$siteEmail,$forgotTemplate,$forgotTemplateSubject);
				if($send)
				{
					$message = 'Your password has been emailed to you.';
				} else {
					$message = 'Internal error.';
				}
			} else {
				$message = 'This email is not associated with any account.';
			}
			return;
		} else {
			// user exists
		
			// is it guest
			if($allowGuest == 1 && $_POST['userName'] == 'Guest')
			{
				$userID = 2;
			} else {
				$checkUser = mysql_fetch_assoc(mysql_query("SELECT `userID`,`userName` FROM `userlist` 
					WHERE `userEmail`='$userEmail' AND `userPassword`='$userPassword' AND `userID` > 2"));
	
				if($checkUser['userID'] != NULL)
				{
					$userID = $checkUser['userID'];
				} else {
					$message = 'Invalid useremail or password.';
					return;
				}
			}
		}
	}
	// By this point, we have a user ID
	$_POST['replyText'] = mysql_real_escape_string(substr($_POST['replyText'],0,$maxWords));
	
	$addReply = mysql_query("INSERT INTO `blogreplies` (`topicID`,`userID`,`replyTitle`,`replyText`,`replyDate`)
		VALUES ('$_POST[topicID]','$userID','$_POST[replyTitle]','$_POST[replyText]',NOW())");
	if($addReply)
	{
		$message = 'Reply added.';
	} else {
		$message = 'Unable to add reply.';
	}
}

?>