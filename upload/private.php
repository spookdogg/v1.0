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

	// Constants
	define('DATETIME',   0);
	define('AUTHOR',     1);
	define('RECIPIENT',  2);
	define('SUBJECT',    3);
	define('ICON',       4);
	define('BEENREAD',   5);
	define('READTIME',   6);
	define('TRACKING',   7);
	define('REPLIED',    8);
	define('STATUS',     9);
	define('BODY',       10);
	define('PARENT',     11);
	define('IPADDRESS',  12);
	define('DSMILIES',   13);
	define('URL',  0);
	define('ALT',  1);

	// Is the user logged in?
	if(!$_SESSION['loggedin'])
	{
		// No, so they can't access private messages.
		Unauthorized();
	}

	// Do we have private messaging enabled?
	else if(!$_SESSION['enablepms'])
	{
		Msg('You have private messaging disabled. You will not be able to view or send private messages until you enable them by editing your account options.', '', 'justify');
	}

	// What section are they dealing with?
	switch(strtolower($_REQUEST['action']))
	{
		// View folder
		case 'viewfolder':
		{
			// Which folder?
			if($_REQUEST['id'] == 0)
			{
				// Inbox
				ViewInbox();
			}
			else if($_REQUEST['id'] == 1)
			{
				// Sent Items
				ViewSentItems();
			}
			else
			{
				// Custom folder
				ViewFolder();
			}
		}

		// View message
		case 'viewmessage':
		{
			ViewMessage();
		}

		// New message
		case 'newmessage':
		{
			NewMessage();
		}

		// Reply to message
		case 'reply':
		{
			NewMessage();
		}

		// Foward message
		case 'forward':
		{
			// They're sending a list; we only forward one message, not multiple ones.
			if(is_array($_REQUEST['id']))
			{
				// Only use the first one.
				$_REQUEST['id'] = $_REQUEST['id'][0];
			}

			NewMessage();
		}

		// Message tracking
		case 'track':
		{
			Tracking();
		}

		// Manage folders
		case 'editfolders':
		{
			Folders();
		}

		// Delete message(s)
		case 'delete':
		{
			Delete();
		}

		// Move message(s)
		case 'move':
		{
			Move();
		}

		// Inbox
		default:
		{
			ViewInbox();
		}
	}

// *************************************************************************** \\

function ViewInbox()
{
	global $CFG, $dbConn, $aPostIcons;
	$aMessages = array();
	$aUsers = array();

	// Get all messages in the Inbox.
	$dbConn->query("SELECT id, datetime, author, subject, icon, beenread, readtime, tracking, replied FROM pm WHERE ownerid={$_SESSION['userid']} AND recipient={$_SESSION['userid']} AND parent=0 ORDER BY datetime DESC");
	while($aSQLResult = $dbConn->getresult(TRUE))
	{
		// Store the message.
		$iMessageID = $aSQLResult['id'];
		$aMessages[$iMessageID][DATETIME] = $aSQLResult['datetime'];
		$aMessages[$iMessageID][AUTHOR] = $aSQLResult['author'];
		$aMessages[$iMessageID][SUBJECT] = $aSQLResult['subject'];
		$aMessages[$iMessageID][ICON][URL] = "{$CFG['paths']['posticons']}{$aPostIcons[$aSQLResult['icon']]['filename']}";
		$aMessages[$iMessageID][ICON][ALT] = $aPostIcons[$aSQLResult['icon']]['title'];
		$aMessages[$iMessageID][BEENREAD] = (bool)$aSQLResult['beenread'];
		$aMessages[$iMessageID][READTIME] = $aSQLResult['readtime'];
		$aMessages[$iMessageID][TRACKING] = $aSQLResult['tracking'];
		$aMessages[$iMessageID][REPLIED] = (bool)$aSQLResult['replied'];
		$aMessages[$iMessageID][IGNORANT] = in_array($aSQLResult['author'], $_SESSION['ignorelist']);

		// Add the author to our list of users to get names for.
		$aUsers[] = $aSQLResult['author'];
	}

	// Get the usernames.
	$aUsernames = GetUsernames($aUsers);
	unset($aUsers);

	// Get our list of custom folders.
	$dbConn->query("SELECT pmfolders FROM citizen WHERE id={$_SESSION['userid']}");
	list($strFolders) = $dbConn->getresult();
	$aFolders = unserialize($strFolders);

	// Template
	require("./skins/{$CFG['skin']}/pm/inbox.tpl.php");

	// Send the page.
	exit;
}

