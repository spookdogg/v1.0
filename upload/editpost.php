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

	// Default values.
	$bParseURLs = FALSE;
	$bParseEMails = TRUE;

	// What post do they want to edit?
	$iPostID = (int)$_REQUEST['postid'];

	// Get the post's information.
	$dbConn->query("SELECT post.*, citizen.username FROM post LEFT JOIN citizen ON (post.author = citizen.id) WHERE post.id={$iPostID}");
	if(!($aPostInfo = $dbConn->getresult(TRUE)))
	{
		Msg("Invalid post specified.{$CFG['msg']['invalidlink']}");
	}

	// Are they authorized to edit this post?
	if((!$_SESSION['permissions']['ceditposts']) || (($_SESSION['userid'] != $aPostInfo['author']) && (!$_SESSION['permissions']['cmeditposts'])))
	{
		Unauthorized();
	}

	// Get the attachments in this post.
	$dbConn->query("SELECT id, filename FROM attachment WHERE parent={$iPostID}");
	while(list($iAttachmentID, $strFilename) = $dbConn->getresult())
	{
		$aAttachments[$iAttachmentID] = $strFilename;
	}

	// Get the thread ID, thread description, forum, and category the post belongs to.
	$dbConn->query("SELECT thread.title, thread.description, board.id AS bID, board.name AS bName, cat.id AS cID, cat.name AS cName FROM thread INNER JOIN board ON (thread.parent = board.id) INNER JOIN board AS cat ON (board.parent = cat.id) WHERE thread.id={$aPostInfo['parent']}");
	list($strThreadTitle, $strThreadDesc, $iForumID, $strForumName, $iCategoryID, $strCategoryName) = $dbConn->getresult();

	// Get the thread's root.
	$dbConn->query("SELECT post.id FROM post LEFT JOIN thread ON (post.parent = thread.id) WHERE thread.id={$aPostInfo['parent']} ORDER BY post.datetime_posted ASC LIMIT 1");
	list($iRootID) = $dbConn->getresult();

	// Are they saving?
	if($_REQUEST['submit'] == 'Save')
	{
		// Yes, do that now.
		$aError = SavePost($aPostInfo);

		// Store the posted values in case we get errors while saving,
		// the user won't have to reenter their information.
		$strSubject = $_REQUEST['subject'];
		$strThreadDesc = $_REQUEST['description'];
		$iPostIcon = (int)$_REQUEST['icon'];
		$strBody = $_REQUEST['message'];
		$bParseURLs = (bool)$_REQUEST['parseurls'];
		$bParseEMails = (bool)$_REQUEST['parseemails'];
		$bDisableSmilies = (bool)$_REQUEST['dsmilies'];
		$aDeleteAttachments = $_REQUEST['deleteattach'];
	}
	else
	{
		// Store the post info into variables.
		$strSubject = $aPostInfo['title'];
		$iPostIcon = (int)$aPostInfo['icon'];
		$strBody = $aPostInfo['body'];
		$bDisableSmilies = $aPostInfo['dsmilies'];
	}

	// Are they deleting?
	if(($_REQUEST['submit'] == 'Delete Now') && ((bool)$_REQUEST['deletepost']))
	{
		// Yes, do that now.
		DeletePost($aPostInfo);
	}

	// Template
	require("./skins/{$CFG['skin']}/editpost.tpl.php");

	// Send the page.
	exit;

// *************************************************************************** \\

