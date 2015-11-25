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

	// Is the user logged in?
	if($_SESSION['loggedin'])
	{
		AlreadyRegistered();
	}

	// Are the GD and FreeType libraries installed?
	if(!function_exists('imagettfbbox'))
	{
		// No. Disable image verification.
		$CFG['reg']['verify_img'] = FALSE;
	}

	// Are they coming for the first time or submitting information?
	if(isset($_REQUEST['submit']))
	{
		// Get the submitted information.
		$aReg['username'] = $_REQUEST['username'];
		$aReg['passworda'] = $_REQUEST['passworda'];
		$aReg['passwordb'] = $_REQUEST['passwordb'];
		$aReg['emaila'] = $_REQUEST['emaila'];
		$aReg['emailb'] = $_REQUEST['emailb'];
		$aReg['verifyimg'] = strtoupper($_REQUEST['verifyimg']);
		$aReg['website'] = $_REQUEST['website'];
		$aReg['aim'] = $_REQUEST['aim'];
		$aReg['icq'] = $_REQUEST['icq'];
		$aReg['msn'] = $_REQUEST['msn'];
		$aReg['yahoo'] = $_REQUEST['yahoo'];
		$aReg['referrer'] = $_REQUEST['referrer'];
		$aReg['birthmonth'] = (int)$_REQUEST['birthmonth'];
		$aReg['birthdate'] = (int)$_REQUEST['birthdate'];
		$aReg['birthyear'] = (int)$_REQUEST['birthyear'];
		if($aReg['birthyear'] == 0) $aReg['birthyear'] = '';
		$aReg['bio'] = $_REQUEST['bio'];
		$aReg['residence'] = $_REQUEST['residence'];
		$aReg['interests'] = $_REQUEST['interests'];
		$aReg['occupation'] = $_REQUEST['occupation'];
		$aReg['signature'] = $_REQUEST['signature'];
		$aReg['allowmail'] = (int)(bool)$_REQUEST['allowmail'];
		$aReg['invisible'] = (int)(bool)$_REQUEST['invisible'];
		$aReg['publicemail'] = (int)(bool)$_REQUEST['publicemail'];
		$aReg['autologin'] = (int)(bool)$_REQUEST['autologin'];
		$aReg['enablepms'] = (int)(bool)$_REQUEST['enablepms'];
		$aReg['pmnotifya'] = (int)(bool)$_REQUEST['pmnotifya'];
		$aReg['pmnotifyb'] = (int)(bool)$_REQUEST['pmnotifyb'];
		$aReg['showsigs'] = (int)(bool)$_REQUEST['showsigs'];
		$aReg['showavatars'] = (int)(bool)$_REQUEST['showavatars'];
		$aReg['threadview'] = abs((int)$_REQUEST['threadview']);
		$aReg['postsperpage'] = abs((int)$_REQUEST['postsperpage']);
		$aReg['threadsperpage'] = abs((int)$_REQUEST['threadsperpage']);
		$aReg['weekstart'] = abs((int)$_REQUEST['weekstart']);
		$aReg['timeoffset'] = (int)$_REQUEST['timeoffset'];
		$aReg['dst'] = (int)(bool)$_REQUEST['dst'];
		$aReg['dsth'] = (int)$_REQUEST['dsth'];
		$aReg['dstm'] = (int)$_REQUEST['dstm'];

		// Validate the information, and submit it to the database if everything's okay.
		$aError = ValidateInfo($aReg);
	}
	else
	{
		// Coming for the first time, so let's default the form elements.
		$aReg['website'] =  'http://';
		$aReg['birthmonth'] = 0;
		$aReg['birthdate'] = 0;
		unset($aReg['birthyear']);
		$aReg['allowmail'] = TRUE;
		$aReg['invisible'] = FALSE;
		$aReg['publicemail'] = FALSE;
		$aReg['autologin'] = FALSE;
		$aReg['enablepms'] = TRUE;
		$aReg['pmnotifya'] = TRUE;
		$aReg['pmnotifyb'] = TRUE;
		$aReg['showsigs'] = TRUE;
		$aReg['showavatars'] = TRUE;
		$aReg['threadview'] = 0;
		$aReg['postsperpage'] = 0;
		$aReg['threadsperpage'] = 0;
		$aReg['weekstart'] = 0;
		$aReg['timeoffset'] = $CFG['time']['display_offset'];
		$aReg['dst'] = $CFG['time']['dst'];
		$aReg['dsth'] = floor($CFG['time']['dst_offset'] / 3600);
		$aReg['dstm'] = ($CFG['time']['dst_offset'] - ($aReg['dsth'] * 3600)) / 60;
	}

	// Template
	require("./skins/{$CFG['skin']}/register.tpl.php");

	// Finish.
	exit;