// *************************************************************************** \\

function ViewSentItems()
{
	global $CFG, $dbConn, $aPostIcons;
	$aMessages = array();
	$aUsers = array();

	// Get all messages in the Sent Items folder.
	$dbConn->query("SELECT id, datetime, recipient, subject, icon FROM pm WHERE ownerid={$_SESSION['userid']} AND author={$_SESSION['userid']} AND parent=1 ORDER BY datetime DESC");
	while($aSQLResult = $dbConn->getresult(TRUE))
	{
		// Store the message.
		$iMessageID = $aSQLResult['id'];
		$aMessages[$iMessageID][DATETIME] = $aSQLResult['datetime'];
		$aMessages[$iMessageID][RECIPIENT] = $aSQLResult['recipient'];
		$aMessages[$iMessageID][SUBJECT] = $aSQLResult['subject'];
		$aMessages[$iMessageID][ICON][URL] = "{$CFG['paths']['posticons']}{$aPostIcons[$aSQLResult['icon']]['filename']}";
		$aMessages[$iMessageID][ICON][ALT] = $aPostIcons[$aSQLResult['icon']]['title'];

		// Add the recipient to our list of users to get names for.
		$aUsers[] = $aSQLResult['recipient'];
	}

	// Get the usernames.
	$aUsernames = GetUsernames($aUsers);
	unset($aUsers);

	// Get our list of custom folders.
	$dbConn->query("SELECT pmfolders FROM citizen WHERE id={$_SESSION['userid']}");
	list($strFolders) = $dbConn->getresult();
	$aFolders = unserialize($strFolders);

	// Template
	require("./skins/{$CFG['skin']}/pm/sentitems.tpl.php");

	// Send the page.
	exit;
}

// *************************************************************************** \\

function ViewFolder()
{
	global $CFG, $dbConn, $aPostIcons;
	$aMessages = array();
	$aUsers = array();

	// What folder do they want?
	$iFolderID = (int)$_REQUEST['id'];

	// Get all messages in the folder.
	$dbConn->query("SELECT id, datetime, author, recipient, subject, icon, beenread, readtime, tracking, replied FROM pm WHERE ownerid={$_SESSION['userid']} AND parent={$iFolderID} ORDER BY datetime DESC");
	while($aSQLResult = $dbConn->getresult(TRUE))
	{
		// Store the message in the master table.
		$iMessageID = $aSQLResult['id'];
		$aMessages[$iMessageID][DATETIME] = $aSQLResult['datetime'];
		$aMessages[$iMessageID][AUTHOR] = $aSQLResult['author'];
		$aMessages[$iMessageID][RECIPIENT] = $aSQLResult['recipient'];
		$aMessages[$iMessageID][SUBJECT] = $aSQLResult['subject'];
		$aMessages[$iMessageID][ICON][URL] = "{$CFG['paths']['posticons']}{$aPostIcons[$aSQLResult['icon']]['filename']}";
		$aMessages[$iMessageID][ICON][ALT] = $aPostIcons[$aSQLResult['icon']]['title'];
		$aMessages[$iMessageID][BEENREAD] = (bool)$aSQLResult['beenread'];
		$aMessages[$iMessageID][READTIME] = $aSQLResult['readtime'];
		$aMessages[$iMessageID][TRACKING] = $aSQLResult['tracking'];
		$aMessages[$iMessageID][REPLIED] = (bool)$aSQLResult['replied'];
		$aMessages[$iMessageID][IGNORANT] = in_array($aSQLResult['author'], $_SESSION['ignorelist']);

		// Add the author and recipient to our list of users to get names for.
		$aUsers[] = $aSQLResult['author'];
		$aUsers[] = $aSQLResult['recipient'];
	}

	// Get the usernames.
	$aUsernames = GetUsernames($aUsers);
	unset($aUsers);

	// Get our list of custom folders.
	$dbConn->query("SELECT pmfolders FROM citizen WHERE id={$_SESSION['userid']}");
	list($strFolders) = $dbConn->getresult();
	$aFolders = unserialize($strFolders);

	// Template
	require("./skins/{$CFG['skin']}/pm/viewfolder.tpl.php");

	// Send the page.
	exit;
}

