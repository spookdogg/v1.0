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

	// What are they wanting to do?
	switch($_REQUEST['action'])
	{
		case 'openthread':
		{
			OpenThread((int)$_REQUEST['threadid']);
		}

		case 'closethread':
		{
			CloseThread((int)$_REQUEST['threadid']);
		}

		case 'stickthread':
		{
			StickThread((int)$_REQUEST['threadid']);
		}

		case 'unstickthread':
		{
			UnStickThread((int)$_REQUEST['threadid']);
		}

		case 'deletethread':
		{
			DeleteThread((int)$_REQUEST['threadid']);
		}

		case 'deleteposts':
		{
			DeletePosts((int)$_REQUEST['threadid']);
		}

		case 'movethread':
		{
			MoveThread((int)$_REQUEST['threadid']);
		}

		case 'getip':
		{
			GetIP();
		}
	}

// *************************************************************************** \\

// Opens the specified thread.
function OpenThread($iThreadID)
{
	global $CFG, $dbConn;

	// Do they have permission to open/close threads?
	if(!$_SESSION['permissions']['cmopenclosethreads'])
	{
		// No, so give them the bad news.
		Unauthorized();
	}

	// Get some information on the thread.
	$dbConn->query("SELECT closed FROM thread WHERE id={$iThreadID}");
	if(!(list($bIsClosed) = $dbConn->getresult()))
	{
		// Thread does not exist.
		Msg("Invalid thread specified.{$CFG['msg']['invalidlink']}");
	}

	// Is the thread already open?
	if(!$bIsClosed)
	{
		Msg("<b>The specified thread is already open.</b><br /><br /><span class=\"smaller\">You should be redirected momentarily. Click <a href=\"thread.php?threadid={$iThreadID}\">here</a> if you do not want to wait any longer or if you are not redirected.</span>", "thread.php?threadid={$iThreadID}");
	}

	// Open the thread.
	$dbConn->query("UPDATE thread SET closed=0 WHERE id={$iThreadID}");

	// Render page.
	Msg("<b>The thread has been successfully opened.</b><br /><br /><span class=\"smaller\">You should be redirected momentarily. Click <a href=\"thread.php?threadid={$iThreadID}\">here</a> if you do not want to wait any longer or if you are not redirected.</span>", "thread.php?threadid={$iThreadID}");
}

// *************************************************************************** \\

// Closes the specified thread.
function CloseThread($iThreadID)
{
	global $CFG, $dbConn;

	// Do they have permission to open/close threads?
	if(!$_SESSION['permissions']['cmopenclosethreads'])
	{
		// No, so give them the bad news.
		Unauthorized();
	}

	// Get some information on the thread.
	$dbConn->query("SELECT closed FROM thread WHERE id={$iThreadID}");
	if(!(list($bIsClosed) = $dbConn->getresult()))
	{
		// Thread does not exist.
		Msg("Invalid thread specified.{$CFG['msg']['invalidlink']}");
	}

	// Is the thread already closed?
	if($bIsClosed)
	{
		Msg("<b>The specified thread is already closed.</b><br /><br /><span class=\"smaller\">You should be redirected momentarily. Click <a href=\"thread.php?threadid={$iThreadID}\">here</a> if you do not want to wait any longer or if you are not redirected.</span>", "thread.php?threadid={$iThreadID}");
	}

	// Close the thread.
	$dbConn->query("UPDATE thread SET closed=1 WHERE id={$iThreadID}");

	// Render page.
	Msg("<b>The thread has been successfully closed.</b><br /><br /><span class=\"smaller\">You should be redirected momentarily. Click <a href=\"thread.php?threadid={$iThreadID}\">here</a> if you do not want to wait any longer or if you are not redirected.</span>", "thread.php?threadid={$iThreadID}");
}

// *************************************************************************** \\

