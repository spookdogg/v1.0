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

	// Do they have authorization to post new threads?
	if(!$_SESSION['permissions']['cmakethreads'] || !$_SESSION['loggedin'])
	{
		// No. Let them know the bad news.
		Unauthorized();
	}

	// Default values.
	$bParseURLs = FALSE;
	$bParseEMails = TRUE;
	$bDisableSmilies = FALSE;
	$bMakePoll = FALSE;
	$iNumberChoices = 4;

	// What forum to post thread in?
	$iForumID = (int)$_REQUEST['forumid'];

	// What category is this forum in? And what is the forum's title?
	$dbConn->query("SELECT id, name, parent FROM board WHERE displaydepth=0 OR id={$iForumID}");
	while($aSQLResult = $dbConn->getresult(TRUE))
	{
		// Is this particular forum us?
		if($aSQLResult['id'] == $iForumID)
		{
			// Yes, grab our name and parent.
			$strForumName = $aSQLResult['name'];
			$iCategoryID = $aSQLResult['parent'];
		}
		else
		{
			// Nope. Just store its information for later analysis.
			$aCategory[$aSQLResult['id']] = $aSQLResult['name'];
		}
	}

	// Do we exist?
	if($strForumName == '')
	{
		Msg("Invalid forum specified.{$CFG['msg']['invalidlink']}");
	}

	// Get the name of the category we are in.
	$strCategoryName = $aCategory[$iCategoryID];

	// Are they submitting? If so, take care of that now so we don't
	// accumulate a superfluous amount of queries.
	if($_REQUEST['submit'] == 'Submit Thread')
	{
		// Submitting thread.
		$aError = SubmitThread();

		// Store what the user posted so if there are errors,
		// they won't have to reenter everything.
		$strSubject = $_REQUEST['subject'];
		$strDescription = $_REQUEST['description'];
		$iPostIcon = (int)$_REQUEST['icon'];
		$strMessage = $_REQUEST['message'];
		$bParseURLs = (bool)$_REQUEST['parseurls'];
		$bParseEMails = (bool)$_REQUEST['parseemails'];
		$bDisableSmilies = (bool)$_REQUEST['dsmilies'];
		$bMakePoll = (bool)$_REQUEST['makepoll'];
		$iNumberChoices = (int)$_REQUEST['numchoices'];
	}

	// Template
	require("./skins/{$CFG['skin']}/newthread.tpl.php");

	// Send the page.
	exit;

// *************************************************************************** \\