// *************************************************************************** \\

function ViewMessage()
{
	global $CFG, $dbConn, $aGroup, $aPostIcons;
	$aFolders = array('Inbox', 'Sent Items');

	// What message do they want?
	$iMessageID = (int)$_REQUEST['id'];

	// Get the message's information.
	$dbConn->query("SELECT datetime, author, recipient, subject, body, parent, ipaddress, icon, dsmilies, beenread FROM pm WHERE id={$iMessageID} AND ownerid={$_SESSION['userid']}");
	if(!($aSQLResult = $dbConn->getresult()))
	{
		Msg("Invalid message specified.{$CFG['msg']['invalidlink']}");
	}
	else
	{
		$aMessage[DATETIME] = $aSQLResult[0];
		$aMessage[AUTHOR] = $aSQLResult[1];
		$aMessage[RECIPIENT] = $aSQLResult[2];
		$aMessage[SUBJECT] = $aSQLResult[3];
		$aMessage[BODY] = $aSQLResult[4];
		$aMessage[PARENT] = $aSQLResult[5];
		$aMessage[IPADDRESS] = $aSQLResult[6];
		$aMessage[ICON] = $aSQLResult[7];
		$aMessage[DSMILIES] = $aSQLResult[8];
		$aMessage[BEENREAD] = $aSQLResult[9];
	}

	// Get the author's information.
	$dbConn->query("SELECT * FROM citizen WHERE id={$aMessage[AUTHOR]}");
	$aAuthor = $dbConn->getresult(TRUE);

	// Get the folders.
	$dbConn->query("SELECT pmfolders FROM citizen WHERE id={$_SESSION['userid']}");
	list($strFolders) = $dbConn->getresult();
	$aFolders = $aFolders + unserialize($strFolders);

	// Have we not read the message until now?
	if((!$aMessage[BEENREAD]) && ($aMessage[RECIPIENT] == $_SESSION['userid']))
	{
		// Nope, so mark it as read.
		$dbConn->query("UPDATE pm SET beenread=1, readtime={$CFG['globaltime']} WHERE id={$iMessageID}");

		// Do we not want to provide a read receipt?
		if($_REQUEST['noreceipt'])
		{
			$dbConn->query("UPDATE pm SET tracking=0 WHERE id={$iMessageID}");
		}
	}

	// Template
	require("./skins/{$CFG['skin']}/pm/viewmessage.tpl.php");

	// Send the page.
	exit;
}

// *************************************************************************** \\

function NewMessage()
{
	global $CFG, $dbConn, $aPostIcons, $aSmilies;

	// Default values.
	$bParseURLs = FALSE;
	$bParseEMails = TRUE;
	$bDisableSmilies = FALSE;
	$bSaveCopy = TRUE;
	$bTrack = TRUE;

	// Are they submitting?
	if($_REQUEST['submit'] == 'Send Message')
	{
		$aError = SendMessage();
	}

	// Are they forwarding?
	if(strtolower($_REQUEST['action']) == 'forward')
	{
		// Yes, get the message they want to forward.
		$iMessageID = (int)$_REQUEST['id'];
		$dbConn->query("SELECT pm.datetime, pm.author, pm.subject, pm.body, citizen.username FROM pm JOIN citizen ON (citizen.id = pm.author) WHERE pm.id={$iMessageID} AND pm.ownerid={$_SESSION['userid']}");
		$aSQLResult = $dbConn->getresult();

		// Change the subject and add a copy of the message being forwarded.
		$strSubject = htmlsanitize("Fw: {$aSQLResult[2]}");
		$strMessage = "\n\n[quote][i]{$aSQLResult[4]} wrote on [dt={$aSQLResult[0]}]:[/i]\n[b]{$aSQLResult[3]}[/b][/quote]";
	}
	// Are they replying?
	if($_REQUEST['action'] == 'reply')
	{
		// Yes, get the message they want to reply to.
		$iMessageID = (int)$_REQUEST['id'];
		$dbConn->query("SELECT pm.datetime, pm.author, pm.subject, pm.body, citizen.username FROM pm JOIN citizen ON (citizen.id = pm.author) WHERE pm.id={$iMessageID} AND pm.ownerid={$_SESSION['userid']}");
		$aSQLResult = $dbConn->getresult();

		// Set the recipient & subject, and add a copy of the message being forwarded.
		$strRecipient = $aSQLResult[4];
		$strSubject = (strpos($aSQLResult[2], 'Re: ') !== 0) ? "Re: {$aSQLResult[2]}" : $aSQLResult[2];
		$strMessage = "\n\n[quote][i]{$aSQLResult[4]} wrote on [dt={$aSQLResult[0]}]:[/i]\n[b]{$aSQLResult[3]}[/b][/quote]";
	}

	// Are they specifying a user ID?
	if(isset($_REQUEST['userid']))
	{
		// Yes, so get the username of the user whose ID was specified.
		$iUserID = (int)$_REQUEST['userid'];
		$dbConn->query("SELECT username FROM citizen WHERE id={$iUserID}");
		list($strRecipient) = $dbConn->getresult();
	}

	// Template
	require("./skins/{$CFG['skin']}/pm/newmessage.tpl.php");

	// Send the page.
	exit;
}