// Makes the specified thread sticky.
function StickThread($iThreadID)
{
	global $CFG, $dbConn;

	// Do they have permission to stick/unstick threads?
	if(!$_SESSION['permissions']['cmstickythreads'])
	{
		// No, so give them the bad news.
		Unauthorized();
	}

	// Get some information on the thread.
	$dbConn->query("SELECT sticky FROM thread WHERE id={$iThreadID}");
	if(!(list($bIsSticky) = $dbConn->getresult()))
	{
		// Thread does not exist.
		Msg("Invalid thread specified.{$CFG['msg']['invalidlink']}");
	}

	// Is the thread already sticky?
	if($bIsSticky)
	{
		Msg("<b>The specified thread is already sticky.</b><br /><br /><span class=\"smaller\">You should be redirected momentarily. Click <a href=\"thread.php?threadid={$iThreadID}\">here</a> if you do not want to wait any longer or if you are not redirected.</span>", "thread.php?threadid={$iThreadID}");
	}

	// Make the thread sticky.
	$dbConn->query("UPDATE thread SET sticky=1 WHERE id={$iThreadID}");

	// Render page.
	Msg("<b>The thread has been successfully stuck.</b><br /><br /><span class=\"smaller\">You should be redirected momentarily. Click <a href=\"thread.php?threadid={$iThreadID}\">here</a> if you do not want to wait any longer or if you are not redirected.</span>", "thread.php?threadid={$iThreadID}");
}

// *************************************************************************** \\

// Makes the specified thread not sticky.
function UnStickThread($iThreadID)
{
	global $CFG, $dbConn;

	// Do they have permission to stick/unstick threads?
	if(!$_SESSION['permissions']['cmstickythreads'])
	{
		// No, so give them the bad news.
		Unauthorized();
	}

	// Get some information on the thread.
	$dbConn->query("SELECT sticky FROM thread WHERE id={$iThreadID}");
	if(!(list($bIsSticky) = $dbConn->getresult()))
	{
		// Thread does not exist.
		Msg("Invalid thread specified.{$CFG['msg']['invalidlink']}");
	}

	// Is the thread already sticky?
	if(!$bIsSticky)
	{
		Msg("<b>The specified thread is not sticky.</b><br /><br /><span class=\"smaller\">You should be redirected momentarily. Click <a href=\"thread.php?threadid={$iThreadID}\">here</a> if you do not want to wait any longer or if you are not redirected.</span>", "thread.php?threadid={$iThreadID}");
	}

	// Unstick the thread.
	$dbConn->query("UPDATE thread SET sticky=0 WHERE id={$iThreadID}");

	// Render page.
	Msg("<b>The thread has been successfully unstuck.</b><br /><br /><span class=\"smaller\">You should be redirected momentarily. Click <a href=\"thread.php?threadid={$iThreadID}\">here</a> if you do not want to wait any longer or if you are not redirected.</span>", "thread.php?threadid={$iThreadID}");
}

// *************************************************************************** \\

// User wants to delete a thread.
function DeleteThread($iThreadID)
{
	global $CFG, $dbConn;

	// Do they have permission to delete threads?
	if(!$_SESSION['permissions']['cmdeletethreads'])
	{
		// No, so give them the bad news.
		Unauthorized();
	}

	// What forum is this thread in? What is its title?
	$dbConn->query("SELECT title, parent FROM thread WHERE id={$iThreadID}");
	if(!(list($strThreadTitle, $iForumID) = $dbConn->getresult()))
	{
		Msg("Invalid thread specified.{$CFG['msg']['invalidlink']}");
	}

	// Are they submitting?
	if(($_REQUEST['submit'] == 'Delete Now') && ((bool)$_REQUEST['delete']))
	{
		// Yes, do that now.
		DeleteThreadNow($iThreadID, $iForumID);
	}

	// Get the forum name as well as the ID and name of the category the thread belongs to.
	$dbConn->query("SELECT board.name, cat.id, cat.name FROM board INNER JOIN board AS cat ON (board.parent = cat.id) WHERE board.id={$iForumID}");
	list($strForumName, $iCategoryID, $strCategoryName) = $dbConn->getresult();

	// Template
	require("./skins/{$CFG['skin']}/deletethread.tpl.php");

	// Send the page.
	exit;
}

