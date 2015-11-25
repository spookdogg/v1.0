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
	define('AUTHOR',    0);
	define('BODY',      1);
	define('DSMILIES',  2);

	// Do they have authorization to post replies?
	if(!$_SESSION['permissions']['creply'] || !$_SESSION['loggedin'])
	{
		// No. Let them know the bad news.
		Unauthorized();
	}

	// Default values.
	$bParseURLs = FALSE;
	$bParseEMails = TRUE;
	$bDisableSmilies = FALSE;

	// Get the thread we're replying to.
	$iThreadID = (int)$_REQUEST['threadid'];

	// Are we replying to a particular post within this thread?
	if(isset($_REQUEST['postid']))
	{
		// Perhaps. What post?
		$iPostID = (int)$_REQUEST['postid'];

		// Get information about it.
		$dbConn->query("SELECT post.author, citizen.username, post.datetime_posted, post.title, post.body FROM post JOIN citizen ON (post.author = citizen.id) WHERE post.id={$iPostID} AND post.parent={$iThreadID}");
		if(list($iPostAuthorID, $strPostAuthor, $tPostTimestamp, $strPostSubject, $strPostBody) = $dbConn->getresult())
		{
			$strSubject = ((trim($strPostSubject) != '') && (strpos($strPostSubject, 'Re: ') !== 0)) ? "Re: {$strPostSubject}" : $strPostSubject;
			$strMessage = "[quote={$strPostAuthor}]{$strPostBody}[/quote]";
		}
	}

	// What forum is this thread in? And what is the thread's title? And is the thread closed?
	$dbConn->query("SELECT title, parent, closed FROM thread WHERE id={$iThreadID}");
	if(!(list($strThreadTitle, $iForumID, $bIsClosed) = $dbConn->getresult()))
	{
		Msg("Invalid thread specified.{$CFG['msg']['invalidlink']}");
	}

	// If the thread is closed, does the user have sufficient authorization to reply anyway?
	if($bIsClosed && !$_SESSION['permissions']['creplyclosed'])
	{
		// No. Let them know the bad news.
		Msg("<b>Sorry, this thread is closed!</b><br /><br /><span class=\"smaller\">You will now be returned to the thread. Click <a href=\"thread.php?threadid={$iThreadID}\">here</a> if you do not want to wait any longer or if you are not redirected.</span>", "thread.php?threadid={$iThreadID}");
	}

	// Are they submitting?
	if($_REQUEST['submit'] == 'Submit Reply')
	{
		// Yes, do that now.
		$aError = SubmitPost();

		// Get the values from the user if they get an error
		// back so they won't have to reenter everything.
		$strSubject = $_REQUEST['subject'];
		$iPostIcon = (int)$_REQUEST['icon'];
		$strMessage = $_REQUEST['message'];
		$bParseURLs = (bool)$_REQUEST['parseurls'];
		$bParseEMails = (bool)$_REQUEST['parseemails'];
		$bDisableSmilies = (bool)$_REQUEST['dsmilies'];
	}

	// Get the ID, name, and display order of each category; and of the forum we're in.
	$dbConn->query("SELECT id, name, parent FROM board WHERE displaydepth=0 OR id={$iForumID} ORDER BY disporder ASC");
	while($aSQLResult = $dbConn->getresult(TRUE))
	{
		// Is this particular forum the forum we are in?
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
	// Get the name of the category we are in.
	$strCategoryName = $aCategory[$iCategoryID];

	// Get some of the last posts for Topic Review.
	$dbConn->query("SELECT post.id, post.body, post.dsmilies, citizen.username FROM post LEFT JOIN citizen ON (post.author = citizen.id) WHERE post.parent={$iThreadID} ORDER BY post.datetime_posted DESC LIMIT {$_SESSION['postsperpage']}");
	while($aSQLResult = $dbConn->getresult())
	{
		$iReviewID = $aSQLResult[0];
		$aReview[$iReviewID][BODY] = $aSQLResult[1];
		$aReview[$iReviewID][DSMILIES] = $aSQLResult[2];
		$aReview[$iReviewID][AUTHOR] = $aSQLResult[3];
	}

	// Template
	require("./skins/{$CFG['skin']}/newreply.tpl.php");

	// Send the page.
	exit;

// *************************************************************************** \\

// The user hit the Submit Reply button, so that's what we'll try to do.
function SubmitPost()
{
	global $CFG, $dbConn, $aPostIcons, $iThreadID, $iForumID;

	// Get the values from the user.
	$strSubject = $_REQUEST['subject'];
	$iPostIcon = (int)$_REQUEST['icon'];
	$strMessage = $_REQUEST['message'];
	$bParseEMails = (int)(bool)$_REQUEST['parseemails'];
	$bDisableSmilies = (int)(bool)$_REQUEST['dsmilies'];

	// Floodcheck
	if(!$_SESSION['permissions']['cbypassflood'] && (($_SESSION['lastpost'] + $CFG['floodcheck']) > $CFG['globaltime']))
	{
		Msg("Sorry! The administrator has specified that users can only post one message every {$CFG['floodcheck']} seconds.", '', 'justify');
	}

	// Subject
	if(strlen($strSubject) > $CFG['maxlen']['subject'])
	{
		// The subject they specified is too long.
		$aError[] = "The subject you specified is longer than {$CFG['maxlen']['subject']} characters.";
	}
	$strCleanSubject = $dbConn->sanitize($strSubject);

	// Icon
	if(($iPostIcon < 0) || ($iPostIcon > (count($aPostIcons)-1)))
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
	if($bParseEMails)
	{
		$strMessage = ParseEMails($strMessage);
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
				if(!is_array($aError))
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
			case UPLOAD_ERR_FORM_SIZE:
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

	// First we obviously need the post in the post table.
	$dbConn->query("INSERT INTO post(author, datetime_posted, title, body, parent, ipaddress, icon, dsmilies) VALUES({$_SESSION['userid']}, {$CFG['globaltime']}, '{$strCleanSubject}', '{$strCleanMessage}', {$iThreadID}, {$_SESSION['userip']}, {$iPostIcon}, {$bDisableSmilies})");

	// Before we continue, get the ID of the post we just created.
	$iPostID = $dbConn->getinsertid('post');

	// Second, we need to update record of the thread we are posting to.
	$dbConn->query("UPDATE thread SET lpost={$CFG['globaltime']}, lposter={$_SESSION['userid']}, postcount=postcount+1 WHERE id={$iThreadID}");

	// Get the post count of the thread we replied to, so we can figure the last page.
	$dbConn->query("SELECT postcount FROM thread WHERE id={$iThreadID}");
	list($iPostCount) = $dbConn->getresult();

	// Third, we need to update the record of the forum that contains the thread we are posting to.
	$dbConn->query("UPDATE board SET postcount=postcount+1, lpost={$CFG['globaltime']}, lposter={$_SESSION['userid']}, lthread={$iThreadID}, lthreadpcount={$iPostCount} WHERE id={$iForumID}");

	// Fourth, we need to update the poster's postcount.
	$dbConn->query("UPDATE citizen SET postcount=postcount+1 WHERE id={$_SESSION['userid']}");

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

	// Update the forum stats.
	$dbConn->query("UPDATE stats SET content=content+1 WHERE name='postcount'");

	// Set user's last post time.
	$_SESSION['lastpost'] = $CFG['globaltime'];

	// What page is this new post on (so we can redirect them)?
	$iPage = ceil($iPostCount / $_SESSION['postsperpage']);

	// Render the page.
	Msg("<b>Thank you for posting.</b><br /><br /><span class=\"smaller\">You should be redirected to your post momentarily. Click <a href=\"thread.php?threadid={$iThreadID}&amp;page={$iPage}#post{$iPostID}\">here</a> if you do not want to wait any longer or if you are not redirected.</span>", "thread.php?threadid={$iThreadID}&page={$iPage}#post{$iPostID}");
}
?>