// *************************************************************************** \\

// The user hit the Send Message button, so that's what we'll try to do.
function SendMessage()
{
	global $CFG, $dbConn;

	// Get the values from the user.
	$strRecipient = $dbConn->sanitize($_REQUEST['recipient']);
	$strSubject = $_REQUEST['subject'];
	$iPostIcon = (int)$_REQUEST['icon'];
	$strMessage = $_REQUEST['message'];
	$bDisableSmilies = (int)(bool)$_REQUEST['dsmilies'];
	$bTracking = (int)(bool)$_REQUEST['track'];

	// Recipient
	$dbConn->query("SELECT id, enablepms, rejectpms, ignorelist FROM citizen WHERE username='{$strRecipient}'");
	list($iRecipientID, $bEnablePMs, $bRejectPMs, $aIgnoreList) = $dbConn->getresult();
	$aIgnoreList = (array)explode(',', $aIgnoreList);

	// Does the user exist?
	if($iRecipientID === NULL)
	{
		$aError[] = 'The user you specified does not exist.';
	}

	// Are they trying to send a message to themself?
	else if($iRecipientID == $_SESSION['userid'])
	{
		$aError[] = 'You cannot send private messages to yourself.';
	}

	// Does the recipient hae private messaging disabled?
	else if(!$bEnablePMs)
	{
		$aError[] = htmlsanitize("The message cannot be sent because {$strRecipient} has private messages disabled.");
	}

	// Are we in the recipient's list, and do they reject PMs from those in their Ignore list?
	else if($bRejectPMs && in_array($_SESSION['userid'], $aIgnoreList))
	{
		$aError[] = 'The user you specified does not accept private messages from members on their Ignore list.';
	}

	// Subject
	if(trim($strSubject) == '')
	{
		// They either put in only whitespace or nothing at all.
		$aError[] = 'You must specify a subject.';
	}
	else if(strlen($strSubject) > $CFG['maxlen']['subject'])
	{
		// The subject they specified is too long.
		$aError[] = "The subject you specified is longer than {$CFG['maxlen']['subject']} characters.";
	}
	$strSubject = $dbConn->sanitize($strSubject);

	// Icon
	if(($iPostIcon < 0) || ($iPostIcon > 14))
	{
		// They don't know what icon they want. We'll give them none.
		$iPostIcon = 0;
	}

	// Message
	if(trim($strMessage) == '')
	{
		// They either put in only whitespace or nothing at all.
		$aError[] = 'You must specify a message.';
	}
	else if(strlen($strMessage) > $CFG['maxlen']['messagebody'])
	{
		// The message they specified is too long.
		$aError[] = "The message you specified is longer than {$CFG['maxlen']['messagebody']} characters.";
	}
	if($_REQUEST['parseemails'])
	{
		$strMessage = ParseEMails($strMessage);
	}
	$strMessage = $dbConn->sanitize($strMessage);

	// If there was an error, let's return it.
	if(is_array($aError))
	{
		return $aError;
	}

	// Add the message to the database.
	$dbConn->query("INSERT INTO pm(ownerid, datetime, author, recipient, subject, body, parent, ipaddress, icon, dsmilies, beenread, tracking) VALUES({$iRecipientID}, {$CFG['globaltime']}, {$_SESSION['userid']}, {$iRecipientID}, '{$strSubject}', '{$strMessage}', 0, {$_SESSION['userip']}, {$iPostIcon}, {$bDisableSmilies}, 0, {$bTracking})");

	// Did they want to save a copy?
	if($_REQUEST['savecopy'])
	{
		// Yes, so do so.
		$dbConn->query("INSERT INTO pm(ownerid, datetime, author, recipient, subject, body, parent, ipaddress, icon, dsmilies, beenread) VALUES({$_SESSION['userid']}, {$CFG['globaltime']}, {$_SESSION['userid']}, {$iRecipientID}, '{$strSubject}', '{$strMessage}', 1, {$_SESSION['userip']}, {$iPostIcon}, {$bDisableSmilies}, 0)");
	}

	// Was this message a reply to another one?
	if($_REQUEST['action'] == 'reply')
	{
		// Yes, mark the original message as been replied.
		$iMessageID = (int)$_REQUEST['id'];
		$dbConn->query("UPDATE pm SET replied=1 WHERE id={$iMessageID} AND ownerid={$_SESSION['userid']}");
	}

	// Render the page.
	Msg("<b>Your message has been successfully sent.</b><br /><br /><span class=\"smaller\">You should be redirected momentarily. Click <a href=\"private.php\">here</a> if you do not want to wait any longer or if you are not redirected.</span>", 'private.php');
}

