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
	if(!$_SESSION['loggedin'])
	{
		// No, so they can't access their control panel.
		Unauthorized();
	}

	// What section do they want to view?
	switch($_REQUEST['section'])
	{
		case 'profile':
		{
			$strSection = $_REQUEST['section'];
			EditProfile();
		}

		case 'options':
		{
			$strSection = $_REQUEST['section'];
			EditOptions();
		}

		case 'avatar':
		{
			$strSection = $_REQUEST['section'];
			EditAvatar();
		}

		case 'password':
		{
			$strSection = $_REQUEST['section'];
			EditPassword();
		}

		case 'buddylist':
		{
			$strSection = $_REQUEST['section'];
			EditBuddyList();
		}

		case 'ignorelist':
		{
			$strSection = $_REQUEST['section'];
			EditIgnoreList();
		}

		default:
		{
			$strSection = 'index';
			ShowIndex();
		}
	}

// *************************************************************************** \\

function ShowIndex()
{
	global $CFG, $dbConn, $aPostIcons;

	// Constants
	define('TITLE',        0);
	define('PARENT',       1);
	define('LPOST',        2);
	define('LPOSTER',      3);
	define('LPOSTERNAME',  4);
	define('BID',          5);
	define('BNAME',        6);

	// Populate Buddy list panel; get our buddies.
	$dbConn->query("SELECT buddylist FROM citizen WHERE id={$_SESSION['userid']}");
	list($strBuddyList) = $dbConn->getresult();

	// Do we have anyone in our Buddy list?
	if($strBuddyList)
	{
		// Yes, so put each of them in the respective Online or Offline list.
		$dbConn->query("SELECT id, username, lastactive, loggedin, invisible FROM citizen WHERE id IN ({$strBuddyList})");
		while($aSQLResult = $dbConn->getresult(TRUE))
		{
			// Is the user online or offline?
			if((($aSQLResult['lastactive'] + 300) >= $CFG['globaltime']) && ($aSQLResult['loggedin'] && !$aSQLResult['invisible']))
			{
				// Online, so add them to the Online Buddies list.
				$aOnlineBuddies[$aSQLResult['id']] = $aSQLResult['username'];
			}
			else
			{
				// Offline, so add them to the Offline Buddies list.
				$aOfflineBuddies[$aSQLResult['id']] = $aSQLResult['username'];
			}
		}
	}

	// Get a list of all of our new/unread PM messages.
	if($_SESSION['enablepms'])
	{
		$dbConn->query("SELECT pm.id, pm.datetime, pm.author, citizen.username, pm.subject, pm.icon, pm.tracking FROM pm LEFT JOIN citizen ON (citizen.id=pm.author) WHERE pm.ownerid={$_SESSION['userid']} AND pm.recipient={$_SESSION['userid']} AND pm.beenread=0 ORDER BY pm.datetime DESC");
		while($aSQLResult = $dbConn->getresult())
		{
			$iMessageID = $aSQLResult[0];
			$aMessages[$iMessageID][0] = $aSQLResult[1];
			$aMessages[$iMessageID][1] = $aSQLResult[2];
			$aMessages[$iMessageID][2] = $aSQLResult[3];
			$aMessages[$iMessageID][3] = $aSQLResult[4];
			$aMessages[$iMessageID][4] = "{$CFG['paths']['posticons']}{$aPostIcons[$aSQLResult[5]]['filename']}";
			$aMessages[$iMessageID][5] = $aPostIcons[$aSQLResult[5]]['title'];
			$aMessages[$iMessageID][6] = $aSQLResult[6];
			$aMessages[$iMessageID][7] = in_array($aSQLResult[2], $_SESSION['ignorelist']);
		}
	}

	// Get our last ten posts.
	$dbConn->query("SELECT p1.id, p1.title, p1.parent FROM post AS p1 LEFT JOIN post AS p2 ON (p1.parent = p2.parent AND p1.author = p2.author AND p1.id < p2.id) WHERE p2.id IS NULL AND p1.author={$_SESSION['userid']} ORDER BY p1.datetime_posted DESC LIMIT 10");
	while($aSQLResult = $dbConn->getresult())
	{
		// Save the post information.
		$iPostID = $aSQLResult[0];
		$aLastPosts[$iPostID][TITLE] = $aSQLResult[1];
		$aLastPosts[$iPostID][PARENT] = $aSQLResult[2];

		// Save the thread ID to a list of threads for which we need to get information.
		$aThreadIDs[] = $aSQLResult[2];
	}

	// Get information regarding the threads of our last ten posts.
	if(is_array($aLastPosts))
	{
		$strThreadIDs = implode(', ', $aThreadIDs);
		$dbConn->query("SELECT DISTINCT t.id, t.title, t.lpost, t.lposter, citizen.username, t.parent, board.name FROM thread AS t LEFT JOIN board ON (board.id = t.parent) LEFT JOIN citizen ON (citizen.id = t.lposter) WHERE t.id IN ({$strThreadIDs})");
		while($aSQLResult = $dbConn->getresult())
		{
			$iThreadID = $aSQLResult[0];
			$aLastThreads[$iThreadID][TITLE] = $aSQLResult[1];
			$aLastThreads[$iThreadID][LPOST] = $aSQLResult[2];
			$aLastThreads[$iThreadID][LPOSTER] = $aSQLResult[3];
			$aLastThreads[$iThreadID][LPOSTERNAME] = $aSQLResult[4];
			$aLastThreads[$iThreadID][BID] = $aSQLResult[5];
			$aLastThreads[$iThreadID][BNAME] = $aSQLResult[6];
		}
	}

	// Template
	require("./skins/{$CFG['skin']}/usercp/main.tpl.php");

	// Send the page.
	exit;
}

// *************************************************************************** \\