// Deletes a specified thread.
function DeleteThreadNow($iThreadID, $iForumID)
{
	global $CFG, $dbConn;

	// Get a list of all posts in the thread.
	$dbConn->query("SELECT id, author FROM post WHERE parent={$iThreadID}");
	while(list($iPostID, $iAuthorID) = $dbConn->getresult())
	{
		// Save the post in the list to delete.
		$aPosts[] = $iPostID;

		// Increment the author's postcount of this thread.
		$aPostCounts[$iAuthorID]++;
	}
	$strPostIDs = implode(', ', $aPosts);

	// Subtract from the users' postcounts the number of posts they had in the thread.
	foreach($aPostCounts as $iAuthorID => $iPostCount)
	{
		$dbConn->query("UPDATE citizen SET postcount=postcount-{$iPostCount} WHERE id={$iAuthorID}");
	}

	// Delete the posts' records, the thread's record, and any attachments.
	$dbConn->query("DELETE FROM post WHERE parent={$iThreadID}");
	$dbConn->query("DELETE FROM thread WHERE id={$iThreadID}");
	$dbConn->query("DELETE FROM attachment WHERE parent IN ({$strPostIDs})");
	$dbConn->query("DELETE FROM poll WHERE id={$iThreadID}");
	$dbConn->query("DELETE FROM pollvote WHERE parent={$iThreadID}");

	// Remove all searchindexes for the posts.
	$dbConn->query("DELETE FROM searchindex WHERE postid IN ({$strPostIDs})");

	// Get the total number of posts we've deleted.
	$iPostCount = count($aPosts);

	// Update the forum's post and thread counts.
	$dbConn->query("UPDATE board SET postcount=postcount-{$iPostCount}, threadcount=threadcount-1 WHERE id={$iForumID}");

	// Update the forum stats.
	$dbConn->query("UPDATE stats SET content=content-{$iPostCount} WHERE name='postcount'");
	$dbConn->query("UPDATE stats SET content=content-1 WHERE name='threadcount'");

	// Update the forum's statistics.
	UpdateForumStats($iForumID);

	// Render the page.
	Msg("<b>The thread has been successfully deleted.</b><br /><br /><span class=\"smaller\">You should be redirected to the forum momentarily. Click <a href=\"forumdisplay.php?forumid={$iForumID}\">here</a> if you do not want to wait any longer or if you are not redirected.</span>", "forumdisplay.php?forumid={$iForumID}");
}

// *************************************************************************** \\

// User wants to delete posts from a thread.
function DeletePosts($iThreadID)
{
	global $CFG, $dbConn;

	// Constants
	define('AUTHOR',    0);
	define('POSTDATE',  1);
	define('BODY',      2);

	// Do they have permission to delete posts?
	if(!$_SESSION['permissions']['cmdeleteposts'])
	{
		// No, so give them the bad news.
		Unauthorized();
	}

	// What forum is this thread in? What is its title?
	$dbConn->query("SELECT title, parent FROM thread WHERE id={$iThreadID}");
	if(!(list($strThreadTitle, $iForumID) = $dbConn->getresult()))
	{
		Msg("Invalid thread specified.{$CFG['msg']['invalidlink']}");
	}

	// Are they submitting?
	if($_REQUEST['submit'] == 'Delete Posts')
	{
		// Yes, do that now.
		DeletePostsNow($iThreadID, $iForumID, (array)$_REQUEST['postid']);
	}

	// Get the forum name as well as the ID and name of the category the thread belongs to.
	$dbConn->query("SELECT board.name, cat.id, cat.name FROM board INNER JOIN board AS cat ON (board.parent = cat.id) WHERE board.id={$iForumID}");
	list($strForumName, $iCategoryID, $strCategoryName) = $dbConn->getresult();

	// Get the information of each post and poster in this thread.
	$dbConn->query("SELECT post.id, post.author, post.datetime_posted, post.body, citizen.username FROM post LEFT JOIN citizen ON (post.author = citizen.id) WHERE post.parent={$iThreadID} ORDER BY post.datetime_posted ASC");
	while($aSQLResult = $dbConn->getresult(TRUE))
	{
		// Store the post information into the Master array.
		$iPostID = $aSQLResult['id'];
		$aPosts[$iPostID][AUTHOR] = $aSQLResult['author'];
		$aPosts[$iPostID][POSTDATE] = $aSQLResult['datetime_posted'];
		$aPosts[$iPostID][BODY] = substr($aSQLResult['body'], 0, 128);

		// Add some ellipses if the conditions are right.
		if(strlen($aSQLResult['body']) > 128)
		{
			if(strpos($aSQLResult['body'], ' ', 128))
			{
				$aPosts[$iPostID][BODY] = "{$aPosts[$iPostID][BODY]}...";
			}
		}

		// Store the member's username.
		if(!isset($aUsernames[$aPosts[$iPostID][AUTHOR]]))
		{
			$aUsernames[$aPosts[$iPostID][AUTHOR]] = $aSQLResult['username'];
		}
	}

	// Template
	require("./skins/{$CFG['skin']}/deleteposts.tpl.php");

	// Send the page.
	exit;
}