// The user hit the Delete [Post] Now button, so that's what we'll attempt to do.
function DeletePost($aPostInfo)
{
	global $CFG, $dbConn, $iForumID;
	$iPostID = $aPostInfo['id'];
	$iThreadID = $aPostInfo['parent'];
	$iAuthorID = $aPostInfo['author'];

	// Get the thread's root.
	$dbConn->query("SELECT post.id FROM post LEFT JOIN thread ON (post.parent = thread.id) WHERE thread.id={$iThreadID} ORDER BY post.datetime_posted ASC LIMIT 1");
	list($iRootID) = $dbConn->getresult();

	// Is the post we're about to delete the thread root?
	if($iRootID == $iPostID)
	{
		// Yes, get a list of all posts in the thread.
		$dbConn->query("SELECT id, author FROM post WHERE parent={$iThreadID}");
		while(list($iPID, $iAuthorID) = $dbConn->getresult())
		{
			// Save the post in the list to delete.
			$aPostList[] = $iPID;

			// Increment the author's postcount of this thread.
			$aPostCounts[$iAuthorID]++;
		}
		$strPostIDs = implode(', ', $aPostList);

		// Subtract from the users' postcounts the number of posts they had in the thread.
		foreach($aPostCounts as $iAuthorID => $iPostCount)
		{
			$dbConn->query("UPDATE citizen SET postcount=postcount-{$iPostCount} WHERE id={$iAuthorID}");
		}

		// Delete the posts' records, the thread's record, any attachments, and any poll.
		$dbConn->query("DELETE FROM post WHERE parent={$iThreadID}");
		$dbConn->query("DELETE FROM thread WHERE id={$iThreadID}");
		$dbConn->query("DELETE FROM attachment WHERE parent IN ({$strPostIDs})");
		$dbConn->query("DELETE FROM poll WHERE id={$iThreadID}");
		$dbConn->query("DELETE FROM pollvote WHERE parent={$iThreadID}");

		// Delete all search indexes for the posts.
		$dbConn->query("DELETE FROM searchindex WHERE postid IN ({$strPostIDs})");

		// Get the total number of posts we've deleted.
		$iPostCount = count($aPostList);

		// Update the forum's post and thread counts.
		$dbConn->query("UPDATE board SET postcount=postcount-{$iPostCount}, threadcount=threadcount-1 WHERE id={$iForumID}");

		// Update the forum stats.
		$dbConn->query("UPDATE stats SET content=content-{$iPostCount} WHERE name='postcount'");
		$dbConn->query("UPDATE stats SET content=content-1 WHERE name='threadcount'");

		// Set the redirect page.
		$strRedirect = "forumdisplay.php?forumid={$iForumID}";
	}
	else
	{
		// No. Get the number of attachments in this post.
		$dbConn->query("SELECT COUNT(*) FROM attachment WHERE parent={$iPostID}");
		list($iAttachmentCount) = $dbConn->getresult();

		// Delete the post's record and any attachments.
		$dbConn->query("DELETE FROM post WHERE id={$iPostID}");
		$dbConn->query("DELETE FROM attachment WHERE parent={$iPostID}");

		// Delete all search indexes for the post.
		$dbConn->query("DELETE FROM searchindex WHERE postid={$iPostID}");

		// Decrease the post count of the author of the post by one.
		$dbConn->query("UPDATE citizen SET postcount=postcount-1 WHERE id={$iAuthorID}");

		// Get the post date and the author ID of the new last post of the thread.
		$dbConn->query("SELECT post.datetime_posted, post.author FROM post LEFT JOIN thread ON (post.parent = thread.id) WHERE thread.id={$iThreadID} ORDER BY post.datetime_posted DESC LIMIT 1");
		list($tLastPost, $iNewLastPoster) = $dbConn->getresult();

		// Update the thread's record.
		$dbConn->query("UPDATE thread SET lpost={$tLastPost}, lposter={$iNewLastPoster}, postcount=postcount-1, attachcount=attachcount-{$iAttachmentCount} WHERE id={$iThreadID}");

		// Update the forum's post count.
		$dbConn->query("UPDATE board SET postcount=postcount-1 WHERE id={$iForumID}");

		// Update the forum stats.
		$dbConn->query("UPDATE stats SET content=content-1 WHERE name='postcount'");

		// Set the redirect page.
		$strRedirect = "thread.php?threadid={$iThreadID}";
	}

	// Update the forum's statistics.
	UpdateForumStats($iForumID);

	// Render the page.
	Msg("<b>The post has been successfully deleted.</b><br /><br /><span class=\"smaller\">You should be redirected momentarily. Click <a href=\"{$strRedirect}\">here</a> if you do not want to wait any longer or if you are not redirected.</span>", $strRedirect);
}

// *************************************************************************** \\

