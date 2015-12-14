<?php
//***************************************************************************//
//                                                                           //
//  Copyright (c) 2004-2007 Jonathon Freeman                                 //
//  Copyright (c) 2007 Brian Otto                                            //
//  All rights reserved.                                                     //
//                                                                           //
//  This program is free software. You may use, modify, and/or redistribute  //
//  it under the terms of the MIT License.                                   //
//                                                                           //
//***************************************************************************//

	// Initialize OvBB.
	require('./includes/init.inc.php');

	// What do they wanna do?
	switch($_REQUEST['action'])
	{
		// Get member's profile
		case 'getprofile':
		{
			GetProfile();
		}

		// Log in
		case 'login':
		{
			Login();
		}

		// Log out
		case 'logout':
		{
			Logout();
		}

		// Request member details
		case 'request':
		{
			RequestDetails();
		}

		// Reset member details
		case 'reset':
		{
			ResetDetails();
		}

		// Activate member account
		case 'activate':
		{
			ActivateMember();
		}

		// E-mail user
		case 'mailuser':
		{
			MailUser();
		}

		// Mark forums as read
		case 'markread':
		{
			MarkRead();
		}

		// Forgot member details
		case 'forgotdetails':
		default:
		{
			ForgotDetails();
		}
	}

// *************************************************************************** \\