// Deletes specified posts.
function DeletePostsNow($iThreadID, $iForumID, $aPosts)
{
	global $dbConn;

	// Are there any posts to delete?
	if(!count($aPosts))
	{
		Msg("<b>No posts specified for deletion.</b><br /><br /><span class=\"smaller\">You should be redirected momentarily. Click <a href=\"thread.php?threadid={$iThreadID}\">here</a> if you do not want to wait any longer or if you are not redirected.</span>", "thread.php?threadid={$iThreadID}");
	}

	// Sanitize the elements of the array, and then convert it into a string.
	$strPostIDs = implode(', ', array_map('intval', $aPosts));
	unset($aPosts);

	// Filter out any posts that aren't in this thread.
	$dbConn->query("SELECT id, author FROM post WHERE id IN ({$strPostIDs}) AND parent={$iThreadID}");
	while(list($iPostID, $iAuthorID) = $dbConn->getresult())
	{
		// Save the post in the list to delete.
		$aPosts[] = $iPostID;

		// Increment the author's postcount of this thread.
		$aPostCounts[$iAuthorID]++;
	}

	// Are there still some posts to delete?
	if(!is_array($aPosts))
	{
		Msg("<b>No posts specified for deletion.</b><br /><br /><span class=\"smaller\">You should be redirected momentarily. Click <a href=\"thread.php?threadid={$iThreadID}\">here</a> if you do not want to wait any longer or if you are not redirected.</span>", "thread.php?threadid={$iThreadID}");
	}
	$strPostIDs = implode(', ', $aPosts);

	// Get the thread's root.
	$dbConn->query("SELECT post.id FROM post LEFT JOIN thread ON (post.parent = thread.id) WHERE thread.id={$iThreadID} ORDER BY post.datetime_posted ASC LIMIT 1");
	list($iRootID) = $dbConn->getresult();

	// Is one of the posts we'll be deleting the root post?
	// (Are we deleting the thread?)
	if(in_array($iRootID, $aPosts))
	{
		// Yes. Subtract from the users' postcounts the number of total posts they had in the thread.
		$dbConn->query("SELECT author, COUNT(post.id) FROM post WHERE post.parent={$iThreadID} GROUP BY author");
		$aSQLAllResults = $dbConn->getall();
		while(list($key, $aPostCount) = each($aSQLAllResults))
		{
			list($iAuthorID, $iPostCount) = $aPostCount;
			$dbConn->query("UPDATE citizen SET postcount=postcount-{$iPostCount} WHERE id={$iAuthorID}");

			// Add it to the total deleted postcount.
			$iTotalCount = $iTotalCount + $iPostCount;
		}

		// Delete the posts' records, the thread's record, and any attachments.
		$dbConn->query("DELETE FROM post WHERE parent={$iThreadID}");
		$dbConn->query("DELETE FROM thread WHERE id={$iThreadID}");
		$dbConn->query("DELETE FROM attachment WHERE parent IN ({$strPostIDs})");
		$dbConn->query("DELETE FROM poll WHERE id={$iThreadID}");
		$dbConn->query("DELETE FROM pollvote WHERE parent={$iThreadID}");

		// Delete all search indexes for the posts.
		$dbConn->query("DELETE FROM searchindex WHERE postid IN ({$strPostIDs})");

		// Update the forum's post and thread counts.
		$dbConn->query("UPDATE board SET postcount=postcount-{$iTotalCount}, threadcount=threadcount-1 WHERE id={$iForumID}");

		// Update the forum stats.
		$dbConn->query("UPDATE stats SET content=content-{$iTotalCount} WHERE name='postcount'");
		$dbConn->query("UPDATE stats SET content=content-1 WHERE name='threadcount'");

		// Set the redirect page.
		$strRedirect = "forumdisplay.php?forumid={$iForumID}";
	}
	else
	{
		// No. Subtract from the users' postcounts the number of posts they had in the thread which are marked for deletion.
		foreach($aPostCounts as $iAuthorID => $iPostCount)
		{
			$dbConn->query("UPDATE citizen SET postcount=postcount-{$iPostCount} WHERE id={$iAuthorID}");
		}

		// Get the number of attachments that we'll be deleting.
		$dbConn->query("SELECT COUNT(*) FROM attachment WHERE parent IN ({$strPostIDs})");
		list($iAttachmentCount) = $dbConn->getresult();

		// Delete the posts' record and any attachments.
		$dbConn->query("DELETE FROM post WHERE id IN ({$strPostIDs})");
		$dbConn->query("DELETE FROM attachment WHERE parent IN ({$strPostIDs})");

		// Delete all search indexes for the post.
		$dbConn->query("DELETE FROM searchindex WHERE postid IN ({$strPostIDs})");

		// Get the total number of posts we've deleted.
		$iPostCount = count($aPosts);

		// Update the forum's post count.
		$dbConn->query("UPDATE board SET postcount=postcount-{$iPostCount} WHERE id={$iForumID}");

		// Update the forum stats.
		$dbConn->query("UPDATE stats SET content=content-{$iPostCount} WHERE name='postcount'");

		// Get the post date and author ID of the new last post of the thread.
		$dbConn->query("SELECT post.datetime_posted, post.author FROM post LEFT JOIN thread ON (post.parent = thread.id) WHERE thread.id={$iThreadID} ORDER BY post.datetime_posted DESC LIMIT 1");
		list($tLastPost, $iNewLastPoster) = $dbConn->getresult();

		// Update the thread's record.
		$dbConn->query("UPDATE thread SET lpost={$tLastPost}, lposter={$iNewLastPoster}, postcount=postcount-{$iPostCount}, attachcount=attachcount-{$iAttachmentCount} WHERE id={$iThreadID}");

		// Set the redirect page.
		$strRedirect = "thread.php?threadid={$iThreadID}";
	}

	// Update the forum's statistics.
	UpdateForumStats($iForumID);

	// Render the page.
	Msg("<b>The posts have been successfully deleted.</b><br /><br /><span class=\"smaller\">You should be redirected momentarily. Click <a href=\"{$strRedirect}\">here</a> if you do not want to wait any longer or if you are not redirected.</span>", $strRedirect);
}