// *************************************************************************** \\

function Tracking()
{
	global $CFG, $dbConn, $aPostIcons;
	$aUsers = array();
	$aFolders = array('Inbox', 'Sent Items');

	// Get all messages that we've sent that have tracking enabled.
	$dbConn->query("SELECT id, datetime, recipient, subject, icon, beenread, readtime FROM pm WHERE author={$_SESSION['userid']} AND tracking=1 ORDER BY datetime DESC");
	while($aSQLResult = $dbConn->getresult(TRUE))
	{
		// Has this message been read or unread?
		if($aSQLResult['beenread'])
		{
			// Read.
			$iMessageID = $aSQLResult['id'];
			$aRead[$iMessageID][DATETIME] = $aSQLResult['datetime'];
			$aRead[$iMessageID][RECIPIENT] = $aSQLResult['recipient'];
			$aRead[$iMessageID][SUBJECT] = $aSQLResult['subject'];
			$aRead[$iMessageID][ICON][URL] = "{$CFG['paths']['posticons']}{$aPostIcons[$aSQLResult['icon']]['filename']}";
			$aRead[$iMessageID][ICON][ALT] = $aPostIcons[$aSQLResult['icon']]['title'];
			$aRead[$iMessageID][READTIME] = $aSQLResult['readtime'];
		}
		else
		{
			// Unread.
			$iMessageID = $aSQLResult['id'];
			$aUnread[$iMessageID][DATETIME] = $aSQLResult['datetime'];
			$aUnread[$iMessageID][RECIPIENT] = $aSQLResult['recipient'];
			$aUnread[$iMessageID][SUBJECT] = $aSQLResult['subject'];
			$aUnread[$iMessageID][ICON][URL] = "{$CFG['paths']['posticons']}{$aPostIcons[$aSQLResult['icon']]['filename']}";
			$aUnread[$iMessageID][ICON][ALT] = $aPostIcons[$aSQLResult['icon']]['title'];
		}

		// Add the author to our list of users to get names for.
		$aUsers[] = $aSQLResult['recipient'];
	}

	// Get the usernames.
	$aUsernames = GetUsernames($aUsers);
	unset($aUsers);

	// Get the folders.
	$dbConn->query("SELECT pmfolders FROM citizen WHERE id={$_SESSION['userid']}");
	list($strFolders) = $dbConn->getresult();
	$aFolders = $aFolders + unserialize($strFolders);

	// Template
	require("./skins/{$CFG['skin']}/pm/tracking.tpl.php");

	// Send the page.
	exit;
}

// *************************************************************************** \\

function Folders()
{
	global $CFG, $dbConn;

	// Get a list of our custom folders.
	$dbConn->query("SELECT pmfolders FROM citizen WHERE id={$_SESSION['userid']}");
	list($strFolders) = $dbConn->getresult();
	$aFolders = unserialize($strFolders);

	// Are they submitting?
	if($_REQUEST['submit'] == 'Save Changes')
	{
		// Yes.
		EditFolders($aFolders);
	}

	// Template
	require("./skins/{$CFG['skin']}/pm/folders.tpl.php");

	// Send the page.
	exit;
}