// Displays a member's profile.
function GetProfile()
{
	global $CFG, $dbConn, $aGroup;

	// Constants
	define('USERID',      0);
	define('USERNAME',    1);
	define('JOINDATE',    2);
	define('TITLE',       3);
	define('WEBSITE',     4);
	define('AIM',         5);
	define('ICQ',         6);
	define('MSN',         7);
	define('YAHOO',       8);
	define('BIO',         9);
	define('LOCATION',    10);
	define('INTERESTS',   11);
	define('OCCUPATION',  12);
	define('POSTCOUNT',   13);
	define('BIRTHDAY',    14);
	define('LASTPOST',    15);
	define('HOURSOLD',    16);
	define('DAYSOLD',     17);
	define('POSTID',      18);
	define('POSTDATE',    19);
	define('THREAD',      20);
	define('ENABLEPMS',   21);
	define('BUDDY',       22);
	define('IGNORED',     23);

	// Does the user have authorization to view this profile?
	if(!$_SESSION['permissions']['cviewprofiles'])
	{
		// No. Let them know the bad news.
		Unauthorized();
	}

	// Build a list of the months.
	$aMonths = array(1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

	// What user do they want?
	$aUserInfo[USERID] = (int)$_REQUEST['userid'];

	// Get the user's information.
	$dbConn->query("SELECT username, datejoined, title, website, aim, icq, msn, yahoo, bio, residence, interests, occupation, postcount, birthday, usergroup, enablepms FROM citizen WHERE id={$aUserInfo[USERID]}");
	if(!($aSQLResult = $dbConn->getresult(TRUE)))
	{
		Msg("Invalid user specified.{$CFG['msg']['invalidlink']}");
	}

	// Store the user information into some easy-to-read variables.
	$aUserInfo[USERNAME] = $aSQLResult['username'];
	$aUserInfo[JOINDATE] = $aSQLResult['datejoined'];
	$aUserInfo[TITLE] = ($aSQLResult['title']) ? $aSQLResult['title'] : $aGroup[$aSQLResult['usergroup']]['usertitle'];
	$aUserInfo[WEBSITE] = $aSQLResult['website'];
	$aUserInfo[AIM] = $aSQLResult['aim'];
	$aUserInfo[ICQ] = $aSQLResult['icq'];
	$aUserInfo[MSN] = $aSQLResult['msn'];
	$aUserInfo[YAHOO] = $aSQLResult['yahoo'];
	$aUserInfo[BIO] = $aSQLResult['bio'];
	$aUserInfo[LOCATION] = $aSQLResult['residence'];
	$aUserInfo[INTERESTS] = $aSQLResult['interests'];
	$aUserInfo[OCCUPATION] = $aSQLResult['occupation'];
	$aUserInfo[POSTCOUNT] = $aSQLResult['postcount'];
	$aUserInfo[BIRTHDAY] = $aSQLResult['birthday'];
	$aUserInfo[ENABLEPMS] = $aSQLResult['enablepms'];

	// Do we have a birthday on file for them?
	if($aUserInfo[BIRTHDAY] != '0000-00-00')
	{
		// Yes, get the birthday and extract the elements.
		list($iYear, $iMonth, $iDate) = sscanf($aUserInfo[BIRTHDAY], '%u-%u-%u');

		// Is there a year?
		if($iYear == 0)
		{
			// No, so they must have month and date.
			$aUserInfo[BIRTHDAY] = "{$aMonths[$iMonth]} {$iDate}";
		}

		// There is a year, but is there a month?
		else if($iMonth == 0)
		{
			// No; they only have the year.
			$aUserInfo[BIRTHDAY] = $iYear;
		}

		// Do they have a date too, or just a month and a year?
		else if($iDate == 0)
		{
			// Just month and year.
			$aUserInfo[BIRTHDAY] = "{$aMonths[$iMonth]} {$iYear}";
		}
		else
		{
			// They have everything.
			$aUserInfo[BIRTHDAY] = "{$aMonths[$iMonth]} {$iDate}, {$iYear}";
		}
	}
	else
	{
		// No birthday. :(
		$aUserInfo[BIRTHDAY] = '';
	}

	// Get some information about the user's last post.
	$dbConn->query("SELECT post.id, post.datetime_posted, post.title, post.parent, thread.title FROM post LEFT JOIN thread ON (thread.id = post.parent) WHERE post.author={$aUserInfo[USERID]} AND thread.closed=0 AND thread.visible=1 ORDER BY post.datetime_posted DESC LIMIT 1");
	if($aSQLResult = $dbConn->getresult())
	{
		// Store the values in easy-to-read variables.
		$aUserStats[LASTPOST][POSTID] = $aSQLResult[0];
		$aUserStats[LASTPOST][POSTDATE] = $aSQLResult[1];
		$aUserStats[LASTPOST][TITLE] = $aSQLResult[2] ? $aSQLResult[2] : $aSQLResult[4];
		$aUserStats[LASTPOST][THREAD] = $aSQLResult[3];
	}

	// Set some more user stats.
	$aUserStats[HOURSOLD] = ($CFG['globaltime'] - strtotime($aUserInfo[JOINDATE])) / 3600;
	$aUserStats[DAYSOLD] = ($aUserStats[HOURSOLD] < 24) ? 1 : ($aUserStats[HOURSOLD] / 24);

	// Check to see if this user is in our Buddy list or Ignore list.
	if($_SESSION['loggedin'])
	{
		// Get our Buddy list.
		$dbConn->query("SELECT buddylist FROM citizen WHERE id={$_SESSION['userid']}");
		list($strBuddyList) = $dbConn->getresult();

		// Are they in our list?
		if($strBuddyList)
		{
			$aBuddies = explode(',', $strBuddyList);
			$aUserInfo[BUDDY] = in_array($aUserInfo[USERID], $aBuddies);
		}
		else
		{
			$aUserInfo[BUDDY] = FALSE;
		}

		// Get our Ignore list.
		$dbConn->query("SELECT ignorelist FROM citizen WHERE id={$_SESSION['userid']}");
		list($strIgnoreList) = $dbConn->getresult();

		// Are they in our list?
		if($strIgnoreList)
		{
			$aIgnorants = explode(',', $strIgnoreList);
			$aUserInfo[IGNORED] = in_array($aUserInfo[USERID], $aIgnorants);
		}
		else
		{
			$aUserInfo[IGNORED] = FALSE;
		}
	}

	// Template
	require("./skins/{$CFG['skin']}/getprofile.tpl.php");

	// Send the page.
	exit;
}

// *************************************************************************** \\

// Logs user in.
function Login()
{
	global $CFG, $dbConn;

	// Are they already logged in?
	if($_SESSION['loggedin'])
	{
		LoggedIn();
	}

	// Are they coming for the first time?
	if(!$_REQUEST['username'])
	{
		// Yes, so give them the login page.
		require("./skins/{$CFG['skin']}/login.tpl.php");
		exit;
	}

	// Grab the values (if any) the user posted.
	$strPostedUsername = $dbConn->sanitize($_REQUEST['username']);
	$strPostedPassword = $_REQUEST['password'];

	// Get the member information of the member whose username was specified.
	$dbConn->query("SELECT * FROM citizen WHERE username='{$strPostedUsername}' AND reghash IS NULL");

	// Was the username of a real member?
	if($aSQLResult = $dbConn->getresult(TRUE))
	{
		// Yes, so do the passwords match?
		if($aSQLResult['passphrase'] == md5($strPostedPassword))
		{
			// Store the member information into the session.
			LoadUser($aSQLResult);

			// Delete any guest entries from the session table.
			$dbConn->query("DELETE FROM guest WHERE id='".session_id()."'");

			// Do they wanna be remembered?
			if($aSQLResult['autologin'])
			{
				setcookie('activeuserid', $_SESSION['userid'], $CFG['globaltime']+2592000, $CFG['paths']['cookies']);
				setcookie('activepassword', $aSQLResult['passphrase'], $CFG['globaltime']+2592000, $CFG['paths']['cookies']);
			}

			// Show them the success page.
			LoggedIn();
		}
	}

	// Invalid username/password pair given.
	Msg("Wrong username or password specified. Click <a href=\"member.php?action=login\">here</a> to go back and try again. Click <a href=\"member.php?action=forgotdetails\">here</a> if you've forgotten your member details.");
}

// *************************************************************************** \\

// Page user gets when user is logged in.
function LoggedIn()
{
	global $CFG;

	$strRedirect = urldecode($_REQUEST['redirect']);
	if(!$strRedirect)
	{
		$strRedirectURL = $strRedirect = 'index.php';
	}
	else
	{
		$strRedirectURL = str_replace('&', '&amp;', $strRedirect);
	}

	$strUsername = htmlsanitize($_SESSION['username']);
	Msg("<b>Thank you for logging in, {$strUsername}.</b><br /><br /><span class=\"smaller\">You should be redirected momentarily. Click <a href=\"{$strRedirectURL}\">here</a><br />if you do not want to wait any longer or if you are not redirected.</span>", $strRedirect);
}

// *************************************************************************** \\

// Logs user out.
function Logout()
{
	global $CFG, $dbConn;

	// Are we logged in?
	if($_SESSION['loggedin'])
	{
		// Yes.
		$dbConn->query("UPDATE citizen SET loggedin=0 WHERE id={$_SESSION['userid']}");
	}

	// Destroy the session.
	session_unset();
	session_destroy();

	// Delete any cookies.
	setcookie('s', '', $CFG['globaltime']-2592000, $CFG['paths']['cookies']);
	setcookie('activeuserid', '', $CFG['globaltime']-2592000, $CFG['paths']['cookies']);
	setcookie('activepassword', '', $CFG['globaltime']-2592000, $CFG['paths']['cookies']);
	setcookie('viewedthreads', '', $CFG['globaltime']-2592000, $CFG['paths']['cookies']);

	// Display message page.
	Msg("<b>You have successfully logged out.</b><br /><br /><span class=\"smaller\">Click <a href=\"index.php\">here</a> to return to the forum index.</span>", 'index.php');
}

// *************************************************************************** \\

// Page user gets when they forgot their details.
function ForgotDetails()
{
	global $CFG;

	// Template
	require("./skins/{$CFG['skin']}/forgotdetails.tpl.php");

	// Send the page.
	exit;
}

// *************************************************************************** \\

// Request user details.
function RequestDetails()
{
	global $CFG, $dbConn;

	// For what e-mail?
	$strEMail = $dbConn->sanitize($_REQUEST['email']);

	// Get the username/password for the account to which the e-mail address corresponds.
	$dbConn->query("SELECT id, username, passphrase FROM citizen WHERE email='{$strEMail}'");
	if(!(list($iUserID, $strUsername, $strPassword) = $dbConn->getresult()))
	{
		Error(' :: Forgot Member Details :. Invalid E-Mail', "The e-mail address you gave is not on file with us. Please go back and try again, or contact the <a href=\"mailto:{$CFG['general']['admin']['email']}\">Webmaster</a>.");
	}

	// Make sure they don't already have an active request out.
	$dbConn->query("SELECT id FROM request WHERE id={$iUserID} AND ((".($CFG['globaltime']-60).") <= rtimestamp)");
	if($dbConn->getresult())
	{
		Error(' :: Forgot Member Details :. Already Requested Details', "You have already made a membership details request within the last minute. Please check your e-mail, and if you are still having problems please contact the <a href=\"mailto:{$CFG['general']['admin']['email']}\">Webmaster</a>.");
	}

	// Generate an 8-digit numberkey.
	$iRequestKey = mt_rand(10000000, 99999999);

	// Add the request to the database, replacing any previous ones.
	$dbConn->query("DELETE FROM request WHERE id={$iUserID}");
	$dbConn->query("INSERT INTO request(id, rkey, rtimestamp) VALUES({$iUserID}, {$iRequestKey}, {$CFG['globaltime']})");

	// Send an e-mail message.
	$strMessage  = "A request was made at {$CFG['general']['name']}, in regard to your account there, to remind you of your username and to reset your password. If you did not make the request, please disregard this e-mail message.\r\n\r\n";
	$strMessage .= "Your username is: {$strUsername}\r\n\r\n";
	$strMessage .= "To reset your password, please visit the following page:\r\n";
	$strMessage .= 'http://'.$_SERVER['HTTP_HOST'].pathinfo($_SERVER['PHP_SELF'], PATHINFO_DIRNAME)."/member.php?action=reset&userid={$iUserID}&key={$iRequestKey}\r\n\r\n";
	$strMessage .= "Your password will be reset when you visit that page, and you will be given your new password.\r\n\r\n\r\n";
	$strMessage .= "Regards,\r\n\r\n";
	$strMessage .= "{$CFG['general']['name']}";
	mail($strEMail, "Your account details for {$CFG['general']['name']}", $strMessage, "From: {$CFG['general']['name']} Mailer <{$CFG['general']['admin']['email']}>");

	// Update the user.
	Msg('<b>Your username and details on how to reset your password have been sent to you via e-mail.</b><br /><br /><span class="smaller">You should be redirected to the forum index momentarily. Click <a href="index.php">here</a><br />if you do not want to wait any longer or if you are not redirected.</span>', 'index.php');
}

// *************************************************************************** \\

// Resets user details.
function ResetDetails()
{
	global $CFG, $dbConn;

	// Get the user ID and key.
	$iUserID = (int)$_REQUEST['userid'];
	$iRequestKey = (int)$_REQUEST['key'];

	// Get the timestamp of the request in question.
	$dbConn->query("SELECT username, email, rtimestamp FROM request, citizen WHERE citizen.id=request.id AND request.id={$iUserID} AND request.rkey={$iRequestKey}");
	if(!(list($strUsername, $strEMail, $dateTimestamp) = $dbConn->getresult()))
	{
		Error('', 'Invalid request specified.');
	}

	// Make sure the current time is no more than 24 hours older than the timestamp.
	if($CFG['globaltime'] > ($dateTimestamp + 86400))
	{
		Error('', 'The request to reset your password has expired, for it was made more than 24 hours ago. To resubmit the request, please use this <a href="member.php?action=forgotdetails">form</a>.');
	}

	// Okay, we're ready to reset the password. First, generate a new one.
	$strNewPassword = mt_rand(10000000, 99999999);

	// Update the user's record.
	$strMD5Password = md5($strNewPassword);
	$dbConn->query("UPDATE citizen SET passphrase='{$strMD5Password}' WHERE id={$iUserID}");

	// Send an e-mail message notifying them of the new password.
	$strMessage  = "As you requested, your password has been reset. Your current details are as follows:\r\n\r\n";
	$strMessage .= "Username: {$strUsername}\r\n";
	$strMessage .= "Password: {$strNewPassword}\r\n\r\n\r\n";
	$strMessage .= "Regards,\r\n\r\n";
	$strMessage .= "{$CFG['general']['name']}";
	mail($strEMail, "Your new password for {$CFG['general']['name']}", $strMessage, "From: {$CFG['general']['name']} Mailer <{$CFG['general']['admin']['email']}>");

	// Delete the request, for it's been fulfilled.
	$dbConn->query("DELETE FROM request WHERE id={$iUserID}");

	// Tell them the good news.
	Msg("<b>Your password has now been reset and e-mailed to you. Please check your e-mail to find your new password.</b>");
}

// *************************************************************************** \\

function Error($strPageTitle, $strError)
{
	global $CFG;

	// Template
	require("./skins/{$CFG['skin']}/membererror.tpl.php");

	// Send the page.
	exit;
}

// *************************************************************************** \\

// Activates a member's account.
function ActivateMember()
{
	global $CFG, $dbConn;

	// Get the user ID and hash.
	$iUserID = (int)$_REQUEST['userid'];
	$strHash = trim($_REQUEST['hash']);

	// Sanitize the hash.
	if(strlen($strHash))
	{
		$strHash = $dbConn->sanitize($strHash);
	}
	else
	{
		Msg('Invalid user specified.');
	}

	// Verify the user ID and hash match.
	$dbConn->query("SELECT COUNT(*) FROM citizen WHERE id={$iUserID} AND reghash='{$strHash}'");
	list($iCount) = $dbConn->getresult();
	if(!$iCount)
	{
		Msg('Invalid user specified.');
	}

	// Activate user.
	$dbConn->query("UPDATE citizen SET reghash=NULL WHERE id={$iUserID} AND reghash='{$strHash}'");

	// Get the information about the user that was just created.
	$dbConn->query("SELECT * FROM citizen WHERE id={$iUserID}");
	$aSQLResult = $dbConn->getresult(TRUE);

	// Store the member information into the session.
	LoadUser($aSQLResult);

	// Delete any guest entries from the session table.
	$dbConn->query("DELETE FROM guest WHERE id='".session_id()."'");

	// Update the forum stats.
	$dbConn->query("UPDATE stats SET content=content+1 WHERE name='membercount'");
	$dbConn->query("UPDATE stats SET content={$iUserID} WHERE name='newestmember'");

	// Render the page.
	Msg("<b>Thank you for activating your account.</b><br /><br /><span class=\"smaller\">You should be redirected to the forum index momentarily. Click <a href=\"index.php\">here</a><br />if you do not want to wait any longer or if you are not redirected.</span>", 'index.php');
}

// *************************************************************************** \\

// User wants to e-mail someone via the form.
function MailUser()
{
	global $CFG, $dbConn;

	// Constants
	define('USERID',    0);
	define('USERNAME',  1);
	define('EMAIL',     2);
	define('SUBJECT',   3);
	define('BODY',      4);

	// Are they logged in?
	if(!$_SESSION['loggedin'])
	{
		// No, so give them the bad news.
		Unauthorized();
	}

	// What user do they want to send an email to?
	$aUserInfo[USERID] = (int)$_REQUEST['userid'];

	// Get the user's information.
	$dbConn->query("SELECT username, email, publicemail, allowmail FROM citizen WHERE id={$aUserInfo[USERID]}");
	if(!(list($aUserInfo[USERNAME], $aUserInfo[EMAIL], $bPublicEMail, $bAllowMail) = $dbConn->getresult()))
	{
		Msg("Invalid user specified.{$CFG['msg']['invalidlink']}");
	}

	// Do they have permission to e-mail this user?
	if((!$_SESSION['permissions']['cviewadmincp'] && !$bPublicEMail) || ($_SESSION['permissions']['cviewadmincp'] && !$bAllowMail && !$bPublicEMail))
	{
		// Nope, let them know the bad news.
		Msg("Sorry! That user has specified that they do not wish to receive e-mails through this board. If you still wish to send an e-mail to this user, please contact the <a href=\"mailto:{$CFG['general']['admin']['email']}\">administrator</a> and they may be able to help.");
	}

	// Are they submitting?
	if(isset($_REQUEST['submit']))
	{
		// Get the information from the user.
		$aMessageInfo[SUBJECT] = $_REQUEST['subject'];
		$aMessageInfo[BODY] = $_REQUEST['body'];

		// Validate it.
		$aError = MailUserNow($aUserInfo, $aMessageInfo);
	}

	// Template
	require("./skins/{$CFG['skin']}/mailuser.tpl.php");

	// Send the page.
	exit;
}

// *************************************************************************** \\

// E-mails a user.
function MailUserNow($aUserInfo, $aMessageInfo)
{
	global $CFG;

	// Subject
	if(trim($aMessageInfo[SUBJECT]) == '')
	{
		// They either put in only whitespace or nothing at all.
		$aError[] = 'You must specify a subject.';
	}
	else if(strlen($aMessageInfo[SUBJECT]) > 64)
	{
		// Their subject is too long.
		$aError[] = 'The subject you specified is longer than 64 characters.';
	}

	// Message
	if(trim($aMessageInfo[BODY]) == '')
	{
		// They either put in only whitespace or nothing at all.
		$aError[] = 'You must specify a message.';
	}
	else if(strlen($aMessageInfo[BODY]) > $CFG['maxlen']['messagebody'])
	{
		// Their message is too long.
		$aError[] = "The message you specified is longer than {$CFG['maxlen']['messagebody']} characters.";
	}

	// If there was an error, let's return it.
	if(is_array($aError))
	{
		return $aError;
	}

	// Tack on the extra information to the message.
	$aMessageInfo[BODY] = "{$aMessageInfo[BODY]}\n\n-----\nThis message was sent using the 'Send E-Mail' feature at {$CFG['general']['name']}.\nThe owner(s) of the forum cannot be held responsible for the contents of this e-mail.";

	// Ensure the message uses CRLF for EOL.
	$aMessageInfo[BODY] = preg_replace("/(\r\n|\r|\n)/s", "\r\n", $aMessageInfo[BODY]);

	// Mail them.
	if(@mail($aUserInfo[EMAIL], $aMessageInfo[SUBJECT], $aMessageInfo[BODY], "From: {$_SESSION['email']}"))
	{
		Msg("<b>Your message has been successfully sent.</b><br /><br /><span class=\"smaller\">You should be redirected momentarily. Click <a href=\"member.php?action=getprofile&amp;userid={$aUserInfo[USERID]}\">here</a> if you do not want to wait any longer or if you are not redirected.</span>", "member.php?action=getprofile&userid={$aUserInfo[USERID]}");
	}
	else
	{
		Msg("There was a problem sending your message. Contact the forum <a href=\"mailto:{$CFG['general']['admin']['email']}\">administrator</a> if the problem persists.");
	}
}

// *************************************************************************** \\

// User wants to mark all forums as read.
function MarkRead()
{
	global $CFG;

	// Set their lastactive value.
	$_SESSION['lastactive'] = $CFG['globaltime'];

	// Let them know it was a success.
	Msg("<b>All forums have been marked as read, and the new post indicators will now be off.</b><br /><br /><span class=\"smaller\">You should be redirected momentarily. Click <a href=\"index.php\">here</a> if you do not want to wait any longer or if you are not redirected.</span>", 'index.php');
}
?>