// *************************************************************************** \\

function ValidateInfo($aReg)
{
	global $CFG, $dbConn;

	// Username
	if($aReg['username'] == '')
	{
		// They didn't specify a username.
		$aError[] = 'You must specify a desired username.';
	}
	else if(strlen($aReg['username']) > $CFG['maxlen']['username'])
	{
		// The username they specified is too long.
		$aError[] = "The username you specified is longer than {$CFG['maxlen']['username']} characters.";
	}
	else if(trim($aReg['username']) != $aReg['username'])
	{
		// Their username contains whitespace at the beginning and/or end.
		$aError[] = 'Usernames must not begin or end with whitespace.';
	}
	$strUsername = $dbConn->sanitize($aReg['username']);

	// Password
	if($aReg['passworda'] != $aReg['passwordb'])
	{
		// The two passwords they specified are not the same.
		$aError[] = 'The passwords you specified do not match.';
	}
	else if($aReg['passworda'] == '')
	{
		// They didn't specify a password.
		$aError[] = 'You must specify a password.';
	}
	else if(strlen($aReg['passworda']) > $CFG['maxlen']['password'])
	{
		// The password they specified is too long.
		$aError[] = "The password you specified is longer than {$CFG['maxlen']['password']} characters.";
	}
	$strPassword = md5($aReg['passworda']);

	// E-Mail Address
	if($aReg['emaila'] != $aReg['emailb'])
	{
		// The two e-mail addresses they specified are not the same.
		$aError[] = 'The e-mail addresses you specified do not match.';
	}
	else if($aReg['emaila'] == '')
	{
		// They didn't specify an e-mail address.
		$aError[] = 'You must specify an e-mail address.';
	}
	else if(strlen($aReg['emaila']) > $CFG['maxlen']['email'])
	{
		// The e-mail address they specified is too long.
		$aError[] = "The e-mail address you specified is longer than {$CFG['maxlen']['email']} characters.";
	}
	else if(!preg_match("/^(([^<>()[\]\\\\.,;:\s@\"]+(\.[^<>()[\]\\\\.,;:\s@\"]+)*)|(\"([^\"\\\\\r]|(\\\\[\w\W]))*\"))@((\[([0-9]{1,3}\.){3}[0-9]{1,3}\])|(([a-z\-0-9]+\.)+[a-z]{2,}))$/i", $aReg['emaila']))
	{
		// The "e-mail address" they specified does not match the format of a typical e-mail address.
		$aError[] = 'The e-mail address you specified is not a valid address.';
	}
	$strEMail = $dbConn->sanitize($aReg['emaila']);

	// Image verification.
	if(($CFG['reg']['verify_img'] == TRUE) && ($aReg['verifyimg'] != $_SESSION['randstr']))
	{
		$aError[] = 'The value you entered for the image verification is incorrect.';
		unset($_SESSION['randstr']);
	}

	// Web Site
	$aURL = @parse_url($aReg['website']);
	if(($aReg['website'] == 'http://') || ($aReg['website'] == ''))
	{
		// Either they specified nothing, or they left it at the default "http://".
		$aReg['website'] = '';
	}
	else if(!$aURL['scheme'])
	{
		// Default to HTTP.
		$aReg['website'] = "http://{$aReg['website']}";
	}
	if(strlen($aReg['website']) > $CFG['maxlen']['website'])
	{
		// The Web site they specified is too long.
		$aError[] = "The Web site you specified is longer than {$CFG['maxlen']['website']} characters.";
	}
	else
	{
		$strWebsite = $dbConn->sanitize($aReg['website']);
	}

	// AIM
	if(strlen($aReg['aim']) > $CFG['maxlen']['aim'])
	{
		// The AIM handle they specified is too long.
		$aError[] = "The AIM handle you specified is longer than {$CFG['maxlen']['aim']} characters.";
	}
	$strAIM = $dbConn->sanitize($aReg['aim']);

	// ICQ
	if(strlen($aReg['icq']) > $CFG['maxlen']['icq'])
	{
		// The ICQ number they specified is too long.
		$aError[] = "The ICQ number you specified is longer than {$CFG['maxlen']['icq']} characters.";
	}
	$strICQ = $dbConn->sanitize($aReg['icq']);

	// MSN
	if(strlen($aReg['msn']) > $CFG['maxlen']['msn'])
	{
		// The MSN Messenger handle they specified is too long.
		$aError[] = "The MSN Messenger handle you specified is longer than {$CFG['maxlen']['msn']} characters.";
	}
	$strMSN = $dbConn->sanitize($aReg['msn']);

	// Yahoo!
	if(strlen($aReg['yahoo']) > $CFG['maxlen']['yahoo'])
	{
		// The Yahoo! handle they specified is too long.
		$aError[] = "The Yahoo! handle you specified is longer than {$CFG['maxlen']['yahoo']} characters.";
	}
	$strYahoo = $dbConn->sanitize($aReg['yahoo']);

	// Referrer
	if(strlen($aReg['referrer']) > $CFG['maxlen']['username'])
	{
		// The referrer they specified is too long.
		$aError[] = "The referrer\'s username you specified is longer than {$CFG['maxlen']['username']} characters.";
	}
	$strReferrer = $dbConn->sanitize($aReg['referrer']);

	// Birthday
	if(($aReg['birthmonth'] < 0) || ($aReg['birthmonth'] > 12))
	{
		// The birthmonth they specified is invalid.
		$aError[] = 'The birthmonth you specified is not a valid month.';
	}
	else if(($aReg['birthmonth']) && ($aReg['birthdate'] == 0) && ($aReg['birthyear'] == ''))
	{
		// They specified a month but no date or year.
		$aError[] = 'If you specify a birthmonth, you must also specify your birthdate and/or birthyear.';
	}
	if(($aReg['birthdate'] < 0) || ($aReg['birthdate'] > 31))
	{
		// The birthdate they specified is invalid.
		$aError[] = 'The birthdate you specified is not a valid date.';
	}
	else if(($aReg['birthdate']) && ($aReg['birthmonth'] == 0))
	{
		// They specified a date but no month.
		$aError[] = 'If you specify a birthdate, you must also specify a birthmonth.';
	}
	if(($aReg['birthyear'] != '') && (($aReg['birthyear'] < 1900) || ($aReg['birthyear'] > date('Y'))))
	{
		// The birthyear they specified is invalid.
		$aError[] = 'The birthyear you specified is not a valid year.';
	}
	if($aReg['birthyear'] == '')
	{
		$aReg['birthyear'] = 0;
	}
	$strBirthday = "'" . sprintf('%04u-%02u-%02u', $aReg['birthyear'], $aReg['birthmonth'], $aReg['birthdate']) . "'";
	
	// Some databases will not accept invalid dates.
	if ($strBirthday == "'0000-00-00'") { $strBirthday = 'NULL'; }

	// Biography
	if(strlen($aReg['bio']) > $CFG['maxlen']['bio'])
	{
		// The biography they specified is too long.
		$aError[] = "The biography you specified is longer than {$CFG['maxlen']['bio']} characters.";
	}
	$strBio = $dbConn->sanitize($aReg['bio']);

	// Location
	if(strlen($aReg['residence']) > $CFG['maxlen']['location'])
	{
		// The location they specified is too long.
		$aError[] = "The location you specified is longer than {$CFG['maxlen']['location']} characters.";
	}
	$strLocation = $dbConn->sanitize($aReg['residence']);

	// Interests
	if(strlen($aReg['interests']) > $CFG['maxlen']['interests'])
	{
		// The interests they specified is too long.
		$aError[] = "The value you specified for interests is longer than {$CFG['maxlen']['interests']} characters.";
	}
	$strInterests = $dbConn->sanitize($aReg['interests']);

	// Occupation
	if(strlen($aReg['occupation']) > $CFG['maxlen']['occupation'])
	{
		// The occupation they specified is too long.
		$aError[] = "The occupation you specified is longer than {$CFG['maxlen']['occupation']} characters.";
	}
	$strOccupation = $dbConn->sanitize($aReg['occupation']);

	// Signature
	if(strlen($aReg['signature']) > $CFG['maxlen']['signature'])
	{
		// The signature they specified is too long.
		$aError[] = "The signature you specified is longer than {$CFG['maxlen']['signature']} characters.";
	}
	$strSignature = $dbConn->sanitize($aReg['signature']);

	// Default Thread View
	if(($aReg['threadview'] > 365) && ($aReg['threadview'] != 1000))
	{
		// They specified an invalid choice for the default thread view.
		$iThreadView = 0;
	}
	else
	{
		$iThreadView = $aReg['threadview'];
	}

	// Default Posts Per Page
	if($aReg['postsperpage'] < 0)
	{
		// They specified an invalid choice for the default posts per page.
		$iPostsPerPage = 0;
	}
	else
	{
		$iPostsPerPage = $aReg['postsperpage'];
	}

	// Default Threads Per Page
	if($aReg['threadsperpage'] < 0)
	{
		// They specified an invalid choice for the default threads per page.
		$iThreadsPerPage = 0;
	}
	else
	{
		$iThreadsPerPage = $aReg['threadsperpage'];
	}

	// Start Of The Week
	if($aReg['weekstart'] > 6)
	{
		// They specified an invalid day for the start of the week.
		$iWeekStart = 0;
	}
	else
	{
		$iWeekStart = $aReg['weekstart'];
	}

	// Time Offset
	if(($aReg['timeoffset'] > 43200) || ($aReg['timeoffset'] < -43200))
	{
		// They specified an invalid time for the time offset.
		$strTimeOffset = $CFG['time']['display_offset'];
	}
	else
	{
		$strTimeOffset = $aReg['timeoffset'];
	}

	// DST Offset
	$iDSTOffset = ($aReg['dsth'] * 3600) + ($aReg['dstm'] * 60);
	if(($iDSTOffset > 65535) || ($iDSTOffset < 0))
	{
		$iDSTOffset = 0;
	}

	// Do they have any errors?
	if(is_array($aError))
	{
		return $aError;
	}

	// Is there already a user with the desired username?
	$dbConn->query("SELECT id FROM citizen WHERE username='{$strUsername}'");
	if($dbConn->getresult())
	{
		// Yep, a user already exists. Let them know the bad news.
		$aError[] = 'There is already a user with that username. Please specify a different one.';
	}

	// Is there already a user with the specified e-mail address?
	$dbConn->query("SELECT id FROM citizen WHERE email='{$strEMail}'");
	if($dbConn->getresult())
	{
		// Yep, e-mail address is already in use. Let them know the bad news.
		$aError[] = 'There is already a user with that e-mail address. Please specify a different one.';
	}

	// Do they have any errors?
	if(is_array($aError))
	{
		return $aError;
	}

	// Is e-mail validation enabled?
	if($CFG['reg']['email'])
	{
		// Yes, so generate a registration hash.
		$strHash = md5(mt_rand());

		// Add the user's member record.
		$dJoined = gmdate('Y-m-d');
		$dbConn->query("INSERT INTO citizen(username, passphrase, email, datejoined, website, aim, icq, msn, yahoo, referrer, birthday, bio, residence, interests, occupation, signature, allowmail, invisible, publicemail, enablepms, pmnotifya, pmnotifyb, threadview, postsperpage, threadsperpage, weekstart, timeoffset, dst, dstoffset, postcount, showsigs, showavatars, autologin, usergroup, pmfolders, reghash) VALUES('{$strUsername}', '{$strPassword}', '{$strEMail}', '{$dJoined}', '{$strWebsite}', '{$strAIM}', '{$strICQ}', '{$strMSN}', '{$strYahoo}', '{$strReferrer}', '{$strBirthday}', '{$strBio}', '{$strLocation}', '{$strInterests}', '{$strOccupation}', '{$strSignature}', {$aReg['allowmail']}, {$aReg['invisible']}, {$aReg['publicemail']}, {$aReg['enablepms']}, {$aReg['pmnotifya']}, {$aReg['pmnotifyb']}, {$iThreadView}, {$iPostsPerPage}, {$iThreadsPerPage}, {$iWeekStart}, {$strTimeOffset}, {$aReg['dst']}, {$iDSTOffset}, 0, {$aReg['showsigs']}, {$aReg['showavatars']}, {$aReg['autologin']}, 1, 'a:0:{}', '{$strHash}')");
		$iUserID = mysql_insert_id();

		// Send the user their activation e-mail.
		$strMessage = file_get_contents('includes/activation.tpl');
		$aReg['actlink'] = 'http://'.$_SERVER['HTTP_HOST'].pathinfo($_SERVER['PHP_SELF'], PATHINFO_DIRNAME)."/member.php?action=activate&userid={$iUserID}&hash={$strHash}";
		@eval("\$strMessage = \"{$strMessage}\";");
		mail($aReg['emaila'], "Action required to activate membership for {$CFG['general']['name']}!", preg_replace("/(\r\n|\r|\n)/s", "\r\n", $strMessage), "From: {$CFG['general']['name']} Mailer <{$CFG['general']['admin']['email']}>");

		// Show them the success page.
		JustRegistered();
	}

	// Add the user's member record.
	$dJoined = gmdate('Y-m-d');
	$dbConn->query("INSERT INTO citizen(username, passphrase, email, datejoined, website, aim, icq, msn, yahoo, referrer, birthday, bio, residence, interests, occupation, signature, allowmail, invisible, publicemail, enablepms, pmnotifya, pmnotifyb, threadview, postsperpage, threadsperpage, weekstart, timeoffset, dst, dstoffset, postcount, showsigs, showavatars, autologin, usergroup, pmfolders) VALUES('{$strUsername}', '{$strPassword}', '{$strEMail}', '{$dJoined}', '{$strWebsite}', '{$strAIM}', '{$strICQ}', '{$strMSN}', '{$strYahoo}', '{$strReferrer}', {$strBirthday}, '{$strBio}', '{$strLocation}', '{$strInterests}', '{$strOccupation}', '{$strSignature}', {$aReg['allowmail']}, {$aReg['invisible']}, {$aReg['publicemail']}, {$aReg['enablepms']}, {$aReg['pmnotifya']}, {$aReg['pmnotifyb']}, {$iThreadView}, {$iPostsPerPage}, {$iThreadsPerPage}, {$iWeekStart}, {$strTimeOffset}, {$aReg['dst']}, {$iDSTOffset}, 0, {$aReg['showsigs']}, {$aReg['showavatars']}, {$aReg['autologin']}, 1, 'a:0:{}')");
	$iUserID = $dbConn->getinsertid('citizen');

	// Update the forum stats.
	$dbConn->query("UPDATE stats SET content=content+1 WHERE name='membercount'");
	$dbConn->query("UPDATE stats SET content={$iUserID} WHERE name='newestmember'");

	// Show them the success page.
	Success($iUserID);
}