function EditProfile()
{
	global $CFG, $dbConn;

	// Are they coming for the first time or submitting information?
	if(isset($_REQUEST['submit']))
	{
		// Submitting information, so store it.
		$aUserInfo['emaila'] = $_REQUEST['emaila'];
		$aUserInfo['emailb'] = $_REQUEST['emailb'];
		$aUserInfo['website'] = $_REQUEST['website'];
		$aUserInfo['aim'] = $_REQUEST['aim'];
		$aUserInfo['icq'] = $_REQUEST['icq'];
		$aUserInfo['msn'] = $_REQUEST['msn'];
		$aUserInfo['yahoo'] = $_REQUEST['yahoo'];
		$aUserInfo['birthmonth'] = (int)$_REQUEST['birthmonth'];
		$aUserInfo['birthdate'] = (int)$_REQUEST['birthdate'];
		$aUserInfo['birthyear'] = (int)$_REQUEST['birthyear'];
		if($aUserInfo['birthyear'] == 0) $aUserInfo['birthyear'] = '';
		$aUserInfo['bio'] = $_REQUEST['bio'];
		$aUserInfo['residence'] = $_REQUEST['residence'];
		$aUserInfo['interests'] = $_REQUEST['interests'];
		$aUserInfo['occupation'] = $_REQUEST['occupation'];
		$aUserInfo['signature'] = $_REQUEST['signature'];

		// Validate the information, and submit it to the database if everything's okay.
		$aError = ValidateProfile($aUserInfo);
	}
	else
	{
		// Coming for the first time, so get the relevant user info. from profile.
		$dbConn->query("SELECT email AS emaila, email AS emailb, website, aim, icq, msn, yahoo, birthday, bio, residence, interests, occupation, signature FROM citizen WHERE id={$_SESSION['userid']}");
		$aUserInfo = $dbConn->getresult(TRUE);

		// Prepare some of their info.
		list($aUserInfo['birthyear'], $aUserInfo['birthmonth'], $aUserInfo['birthdate']) = sscanf($aUserInfo['birthday'], '%u-%u-%u');
		if(!$aUserInfo['website'])
		{
			$aUserInfo['website'] = 'http://';
		}
	}

	// Template
	require("./skins/{$CFG['skin']}/usercp/profile.tpl.php");

	// Send the page.
	exit;
}

function ValidateProfile($aUserInfo)
{
	global $CFG, $dbConn;

	// E-Mail Address
	if($aUserInfo['emaila'] != $aUserInfo['emailb'])
	{
		// The two e-mail addresses they specified are not the same.
		$aError[] = 'The e-mail addresses you specified do not match.';
	}
	else if($aUserInfo['emaila'] == '')
	{
		// They didn't specify an e-mail address.
		$aError[] = 'You must specify an e-mail address.';
	}
	else if(strlen($aUserInfo['emaila']) > $CFG['maxlen']['email'])
	{
		// The e-mail address they specified is too long.
		$aError[] = "The e-mail address you specified is longer than {$CFG['maxlen']['email']} characters.";
	}
	else if(!preg_match("/^(([^<>()[\]\\\\.,;:\s@\"]+(\.[^<>()[\]\\\\.,;:\s@\"]+)*)|(\"([^\"\\\\\r]|(\\\\[\w\W]))*\"))@((\[([0-9]{1,3}\.){3}[0-9]{1,3}\])|(([a-z\-0-9]+\.)+[a-z]{2,}))$/i", $aUserInfo['emaila']))
	{
		// The "e-mail address" they specified does not match the format of a typical e-mail address.
		$aError[] = 'The e-mail address you specified is not a valid address.';
	}
	$strEMail = $dbConn->sanitize($aUserInfo['emaila']);

	// Web Site
	$aURL = @parse_url($aUserInfo['website']);
	if(($aUserInfo['website'] == 'http://') || ($aUserInfo['website'] == ''))
	{
		// Either they specified nothing, or they left it at the default "http://".
		$aUserInfo['website'] = '';
	}
	else if(!$aURL['scheme'])
	{
		// Default to HTTP.
		$aUserInfo['website'] = "http://{$aUserInfo['website']}";
	}
	if(strlen($aUserInfo['website']) > $CFG['maxlen']['website'])
	{
		// The Web site they specified is too long.
		$aError[] = "The Web site you specified is longer than {$CFG['maxlen']['website']} characters.";
	}
	else
	{
		$strWebsite = $dbConn->sanitize($aUserInfo['website']);
	}

	// AIM
	if(strlen($aUserInfo['aim']) > $CFG['maxlen']['aim'])
	{
		// The AIM handle they specified is too long.
		$aError[] = "The AIM handle you specified is longer than {$CFG['maxlen']['aim']} characters.";
	}
	$strAIM = $dbConn->sanitize($aUserInfo['aim']);

	// ICQ
	if(strlen($aUserInfo['icq']) > $CFG['maxlen']['icq'])
	{
		// The ICQ number they specified is too long.
		$aError[] = "The ICQ number you specified is longer than {$CFG['maxlen']['icq']} characters.";
	}
	$strICQ = $dbConn->sanitize($aUserInfo['icq']);

	// MSN
	if(strlen($aUserInfo['msn']) > $CFG['maxlen']['msn'])
	{
		// The MSN Messenger handle they specified is too long.
		$aError[] = "The MSN Messenger handle you specified is longer than {$CFG['maxlen']['msn']} characters.";
	}
	$strMSN = $dbConn->sanitize($aUserInfo['msn']);

	// Yahoo!
	if(strlen($aUserInfo['yahoo']) > $CFG['maxlen']['yahoo'])
	{
		// The Yahoo! handle they specified is too long.
		$aError[] = "The Yahoo! handle you specified is longer than {$CFG['maxlen']['yahoo']} characters.";
	}
	$strYahoo = $dbConn->sanitize($aUserInfo['yahoo']);

	// Birthday
	if(($aUserInfo['birthmonth'] < 0) || ($aUserInfo['birthmonth'] > 12))
	{
		// The birthmonth they specified is invalid.
		$aError[] = 'The birthmonth you specified is not a valid month.';
	}
	else if(($aUserInfo['birthmonth']) && ($aUserInfo['birthdate'] == 0) && ($aUserInfo['birthyear'] == ''))
	{
		// They specified a month but no date or year.
		$aError[] = 'If you specify a birthmonth, you must also specify your birthdate and/or birthyear.';
	}
	if(($aUserInfo['birthdate'] < 0) || ($aUserInfo['birthdate'] > 31))
	{
		// The birthdate they specified is invalid.
		$aError[] = 'The birthdate you specified is not a valid date.';
	}
	else if(($aUserInfo['birthdate']) && ($aUserInfo['birthmonth'] == 0))
	{
		// They specified a date but no month.
		$aError[] = 'If you specify a birthdate, you must also specify a birthmonth.';
	}
	if(($aUserInfo['birthyear'] != '') && (($aUserInfo['birthyear'] < 1900) || ($aUserInfo['birthyear'] > date('Y'))))
	{
		// The birthyear they specified is invalid.
		$aError[] = 'The birthyear you specified is not a valid year.';
	}
	if($aUserInfo['birthyear'] == '')
	{
		$aUserInfo['birthyear'] = 0;
	}
	$strBirthday = sprintf('%04u-%02u-%02u', $aUserInfo['birthyear'], $aUserInfo['birthmonth'], $aUserInfo['birthdate']);

	// Biography
	if(strlen($aUserInfo['bio']) > $CFG['maxlen']['bio'])
	{
		// The biography they specified is too long.
		$aError[] = "The biography you specified is longer than {$CFG['maxlen']['bio']} characters.";
	}
	$strBio = $dbConn->sanitize($aUserInfo['bio']);

	// Location
	if(strlen($aUserInfo['residence']) > $CFG['maxlen']['location'])
	{
		// The location they specified is too long.
		$aError[] = "The location you specified is longer than {$CFG['maxlen']['location']} characters.";
	}
	$strLocation = $dbConn->sanitize($aUserInfo['residence']);

	// Interests
	if(strlen($aUserInfo['interests']) > $CFG['maxlen']['interests'])
	{
		// The interests they specified is too long.
		$aError[] = "The value you specified for interests is longer than {$CFG['maxlen']['interests']} characters.";
	}
	$strInterests = $dbConn->sanitize($aUserInfo['interests']);

	// Occupation
	if(strlen($aUserInfo['occupation']) > $CFG['maxlen']['occupation'])
	{
		// The occupation they specified is too long.
		$aError[] = "The occupation you specified is longer than {$CFG['maxlen']['occupation']} characters.";
	}
	$strOccupation = $dbConn->sanitize($aUserInfo['occupation']);

	// Signature
	if(strlen($aUserInfo['signature']) > $CFG['maxlen']['signature'])
	{
		// The signature they specified is too long.
		$aError[] = "The signature you specified is longer than {$CFG['maxlen']['signature']} characters.";
	}
	$strSignature = $dbConn->sanitize($aUserInfo['signature']);

	// Do they have any error?
	if(is_array($aError))
	{
		return $aError;
	}

	// Save the new information to the member's record.
	$dbConn->query("UPDATE citizen SET email='{$strEMail}', website='{$strWebsite}', aim='{$strAIM}', icq='{$strICQ}', msn='{$strMSN}', yahoo='{$strYahoo}', birthday='{$strBirthday}', bio='{$strBio}', residence='{$strLocation}', interests='{$strInterests}', occupation='{$strOccupation}', signature='{$strSignature}' WHERE id={$_SESSION['userid']}");

	// Update the user's session, so they don't have to logout then back in for the settings to take effect.
	$_SESSION['email'] = $aUserInfo['emaila'];

	// Render a success page.
	$strUsername = htmlsanitize($_SESSION['username']);
	Msg("<b>Thank you for updating your profile, {$strUsername}.</b><br /><br /><span class=\"smaller\">You should be redirected to the User Control Panel momentarily. Click <a href=\"usercp.php\">here</a><br />if you do not want to wait any longer or if you are not redirected.</span>", 'usercp.php');
}