// The user hit the Submit Thread button, so that's what we'll try to do.
function SubmitThread()
{
	global $CFG, $dbConn, $aPostIcons, $iForumID;

	// Get the values from the user.
	$strSubject = $_REQUEST['subject'];
	$strDescription = $_REQUEST['description'];
	$iThreadIcon = (int)$_REQUEST['icon'];
	$strMessage = $_REQUEST['message'];
	$bDisableSmilies = (int)(bool)$_REQUEST['dsmilies'];
	$bMakePoll = (int)(bool)$_REQUEST['makepoll'];
	$iNumberChoices = (int)$_REQUEST['numchoices'];

	// Floodcheck
	if(!$_SESSION['permissions']['cbypassflood'] && (($_SESSION['lastpost'] + $CFG['floodcheck']) > $CFG['globaltime']))
	{
		Msg("Sorry! The administrator has specified that users can only post one message every {$CFG['floodcheck']} seconds.", '', 'justify');
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
	$strCleanSubject = $dbConn->sanitize($strSubject);

	// Description
	if(strlen($strDescription) > $CFG['maxlen']['desc'])
	{
		// The description they specified is too long.
		$aError[] = "The description you specified is longer than {$CFG['maxlen']['desc']} characters.";
	}
	$strDescription = $dbConn->sanitize($strDescription);

	// Icon
	if(($iThreadIcon < 0) || ($iThreadIcon > (count($aPostIcons)-1)))
	{
		// They don't know what icon they want. We'll give them none.
		$iThreadIcon = 0;
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
	$strCleanMessage = $dbConn->sanitize($strMessage);

	// Attachment
	if((isset($_FILES['attachment'])) && ($_FILES['attachment']['error'] != UPLOAD_ERR_NO_FILE))
	{
		// What is the problem?
		switch($_FILES['attachment']['error'])
		{
			// Upload was successful?
			case UPLOAD_ERR_OK:
			{
				// Is it bigger than 100KB?
				if($_FILES['attachment']['size'] > $CFG['uploads']['maxsize'])
				{
					$aError[] = "The attachment you uploaded is too large. The maximum allowable filesize is {$CFG['uploads']['maxsize']} bytes.";
				}

				// Is it an invalid filetype?
				if(!isset($CFG['uploads']['oktypes'][strtolower(substr(strrchr($_FILES['attachment']['name'], '.'), 1))]))
				{
					$aError[] = 'The file you uploaded is an invalid type of attachment. Valid types are: '.htmlsanitize(implode(', ', array_keys($CFG['uploads']['oktypes']))).'.';
				}

				// If there are no errors, grab the data from the temporary file.
				if(!is_array(aError))
				{
					$strAttachmentName = $dbConn->sanitize($_FILES['attachment']['name']);
					if($fileUploaded = fopen($_FILES['attachment']['tmp_name'], 'rb'))
					{
						$blobAttachment = $dbConn->sanitize(fread($fileUploaded, 65536), TRUE);
					}
					else
					{
						$aError[] = 'There was a problem while reading the attachment. If this problem persists, please contact the Webmaster.';
					}
				}

				break;
			}

			// File is too big?
			case UPLOAD_ERR_INI_SIZE:
			//case UPLOAD_ERR_FORM_SIZE:
			{
				$aError[] = "The attachment you uploaded is too large. The maximum allowable filesize is {$CFG['uploads']['maxsize']} bytes.";
				break;
			}

			// File was partially uploaded?
			case UPLOAD_ERR_PARTIAL:
			{
				$aError[] = 'The attachment was only partially uploaded.';
				break;
			}

			// WTF happened?
			default:
			{
				$aError[] = 'There was an error while uploading the attachment.';
				break;
			}
		}
	}

	// If there was an error, let's return it.
	if(is_array($aError))
	{
		return $aError;
	}

	// Are we posting a poll?
	if($bMakePoll && $_SESSION['permissions']['cmakepolls'])
	{
		// Yes, so delay making the thread public until we finish the poll.
		$strPatch = '1, 1, 0';
	}
	else
	{
		// No, so we're going to make the thread public now.
		$strPatch = '0, 0, 1';
	}

	// First, we need to add a new post in the post table.
	$dbConn->query("INSERT INTO post(author, datetime_posted, title, body, ipaddress, icon, dsmilies) VALUES({$_SESSION['userid']}, {$CFG['globaltime']}, '{$strCleanSubject}', '{$strCleanMessage}', {$_SESSION['userip']}, {$iThreadIcon}, {$bDisableSmilies})");

	// Before we continue, get the ID of the post we just created.
	$iPostID = $dbConn->getinsertid('post');

	// Second, we need to add a new thread in the thread table.
	$dbConn->query("INSERT INTO thread(title, description, parent, viewcount, postcount, attachcount, lpost, lposter, icon, author, poll, closed, visible, sticky) VALUES('{$strCleanSubject}', '{$strDescription}', {$iForumID}, 0, 1, 0, {$CFG['globaltime']}, {$_SESSION['userid']}, {$iThreadIcon}, {$_SESSION['userid']}, {$strPatch}, 0)");

	// Before we continue, we need the ID of the thread we created.
	$iThreadID = $dbConn->getinsertid('thread');

	// Now with the thread ID, we can update the post record we originally created.
	$dbConn->query("UPDATE post SET parent={$iThreadID} WHERE id={$iPostID}");

	// Only do these if we're not posting a poll.
	if(!$bMakePoll || !$_SESSION['permissions']['cmakepolls'])
	{
		// Update the record of the forum that contains the thread we just created.
		$dbConn->query("UPDATE board SET postcount=postcount+1, threadcount=threadcount+1, lpost={$CFG['globaltime']}, lposter={$_SESSION['userid']}, lthread={$iThreadID}, lthreadpcount=1 WHERE id={$iForumID}");

		// Update the poster's profile.
		$dbConn->query("UPDATE citizen SET postcount=postcount+1 WHERE id={$_SESSION['userid']}");

		// Update the forum stats.
		$dbConn->query("UPDATE stats SET content=content+1 WHERE name IN ('postcount', 'threadcount')");

	}

	// And finally, we need to store the attachment, if there is one.
	if($fileUploaded)
	{
		// Insert the first chunk of the file.
		$dbConn->query("INSERT INTO attachment(filename, filedata, viewcount, parent) VALUES('{$strAttachmentName}', '{$blobAttachment}', 0, {$iPostID})");

		// Get the ID of the attachment we just created.
		$iAttachmentID = $dbConn->getinsertid('attachment');

		// Insert the rest of the file, if any, into the database.
		while(!feof($fileUploaded))
		{
			$blobAttachment = $dbConn->sanitize(fread($fileUploaded, 65536), TRUE);
			$dbConn->squery(CONCAT_ATTACHMENT, $blobAttachment, $iAttachmentID);
		}

		// Close the temporary file.
		fclose($fileUploaded);

		// Update the attachment count for the thread.
		$dbConn->query("UPDATE thread SET attachcount=attachcount+1 WHERE id={$iThreadID}");
	}

	// Now let's add the message into the search engine index.
	AddSearchIndex($iPostID, $strSubject, $strMessage);

	// Set user's last post time.
	$_SESSION['lastpost'] = $CFG['globaltime'];

	// Render the page.
	$strRedirect = ($bMakePoll && $_SESSION['permissions']['cmakepolls']) ? "poll.php?action=newpoll&amp;threadid={$iThreadID}&amp;numchoices={$iNumberChoices}" : "thread.php?threadid={$iThreadID}";
	Msg("<b>Thank you for posting.</b><br /><br /><span class=\"smaller\">You should be redirected to your post momentarily. Click <a href=\"{$strRedirect}\">here</a> if you do not want to wait any longer or if you are not redirected.</span>", str_replace('&amp;', '&', $strRedirect));
}
?>