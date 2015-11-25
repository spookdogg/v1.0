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
	define('NAME',           0);
	define('DESCRIPTION',    1);
	define('POSTCOUNT',      2);
	define('THREADCOUNT',    3);
	define('LPOST',          4);
	define('LPOSTER',        5);
	define('LTHREAD',        6);
	define('LTHREADPCOUNT',  7);
	define('CHILDREN',  2);

	// Get the user's posts-per-page value to compute last page values.
	$iPostsPerPage = $_SESSION['postsperpage'];

	// Forum statistics: Get the newest member; get the total number of members, threads, and posts.
	$dbConn->query("SELECT name, content FROM stats");
	while(list($strName, $strValue) = $dbConn->getresult())
	{
		$aStats[$strName] = $strValue;
	}

	// Add the newest member to the list of users to get names for.
	if(isset($aStats['newestmember']))
	{
		$aUserIDs[] = $aStats['newestmember'];
	}

	// Get the information of each forum.
	$dbConn->query("SELECT id, parent, name, description, postcount, threadcount, lpost, lposter, lthread, lthreadpcount, displaydepth FROM board WHERE displaydepth IN (0, 1) ORDER BY displaydepth ASC, disporder ASC");
	while($aSQLResult = $dbConn->getresult())
	{
		// Is this a 'Level 0' or a 'Level 1' forum?
		switch($aSQLResult[10])
		{
			// Level 0: Category
			case 0:
			{
				// Store the category information into the Category array.
				$iCategoryID = $aSQLResult[0];
				$aCategories[$iCategoryID][NAME] = $aSQLResult[2];
				$aCategories[$iCategoryID][DESCRIPTION] = $aSQLResult[3];
				break;
			}

			// Level 1: Forum
			case 1:
			{
				// Store the forum information into the Forum array.
				$iForumID = $aSQLResult[0];
				$iCategoryID = $aSQLResult[1];
				$aCategories[$iCategoryID][CHILDREN][$iForumID][NAME] = $aSQLResult[2];
				$aCategories[$iCategoryID][CHILDREN][$iForumID][DESCRIPTION] = $aSQLResult[3];
				$aCategories[$iCategoryID][CHILDREN][$iForumID][POSTCOUNT] = $aSQLResult[4];
				$aCategories[$iCategoryID][CHILDREN][$iForumID][THREADCOUNT] = $aSQLResult[5];
				$aCategories[$iCategoryID][CHILDREN][$iForumID][LPOST] = $aSQLResult[6];
				$aCategories[$iCategoryID][CHILDREN][$iForumID][LPOSTER] = $aSQLResult[7];
				$aCategories[$iCategoryID][CHILDREN][$iForumID][LTHREAD] = $aSQLResult[8];
				$aCategories[$iCategoryID][CHILDREN][$iForumID][LTHREADPCOUNT] = $aSQLResult[9];

				// Add the last poster to our list of users to get names for.
				if($aSQLResult[7])
				{
					$aUserIDs[] = $aSQLResult[7];
				}
				break;
			}
		}
	}

	// Get the online users.
	$iOnlineMembers = 0;
	$tOnlineTime = $CFG['globaltime'] - 300;
	$dbConn->query("SELECT id, invisible FROM citizen WHERE (lastactive >= {$tOnlineTime}) AND (loggedin = 1)");
	while(list($iUserID, $bInvisible) = $dbConn->getresult())
	{
		// Yes. Are they visible (or do we have permission to view invisible users)?
		if(($bInvisible == 0) || ($_SESSION['permissions']['cviewinvisible']))
		{
			// Yes. Add them to the list of online users.
			$aOnlineIDs[] = $iUserID;
			$aUserIDs[] = $iUserID;
		}

		// Increment the count of online members.
		$iOnlineMembers++;
	}

	// Get the online guests.
	$dbConn->query("SELECT COUNT(*) FROM guest WHERE lastactive >= {$tOnlineTime}");
	list($iOnlineGuests) = $dbConn->getresult();

	// Get any usernames we need.
	$aUsernames = GetUsernames($aUserIDs);
	unset($aUserIDs);

	// Get the visible online users.
	if(isset($aOnlineIDs) && is_array($aOnlineIDs))
	{
		foreach($aOnlineIDs as $iUserID)
		{
			$aOnline[$iUserID] = $aUsernames[$iUserID];
		}
		asort($aOnline);
		reset($aOnline);
	}

	// Get most users stats.
	$iOnlineUsers = $iOnlineMembers + $iOnlineGuests;
	$iMostUsersCount = (int)$aStats['mostuserscount'];
	$iMostUsersDate  = (int)$aStats['mostusersdate'];

	// Do we have a record number of users?
	if($iOnlineUsers > $iMostUsersCount)
	{
		$iMostUsersCount = $iOnlineUsers;
		$iMostUsersDate  = $CFG['globaltime'];

		// Yes, so update the stats.
		$dbConn->query("UPDATE stats SET content={$iOnlineUsers} WHERE name='mostuserscount'");
		$dbConn->query("UPDATE stats SET content={$CFG['globaltime']} WHERE name='mostusersdate'");
	}

	// Get any new posts.
	$dbConn->query("SELECT post.id, post.datetime_posted, post.parent, thread.parent FROM post LEFT JOIN thread ON (thread.id = post.parent) WHERE datetime_posted > {$_SESSION['lastactive']} ORDER BY datetime_posted DESC");
	while(list($iPostID, $tPosted, $iThreadID, $iParentID) = $dbConn->getresult())
	{
		// If we've read the thread, have we read it since the post was made?
		if(!isset($aViewedThreads[$iThreadID]) || ($aViewedThreads[$iThreadID] < $tPosted))
		{
			// No, so there are unread posts for that forum.
			$aNewPosts[$iParentID] = TRUE;
		}
	}

	// Get PM information.
	if($_SESSION['loggedin'] && $_SESSION['enablepms'])
	{
		$aPMInfo = array('unreadcount' => 0, 'newcount' => 0, 'totalcount' => 0);
		$dbConn->query("SELECT recipient, datetime, beenread FROM pm WHERE ownerid={$_SESSION['userid']}");
		while(list($iRecipientID, $tSent, $bRead) = $dbConn->getresult())
		{
			// Unread PM?
			if(($iRecipientID == $_SESSION['userid']) && !$bRead)
			{
				$aPMInfo['unreadcount']++;

				// New PM since last visit?
				if($tSent > $_SESSION['lastactive'])
				{
					$aPMInfo['newcount']++;
				}
			}

			// Increase the total PM count.
			$aPMInfo['totalcount']++;
		}
	}

	// Template
	require("./skins/{$CFG['skin']}/index.tpl.php");
?>