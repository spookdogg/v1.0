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
	define('NAME',          0);
	define('DESCRIPTION',   1);
	define('DISPLAYDEPTH',  2);
	define('PARENT',        3);
	define('LPOST',         4);
	define('LPOSTER',       5);
	define('LTHREAD',       6);
	define('LTHREADPCOUNT', 7);
	define('POSTCOUNT',     8);
	define('THREADCOUNT',   9);
	define('TITLE',         10);
	define('AUTHOR',        11);
	define('VIEWCOUNT',     12);
	define('ICON',          13);
	define('ATTACHCOUNT',   14);
	define('HASPOLL',       15);
	define('ISSTICKY',      16);
	define('ISOPEN',        17);
	define('PARENTNAME',    18);
	define('NEWPOSTS',      19);

	// What forum do they want?
	$iForumID = (int)$_REQUEST['forumid'];

	// Get the forums' information.
	$dbConn->query("SELECT id, name, description, displaydepth, parent, lpost, lposter, lthread, lthreadpcount, postcount, threadcount FROM board ORDER BY disporder ASC");
	while($aSQLResult = $dbConn->getresult())
	{
		// Is this a 'Level 0' or a 'Level 1' forum?
		switch($aSQLResult[3])
		{
			// Level 0: Category
			case 0:
			{
				// Store the category information into the Category array.
				$aCategories[$aSQLResult[0]][NAME] = $aSQLResult[1];
				$aCategories[$aSQLResult[0]][DESCRIPTION] = $aSQLResult[2];
				break;
			}

			// Level 1: Forum
			case 1:
			{
				// Store the forum information into the Forum array.
				$aForums[$aSQLResult[0]][NAME] = $aSQLResult[1];
				$aForums[$aSQLResult[0]][DESCRIPTION] = $aSQLResult[2];
				$aForums[$aSQLResult[0]][PARENT] = $aSQLResult[4];
				$aForums[$aSQLResult[0]][LPOST] = $aSQLResult[5];
				$aForums[$aSQLResult[0]][LPOSTER] = $aSQLResult[6];
				$aForums[$aSQLResult[0]][LTHREAD] = $aSQLResult[7];
				$aForums[$aSQLResult[0]][LTHREADPCOUNT] = $aSQLResult[8];
				$aForums[$aSQLResult[0]][POSTCOUNT] = $aSQLResult[9];
				$aForums[$aSQLResult[0]][THREADCOUNT] = $aSQLResult[10];
				break;
			}
		}
	}

	// Free memory.
	unset($aSQLResult);

	// Display the forum.
	if(isset($aCategories[$iForumID]))
	{
		DisplayCategory($iForumID, $aCategories[$iForumID]);
	}
	else if(isset($aForums[$iForumID]))
	{
		DisplayForum($iForumID, $aForums[$iForumID]);
	}

	// They didn't specify a valid forum ID.
	Msg("Invalid forum specified.{$CFG['msg']['invalidlink']}");

// *************************************************************************** \\

