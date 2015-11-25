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
	define('AUTHOR',       0);
	define('DT_POSTED',    1);
	define('DT_EDITED',    2);
	define('TITLE',        3);
	define('BODY',         4);
	define('ICON',         5);
	define('DSMILIES',     6);
	define('LOGGEDIP',     7);
	define('PARENT',       8);
	define('POSTCOUNT',    9);
	define('ATTACHCOUNT',  10);
	define('POLL',         11);
	define('CLOSED',       12);
	define('VISIBLE',      13);
	define('STICKY',       14);
	define('NOTES',        15);
	define('USERNAME',     16);
	define('JOINDATE',     17);
	define('RESIDENCE',    18);
	define('SIGNATURE',    19);
	define('WWW',          20);
	define('LASTACTIVE',   21);
	define('ONLINE',       22);
	define('INVISIBLE',    23);

	// What do they want to do?
	switch($_REQUEST['action'])
	{
		case 'showpost':
		{
			ShowPost();
		}

		case 'showthread':
		default:
		{
			ShowThread();
		}
	}

// *************************************************************************** \\

// Displays a post.
function ShowPost()
{
	global $CFG, $dbConn, $aViewedThreads, $aPostIcons, $aGroup;

	// What post do they want?
	$iPostID = (int)$_REQUEST['postid'];

	// Get the post's information.
	$dbConn->query("SELECT author, datetime_posted, datetime_edited, title, body, icon, dsmilies, ipaddress, parent FROM post WHERE id={$iPostID}");
	if(!($aSQLResult = $dbConn->getresult()))
	{
		Msg("Invalid post specified.{$CFG['msg']['invalidlink']}");
	}

	// Store the post's information.
	$aPost[AUTHOR] = $aSQLResult[0];
	$aPost[DT_POSTED] = $aSQLResult[1];
	$aPost[DT_EDITED] = $aSQLResult[2];
	$aPost[TITLE] = $aSQLResult[3];
	$aPost[BODY] = $aSQLResult[4];
	$aPost[ICON] = $aSQLResult[5];
	$aPost[DSMILIES] = $aSQLResult[6];
	$aPost[LOGGEDIP] = ($aSQLResult[7] === NULL) ? FALSE : TRUE;
	$aPost[PARENT] = $aSQLResult[8];

	// Get the author's information.
	$dbConn->query("SELECT username, datejoined, title, signature, residence, website, lastactive, loggedin, postcount, invisible, usergroup FROM citizen WHERE id={$aPost[AUTHOR]}");
	$aSQLResult = $dbConn->getresult();

	// Store author's information.
	$aAuthor[USERNAME] = $aSQLResult[0];
	$aAuthor[JOINDATE] = $aSQLResult[1];
	$aAuthor[TITLE] = ($aSQLResult[2]) ? $aSQLResult[2] : $aGroup[$aSQLResult[10]]['usertitle'];
	$aAuthor[SIGNATURE] = $aSQLResult[3];
	$aAuthor[RESIDENCE] = $aSQLResult[4];
	$aAuthor[WWW] = $aSQLResult[5];
	$aAuthor[LASTACTIVE] = $aSQLResult[6];
	$aAuthor[ONLINE] = $aSQLResult[7];
	$aAuthor[POSTCOUNT] = $aSQLResult[8];
	$aAuthor[INVISIBLE] = (bool)$aSQLResult[9];

	// Get the information of any attachments.
	$dbConn->query("SELECT id, filename, viewcount FROM attachment WHERE parent={$iPostID}");
	while(list($iAttachmentID, $strFilename, $iViewCount) = $dbConn->getresult())
	{
		// Store the attachments' information into the Attachments array.
		$aAttachments[$iAttachmentID][0] = $strFilename;
		$aAttachments[$iAttachmentID][1] = $iViewCount;
	}

	// Get the thread's information.
	$dbConn->query("SELECT title, parent, visible FROM thread WHERE id={$aPost[PARENT]}");
	list($strThreadTitle, $iForumID, $bVisible) = $dbConn->getresult();

	// Is the thread visible?
	if(!$bVisible)
	{
		// Nope.
		Msg("Invalid post specified.{$CFG['msg']['invalidlink']}");
	}

	// Get our forum name as well as the ID and name of the category we belong to.
	list($aCategory, $aBoard) = GetForumInfo();
	$iCategoryID = $aBoard[$iForumID][0];
	$strCategoryName = $aCategory[$iCategoryID];
	$strForumName = $aBoard[$iForumID][1];

	// Update the user's last visit of this post's thread.
	$tLastViewed = isset($aViewedThreads[$aPost[PARENT]]) ? $aViewedThreads[$aPost[PARENT]] : $_SESSION['lastactive'];

	// Template
	require("./skins/{$CFG['skin']}/post.tpl.php");

	// Send the page.
	exit;
}

// *************************************************************************** \\