// *************************************************************************** \\

function EditOptions()
{
	global $CFG, $dbConn;

	// Are they coming for the first time or submitting information?
	if(isset($_REQUEST['submit']) || isset($_REQUEST['editavatar']))
	{
		// Submitting information, so store it.
		$aUserInfo['allowmail'] = (int)(bool)$_REQUEST['allowmail'];
		$aUserInfo['invisible'] = (int)(bool)$_REQUEST['invisible'];
		$aUserInfo['publicemail'] = (int)(bool)$_REQUEST['publicemail'];
		$aUserInfo['enablepms'] = (int)(bool)$_REQUEST['enablepms'];
		$aUserInfo['pmnotifya'] = (int)(bool)$_REQUEST['pmnotifya'];
		$aUserInfo['pmnotifyb'] = (int)(bool)$_REQUEST['pmnotifyb'];
		$aUserInfo['rejectpms'] = (int)(bool)$_REQUEST['rejectpms'];
		$aUserInfo['threadview'] = abs((int)$_REQUEST['threadview']);
		$aUserInfo['postsperpage'] = abs((int)$_REQUEST['postsperpage']);
		$aUserInfo['threadsperpage'] = abs((int)$_REQUEST['threadsperpage']);
		$aUserInfo['weekstart'] = abs((int)$_REQUEST['weekstart']);
		$aUserInfo['timeoffset'] = (int)$_REQUEST['timeoffset'];
		$aUserInfo['dst'] = (int)(bool)$_REQUEST['dst'];
		$aUserInfo['dsth'] = abs((int)$_REQUEST['dsth']);
		$aUserInfo['dstm'] = abs((int)$_REQUEST['dstm']);
		$aUserInfo['showsigs'] = (int)(bool)$_REQUEST['showsigs'];
		$aUserInfo['showavatars'] = (int)(bool)$_REQUEST['showavatars'];
		$aUserInfo['autologin'] = (int)(bool)$_REQUEST['autologin'];

		// Validate the information, and submit it to the database if everything's okay.
		$aError = ValidateOptions($aUserInfo);
	}
	else
	{
		// Coming for the first time, so get the relevant user info. from profile.
		$dbConn->query("SELECT allowmail, invisible, publicemail, enablepms, pmnotifya, pmnotifyb, rejectpms, threadview, postsperpage, threadsperpage, weekstart, timeoffset, dst, dstoffset, showsigs, showavatars, autologin FROM citizen WHERE id={$_SESSION['userid']}");
		$aUserInfo = $dbConn->getresult(TRUE);
		$aUserInfo['dsth'] = floor($aUserInfo['dstoffset'] / 3600);
		$aUserInfo['dstm'] = ($aUserInfo['dstoffset'] - ($aUserInfo['dsth'] * 3600)) / 60;
	}

	// Template
	require("./skins/{$CFG['skin']}/usercp/options.tpl.php");

	// Send the page.
	exit;
}