// Renders the regular forum display.
function DisplayForum($iForumID, $aForum)
{
	global $CFG, $dbConn, $aViewedThreads, $aCategories, $aForums, $aPostIcons;
	$aUsers = array();

	// Get the name of the forum's parent.
	$aForum[PARENTNAME] = $aCategories[$aForum[PARENT]][NAME];

	// Get the user's view settings.
	$iThreadsPerPage = $_SESSION['threadsperpage'];
	$iPostsPerPage = $_SESSION['postsperpage'];
	$iDaysPrune = $_SESSION['threadview'];

	// User-specified values take precedence.
	if(isset($_REQUEST['perpage']) && (int)$_REQUEST['perpage'])
	{
		$iThreadsPerPage = abs($_REQUEST['perpage']);
	}
	if(isset($_REQUEST['daysprune']) && (int)$_REQUEST['daysprune'])
	{
		$iDaysPrune = abs($_REQUEST['daysprune']);
	}

	// What page do they want to view?
	$iPage = (int)$_REQUEST['page'];
	if($iPage < 1)
	{
		// They don't know what they want. Give them the first page.
		$iPage = 1;
	}

	// What do they want to sort by?
	$strSortBy = strtolower($_REQUEST['sortby']);
	switch($strSortBy)
	{
		// They specified us something valid.
		case 'lpost':
		case 'title':
		case 'postcount':
		case 'viewcount':
		case 'author':
		{
			break;
		}

		// They don't know what they want. We'll sort by last post.
		default:
		{
			$strSortBy = 'lpost';
			break;
		}
	}

	// What order do they want it sorted in?
	$strSortOrder = strtoupper($_REQUEST['sortorder']);
	if(($strSortOrder != 'ASC') && ($strSortOrder != 'DESC'))
	{
		// They don't know what they want. Are they sorting by last post?
		if($strSortBy == 'lpost')
		{
			// Yes, we'll sort descending.
			$strSortOrder = 'DESC';
		}
		else
		{
			// No, we'll sort ascending.
			$strSortOrder = 'ASC';
		}
	}

	// Prune by days?
	if($iDaysPrune != 1000)
	{
		$tDays = $CFG['globaltime'] - ($iDaysPrune * 86400);
		$strDaysPrune = " AND (lpost >= {$tDays} OR sticky=1)";
	}

	// Calculate the offset.
	$iOffset = ($iPage * $iThreadsPerPage) - $iThreadsPerPage;

	// Get the number of our threads, so we can calculate the number of pages. Are we pruning any days?
	if($iDaysPrune == 1000)
	{
		// No, so just get the cached threadcount.
		$aForum[THREADCOUNT] = $aForums[$iForumID][THREADCOUNT];
	}
	else
	{
		// Yes, so get the threadcount taking into account the days pruned.
		$dbConn->query("SELECT COUNT(*) FROM thread WHERE parent={$iForumID} AND visible=1{$strDaysPrune}");
		list($aForum[THREADCOUNT]) = $dbConn->getresult();
	}

	// Calculate the number of pages this forum contains.
	$iNumberPages = ceil($aForum[THREADCOUNT] / $iThreadsPerPage);

	// Is the page they asked for out of range?
	if($iPage > $iNumberPages)
	{
		// Yes, so give them the last page and recalculate the offset.
		$iPage = $iNumberPages;
		$iOffset = ($iPage * $iThreadsPerPage) - $iThreadsPerPage;
	}

	// Which threads are we showing from and to?
	$iShowFrom = $iOffset + 1;
	if($aForum[THREADCOUNT] < $iThreadsPerPage)
	{
		$iShowTo = $aForum[THREADCOUNT];
	}
	else
	{
		$iShowTo = $iOffset + $iThreadsPerPage;
	}

	// Are there any threads in this forum?
	if($aForum[THREADCOUNT])
	{
		// Yes, get the information of each one.
		$dbConn->query("SELECT id, title, description, icon, author, postcount, viewcount, lpost, lposter, attachcount, poll, sticky, closed FROM thread WHERE parent={$iForumID}{$strDaysPrune} AND visible=1 ORDER BY sticky DESC, {$strSortBy} {$strSortOrder}, id {$strSortOrder} LIMIT {$iThreadsPerPage} OFFSET {$iOffset}");
		while($aSQLResult = $dbConn->getresult())
		{
			// Store the thread information into the array.
			$iThreadID = $aSQLResult[0];
			$aThreads[$iThreadID][NAME] = $aSQLResult[1];
			$aThreads[$iThreadID][DESCRIPTION] = $aSQLResult[2];
			$aThreads[$iThreadID][AUTHOR] = $aSQLResult[4];
			$aThreads[$iThreadID][POSTCOUNT] = $aSQLResult[5];
			$aThreads[$iThreadID][VIEWCOUNT] = $aSQLResult[6];
			$aThreads[$iThreadID][LPOST] = $aSQLResult[7];
			$aThreads[$iThreadID][LPOSTER] = $aSQLResult[8];
			$aThreads[$iThreadID][ICON][0] = "{$CFG['paths']['posticons']}{$aPostIcons[$aSQLResult[3]]['filename']}";
			$aThreads[$iThreadID][ICON][1] = $aPostIcons[$aSQLResult[3]]['title'];
			$aThreads[$iThreadID][ATTACHCOUNT] = $aSQLResult[9];
			$aThreads[$iThreadID][HASPOLL] = $aSQLResult[10];
			$aThreads[$iThreadID][ISSTICKY] = $aSQLResult[11];
			$aThreads[$iThreadID][ISOPEN] = !$aSQLResult[12];
			$tLastViewed = isset($aViewedThreads[$iThreadID]) ? $aViewedThreads[$iThreadID] : $_SESSION['lastactive'];
			$aThreads[$iThreadID][NEWPOSTS] = ($tLastViewed < $aSQLResult[7]) ? TRUE : FALSE;

			// Add the author and last poster to our list of users to get names for.
			$aUsers[] = $aSQLResult[4];
			$aUsers[] = $aSQLResult[8];
		}

		// Free up the memory.
		unset($aSQLResult);
	}

	// Get any needed usernames.
	$aUsernames = GetUsernames($aUsers);
	unset($aUsers);

	// Template
	require("./skins/{$CFG['skin']}/displayforum.tpl.php");

	// Send the page.
	exit;
}

// *************************************************************************** \\

// Renders the category display.
function DisplayCategory($iCategoryID, $aCategory)
{
	global $CFG, $dbConn, $aViewedThreads, $aCategories, $aForums;
	$aUsers = array();

	// Get the user's per-page settings.
	$iThreadsPerPage = $_SESSION['threadsperpage'];
	$iPostsPerPage = $_SESSION['postsperpage'];

	// Build a list of our children forums.
	foreach($aForums as $iForumID => $aForum)
	{
		// Is this forum our child?
		if($aForum[PARENT] == $iCategoryID)
		{
			// Yes, so save the forum to the list.
			$aChildren[$iForumID] = $aForum;

			// Add the last poster to our list of users to get names for.
			if($aForum[LPOSTER])
			{
				$aUsers[] = $aForum[LPOSTER];
			}
		}
	}

	// Get any needed usernames.
	$aUsernames = GetUsernames($aUsers);
	unset($aUsers);

	// Get any new posts.
	$strForums = implode(', ', array_keys($aChildren));
	$dbConn->query("SELECT post.id, post.datetime_posted, post.parent, thread.parent FROM post LEFT JOIN thread ON (thread.id = post.parent) WHERE thread.parent IN ($strForums) AND datetime_posted > {$_SESSION['lastactive']} ORDER BY datetime_posted DESC");
	while(list($iPostID, $tPosted, $iThreadID, $iParentID) = $dbConn->getresult())
	{
		// If we've read the thread, have we read it since the post was made?
		if(!isset($aViewedThreads[$iThreadID]) || ($aViewedThreads[$iThreadID] < $tPosted))
		{
			// No, so there are unread posts for that forum.
			$aNewPosts[$iParentID] = TRUE;
		}
	}

	// Template
	require("./skins/{$CFG['skin']}/displaycategory.tpl.php");

	// Send the page.
	exit;
}
?>