// Displays a thread.
function ShowThread()
{
	global $CFG, $dbConn, $aViewedThreads, $aPostIcons, $aGroup;

	// What thread do they want?
	$iThreadID = (int)$_REQUEST['threadid'];

	// How many posts per page do they want to view?
	$iPostsPerPage = (int)$_REQUEST['perpage'];
	if($iPostsPerPage < 1)
	{
		// They don't know what they want. Use their value.
		$iPostsPerPage = $_SESSION['postsperpage'];
	}

	// What page do they want to view?
	$iPage = (int)$_REQUEST['page'];
	if($iPage < 1)
	{
		// They don't know what they want. Give them the first page.
		$iPage = 1;
	}

	// Calculate the offset.
	$iOffset = ($iPage * $iPostsPerPage) - $iPostsPerPage;

	// Get the thread's information.
	$dbConn->query("SELECT title, parent, postcount, attachcount, poll, closed, visible, sticky, notes FROM thread WHERE id={$iThreadID}");
	if(!($aSQLResult = $dbConn->getresult()))
	{
		Msg("Invalid thread specified.{$CFG['msg']['invalidlink']}");
	}

	// Store the thread's information.
	$aThread[TITLE] = $aSQLResult[0];
	$aThread[PARENT] = $aSQLResult[1];
	$aThread[POSTCOUNT] = $aSQLResult[2];
	$aThread[ATTACHCOUNT] = $aSQLResult[3];
	$aThread[POLL] = $aSQLResult[4];
	$aThread[CLOSED] = $aSQLResult[5];
	$aThread[VISIBLE] = $aSQLResult[6];
	$aThread[STICKY] = $aSQLResult[7];
	$aThread[NOTES] = $aSQLResult[8];

	// Is the thread visible?
	if(!$aThread[VISIBLE])
	{
		// No.
		Msg("Invalid thread specified.{$CFG['msg']['invalidlink']}");
	}

	// Calculate the number of pages this thread is made of.
	$iNumberPages = ceil($aThread[POSTCOUNT] / $iPostsPerPage);

	// Is the page they asked for out of range?
	if($iPage > $iNumberPages)
	{
		// Yes, give them the last page and recalculate the offset.
		$iPage = $iNumberPages;
		$iOffset = ($iPage * $iPostsPerPage) - $iPostsPerPage;
	}

	// Do they want to go to a specific post?
	if(isset($_REQUEST['postid']))
	{
		$iPostID = (int)$_REQUEST['postid'];

		// Get the page the post is on.
		$dbConn->query("SELECT id FROM post WHERE parent={$iThreadID} ORDER BY datetime_posted");
		for($iPosition = 1; list($iPost) = $dbConn->getresult(); $iPosition++)
		{
			if($iPost == $iPostID) break;
		}

		// Is the post in this thread?
		if($iPosition <= $aThread[POSTCOUNT])
		{
			// Yes, reset the page and recalculate the offset.
			$iPage = ceil($iPosition / $iPostsPerPage);
			$iOffset = ($iPage * $iPostsPerPage) - $iPostsPerPage;
		}
	}

	// Do they want to go to the first newest post?
	else if($_REQUEST['goto'] == 'newest')
	{
		// Yes, so set what the minimum newest post time is.
		$tNewest = isset($aViewedThreads[$iThreadID]) ? $aViewedThreads[$iThreadID] : $_SESSION['lastactive'];

		// Get the newest post's ID.
		$dbConn->query("SELECT id FROM post WHERE parent={$iThreadID} AND datetime_posted > {$tNewest} ORDER BY datetime_posted LIMIT 1");
		if(list($iPostID) = $dbConn->getresult())
		{
			// Redirect the user to the newest post.
			$strSID = SID ? '&'.SID : '';
			header("Location: thread.php?threadid={$iThreadID}&postid={$iPostID}{$strSID}#post{$iPostID}");
			exit;
		}
	}

	// Get the information of all the categories and forums.
	list($aCategories, $aBoards) = GetForumInfo();

	// Save our forum name, as well as the ID and name of the category we belong to.
	$iCategoryID = $aBoards[$aThread[PARENT]][0];
	$strCategoryName = $aCategories[$iCategoryID];
	$strForumName = $aBoards[$aThread[PARENT]][1];

	// Get the information of each post and poster in this thread.
	$dbConn->query("SELECT post.id, post.author, post.datetime_posted, post.datetime_edited, post.title AS ptitle, post.body, post.icon, post.dsmilies, post.ipaddress, citizen.username, citizen.datejoined, citizen.title AS mtitle, citizen.signature, citizen.residence, citizen.website, citizen.lastactive, citizen.loggedin, citizen.postcount, citizen.usergroup, citizen.invisible FROM post LEFT JOIN citizen ON (post.author = citizen.id) WHERE post.parent={$iThreadID} ORDER BY post.datetime_posted ASC LIMIT {$iPostsPerPage} OFFSET {$iOffset}");
	while($aSQLResult = $dbConn->getresult(TRUE))
	{
		// Store the post information.
		$iPostID = $aSQLResult['id'];
		$aPosts[$iPostID][AUTHOR] = $aSQLResult['author'];
		$aPosts[$iPostID][DT_POSTED] = $aSQLResult['datetime_posted'];
		$aPosts[$iPostID][DT_EDITED] = $aSQLResult['datetime_edited'];
		$aPosts[$iPostID][TITLE] = $aSQLResult['ptitle'];
		$aPosts[$iPostID][BODY] = $aSQLResult['body'];
		$aPosts[$iPostID][ICON] = $aSQLResult['icon'];
		$aPosts[$iPostID][DSMILIES] = $aSQLResult['dsmilies'];
		$aPosts[$iPostID][LOGGEDIP] = ($aSQLResult['ipaddress'] === NULL) ? FALSE : TRUE;

		// Store member's information into the Users array.
		if(!isset($aUsers[$aSQLResult['author']]))
		{
			$aUsers[$aPosts[$iPostID][AUTHOR]][USERNAME] = $aSQLResult['username'];
			$aUsers[$aPosts[$iPostID][AUTHOR]][JOINDATE] = $aSQLResult['datejoined'];
			$aUsers[$aPosts[$iPostID][AUTHOR]][TITLE] = ($aSQLResult['mtitle']) ? $aSQLResult['mtitle'] : $aGroup[$aSQLResult['usergroup']]['usertitle'];
			$aUsers[$aPosts[$iPostID][AUTHOR]][RESIDENCE] = $aSQLResult['residence'];
			$aUsers[$aPosts[$iPostID][AUTHOR]][SIGNATURE] = $aSQLResult['signature'];
			$aUsers[$aPosts[$iPostID][AUTHOR]][WWW] = $aSQLResult['website'];
			$aUsers[$aPosts[$iPostID][AUTHOR]][LASTACTIVE] = $aSQLResult['lastactive'];
			$aUsers[$aPosts[$iPostID][AUTHOR]][ONLINE] = $aSQLResult['loggedin'];
			$aUsers[$aPosts[$iPostID][AUTHOR]][POSTCOUNT] = $aSQLResult['postcount'];
			$aUsers[$aPosts[$iPostID][AUTHOR]][INVISIBLE] = (bool)$aSQLResult['invisible'];
		}
	}

	// Get the information of any attachments.
	if($aThread[ATTACHCOUNT])
	{
		$dbConn->query("SELECT post.id AS parent, attachment.id, attachment.filename, attachment.viewcount FROM post INNER JOIN attachment ON (attachment.parent = post.id) WHERE post.parent={$iThreadID}");
		while(list($iPostID, $iAttachmentID, $strFilename, $iViewCount) = $dbConn->getresult())
		{
			// Store the attachments' information into the Attachments array.
			$aAttachments[$iPostID][$iAttachmentID][0] = $strFilename;
			$aAttachments[$iPostID][$iAttachmentID][1] = $iViewCount;
		}
	}

	// Tally the votes if we have a poll.
	if($aThread[POLL])
	{
		// Get the poll information.
		$iPollID = $iThreadID;
		$dbConn->query("SELECT question, answers, multiplechoices, timeout, datetime FROM poll WHERE id={$iPollID}");
		list($strPollQuestion, $strPollAnswers, $bMultipleChoices, $iTimeout, $tPosted) = $dbConn->getresult();
		$aPollAnswers = unserialize($strPollAnswers);
		$bClosed = ($iTimeout && ($CFG['globaltime'] > ($tPosted + ($iTimeout * 86400)))) ? TRUE : FALSE;

		// Get the votes.
		$dbConn->query("SELECT ownerid, vote FROM pollvote WHERE parent={$iPollID}");
		while(list($iOwnerID, $iVote) = $dbConn->getresult())
		{
			// Tally the vote.
			$aVotes[$iVote]++;

			// Increment the vote counter.
			$iVoteCount++;

			// Is this our vote?
			if($iOwnerID == $_SESSION['userid'])
			{
				// Yes.
				$bHasVoted = TRUE;
			}
		}
	}

	// Add to the thread's viewcount.
	$dbConn->query("UPDATE thread SET viewcount=viewcount+1 WHERE id={$iThreadID}");

	// Update the user's last visit of this thread.
	$tLastViewed = isset($aViewedThreads[$iThreadID]) ? $aViewedThreads[$iThreadID] : $_SESSION['lastactive'];

	// Update the user's viewed threads cookie.
	$aViewedThreads[$iThreadID] = $CFG['globaltime'];
	setcookie('viewedthreads', base64_encode(serialize($aViewedThreads)), 0, $CFG['paths']['cookies']);

	// Template
	require("./skins/{$CFG['skin']}/thread.tpl.php");

	// Send the page.
	exit;
}
?>