// *************************************************************************** \\

// User wants to move or copy the specified thread to another forum.
function MoveThread($iThreadID)
{
	global $CFG, $dbConn;

	// Do they have permission to move/copy threads?
	if(!$_SESSION['permissions']['cmovethreads'])
	{
		// No, so give them the bad news.
		Unauthorized();
	}

	// Are they submitting?
	if(isset($_REQUEST['submit']))
	{
		// What method do they want to use?
		switch((int)$_REQUEST['method'])
		{
			// Move thread
			case 0:
			{
				MoveThreadNow($iThreadID);
			}

			// Move thread with redirect
			case 1:
			{
				// TODO
				break;
			}

			// Copy thread
			case 2:
			{
				CopyThreadNow($iThreadID);
			}

			default:
			{
				Msg('You must specify whether to move or copy the thread.');
			}
		}
	}

	// Get the thread's title and its parent.
	$dbConn->query("SELECT title, parent FROM thread WHERE id={$iThreadID}");
	if(!(list($strThreadTitle, $iForumID) = $dbConn->getresult()))
	{
		Msg("Invalid thread specified.{$CFG['msg']['invalidlink']}");
	}

	// Get the information of each forum.
	list($aCategory, $aForum) = GetForumInfo();

	// Get the name of the category and forum the thread is in.
	$iCategoryID = $aForum[$iForumID][0];
	$strCategoryName = $aCategory[$iCategoryID];
	$strForumName = $aForum[$iForumID][1];

	// Template
	require("./skins/{$CFG['skin']}/movecopythread.tpl.php");

	// Send the page.
	exit;
}

