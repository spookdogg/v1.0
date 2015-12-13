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

	// Get the global time and start timer.
	list($msec, $sec) = explode(' ', microtime());
	$tStartTime = (float)$msec + $sec;

	// Handle unneccessary time problems introduced in PHP 5.1.0.
	if(function_exists('date_default_timezone_set'))
	{
		date_default_timezone_set(date_default_timezone_get());
	}

	// Global functions
	require('./includes/functions.inc.php');

	// Set up our custom error handler.
	$aGlobalErrors = array();
	error_reporting(E_ALL ^ E_NOTICE);
	set_error_handler('HandleError');

	// Disable magic quotes if need be.
	if(get_magic_quotes_gpc())
	{
		$_REQUEST = dmq($_REQUEST);
	}

	// Connect to the database.
	require('./includes/db.inc.php');

	// Load the configuration settings.
	LoadConfigs();

	// Set the global time.
	$CFG['globaltime'] = $sec;

	// Set upload settings.
	ini_set('upload_max_filesize', $CFG['uploads']['maxsize']);

	// What is this page?
	$CFG['currentpage'] = substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '/')+1, strlen($_SERVER['PHP_SELF']));

	// Handle data output.
	if(($CFG['general']['gzip']['enabled']) && ($CFG['currentpage'] != 'avatar.php') && ($CFG['currentpage'] != 'attachment.php') && ($CFG['currentpage'] != 'regimage.php'))
	{
		// Compress the page if it's enabled.
		ini_set('zlib.output_compression_level', $CFG['general']['gzip']['level']);
		ob_start('ob_gzhandler');
	}
	else if($CFG['bufferoutput'])
	{
		// Buffer output if it's enabled.
		ob_start();
	}

	// Initialize PHP session.
	if(isset($_REQUEST['s']) && ($_REQUEST['s'] == ''))
	{
		unset($_REQUEST['s']);
	}
	ini_set('arg_separator.output', '&amp;');
	ini_set('session.cookie_path', $CFG['paths']['cookies']);
	session_name('s');
	session_start();

	// Don't screw with our URLs, you crazy PHP you.
	output_reset_rewrite_vars();

	// Add the session ID to all local URLs (if it hasn't been saved to a cookie).
	if(SID)
	{
		output_add_rewrite_var('s', stripslashes(session_id()));
	}

	// Were we redirected and do we have some request information?
	if($_SESSION['redirected'] && isset($_SESSION['request']) && ($CFG['currentpage'] != 'style.php'))
	{
		// Yes, restore the redirect information.
		$_REQUEST = $_SESSION['request'];

		// Reset the request information.
		unset($_SESSION['request']);
	}

	// Reset the redirected flag.
	if($CFG['currentpage'] != 'style.php')
	{
		$_SESSION['redirected'] = FALSE;
	}

	// Set the user's IP address.
	$_SESSION['userip'] = $CFG['iplogging'] ? ip2long($_SERVER['REMOTE_ADDR']) : 'NULL';

	// We have no need for these things (mostly cookies) to be in the request array.
	unset($_REQUEST['s']);
	unset($_REQUEST['activeuserid']);
	unset($_REQUEST['activepassword']);
	unset($_REQUEST['viewedthreads']);

	// Initialize member settings, if user is logged in.
	$strLastLocation = $dbConn->sanitize($CFG['currentpage']);
	$strLastRequest = $dbConn->sanitize(serialize($_REQUEST));
	if($_SESSION['loggedin'])
	{
		// Store the user's time information.
		$CFG['time']['display_offset'] = $_SESSION['timeoffset'];
		$CFG['time']['dst'] = $_SESSION['dst'];
		$CFG['time']['dst_offset'] = $_SESSION['dstoffset'];

		// Load the permissions for this user.
		$_SESSION['permissions'] = $aGroup[$_SESSION['usergroup']];

		// Also update the user's lastactive, lastlocation, lastrequest, and ipaddress values in their profile.
		if(($CFG['currentpage'] != 'avatar.php') && ($CFG['currentpage'] != 'regimage.php') && ($CFG['currentpage'] != 'style.php'))
		{
			$dbConn->query("UPDATE citizen SET lastactive={$CFG['globaltime']}, loggedin=1, lastlocation='{$strLastLocation}', lastrequest='{$strLastRequest}', ipaddress={$_SESSION['userip']} WHERE id={$_SESSION['userid']}");
		}
	}
	else
	{
		// Does the user have our cookie?
		if(isset($_COOKIE['activeuserid']) && isset($_COOKIE['activepassword']))
		{
			// Yes, save the data.
			$iUserID = (int)$_COOKIE['activeuserid'];
			$strPassword = $_COOKIE['activepassword'];

			// Get the member information of the member whose user ID was specified.
			$dbConn->query("SELECT * FROM citizen WHERE id={$iUserID} AND reghash IS NULL");

			// Was the username of a real member?
			if($aSQLResult = $dbConn->getresult(TRUE))
			{
				// Yes, so do the passwords match?
				if($aSQLResult['passphrase'] == $strPassword)
				{
					// Store the member information into the session.
					LoadUser($aSQLResult);

					// Load the permissions for this user.
					$_SESSION['permissions'] = $aGroup[$_SESSION['usergroup']];

					// Also update the user's lastactive, lastlocation, lastrequest, and ipaddress values in their profile.
					if(($CFG['currentpage'] != 'avatar.php') && ($CFG['currentpage'] != 'regimage.php') && ($CFG['currentpage'] != 'style.php'))
					{
						$dbConn->query("UPDATE citizen SET lastactive={$CFG['globaltime']}, loggedin=1, lastlocation='{$strLastLocation}', lastrequest='{$strLastRequest}', ipaddress={$_SESSION['userip']} WHERE id={$_SESSION['userid']}");
					}
				}
			}
		}

		// Are they still not logged in?
		if(!$_SESSION['loggedin'])
		{
			// No. Load the permissions for this guest.
			$_SESSION['permissions'] = $aGroup[0];

			// They're a guest, but have their session settings been set?
			if(!$_SESSION['guest'])
			{
				// No, so set them.
				$_SESSION['userid'] = 0;
				$_SESSION['guest'] = TRUE;
				$_SESSION['loggedin'] = FALSE;
				$_SESSION['showsigs'] = TRUE;
				$_SESSION['showavatars'] = TRUE;
				$_SESSION['threadview'] = $CFG['default']['threadview'];
				$_SESSION['postsperpage'] = $CFG['default']['postsperpage'];
				$_SESSION['threadsperpage'] = $CFG['default']['threadsperpage'];
				$_SESSION['weekstart'] = $CFG['default']['weekstart'];
				$_SESSION['lastactive'] = $CFG['globaltime'];
				$_SESSION['usergroup'] = 0;
				$_SESSION['ignorelist'] = array();
			}

			// User isn't logged in, so we'll add them to the session table.
			if(($CFG['currentpage'] != 'avatar.php') && ($CFG['currentpage'] != 'regimage.php') && ($CFG['currentpage'] != 'style.php'))
			{
				$dbConn->query("DELETE FROM guest WHERE id='".session_id()."'");
				$dbConn->query("INSERT INTO guest(id, lastactive, lastlocation, lastrequest, ipaddress) VALUES('".session_id()."', {$CFG['globaltime']}, '{$strLastLocation}', '{$strLastRequest}', {$_SESSION['userip']})");
			}

			// Delete old (20+ minutes) session entries about every 100 times one is added.
			if(mt_rand(1, 100) == 50)
			{
				$tOld = $CFG['globaltime'] - 1200;
				$dbConn->query("DELETE FROM guest WHERE lastactive <= {$tOld}");
			}
		}
	}

	// Get any unread private messages since last visit.
	if($_SESSION['loggedin'] && $_SESSION['enablepms'] && $_SESSION['pmnotifyb'] && mt_rand(0, 1))
	{
		$strAlertedPMs = (is_array($_SESSION['alertedpms']) && count($_SESSION['alertedpms'])) ? ' AND pm.id NOT IN ('.implode(', ', $_SESSION['alertedpms']).')' : '';
		if(count($_SESSION['ignorelist']))
		{
			$strRejects = " AND pm.author NOT IN (".implode(', ', $_SESSION['ignorelist']).")";
		}
		$dbConn->query("SELECT pm.id FROM pm LEFT JOIN citizen ON (citizen.id = pm.author) WHERE pm.ownerid={$_SESSION['userid']} AND pm.recipient={$_SESSION['userid']} AND pm.beenread=0 AND pm.datetime > {$_SESSION['lastactive']}{$strAlertedPMs}{$strRejects} ORDER BY pm.datetime DESC LIMIT 1");
		if(list($iMessageID) = $dbConn->getresult())
		{
			// Mark the new PM flag.
			if(($CFG['currentpage'] != 'private.php') && ($CFG['currentpage'] != 'usercp.php'))
			{
				$CFG['newpm'] = TRUE;
			}

			// So we don't keep notifying them of this message.
			$_SESSION['alertedpms'][] = $iMessageID;
		}
	}

	// Load the list of threads we've viewed during this session.
	$aViewedThreads = (array)unserialize(base64_decode($_COOKIE['viewedthreads']));