// The user hit the Save button, so that's what we'll attempt to do.
function SavePost($aPostInfo)
{
	global $CFG, $dbConn, $iRootID, $aAttachments, $aPostIcons;
	$iPostID = $aPostInfo['id'];
	$iThreadID = $aPostInfo['parent'];

	// Initiate some variables.
	$aToDelete = array();
	$iAddedAttachments = 0;
	$iRemovedAttachments = 0;

	// Grab the info. specified by the user.
	$strSubject = $_REQUEST['subject'];
	$strThreadDesc = $_REQUEST['description'];
	$iPostIcon = (int)$_REQUEST['icon'];
	$strBody = $_REQUEST['message'];
	$bParseURLs = (bool)$_REQUEST['parseurls'];
	$bParseEMails = (bool)$_REQUEST['parseemails'];
	$bDisableSmilies = (int)((bool)$_REQUEST['dsmilies']);
	$aDeleteAttachments = $_REQUEST['deleteattach'];

	// Subject
	if((trim($strSubject) == '') && ($iPostID == $iRootID))
	{
		// This post is the thread root, and they either put in only whitespace or nothing at all.
		$aError[] = 'You must specify a subject.';
	}
	else if(strlen($strSubject) > $CFG['maxlen']['subject'])
	{
		// The subject they specified is too long.
		$aError[] = "The subject you specified is longer than {$CFG['maxlen']['subject']} characters.";
	}
	$strCleanSubject = $dbConn->sanitize($strSubject);

	// Description
	if(strlen($strThreadDesc) > $CFG['maxlen']['desc'])
	{
		// The description they specified is too long.
		$aError[] = "The description you specified is longer than {$CFG['maxlen']['desc']} characters.";
	}
	$strThreadDesc = $dbConn->sanitize($strThreadDesc);

	// Icon
	if(($iPostIcon < 0) || ($iPostIcon > (count($aPostIcons)-1)))
	{
		// They don't know what icon they want. We'll give them none.
		$iPostIcon = 0;
	}

	// Body
	if(trim($strBody) == '')
	{
		// They either put in only whitespace or nothing at all.
		$aError[] = 'You must specify a message.';
	}
	else if(strlen($strBody) > $CFG['maxlen']['messagebody'])
	{
		// The body they specified is too long.
		$aError[] = "The message you specified is longer than {$CFG['maxlen']['messagebody']} characters.";
	}
	$strCleanBody = $dbConn->sanitize($strBody);

	// Attachment
	if((isset($_FILES['attachment'])) && ($_FILES['attachment']['error'] != UPLOAD_ERR_NO_FILE))
	{
		// What is the problem?
		switch($_FILES['attachment']['error'])
		{
			// Upload was successful?
			case UPLOAD_ERR_OK:
			{
				// Is it bigger than the allowable maximum?
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
	if($aError)
	{
		return $aError;
	}

	// Update the post's record.
	$dbConn->query("UPDATE post SET datetime_edited={$CFG['globaltime']}, title='{$strCleanSubject}', body='{$strCleanBody}', icon={$iPostIcon}, dsmilies={$bDisableSmilies} WHERE id={$iPostID}");

	// Was this post the thread root?
	if($iPostID == $iRootID)
	{
		// Yes, update the thread description.
		$dbConn->query("UPDATE thread SET title='{$strCleanSubject}', icon={$iPostIcon}, description='{$strThreadDesc}' WHERE id={$iThreadID}");
	}

	// Store the attachment, if there is one.
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

		// Increment the added attachment count.
		$iAddedAttachments++;
	}

	// Are there any attachments to delete?
	if(is_array($aDeleteAttachments) && is_array($aAttachments))
	{
		// Yes, so remove the ones that don't belong to this post.
		foreach($aDeleteAttachments as $iAttachmentID => $null)
		{
			// Is the attachment in this post?
			if(array_key_exists($iAttachmentID, $aAttachments) && !array_search($iAttachmentID, $aToDelete))
			{
				// Yes, so add the attachment to the list to delete.
				$aToDelete[] = $iAttachmentID;
			}
		}

		// Are there still attachments to delete?
		if(is_array($aToDelete))
		{
			// Yes, so delete them.
			$strToDelete = implode(', ', $aToDelete);
			$dbConn->query("DELETE FROM attachment WHERE id IN ({$strToDelete})");

			// Set the removed attachments counter.
			$iRemovedAttachments = count($aToDelete);
		}
	}

	// Are there any changes to the number of attachments in this post (and therefore the parent thread)?
	$iAttachmentCount = $iAddedAttachments - $iRemovedAttachments;
	if($iAttachmentCount != 0)
	{
		// Yes, so update the thread's record.
		$dbConn->query("UPDATE thread SET attachcount=attachcount+({$iAttachmentCount}) WHERE id={$iThreadID}");
	}

	// Remove all searchindexes for this post.
	$dbConn->query("DELETE FROM searchindex WHERE postid={$iPostID}");

	// Now let's re-add the message into the search engine index.
	AddSearchIndex($iPostID, $strSubject, $strBody);

	// Update the user.
	Msg("<b>Your changes have been successfully saved.</b><br /><br /><span class=\"smaller\">You should be redirected to your post momentarily. Click <a href=\"thread.php?threadid={$iThreadID}&amp;postid={$iPostID}#post{$iPostID}\">here</a> if you do not want to wait any longer or if you are not redirected.</span>", "thread.php?threadid={$iThreadID}&postid={$iPostID}#post{$iPostID}");
}
?>