// Moves the specified thread to another forum.
function MoveThreadNow($iThreadID)
{
	global $CFG, $dbConn;

	// What (destination) forum do they want to move it to?
	$iDestinationID = (int)$_REQUEST['forumid'];

	// Get the thread's parent (source) and its postcount.
	$dbConn->query("SELECT parent, postcount FROM thread WHERE id={$iThreadID}");
	if(!(list($iSourceID, $iPostCount) = $dbConn->getresult()))
	{
		Msg("Invalid thread specified.{$CFG['msg']['invalidlink']}");
	}

	// Make sure the destination isn't the same as the source.
	if($iSourceID == $iDestinationID)
	{
		Msg("You cannot move a thread to a forum it's already in.");
	}

	// Make sure the destination is valid (exists and isn't a category).
	$dbConn->query("SELECT displaydepth FROM board WHERE id={$iDestinationID}");
	if(!(list($iLevel) = $dbConn->getresult()))
	{
		Msg('The destination forum you specified does not exist.');
	}
	else if($iLevel == 0)
	{
		Msg('The destination forum you specified cannot contain posts. Please select a different forum.');
	}

	// Move the thread, i.e. change the thread's parent.
	$dbConn->query("UPDATE thread SET parent={$iDestinationID} WHERE id={$iThreadID}");

	// Update the forums' post and thread counts.
	$dbConn->query("UPDATE board SET postcount=postcount-{$iPostCount}, threadcount=threadcount-1 WHERE id={$iSourceID}");
	$dbConn->query("UPDATE board SET postcount=postcount+{$iPostCount}, threadcount=threadcount+1 WHERE id={$iDestinationID}");

	// Update the source and destination forums' stats.
	UpdateForumStats($iSourceID);
	UpdateForumStats($iDestinationID);

	// Render page.
	Msg("<b>The thread has been moved successfully.</b><br /><br /><span class=\"smaller\">You should be redirected momentarily. Click <a href=\"thread.php?threadid={$iThreadID}\">here</a> if you do not want to wait any longer or if you are not redirected.</span>", "thread.php?threadid={$iThreadID}");
}