function ValidateOptions($aUserInfo)
{
	global $CFG, $dbConn;

	// Default Thread View
	if(($aUserInfo['threadview'] > 365) && ($aUserInfo['threadview'] != 1000))
	{
		// They specified an invalid choice for the default thread view.
		$iThreadView = $CFG['defaults']['threadview'];
	}
	else
	{
		$iThreadView = $aUserInfo['threadview'];
	}

	// Default Posts Per Page
	if($aUserInfo['postsperpage'] < 0)
	{
		// They specified an invalid choice for the default posts per page.
		$iPostsPerPage = 0;
	}
	else
	{
		$iPostsPerPage = $aUserInfo['postsperpage'];
	}

	// Default Threads Per Page
	if($aUserInfo['threadsperpage'] < 0)
	{
		// They specified an invalid choice for the default threads per page.
		$iThreadsPerPage = 0;
	}
	else
	{
		$iThreadsPerPage = $aUserInfo['threadsperpage'];
	}

	// Start Of The Week
	if($aUserInfo['weekstart'] > 6)
	{
		// They specified an invalid day for the start of the week.
		$iWeekStart = 0;
	}
	else
	{
		$iWeekStart = $aUserInfo['weekstart'];
	}

	// Time Offset
	if(($aUserInfo['timeoffset'] > 43200) || ($aUserInfo['timeoffset'] < -43200))
	{
		// They specified an invalid time for the time offset.
		$strTimeOffset = $CFG['time']['display_offset'];
	}
	else
	{
		$strTimeOffset = $aUserInfo['timeoffset'];
	}

	// DST Offset
	$iDSTOffset = ($aUserInfo['dsth'] * 3600) + ($aUserInfo['dstm'] * 60);
	if(($iDSTOffset > 65535) || ($iDSTOffset < 0))
	{
		$iDSTOffset = 0;
	}

	// Save the new information to the member's record.
	$dbConn->query("UPDATE citizen SET allowmail={$aUserInfo['allowmail']}, invisible={$aUserInfo['invisible']}, publicemail={$aUserInfo['publicemail']}, enablepms={$aUserInfo['enablepms']}, pmnotifya={$aUserInfo['pmnotifya']}, pmnotifyb={$aUserInfo['pmnotifyb']}, rejectpms={$aUserInfo['rejectpms']}, threadview={$iThreadView}, postsperpage={$iPostsPerPage}, threadsperpage={$iThreadsPerPage}, weekstart={$iWeekStart}, timeoffset={$strTimeOffset}, dst={$aUserInfo['dst']}, dstoffset={$iDSTOffset}, showsigs={$aUserInfo['showsigs']}, showavatars={$aUserInfo['showavatars']}, autologin={$aUserInfo['autologin']} WHERE id={$_SESSION['userid']}");

	if($aUserInfo['autologin'] && (!$_SESSION['autologin']))
	{
		setcookie('activeuserid', $_SESSION['userid'], $CFG['globaltime']+2592000, $CFG['paths']['cookies']);
		setcookie('activepassword', $_SESSION['passphrase'], $CFG['globaltime']+2592000, $CFG['paths']['cookies']);
	}
	else if((!$aUserInfo['autologin']) && $_SESSION['autologin'])
	{
		setcookie('activeuserid', '', $CFG['globaltime']+2592000, $CFG['paths']['cookies']);
		setcookie('activepassword', '', $CFG['globaltime']+2592000, $CFG['paths']['cookies']);
	}

	// Update the user's session, so they don't have to logout then back in for the settings to take effect.
	$_SESSION['autologin'] = $aUserInfo['autologin'];
	$_SESSION['pmnotifyb'] = (bool)$aUserInfo['pmnotifyb'];
	$_SESSION['enablepms'] = (bool)$aUserInfo['enablepms'];
	$_SESSION['rejectpms'] = (bool)$aUserInfo['rejectpms'];
	$_SESSION['showsigs'] = $aUserInfo['showsigs'];
	$_SESSION['showavatars'] = $aUserInfo['showavatars'];
	$_SESSION['threadview'] = $iThreadView ? $iThreadView : $CFG['default']['threadview'];
	$_SESSION['postsperpage'] = $iPostsPerPage ? $iPostsPerPage : $CFG['default']['postsperpage'];
	$_SESSION['threadsperpage'] = $iThreadsPerPage ? $iThreadsPerPage : $CFG['default']['threadsperpage'];
	$_SESSION['weekstart'] = $iWeekStart;
	$_SESSION['timeoffset'] = $strTimeOffset;
	$_SESSION['dst'] = $aUserInfo['dst'];
	$_SESSION['dstoffset'] = $iDSTOffset;
	$CFG['time']['display_offset'] = $_SESSION['timeoffset'];
	$CFG['time']['dst'] = $_SESSION['dst'];
	$CFG['time']['dst_offset'] = $_SESSION['dstoffset'];

	// Render a success page.
	if($_REQUEST['editavatar'])
	{
		$strRedirect = 'usercp.php?section=avatar';
	}
	else
	{
		$strRedirect = 'usercp.php';
	}
	$strUsername = htmlsanitize($_SESSION['username']);
	Msg("<b>Thank you for updating your options, {$strUsername}.</b><br /><br /><span class=\"smaller\">You should be redirected to the User Control Panel momentarily. Click <a href=\"{$strRedirect}\">here</a><br />if you do not want to wait any longer or if you are not redirected.</span>", $strRedirect);
}

// *************************************************************************** \\

function EditAvatar()
{
	global $CFG, $dbConn, $aAvatars;

	// Are they submitting information?
	if(isset($_REQUEST['submit']))
	{
		// Yup, so try and validate it; submit it to the database if everything's okay.
		$aError = ValidateAvatar();
	}

	// Get the avatar information.
	$dbConn->query("SELECT filename, datum FROM avatar WHERE id={$_SESSION['userid']}");
	list($strFilename, $strAvatarData) = $dbConn->getresult();
	if($strFilename == NULL)
	{
		$iAvatarID = $strAvatarData;
	}
	else
	{
		unset($strAvatarData);
	}

	// Template
	require("./skins/{$CFG['skin']}/usercp/avatar.tpl.php");

	// Send the page.
	exit;
}