// *************************************************************************** \\

// Loads the forum configuration.
function LoadConfigs()
{
	global $CFG, $dbConn, $aGroup, $aCensored, $aAvatars, $aPostIcons, $aSmilies;

	// Retrieve the configuration data from the database.
	$dbConn->query("SELECT name, content FROM configuration");
	while(list($strKey, $strValue) = $dbConn->getresult())
	{
		// Load the configuration in its appropriate array.
		switch($strKey)
		{
			case 'settings':
			{
				$CFG = (array)unserialize($strValue);
				break;
			}

			case 'usergroups':
			{
				$aGroup = (array)unserialize($strValue);
				break;
			}

			case 'censored':
			{
				$aCensored = (array)unserialize($strValue);
				break;
			}

			case 'avatars':
			{
				$aAvatars = (array)unserialize($strValue);
				break;
			}

			case 'posticons':
			{
				$aPostIcons = (array)unserialize($strValue);
				break;
			}

			case 'smilies':
			{
				$aSmilies = (array)unserialize($strValue);
				break;
			}

			case 'skins':
			{
				$aSkins = (array)unserialize($strValue);
				break;
			}
		}
	}

	// Set the skin.
	$CFG['skin'] = $aSkins[$CFG['skin']]['folder'];
	unset($aSkins);
}

// *************************************************************************** \\

// Recursively strips slashes from the given string or array.
function dmq($given)
{
	return is_array($given) ? array_map('dmq', $given) : stripslashes($given);
}
?>