// Copies the specified thread (and all its posts, attachments, and polls) to another forum.
// (Let me know if you have a better way of doing all of this. ;D)
function CopyThreadNow($iThreadID)
{
	global $CFG, $dbConn;

	// What forum do they want to copy it to?
	$iDestinationID = (int)$_REQUEST['forumid'];

	// Get the thread's information.
	$dbConn->query("SELECT * FROM thread WHERE thread.id={$iThreadID}");
	if(!($aThreadInfo = $dbConn->getresult(TRUE)))
	{
		Msg("Invalid thread specified.{$CFG['msg']['invalidlink']}");
	}

	// Make sure the destination isn't the same as the parent.
	if($aThreadInfo['parent'] == $iDestinationID)
	{
		Msg("You cannot copy a thread to a forum it's already in.");
	}

	// Make sure the destination is valid (exists and isn't a category).
	$dbConn->query("SELECT displaydepth FROM board WHERE id={$iDestinationID}");
	if(!(list($iLevel) = $dbConn->getresult()))
	{
		Msg('The destination forum you specified does not exist.');
	}
	else if($iLevel == 0)
	{
		Msg('The destination forum you specified cannot contain posts. Please select a different forum.');
	}

	// Get a list of the posts in the thread.
	$dbConn->query("SELECT id, author FROM post WHERE parent={$iThreadID}");
	while(list($iPostID, $iAuthorID) = $dbConn->getresult())
	{
		// Save the post to the list.
		$aPosts[] = $iPostID;

		// Increment the author's postcount of this thread.
		$aPostCounts[$iAuthorID]++;
	}
	$strPosts = implode(', ', $aPosts);

	// Sanitize some thread fields.
	$aThreadInfo['title'] = $dbConn->sanitize($aThreadInfo['title']);
	$aThreadInfo['description'] = $dbConn->sanitize($aThreadInfo['description']);
	$aThreadInfo['notes'] = $dbConn->sanitize($aThreadInfo['notes']);
	$aThreadInfo['poll'] = (int)(bool)$aThreadInfo['poll'];

	// Copy the thread record (but keep it closed and invisible until we're done).
	$dbConn->query("INSERT INTO thread(title, description, parent, viewcount, postcount, attachcount, lpost, lposter, icon, author, notes, poll, closed, visible, sticky) VALUES('{$aThreadInfo['title']}', '{$aThreadInfo['description']}', {$iDestinationID}, {$aThreadInfo['viewcount']}, {$aThreadInfo['postcount']}, {$aThreadInfo['attachcount']}, {$aThreadInfo['lpost']}, {$aThreadInfo['lposter']}, {$aThreadInfo['icon']}, {$aThreadInfo['author']}, '{$aThreadInfo['notes']}', {$aThreadInfo['poll']}, 1, 0, {$aThreadInfo['sticky']})");

	// What is the ID of the thread we just created?
	$iNewThreadID = $dbConn->getinsertid('thread');

	// Get a list of the attachments in this thread.
	$dbConn->query("SELECT id, parent FROM attachment WHERE parent IN ({$strPosts})");
	while(list($iAttachmentID, $iParentID) = $dbConn->getresult())
	{
		$aAttachments[$iAttachmentID] = $iParentID;
	}

	// Copy all of the post records.
	$dbConn->query("SELECT * FROM post WHERE parent={$iThreadID}");
	$aSQLAllResults = $dbConn->getall(TRUE);
	while(list($key, $aPostInfo) = each($aSQLAllResults))
	{
		// Convert data types, if necessary.
		if($aPostInfo['datetime_edited'] === NULL)
		{
			$aPostInfo['datetime_edited'] = 'NULL';
		}
		if($aPostInfo['ipaddress'] === NULL)
		{
			$aPostInfo['ipaddress'] = 'NULL';
		}

		// Sanitize some post fields.
		$strTitle = $aPostInfo['title'];
		$strBody = $aPostInfo['body'];
		$aPostInfo['title'] = $dbConn->sanitize($aPostInfo['title']);
		$aPostInfo['body'] = $dbConn->sanitize($aPostInfo['body']);

		// Insert the copy of the post.
		$dbConn->query("INSERT INTO post(author, datetime_posted, datetime_edited, title, body, parent, ipaddress, icon, dsmilies) VALUES({$aPostInfo['author']}, {$aPostInfo['datetime_posted']}, {$aPostInfo['datetime_edited']}, '{$aPostInfo['title']}', '{$aPostInfo['body']}', {$iNewThreadID}, {$aPostInfo['ipaddress']}, {$aPostInfo['icon']}, {$aPostInfo['dsmilies']})");

		// Get the ID of the post we just created.
		$iPostID = $dbConn->getinsertid('post');

		// Now let's add the message into the search engine index.
		AddSearchIndex($iPostID, $strTitle, $strBody);

		// Store the attachments the post has (if any).
		if(is_array($aAttachments))
		{
			// Did the original post contain any attachments?
			$temp = array_keys($aAttachments, $aPostInfo['id']);
			if(is_array($temp))
			{
				// Yes, so save them.
				foreach($temp as $iAttachmentID)
				{
					$aAttachments[$iAttachmentID] = $iPostID;
				}
			}
		}
	}

	// Copy all of the attachment records.
	if(is_array($aAttachments))
	{
		$dbConn->query("SELECT * FROM attachment WHERE parent IN ({$strPosts})");
		$aSQLAllResults = $dbConn->getall(TRUE);
		while(list($key, $aAttachmentInfo) = each($aSQLAllResults))
		{
			// What is the new post ID that will be the parent of this new attachment?
			$iParentID = $aAttachments[$aAttachmentInfo['id']];

			// Break the file data into an array of 64KB strings.
			$aData = sqlsplit($aAttachmentInfo['filedata'], 65536);

			// Sanitize the filename.
			$aAttachmentInfo['filename'] = $dbConn->sanitize($aAttachmentInfo['filename']);

			// Insert the duplicate attachment's record.
			$dbConn->query("INSERT INTO attachment(filename, filedata, viewcount, parent) VALUES('{$aAttachmentInfo['filename']}', '{$aData[0]}', {$aAttachmentInfo['viewcount']}, {$iParentID})");
			$iAttachmentID = $dbConn->getinsertid('attachment');
			unset($aData[0]);

			// Insert the rest of the attachment's data.
			foreach($aData as $strData)
			{
				$dbConn->squery(CONCAT_ATTACHMENT, $strData, $iAttachmentID);
			}

			// Reset the data array for the next attachment.
			unset($aData);
		}
	}

	// Copy the poll, if there is one.
	if($aThreadInfo['poll'])
	{
		CopyPoll($iThreadID, $iNewThreadID);
	}

	// Add to the users' postcounts the number of posts they had in the original thread.
	foreach($aPostCounts as $iAuthorID => $iPostCount)
	{
		$dbConn->query("UPDATE citizen SET postcount=postcount+{$iPostCount} WHERE id={$iAuthorID}");
	}

	// Give the new thread life.
	$dbConn->query("UPDATE thread SET closed={$aThreadInfo['closed']}, visible={$aThreadInfo['visible']} WHERE id={$iNewThreadID}");

	// Get the total number of posts we've copied/added.
	$iPostCount = count($aPosts);

	// Update the destination forum's post and thread counts.
	$dbConn->query("UPDATE board SET postcount=postcount+{$iPostCount}, threadcount=threadcount+1 WHERE id={$iDestinationID}");

	// Update the forum stats.
	$dbConn->query("UPDATE stats SET content=content+{$iPostCount} WHERE name='postcount'");
	$dbConn->query("UPDATE stats SET content=content+1 WHERE name='threadcount'");

	// Update the destination forum's stats.
	UpdateForumStats($iDestinationID);

	// Render page.
	Msg("<b>The thread has been copied successfully.</b><br /><br /><span class=\"smaller\">You should be redirected momentarily. Click <a href=\"thread.php?threadid={$iNewThreadID}\">here</a> if you do not want to wait any longer or if you are not redirected.</span>", "thread.php?threadid={$iNewThreadID}");
}