function ValidateAvatar()
{
	global $CFG, $dbConn, $aAvatars;

	// What did they choose?
	if($_REQUEST['avatarid'] == -2)
	{
		// No avatar, so delete the current one.
		$dbConn->query("DELETE FROM avatar WHERE id={$_SESSION['userid']}");
	}
	else if($_REQUEST['avatarid'] == -1)
	{
		// Custom avatar. Are they uploading?
		if((isset($_FILES['avatarfile'])) && ($_FILES['avatarfile']['error'] != UPLOAD_ERR_NO_FILE))
		{
			// Yes, what is the situation?
			switch($_FILES['avatarfile']['error'])
			{
				// Upload was successful.
				case UPLOAD_ERR_OK:
				{
					// Get some information on the new file.
					list($iWidth, $iHeight, $iAvatarType) = @getimagesize($_FILES['avatarfile']['tmp_name']);

					// Check the filesize.
					if($_FILES['avatarfile']['size'] > $CFG['avatars']['maxsize'])
					{
						$aError[] = "The avatar you uploaded is too large. The maximum allowable filesize is {$CFG['avatars']['maxsize']} bytes.";
					}

					// Check the file format.
					if(($iAvatarType < 1) || ($iAvatarType > 3))
					{
						$aError[] = 'The file you specified is not a valid image format. Valid formats are: JPG/JPEG, GIF, and PNG.';
					}

					// Check the dimensions.
					if(($iWidth > $CFG['avatars']['maxdims']) || ($iHeight > $CFG['avatars']['maxdims']))
					{
						$aError[] = "The avatar you specified is too large. The maximum allowable dimensions are {$CFG['avatars']['maxdims']} by {$CFG['avatars']['maxdims']} pixels.";
					}

					// If there are no errors, grab the contents of the file.
					if(!is_array($aError))
					{
						$strAvatarName = $dbConn->sanitize($_FILES['avatarfile']['name']);
						if($fileUploaded = fopen($_FILES['avatarfile']['tmp_name'], 'rb'))
						{
							$blobAvatar = $dbConn->sanitize(fread($fileUploaded, 65536), TRUE);
						}
						else
						{
							$aError[] = 'There was a problem while reading the avatar. If this problem persists, please contact the Webmaster.';
						}
					}

					break;
				}

				// File is too big.
				case UPLOAD_ERR_INI_SIZE:
				case UPLOAD_ERR_FORM_SIZE:
				{
					$aError[] = "The avatar you uploaded is too large. The maximum allowable filesize is {$CFG['avatars']['maxsize']} bytes.";
					break;
				}

				// File was partially uploaded.
				case UPLOAD_ERR_PARTIAL:
				{
					$aError[] = 'The avatar was only partially uploaded.';
					break;
				}

				// WTF happened?
				default:
				{
					$aError[] = 'There was an error while uploading the avatar.';
					break;
				}
			}

			// Store the avatar.
			if($fileUploaded)
			{
				// Insert the first chunk of the file.
				$dbConn->query("DELETE FROM avatar WHERE id={$_SESSION['userid']}");
				$dbConn->query("INSERT INTO avatar(id, filename, datetime, datatype, datum) VALUES({$_SESSION['userid']}, '{$strAvatarName}', {$CFG['globaltime']}, {$iAvatarType}, '{$blobAvatar}')");

				// Insert the rest of the file, if any, into the database.
				while(!feof($fileUploaded))
				{
					$blobAvatar = $dbConn->sanitize(fread($fileUploaded, 65536), TRUE);
					$dbConn->squery(CONCAT_AVATAR, $blobAvatar, $_SESSION['userid']);
				}

				// Close the temporary file.
				fclose($fileUploaded);
			}
		}
		else if(($_REQUEST['avatarurl'] != '') && ($_REQUEST['avatarurl'] != 'http://'))
		{
			// Read the file.
			if(!($strAvatar = file_get_contents($_REQUEST['avatarurl'])))
			{
				return(array('There was a problem while reading the avatar. If this problem persists, please contact the Webmaster.'));
			}

			// Check the filesize.
			if(strlen($strAvatar) > $CFG['avatars']['maxsize'])
			{
				return(array("The avatar you uploaded is too large. The maximum allowable filesize is {$CFG['avatars']['maxsize']} bytes."));
			}

			// Save the data to a temporary file.
			$strTemp = tempnam('', '');
			$fileTemp = fopen($strTemp, 'w+b');
			fwrite($fileTemp, $strAvatar);

			// Get some information on the file.
			list($iWidth, $iHeight, $iAvatarType) = getimagesize($strTemp);

			// Make sure it's a valid image format.
			if(($iAvatarType < 1) || ($iAvatarType > 3))
			{
				$aError[] = 'The file you specified is not a valid image format. Valid formats are: JPG/JPEG, GIF, and PNG.';
			}

			// Make sure it's not too big in dimensions.
			if(($iWidth > $CFG['avatars']['maxdims']) || ($iHeight > $CFG['avatars']['maxdims']))
			{
				$aError[] = "The avatar you specified is too large. The maximum allowable dimensions are {$CFG['avatars']['maxdims']} by {$CFG['avatars']['maxdims']} pixels.";
			}

			// Are there any errors?
			if(is_array($aError))
			{
				// Close and delete the temporary file.
				if($fileTemp)
				{
					fclose($fileTemp);
					unlink($strTemp);
				}

				// Return the error(s).
				return $aError;
			}

			// Set the filename.
			$strAvatarName = $dbConn->sanitize(basename($_REQUEST['avatarurl']));

			// Store the avatar.
			if($fileTemp)
			{
				rewind($fileTemp);

				// Insert the first chunk of the file.
				$blobAvatar = $dbConn->sanitize(fread($fileTemp, 2048), TRUE);
				$dbConn->query("DELETE FROM avatar WHERE id={$_SESSION['userid']}");
				$dbConn->query("INSERT INTO avatar(id, filename, datetime, datatype, datum) VALUES({$_SESSION['userid']}, '{$strAvatarName}', {$CFG['globaltime']}, {$iAvatarType}, '{$blobAvatar}')");

				// Insert the rest of the file, if any, into the database.
				while(!feof($fileTemp))
				{
					$blobAvatar = $dbConn->sanitize(fread($fileTemp, 2048), TRUE);
					$dbConn->squery(CONCAT_AVATAR, $blobAvatar, $_SESSION['userid']);
				}

				// Close and delete the temporary file.
				fclose($fileTemp);
				unlink($strTemp);
			}
		}
	}
	else
	{
		// One of the public avatars.
		$iAvatarID = (int)$_REQUEST['avatarid'];

		// Make sure it's a valid avatar.
		if($aAvatars[$iAvatarID] != NULL)
		{
			// What is the avatar's type?
			list(,,$iAvatarType) = getimagesize("{$CFG['paths']['avatars']}{$aAvatars[$iAvatarID]['filename']}");

			// Update the avatar record for this user.
			$dbConn->query("DELETE FROM avatar WHERE id={$_SESSION['userid']}");
			$dbConn->query("INSERT INTO avatar(id, filename, datetime, datatype, datum) VALUES({$_SESSION['userid']}, NULL, {$CFG['globaltime']}, {$iAvatarType}, '{$iAvatarID}')");
		}
	}

	// Do they have any errors?
	if(is_array($aError))
	{
		return $aError;
	}

	// Render a success page.
	$strUsername = htmlsanitize($_SESSION['username']);
	Msg("<b>Thank you for updating your profile, {$strUsername}.</b><br /><br /><span class=\"smaller\">You should be redirected to the User Control Panel momentarily. Click <a href=\"usercp.php\">here</a><br />if you do not want to wait any longer or if you are not redirected.</span>", 'usercp.php');
}