// *************************************************************************** \\

function EditFolders($aCurrentFolders)
{
	global $CFG, $dbConn;

	// Insert placeholders for the Inbox and Sent Items folders.
	$aFolders = array(0 => 'Inbox', 1 => 'Sent Items');

	// Process the submitted current folders.
	foreach((array)$_REQUEST['curfolders'] as $k => $v)
	{
		if(array_key_exists($k, $aCurrentFolders) && (trim($v) != ''))
		{
			$aFolders[$k] = $v;
		}
	}

	// Process the submitted new folders.
	foreach((array)$_REQUEST['newfolders'] as $k => $v)
	{
		if(trim($v) != '')
		{
			$aFolders[] = $v;
		}
	}

	// Remove the dummy entries.
	unset($aFolders[0]);
	unset($aFolders[1]);

	// Serialize and sterilize our folder list.
	$strFolders = $dbConn->sanitize(serialize($aFolders));

	// Save the updated folder list.
	$dbConn->query("UPDATE citizen SET pmfolders='{$strFolders}' WHERE id={$_SESSION['userid']}");

	// Make a list of folders to be deleted.
	if(is_array($aCurrentFolders))
	{
		$aToDelete = array_values(array_diff(array_flip($aCurrentFolders), array_flip($aFolders)));
	}

	// Are there any folders to delete?
	if(is_array($aToDelete) && count($aToDelete))
	{
		// Yes, put the list into a string for SQL.
		$strFolders = implode(', ', $aToDelete);

		// Move any messages that were in deleted folders to the Inbox.
		$dbConn->query("UPDATE pm SET parent=0 WHERE parent IN ({$strFolders})");
	}

	// Header
	$strPageTitle = ' :: Private Messages';

	// Render the page.
	Msg("<b>Your folders were successfully updated. Any messages in folders you deleted have been moved into your Inbox.</b><br /><br /><span class=\"smaller\">You should be redirected to your Inbox momentarily. Click <a href=\"private.php\">here</a><br />if you do not want to wait any longer or if you are not redirected.</span>", 'private.php');
}

// *************************************************************************** \\

// Deletes the specified messages.
function Delete()
{
	global $CFG, $dbConn;

	// Get the list of messages to be deleted.
	$aMessages = $_REQUEST['id'];

	// Delete the messages.
	if(is_array($aMessages))
	{
		$strMessages = $dbConn->sanitize(implode(', ', $aMessages));
		$dbConn->query("DELETE FROM pm WHERE id IN ({$strMessages}) AND ownerid={$_SESSION['userid']}");
	}

	// Render the page.
	Msg("<b>The message(s) were successfully deleted.</b><br /><br /><span class=\"smaller\">You should be redirected momentarily. Click <a href=\"private.php\">here</a> if you do not want to wait any longer or if you are not redirected.</span>", 'private.php');
}

// *************************************************************************** \\

// Moves the specified messages to the specified folder.
function Move()
{
	global $CFG, $dbConn;

	// Get the list of messages to be moved.
	$aMessages = $_REQUEST['id'];

	// Get the destination.
	$iDestinationID = (int)$_REQUEST['dest'];

	// Get a list of our custom folders.
	$dbConn->query("SELECT pmfolders FROM citizen WHERE id={$_SESSION['userid']}");
	list($strFolders) = $dbConn->getresult();
	$aFolders = unserialize($strFolders);

	// Move the messages.
	if(is_array($aMessages) && isset($aFolders[$iDestinationID]))
	{
		$strMessages = $dbConn->sanitize(implode(', ', $aMessages));
		$dbConn->query("UPDATE pm SET parent={$iDestinationID} WHERE id IN ({$strMessages}) AND ownerid={$_SESSION['userid']}");
	}

	// Render the page.
	Msg("<b>The message(s) were successfully moved.</b><br /><br /><span class=\"smaller\">You should be redirected momentarily. Click <a href=\"private.php\">here</a> if you do not want to wait any longer or if you are not redirected.</span>", 'private.php');
}

// *************************************************************************** \\

function PrintCPMenu()
{
	global $CFG;

	// Template
	$strSection = 'pm';
	require("./skins/{$CFG['skin']}/usercp/menu.tpl.php");
}
?>