// Copies a poll (and all votes) of a thread to another thread.
function CopyPoll($iSourceID, $iDestinationID)
{
	global $dbConn;

	// Get the poll's information.
	$dbConn->query("SELECT * FROM poll WHERE id={$iSourceID}");
	$aPollInfo = $dbConn->getresult(TRUE);

	// Sanitize the poll's information.
	$aPollInfo['question'] = $dbConn->sanitize($aPollInfo['question']);
	$aPollInfo['answers'] = $dbConn->sanitize($aPollInfo['answers']);

	// Insert a copy of the poll's record.
	$dbConn->query("INSERT INTO poll(id, datetime, question, answers, multiplechoices, timeout) VALUES({$iDestinationID}, {$aPollInfo['datetime']}, '{$aPollInfo['question']}', '{$aPollInfo['answers']}', {$aPollInfo['multiplechoices']}, {$aPollInfo['timeout']})");

	// Now we need to copy all of the votes for the poll.
	$dbConn->query("SELECT * FROM pollvote WHERE parent={$iSourceID}");
	$aSQLAllResults = $dbConn->getall(TRUE);
	while(list($key, $aPollInfo) = each($aSQLAllResults))
	{
		$dbConn->query("INSERT INTO pollvote(parent, ownerid, vote, votedate) VALUES({$iDestinationID}, {$aPollInfo['ownerid']}, {$aPollInfo['vote']}, {$aPollInfo['votedate']})");
	}
}

// *************************************************************************** \\

// Gets the IP of a specified post or private message.
function GetIP()
{
	global $CFG, $dbConn;

	// Are they authorized to view IP addresses?
	if(!$_SESSION['permissions']['cviewips'])
	{
		// No, so give them the bad news.
		Unauthorized();
	}

	// What do they want to get an IP address of?
	if(isset($_REQUEST['postid']))
	{
		// Post
		$iPostID = (int)$_REQUEST['postid'];
		$strWhat = 'post';

		// Get the IP address and thread ID of the post.
		$dbConn->query("SELECT ipaddress, parent FROM post WHERE id={$iPostID}");
		if(!(list($iIP, $iThreadID) = $dbConn->getresult()))
		{
			// Invalid post specified.
			Msg("Invalid post specified.{$CFG['msg']['invalidlink']}");
		}
		$strIP = long2ip($iIP);
		$strBackURL = "thread.php?threadid={$iThreadID}&postid={$iPostID}#post{$iPostID}";
	}
	else if(isset($_REQUEST['messageid']))
	{
		// Private message
		$iMessageID = (int)$_REQUEST['messageid'];
		$strWhat = 'PM';
		$strBackURL = "private.php?action=viewmessage&id={$iMessageID}";

		// Get the IP address of the PM.
		$dbConn->query("SELECT ipaddress FROM pm WHERE id={$iMessageID}");
		if(!(list($iIP) = $dbConn->getresult()))
		{
			// Invalid PM specified.
			Msg("Invalid PM specified.{$CFG['msg']['invalidlink']}");
		}
		$strIP = long2ip($iIP);
	}
	else
	{
		// Nothing was specified.
		Msg('You must specify a post or PM for which to get an IP address.');
	}

	// Was there an IP address stored with the post/PM?
	if($iIP === NULL)
	{
		// Nope.
		Msg("No IP address was stored with the specified {$strWhat}. If you believe this is an error, please notify the <a href=\"mailto:{$CFG['general']['admin']['email']}\">Webmaster</a>.");
	}

	// Get the information of each forum.
	list($aCategory, $aForum) = GetForumInfo();

	// Template
	require("./skins/{$CFG['skin']}/getip.tpl.php");

	// Send the page.
	exit;
}
?>