// *************************************************************************** \\

function EditPassword()
{
	global $CFG;

	// Are they submitting information?
	if(isset($_REQUEST['submit']))
	{
		// Yup, so try and validate it; submit it to the database if everything's okay.
		$aError = ValidatePassword($_REQUEST['presentpw'], $_REQUEST['newpwa'], $_REQUEST['newpwb']);
	}

	// Template
	require("./skins/{$CFG['skin']}/usercp/password.tpl.php");

	// Send the page.
	exit;
}

function ValidatePassword($strOldPassword, $strNewPasswordA, $strNewPasswordB)
{
	global $CFG, $dbConn;

	// Get the user's actual present password.
	$dbConn->query("SELECT passphrase FROM citizen WHERE id={$_SESSION['userid']}");
	list($strPassword) = $dbConn->getresult();

	// Compare their password to the one they specified.
	if(md5($strOldPassword) != $strPassword)
	{
		// Passwords don't match.
		$aError[] = 'Incorrect present password specified. Click <a href="member.php?action=forgotdetails">here</a> if you\'ve forgotten it.';
	}

	// New Password
	if($strNewPasswordA != $strNewPasswordB)
	{
		// The two passwords they specified are not the same.
		$aError[] = 'The New Password and Confirm New Password pair you specified does not match.';
	}
	else if($strNewPasswordA == '')
	{
		// They didn't specify a password.
		$aError[] = 'You must specify a new password.';
	}
	else if(strlen($strNewPasswordA) > $CFG['maxlen']['password'])
	{
		// The password they specified is too long.
		$aError[] = "The new password you specified is longer than {$CFG['maxlen']['password']} characters.";
	}
	$strPassword = md5($strNewPasswordA);

	// Do they have any error?
	if(is_array($aError))
	{
		return $aError;
	}

	// Save the new information to the member's record.
	$dbConn->query("UPDATE citizen SET passphrase='{$strPassword}' WHERE id={$_SESSION['userid']}");

	// Show them the success page; header.
	$strUsername = htmlsanitize($_SESSION['username']);
	Msg("<b>Your password has been successfully updated.</b><br /><br /><span class=\"smaller\">You should be redirected to the User Control Panel momentarily. Click <a href=\"usercp.php\">here</a><br />if you do not want to wait any longer or if you are not redirected.</span>", 'usercp.php');
}

// *************************************************************************** \\

function EditBuddyList()
{
	global $CFG, $dbConn;

	// Are they coming for the first time, submitting via form, adding via link, or removing via link?
	if(isset($_REQUEST['submit']))
	{
		// Submitting via form, so remove any empty elements from the array and save it.
		$aUsernames = (array)$_REQUEST['buddylist'];
		foreach($aUsernames as $k => $v)
		{
			if(empty($v)) unset($aUsernames[$k]);
		}

		// Validate the usernames, and submit it to the database if everything's okay.
		$aError = ValidateBuddyList($aUsernames);
	}
	else if($_REQUEST['action'] == 'add')
	{
		// Adding via link, so store the ID of the user we're adding to our Buddy list.
		$iUserID = (int)$_REQUEST['userid'];

		// Get our new buddy's username, verifying we have a valid user ID,
		// and get our current Buddy list for use later on.
		$dbConn->query("SELECT their.username, our.buddylist FROM citizen AS their LEFT JOIN citizen AS our ON 1 WHERE their.id={$iUserID} AND our.id={$_SESSION['userid']}");
		list($strUsername, $strBuddyList) = $dbConn->getresult();

		// Is the user ID we have invalid?
		if(!$strUsername)
		{
			// Yes. Give the user an error message.
			ListError();
		}

		// Store our current Buddy list (if it exists) into an array for easy manipulation.
		if($strBuddyList)
		{
			$aBuddyList = explode(',', $strBuddyList);
		}

		// Add our new buddy's ID to the Buddy list array.
		$aBuddyList[] = $iUserID;

		// Remove any duplicates.
		$aBuddyList = array_unique($aBuddyList);

		// Put our updated Buddy list array into a plaintext string for use in our SQL query.
		$strBuddyList = implode(',', $aBuddyList);

		// Save the new Buddy list to the member's record.
		$dbConn->query("UPDATE citizen SET buddylist='{$strBuddyList}' WHERE id={$_SESSION['userid']}");

		// Remove the new buddy from our Ignore list.
		RemoveIgnorant($iUserID);

		// Show them the success page.
		ListSuccess('Buddy');
	}
	else if($_REQUEST['action'] == 'remove')
	{
		// Removing via link, so store the ID of the user we're removing from our Buddy list.
		$iUserID = (int)$_REQUEST['userid'];

		// Remove the buddy.
		if(RemoveBuddy($iUserID))
		{
			// Show them the success page.
			ListSuccess('Buddy');
		}
		else
		{
			// Give the user an error message.
			ListError();
		}
	}
	else
	{
		// Coming for the first time, so grab the Buddy list from profile.
		$dbConn->query("SELECT buddylist FROM citizen WHERE id={$_SESSION['userid']}");
		list($strBuddyList) = $dbConn->getresult();

		// Get the usernames of the users (if any) in the Buddy list.
		if($strBuddyList)
		{
			$dbConn->query("SELECT id, username FROM citizen WHERE id IN ({$strBuddyList}) ORDER BY username ASC");
			while(list($iUserID, $strUsername) = $dbConn->getresult())
			{
				// Store the username in the usernames array.
				$aUsernames[$iUserID] = $strUsername;
			}
		}
	}

	// Template
	require("./skins/{$CFG['skin']}/usercp/buddylist.tpl.php");

	// Send the page.
	exit;
}