function Success($iUserID)
{
	global $CFG, $dbConn;

	// Get the information about the user that was just created.
	$dbConn->query("SELECT * FROM citizen WHERE id={$iUserID}");
	$aSQLResult = $dbConn->getresult(TRUE);

	// Store the member information into the session.
	LoadUser($aSQLResult);

	// Delete any guest entries from the session table.
	$dbConn->query("DELETE FROM guest WHERE id='".session_id()."'");

	// Render the page.
	Msg("<b>Thank you for registering.</b><br /><br /><span class=\"smaller\">You should be redirected to the forum index momentarily. Click <a href=\"index.php\">here</a><br />if you do not want to wait any longer or if you are not redirected.</span>", 'index.php');
}

function JustRegistered()
{
	global $CFG;

	// Get the information of each forum, for our Forum Jump later.
	list($aCategory, $aForum) = GetForumInfo();

	// Template
	require("./skins/{$CFG['skin']}/justregistered.tpl.php");

	// Send the page.
	exit;
}

function AlreadyRegistered()
{
	global $CFG;

	// Get the information of each forum, for our Forum Jump later.
	list($aCategory, $aForum) = GetForumInfo();

	// Template
	require("./skins/{$CFG['skin']}/alreadyregistered.tpl.php");

	// Send the page.
	exit;
}
?>