function ValidateBuddyList($aBuddyList)
{
	global $CFG, $dbConn;

	// Put the array of buddy usernames into a plaintext string for use in our SQL query.
	$strBuddyList = implode("', '", array_map(array($dbConn, 'sanitize'), $aBuddyList));

	// Swap the keys with the values of the Buddy list array.
	$aBuddyList = array_flip($aBuddyList);

	// Empty all of the values, leaving only the keys (usernames).
	foreach($aBuddyList as $k => $v)
	{
		$aBuddyList[$k] = NULL;
	}

	// Get the usernames of each of the buddies in our list.
	$dbConn->query("SELECT id, username FROM citizen WHERE username IN ('{$strBuddyList}')");
	while($aSQLResult = $dbConn->getresult(TRUE))
	{
		// Store the ID in the Buddy list, corresponding with its username.
		$aBuddyList[$aSQLResult['username']] = $aSQLResult['id'];
	}

	// Find any invalid usernames in the list.
	foreach($aBuddyList as $strUsername => $iUserID)
	{
		if($iUserID == NULL)
		{
			// Return the error.
			$strUsername = htmlsanitize($strUsername);
			return(array("'{$strUsername}' appears to be an invalid user."));
		}
	}

	// Put the Buddy list into a plaintext string for use in our SQL query.
	$strBuddyList = implode(',', $aBuddyList);

	// Save the new Buddy list to the member's record.
	$dbConn->query("UPDATE citizen SET buddylist='{$strBuddyList}' WHERE id={$_SESSION['userid']}");

	// Get our Ignore list.
	$dbConn->query("SELECT ignorelist FROM citizen WHERE id={$_SESSION['userid']}");
	list($strIgnoreList) = $dbConn->getresult();

	// Remove our buddies from our Ignore list.
	if($strIgnoreList)
	{
		$aIgnoreList = array_diff(explode(',', $strIgnoreList), $aBuddyList);
		$strIgnoreList = implode(',', $aIgnoreList);
		$dbConn->query("UPDATE citizen SET ignorelist='{$strIgnoreList}'");
		$_SESSION['ignorelist'] = $aIgnoreList;
	}

	// Show them the success page.
	ListSuccess('Buddy');
}

function RemoveBuddy($iUserID)
{
	global $dbConn;
	// Get our current Buddy list.
	$dbConn->query("SELECT buddylist FROM citizen WHERE id={$_SESSION['userid']}");
	list($strBuddyList) = $dbConn->getresult();
	if(!$strBuddyList)
	{
		return FALSE;
	}

	// Put our Buddy list into the form of an array.
	$aBuddyList = explode(',', $strBuddyList);

	// Get the key of the array value that corresponds to the user ID.
	$iKey = array_search($iUserID, $aBuddyList);

	// Is the user ID we have invalid?
	if($iKey === FALSE)
	{
		// Yes, so return FALSE.
		return FALSE;
	}

	// Remove the user ID from the array.
	unset($aBuddyList[$iKey]);

	// Put our updated Buddy list array into a plaintext string for use in our SQL query.
	$strBuddyList = implode(',', $aBuddyList);

	// Save the new Buddy list to the member's record.
	$dbConn->query("UPDATE citizen SET buddylist='{$strBuddyList}' WHERE id={$_SESSION['userid']}");

	// Return success.
	return TRUE;
}

// *************************************************************************** \\

function EditIgnoreList()
{
	global $CFG, $dbConn;

	// Are they coming for the first time, submitting via form, or submitting via link?
	if(isset($_REQUEST['submit']))
	{
		// Submitting via form, so remove any empty elements from the array and save it.
		$aUsernames = (array)$_REQUEST['ignorelist'];
		foreach($aUsernames as $k => $v)
		{
			if(empty($v)) unset($aUsernames[$k]);
		}

		// Validate the usernames, and submit it to the database if everything's okay.
		$aError = ValidateIgnoreList($aUsernames);
	}
	else if($_REQUEST['action'] == 'add')
	{
		// Submitting via link, so store the ID of the user we're adding to our Ignore list.
		$iUserID = (int)$_REQUEST['userid'];

		// Make sure they're not trying to ignore themself.
		if($iUserID == $_SESSION['userid'])
		{
		    Msg('You can\'t ignore yourself.');
		}

		// Get our new ignorant's username, verifying we have a valid user ID,
		// and get our current Ignore list for use later on.
		$dbConn->query("SELECT their.username, our.ignorelist FROM citizen AS their LEFT JOIN citizen AS our ON 1 WHERE their.id={$iUserID} AND our.id={$_SESSION['userid']}");
		list($strUsername, $strIgnoreList) = $dbConn->getresult();

		// Is the user ID we have an invalid one?
		if(!$strUsername)
		{
			// Yes. Give the user an error message.
			ListError();
		}

		// Store our current Ignore list (if it exists) into an array for easy manipulation.
		if($strIgnoreList)
		{
			$aIgnoreList = explode(',', $strIgnoreList);
		}

		// Add our new ignorant's ID to the Ignore list array.
		$aIgnoreList[] = $iUserID;

		// Remove any duplicates.
		$aIgnoreList = array_unique($aIgnoreList);

		// Put our updated Ignore list array into a plaintext string for use in our SQL query.
		$strIgnoreList = implode(',', $aIgnoreList);

		// Save the new Ignore list to the member's record.
		$dbConn->query("UPDATE citizen SET ignorelist='{$strIgnoreList}' WHERE id={$_SESSION['userid']}");

		// Update the user's live Ignore list.
		$_SESSION['ignorelist'] = $aIgnoreList;

		// Remove the new ignorant from our Buddy list.
		RemoveBuddy($iUserID);

		// Show them the success page.
		ListSuccess('Ignore');
	}
	else if($_REQUEST['action'] == 'remove')
	{
		// Removing via link, so store the ID of the user we're removing from our Ignore list.
		$iUserID = (int)$_REQUEST['userid'];

		// Remove the ignorant.
		if(RemoveIgnorant($iUserID))
		{
			// Show them the success page.
			ListSuccess('Ignore');
		}
		else
		{
			// Give the user an error message.
			ListError();
		}
	}
	else
	{
		// Coming for the first time, so grab the Ignore list from profile.
		$dbConn->query("SELECT ignorelist FROM citizen WHERE id={$_SESSION['userid']}");
		list($strIgnoreList) = $dbConn->getresult();

		// Get the usernames of the users (if any) in the Ignore list.
		if($strIgnoreList)
		{
			$dbConn->query("SELECT id, username FROM citizen WHERE id IN ({$strIgnoreList}) ORDER BY username ASC");
			while(list($iUserID, $strUsername) = $dbConn->getresult())
			{
				// Store the username in the usernames array.
				$aUsernames[$iUserID] = $strUsername;
			}
		}
	}

	// Template
	require("./skins/{$CFG['skin']}/usercp/ignorelist.tpl.php");

	// Send the page.
	exit;
}

function ValidateIgnoreList($aIgnoreList)
{
	global $CFG, $dbConn;

	// Put the array of ignorant usernames into a plaintext string for use in our SQL query.
	$strIgnoreList = implode("', '", array_map(array($dbConn, 'sanitize'), $aIgnoreList));

	// Swap the keys with the values of the Ignore list array.
	$aIgnoreList = array_flip($aIgnoreList);

	// Empty all of the values, leaving only the keys (usernames).
	foreach($aIgnoreList as $k => $v)
	{
		$aIgnoreList[$k] = NULL;
	}

	// Get the usernames of each of the ignorants in our list.
	$dbConn->query("SELECT id, username FROM citizen WHERE username IN ('{$strIgnoreList}')");
	while($aSQLResult = $dbConn->getresult(TRUE))
	{
		// Store the ID in the Ignore list, corresponding with its username.
		$aIgnoreList[$aSQLResult['username']] = $aSQLResult['id'];
	}

	// Find any invalid usernames in the list.
	foreach($aIgnoreList as $strUsername => $iUserID)
	{
		if($iUserID == NULL)
		{
			// Return the error.
			$strUsername = htmlsanitize($strUsername);
			return(array("'{$strUsername}' appears to be an invalid user."));
		}
		else if($iUserID == $_SESSION['userid'])
		{
			return(array('You can\'t ignore yourself.'));
		}
	}

	// Put the Ignore list into a plaintext string for use in our SQL query.
	$strIgnoreList = implode(',', $aIgnoreList);

	// Save the new Ignore list to the member's record.
	$dbConn->query("UPDATE citizen SET ignorelist='{$strIgnoreList}' WHERE id={$_SESSION['userid']}");

	// Update the user's live Ignore list.
	$_SESSION['ignorelist'] = (array)array_values($aIgnoreList);

	// Get our Buddy list.
	$dbConn->query("SELECT buddylist FROM citizen WHERE id={$_SESSION['userid']}");
	list($strBuddyList) = $dbConn->getresult();

	// Remove our ignorants from our Buddy list.
	if($strBuddyList)
	{
		$aBuddyList = array_diff(explode(',', $strBuddyList), $aIgnoreList);
		$strBuddyList = implode(',', $aBuddyList);
		$dbConn->query("UPDATE citizen SET buddylist='{$strBuddyList}'");
	}

	// Show them the success page.
	ListSuccess('Ignore');
}

function RemoveIgnorant($iUserID)
{
	global $dbConn;

	// Get our current Ignore list.
	$dbConn->query("SELECT ignorelist FROM citizen WHERE id={$_SESSION['userid']}");
	list($strIgnoreList) = $dbConn->getresult();
	if(!$strIgnoreList)
	{
		return FALSE;
	}

	// Put our Ignore list into the form of an array.
	$aIgnoreList = explode(',', $strIgnoreList);

	// Get the key of the array value that corresponds to the user ID.
	$iKey = array_search($iUserID, $aIgnoreList);

	// Is the user ID we have invalid?
	if($iKey === FALSE)
	{
		// Yes, so return FALSE.
		return FALSE;
	}

	// Remove the user ID from the array.
	unset($aIgnoreList[$iKey]);

	// Put our updated Ignore list array into a plaintext string for use in our SQL query.
	$strIgnoreList = implode(',', $aIgnoreList);

	// Save the new Ignore list to the member's record.
	$dbConn->query("UPDATE citizen SET ignorelist='{$strIgnoreList}' WHERE id={$_SESSION['userid']}");

	// Update the user's live Ignore list.
	$_SESSION['ignorelist'] = $aIgnoreList;

	// Return success.
	return TRUE;
}

// *************************************************************************** \\

function PrintCPMenu()
{
	global $CFG, $strSection;

	// Template
	require("./skins/{$CFG['skin']}/usercp/menu.tpl.php");
}

// *************************************************************************** \\

// Prints message the user sees when their Buddy or
// Ignore list has been successfully updated.
function ListSuccess($strList)
{
	// Render a success page.
	Msg("<b>Your {$strList} list has been successfully updated.</b><br /><br /><span class=\"smaller\">You should be redirected to the User Control Panel momentarily. Click <a href=\"usercp.php\">here</a><br />if you do not want to wait any longer or if you are not redirected.</span>", 'usercp.php');
}

// *************************************************************************** \\

// Prints message the user sees when there's been a problem
// updating their Buddy or Ignore list via link.
function ListError()
{
	global $CFG;

	// Render an error page.
	Msg("Invalid user ID specified.{$CFG['msg']['invalidlink']}");
}
?>