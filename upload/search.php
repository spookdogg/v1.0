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
	define('PID',       0);
	define('BID',       1);
	define('BNAME',     2);
	define('TITLE',     3);
	define('DESC',      4);
	define('AUTHOR',    5);
	define('PCOUNT',    6);
	define('VCOUNT',    7);
	define('LPOST',     8);
	define('LPOSTER',   9);
	define('ACOUNT',    10);
	define('POLL',      11);
	define('STICKY',    12);
	define('CLOSED',    13);
	define('ICON',      14);
	define('BODY',      15);
	define('POSTDATE',  16);
	define('PARENT',    17);
	define('ISOPEN',    18);
	define('NEWPOSTS',  19);
	define('URL',  0);
	define('ALT',  1);

	// Do they have authorization to use the Search engine?
	if(!$_SESSION['permissions']['csearch'])
	{
		// No. Let them know the bad news.
		Unauthorized();
	}

	// Get the information of each forum.
	list($aCategory, $aForum) = GetForumInfo();

	// What are they wanting to do?
	switch($_REQUEST['action'])
	{
		case 'showresult':
		{
			ShowResult();
		}

		case 'query':
		{
			Query();
		}

		case 'finduser':
		{
			FindUser();
		}

		case 'getnew':
		{
			GetNewPosts();
		}

		case 'getdaily':
		{
			GetDailyPosts();
		}
	}

	// Search page template
	require("./skins/{$CFG['skin']}/search/main.tpl.php");

// *************************************************************************** \\

function Query()
{
	// Are they searching by keyword or username?
	if(trim($_REQUEST['keywordsearch']) != '')
	{
		// Keyword
		KeywordSearch();
	}
	else if(trim($_REQUEST['usersearch']) != '')
	{
		// Username
		UsernameSearch();
	}
	else
	{
		// They didn't specify anything.
		Msg('Please <a href="search.php">go back</a> and specify at least one keyword or username for which to search.');
	}
}

// *************************************************************************** \\

// Perform a keyword search.
function KeywordSearch()
{
	global $CFG, $dbConn;

	// What are they searching for?
	$strQueryString = strtolower(trim($_REQUEST['keywordsearch']));
	if(strlen($strQueryString) > $CFG['maxlen']['query'])
	{
		Msg("The querystring you gave is longer than {$CFG['maxlen']['query']} characters.");
	}

	// Which forum do they want to search in?
	$strSearchForum = BuildForumString($_REQUEST['whichforum']);

	// Search in titles, bodies, or both?
	$strInTitles = BuildInTitlesString($_REQUEST['whichpart']);

	// Show the results as posts or threads?
	$bShowPosts = (int)(bool)$_REQUEST['showposts'];

	// Search date
	$iSearchDate = (int)$_REQUEST['searchdate'];

	// What do they want the results sorted by?
	$iSortBy = (int)$_REQUEST['sortby'];
	if(($iSortBy < 0) || ($iSortBy > 5))
	{
		// They don't know what they want. Search by date.
		$iSortBy = 3;
	}

	// How do they want the results ordered?
	$bSortOrder = (int)(bool)$_REQUEST['sortorder'];

	// Parse the query terms.
	$aSearchQuery = ParseTerms($strQueryString);

	// Search the keys for the query terms.
	$strSearchStrings = implode(' OR searchword.word ', $aSearchQuery);
	$dbConn->query("SELECT searchindex.postid FROM searchindex LEFT JOIN searchword ON (searchword.wordid = searchindex.wordid) WHERE (({$strInTitles}) AND (searchword.word {$strSearchStrings}))");
	while($aSQLResult = $dbConn->getresult())
	{
		$aPosts[] = $aSQLResult[0];
	}

	// Did we get any posts back?
	if(!is_array($aPosts))
	{
		Msg('No results found that match your search criteria.');
	}

	// Build the search date conditional string.
	$strSearchDate = BuildDateString($iSearchDate, $_REQUEST['beforeafter']);

	// Narrow our results based on the user's criteria.
	$strPosts = implode(', ', $aPosts);
	unset($aPosts);
	$dbConn->query("SELECT post.id FROM post LEFT JOIN thread ON (post.parent=thread.id) WHERE{$strSearchDate} post.id IN ({$strPosts}){$strSearchForum}");
	while($aSQLResult = $dbConn->getresult())
	{
		if($aSQLResult[0])
		{
			$aPosts[] = $aSQLResult[0];
		}
	}

	// Did we get any results?
	if(!is_array($aPosts))
	{
		Msg('No results found that match your search criteria.');
	}

	// Set the author.
	if($_SESSION['loggedin'])
	{
		$iAuthorID = $_SESSION['userid'];
	}
	else
	{
		$iAuthorID = 0;
	}

	// Store the results.
	$strQueryString = $dbConn->sanitize($strQueryString);
	$strResults = implode(',', $aPosts);
	$strSortInfo = "{$iSortBy},{$bSortOrder}";
	$dbConn->query("INSERT INTO searchresult(author, ipaddress, searchtime, querystring, results, sortinfo, showposts) VALUES({$iAuthorID}, {$_SESSION['userip']}, {$CFG['globaltime']}, '{$strQueryString}', '{$strResults}', '{$strSortInfo}', {$bShowPosts})");
	$iResultID = $dbConn->getinsertid('searchresult');

	// Garbage collection; delete search results older than a day.
	$tOld = $CFG['globaltime'] - 86400;
	$dbConn->query("DELETE FROM searchresult WHERE searchtime < {$tOld}");

	// Redirect the user to the results we just created.
	Msg("<b>Thank you for searching.</b><br /><br /><span class=\"smaller\">You should be redirected to the results momentarily. Click <a href=\"search.php?action=showresult&amp;searchid={$iResultID}\">here</a> if you do not want to wait any longer or if you are not redirected.</span>", "search.php?action=showresult&searchid={$iResultID}");
}

// *************************************************************************** \\

// Perform a username search.
function UsernameSearch()
{
	global $CFG, $dbConn;

	// Who are they searching for?
	$strUsername = trim($_REQUEST['usersearch']);

	// Which forum do they want to search in?
	$strSearchForum = BuildForumString($_REQUEST['whichforum']);

	// Show the results as posts or threads?
	$bShowPosts = (int)(bool)$_REQUEST['showposts'];

	// Search date
	$iSearchDate = (int)$_REQUEST['searchdate'];

	// What do they want the results sorted by?
	$iSortBy = (int)$_REQUEST['sortby'];
	if(($iSortBy < 0) || ($iSortBy > 5))
	{
		// They don't know what they want. Search by date.
		$iSortBy = 3;
	}

	// How do they want the results ordered?
	$bSortOrder = (int)(bool)$_REQUEST['sortorder'];

	// Build the search date conditional string.
	$strSearchDate = BuildDateString($iSearchDate, $_REQUEST['beforeafter']);

	// Build the search username string.
	$strUsername = $dbConn->sanitize($strUsername);
	if((bool)$_REQUEST['exactname'])
	{
		$strSearchUsername = "citizen.username='{$strUsername}'";
	}
	else
	{
		$strSearchUsername = "citizen.username LIKE '%{$strUsername}%'";
	}

	// Search for all posts made by this user.
	$dbConn->query("SELECT post.id FROM citizen LEFT JOIN post ON (post.author = citizen.id) LEFT JOIN thread ON (post.parent = thread.id) WHERE {$strSearchDate}{$strSearchUsername}{$strSearchForum}");
	while($aSQLResult = $dbConn->getresult())
	{
		if($aSQLResult[0])
		{
			$aPosts[] = $aSQLResult[0];
		}
	}

	// Did we get any results?
	if(!is_array($aPosts))
	{
		Msg('No results found that match your search criteria.');
	}

	// Set the author.
	if($_SESSION['loggedin'])
	{
		$iAuthorID = $_SESSION['userid'];
	}
	else
	{
		$iAuthorID = 0;
	}

	// Store the results.
	$strResults = implode(',', $aPosts);
	$strSortInfo = "{$iSortBy},{$bSortOrder}";
	$dbConn->query("INSERT INTO searchresult(author, ipaddress, searchtime, querystring, results, sortinfo, showposts) VALUES({$iAuthorID}, {$_SESSION['userip']}, {$CFG['globaltime']}, '{$strUsername}', '{$strResults}', '{$strSortInfo}', {$bShowPosts})");
	$iResultID = $dbConn->getinsertid('searchresult');

	// Garbage collection; delete search results older than a day.
	$tOld = $CFG['globaltime'] - 86400;
	$dbConn->query("DELETE FROM searchresult WHERE searchtime < {$tOld}");

	// Redirect the user to the results we just created.
	Msg("<b>Thank you for searching.</b><br /><br /><span class=\"smaller\">You should be redirected to the results momentarily. Click <a href=\"search.php?action=showresult&amp;searchid={$iResultID}\">here</a> if you do not want to wait any longer or if you are not redirected.</span>", "search.php?action=showresult&searchid={$iResultID}");
}

// *************************************************************************** \\

// Perform a user ID search.
function FindUser()
{
	global $CFG, $dbConn;

	// Who are they searching for?
	$iUserID = (int)$_REQUEST['userid'];

	// Which forum do they want to search in?
	$strSearchForum = BuildForumString($_REQUEST['whichforum']);

	// Show the results as posts or threads?
	if(isset($_REQUEST['showposts']))
	{
		$bShowPosts = (int)(bool)$_REQUEST['showposts'];
	}
	else
	{
		$bShowPosts = TRUE;
	}

	// Search date
	$iSearchDate = (int)$_REQUEST['searchdate'];

	// What do they want the results sorted by?
	$iSortBy = (int)$_REQUEST['sortby'];
	if(($iSortBy < 0) || ($iSortBy > 5))
	{
		// They don't know what they want. Search by date.
		$iSortBy = 3;
	}

	// How do they want the results ordered?
	$bSortOrder = (int)(bool)$_REQUEST['sortorder'];

	// Build the search date conditional string.
	$strSearchDate = BuildDateString($iSearchDate, $_REQUEST['beforeafter']);

	// Search for all posts made by this user.
	$dbConn->query("SELECT post.id FROM citizen LEFT JOIN post ON (post.author = citizen.id) LEFT JOIN thread ON (post.parent = thread.id) WHERE {$strSearchDate}citizen.id={$iUserID}{$strSearchForum}");
	while($aSQLResult = $dbConn->getresult())
	{
		if($aSQLResult[0])
		{
			$aPosts[] = $aSQLResult[0];
		}
	}

	// Did we get any results?
	if(!is_array($aPosts))
	{
		Msg('No results found that match your search criteria.');
	}

	// Set the author.
	if($_SESSION['loggedin'])
	{
		$iAuthorID = $_SESSION['userid'];
	}
	else
	{
		$iAuthorID = 0;
	}

	// Store the results.
	$strResults = implode(',', $aPosts);
	$strSortInfo = "{$iSortBy},{$bSortOrder}";
	$dbConn->query("INSERT INTO searchresult(author, ipaddress, searchtime, querystring, results, sortinfo, showposts) VALUES({$iAuthorID}, {$_SESSION['userip']}, {$CFG['globaltime']}, 'Find User', '{$strResults}', '{$strSortInfo}', {$bShowPosts})");
	$iResultID = $dbConn->getinsertid('searchresult');

	// Garbage collection; delete search results older than a day.
	$tOld = $CFG['globaltime'] - 86400;
	$dbConn->query("DELETE FROM searchresult WHERE searchtime < {$tOld}");

	// Redirect the user to the results we just created.
	Msg("<b>Thank you for searching.</b><br /><br /><span class=\"smaller\">You should be redirected to the results momentarily. Click <a href=\"search.php?action=showresult&amp;searchid={$iResultID}\">here</a> if you do not want to wait any longer or if you are not redirected.</span>", "search.php?action=showresult&searchid={$iResultID}");
}

// *************************************************************************** \\

// Find all posts made since the user's last visit.
function GetNewPosts()
{
	global $CFG, $dbConn;

	// What do they want the results sorted by?
	$iSortBy = (int)$_REQUEST['sortby'];
	if(($iSortBy < 0) || ($iSortBy > 5))
	{
		// They don't know what they want. Sort by date.
		$iSortBy = 3;
	}

	// How do they want the results ordered?
	$bSortOrder = (int)(bool)$_REQUEST['sortorder'];

	// Search for all new posts made.
	$dbConn->query("SELECT post.id FROM citizen LEFT JOIN post ON (post.author = citizen.id) LEFT JOIN thread ON (post.parent = thread.id) WHERE post.datetime_posted > {$_SESSION['lastactive']}");
	while($aSQLResult = $dbConn->getresult())
	{
		if($aSQLResult[0])
		{
			$aPosts[] = $aSQLResult[0];
		}
	}

	// Did we get any results?
	if(!is_array($aPosts))
	{
		Msg('No results found that match your search criteria.');
	}

	// Set the author.
	$iAuthorID = ($_SESSION['loggedin']) ? $_SESSION['userid'] : 0;

	// Store the results.
	$strResults = implode(',', $aPosts);
	$strSortInfo = "{$iSortBy},{$bSortOrder}";
	$dbConn->query("INSERT INTO searchresult(author, ipaddress, searchtime, querystring, results, sortinfo, showposts) VALUES({$iAuthorID}, {$_SESSION['userip']}, {$CFG['globaltime']}, 'Find New Posts', '{$strResults}', '{$strSortInfo}', 1)");
	$iResultID = $dbConn->getinsertid('searchresult');

	// Garbage collection; delete search results older than a day.
	$tOld = $CFG['globaltime'] - 86400;
	$dbConn->query("DELETE FROM searchresult WHERE searchtime < {$tOld}");

	// Redirect the user to the results we just created.
	Msg("<b>Thank you for searching.</b><br /><br /><span class=\"smaller\">You should be redirected to the results momentarily. Click <a href=\"search.php?action=showresult&amp;searchid={$iResultID}\">here</a> if you do not want to wait any longer or if you are not redirected.</span>", "search.php?action=showresult&searchid={$iResultID}");
}

// *************************************************************************** \\

// Find all posts made in the last 24 hours.
function GetDailyPosts()
{
	global $CFG, $dbConn;

	// What do they want the results sorted by?
	$iSortBy = (int)$_REQUEST['sortby'];
	if(($iSortBy < 0) || ($iSortBy > 5))
	{
		// They don't know what they want. Sort by date.
		$iSortBy = 3;
	}

	// How do they want the results ordered?
	$bSortOrder = (int)(bool)$_REQUEST['sortorder'];

	// Build the time search range.
	$tSearchRange = $CFG['globaltime'] - 86400;

	// Search for all posts made in the last 24 hours.
	$dbConn->query("SELECT post.id FROM citizen LEFT JOIN post ON (post.author = citizen.id) LEFT JOIN thread ON (post.parent = thread.id) WHERE post.datetime_posted >= {$tSearchRange}");
	while($aSQLResult = $dbConn->getresult())
	{
		if($aSQLResult[0])
		{
			$aPosts[] = $aSQLResult[0];
		}
	}

	// Did we get any results?
	if(!is_array($aPosts))
	{
		Msg('No results found that match your search criteria.');
	}

	// Set the author.
	if($_SESSION['loggedin'])
	{
		$iAuthorID = $_SESSION['userid'];
	}
	else
	{
		$iAuthorID = 0;
	}

	// Store the results.
	$strResults = implode(',', $aPosts);
	$strSortInfo = "{$iSortBy},{$bSortOrder}";
	$dbConn->query("INSERT INTO searchresult(author, ipaddress, searchtime, querystring, results, sortinfo, showposts) VALUES({$iAuthorID}, {$_SESSION['userip']}, {$CFG['globaltime']}, 'Find New Posts', '{$strResults}', '{$strSortInfo}', 0)");
	$iResultID = $dbConn->getinsertid('searchresult');

	// Garbage collection; delete search results older than a day.
	$tOld = $CFG['globaltime'] - 86400;
	$dbConn->query("DELETE FROM searchresult WHERE searchtime < {$tOld}");

	// Redirect the user to the results we just created.
	Msg("<b>Thank you for searching.</b><br /><br /><span class=\"smaller\">You should be redirected to the results momentarily. Click <a href=\"search.php?action=showresult&amp;searchid={$iResultID}\">here</a> if you do not want to wait any longer or if you are not redirected.</span>", "search.php?action=showresult&searchid={$iResultID}");
}

// *************************************************************************** \\

// Show search result.
function ShowResult()
{
	global $CFG, $dbConn;

	// What search result do they want?
	$iResultID = (int)$_REQUEST['searchid'];

	// Does it show posts?
	$dbConn->query("SELECT id, author, ipaddress, querystring, results, sortinfo, showposts FROM searchresult WHERE id={$iResultID}");
	$aSQLResult = $dbConn->getresult(TRUE);
	if(is_array($aSQLResult))
	{
		if($aSQLResult['showposts'])
		{
			ViewResultPosts($aSQLResult);
		}
		else
		{
			ViewResultThreads($aSQLResult);
		}
	}
	else
	{
		Msg("Invalid search result specified.{$CFG['msg']['invalidlink']}");
	}
}

// *************************************************************************** \\

// View search result as threads.
function ViewResultThreads($aResultInfo)
{
	global $CFG, $dbConn, $aViewedThreads, $aPostIcons;
	$aUsers = array();

	// Did this user create the result they're trying to view?
	if($aResultInfo['author'])
	{
		if($aResultInfo['author'] != $_SESSION['userid'])
		{
			// Nope.
			Msg("Invalid search result specified.{$CFG['msg']['invalidlink']}");
		}
	}
	else if(($aResultInfo['ipaddress'] != $_SESSION['userip']) || ($CFG['iplogging'] == FALSE))
	{
		// Nope.
		Msg("Invalid search result specified.{$CFG['msg']['invalidlink']}");
	}

	// Parse the result information.
	$iResultID = $aResultInfo['id'];
	$strQueryString = $aResultInfo['querystring'];
	$aResults = explode(',', $aResultInfo['results']);
	list($iSortBy, $bSortOrder) = explode(',', $aResultInfo['sortinfo']);

	// Get the user's per-page settings.
	$iThreadsPerPage = $_SESSION['threadsperpage'];
	$iPostsPerPage = $_SESSION['postsperpage'];

	// User-specified value takes precedence.
	if((int)$_REQUEST['perpage'])
	{
		$iThreadsPerPage = abs($_REQUEST['perpage']);
	}

	// What page do they want to view?
	$iPage = (int)$_REQUEST['page'];
	if($iPage < 1)
	{
		// They don't know what they want. Give them the first page.
		$iPage = 1;
	}

	// Calculate the offset.
	$iOffset = ($iPage * $iThreadsPerPage) - $iThreadsPerPage;

	// Did they specify by what to sort?
	if(isset($_REQUEST['sortby']))
	{
		// Yes, so use it.
		$strSortBy = strtolower($_REQUEST['sortby']);
		switch($strSortBy)
		{
			// They specified us something valid.
			case 'lpost':
			case 'title':
			case 'postcount':
			case 'viewcount':
			case 'author':
			case 'forum':
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
	}
	else
	{
		// No, so use what was stored in the search result.
		$aSortBy = array('title', 'postcount', 'viewcount', 'lpost', 'author', 'forum');
		$strSortBy = $aSortBy[$iSortBy];
		unset($aSortBy);
	}

	// Did they specify a sort order?
	if(isset($_REQUEST['sortorder']))
	{
		// Yes, so use it.
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
	}
	else
	{
		// No, so use the one stored in the search result.
		$strSortOrder = $bSortOrder ? 'DESC' : 'ASC';
	}

	$strPostIDs = implode(', ', $aResults);

	// Get the number of threads this result contains.
	$dbConn->query("SELECT COUNT(DISTINCT post.parent) FROM post WHERE post.id IN ({$strPostIDs})");
	list($iThreadCount) = $dbConn->getresult();

	// Calculate the number of pages this result is made of.
	$iNumberPages = ceil($iThreadCount / $iThreadsPerPage);

	// Is the page they asked for out of range?
	if($iPage > $iNumberPages)
	{
		// Yes, give them the last page and recalculate offset.
		$iPage = $iNumberPages;
		$iOffset = ($iPage * $iThreadsPerPage) - $iThreadsPerPage;
	}

	// Get the threads.
	$dbConn->query("SELECT DISTINCT t.id, p.id, b.id, b.name AS forum, t.title, t.description, t.icon, t.author, t.postcount, t.viewcount, t.lpost, t.lposter, t.attachcount, t.poll, t.sticky, t.closed FROM post AS p LEFT JOIN thread AS t ON (t.id = p.parent) LEFT JOIN post ON (post.parent = t.id) LEFT JOIN board AS b ON (b.id = t.parent) WHERE p.id IN ({$strPostIDs}) AND t.visible=1 ORDER BY {$strSortBy} {$strSortOrder}, t.id {$strSortOrder} LIMIT {$iThreadsPerPage} OFFSET {$iOffset}");
	while($aSQLResult = $dbConn->getresult())
	{
		// Store the thread information.
		$iThreadID = $aSQLResult[0];
		$aThreads[$iThreadID][PID] = $aSQLResult[1];
		$aThreads[$iThreadID][BID] = $aSQLResult[2];
		$aThreads[$iThreadID][BNAME] = $aSQLResult[3];
		$aThreads[$iThreadID][TITLE] = $aSQLResult[4];
		$aThreads[$iThreadID][DESC] = $aSQLResult[5];
		$aThreads[$iThreadID][AUTHOR] = $aSQLResult[7];
		$aThreads[$iThreadID][PCOUNT] = $aSQLResult[8];
		$aThreads[$iThreadID][VCOUNT] = $aSQLResult[9];
		$aThreads[$iThreadID][LPOST] = $aSQLResult[10];
		$aThreads[$iThreadID][LPOSTER] = $aSQLResult[11];
		$aThreads[$iThreadID][ACOUNT] = $aSQLResult[12];
		$aThreads[$iThreadID][POLL] = $aSQLResult[13];
		$aThreads[$iThreadID][STICKY] = $aSQLResult[14];
		$aThreads[$iThreadID][CLOSED] = $aSQLResult[15];
		$aThreads[$iThreadID][NEWPOSTS] = ((!isset($aViewedThreads[$iThreadID]) && ($aSQLResult[10] > $_SESSION['lastactive'])) || (isset($aViewedThreads[$iThreadID]) && ($aViewedThreads[$iThreadID] < $aSQLResult[10]))) ? TRUE : FALSE;
		$aThreads[$iThreadID][ICON][URL] = "{$CFG['paths']['posticons']}{$aPostIcons[$aSQLResult[6]]['filename']}";
		$aThreads[$iThreadID][ICON][ALT] = $aPostIcons[$aSQLResult[6]]['title'];

		// Add the author and last poster to our list of users to get names for.
		$aUsers[] = $aThreads[$iThreadID][AUTHOR];
		$aUsers[] = $aThreads[$iThreadID][LPOSTER];

	}

	// Get the usernames.
	$aUsernames = GetUsernames($aUsers);
	unset($aUsers);

	// Results page template
	require("./skins/{$CFG['skin']}/search/threadresults.tpl.php");

	// Send the page.
	exit;
}

// *************************************************************************** \\

// View search result as posts.
function ViewResultPosts($aResultInfo)
{
	global $CFG, $dbConn, $aViewedThreads, $aPostIcons;
	$aUsers = array();

	// Did this user create the result they're trying to view?
	if($aResultInfo['author'])
	{
		if($aResultInfo['author'] != $_SESSION['userid'])
		{
			// Nope.
			Msg("Invalid search result specified.{$CFG['msg']['invalidlink']}");
		}
	}
	else if(($aResultInfo['ipaddress'] != $_SESSION['userip']) || ($CFG['iplogging'] == FALSE))
	{
		// Nope.
		Msg("Invalid search result specified.{$CFG['msg']['invalidlink']}");
	}

	// Parse the result information.
	$iResultID = $aResultInfo['id'];
	$strQueryString = $aResultInfo['querystring'];
	$aResults = explode(',', $aResultInfo['results']);
	list($iSortBy, $bSortOrder) = explode(',', $aResultInfo['sortinfo']);

	// Get the user's per-page settings.
	$iPostsPerPage = $_SESSION['postsperpage'];

	// User-specified value takes precedence.
	if((int)$_REQUEST['perpage'])
	{
		$iPostsPerPage = abs($_REQUEST['perpage']);
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

	// Calculate the number of pages this result is made of.
	$iNumberPages = ceil(count($aResults) / $iPostsPerPage);

	// Is the page they asked for out of range?
	if($iPage > $iNumberPages)
	{
		// Yes, give them the last page and recalculate offset.
		$iPage = $iNumberPages;
		$iOffset = ($iPage * $iPostsPerPage) - $iPostsPerPage;
	}

	// Did they specify by what to sort?
	if(isset($_REQUEST['sortby']))
	{
		// Yes, so use it.
		$strSortBy = strtolower($_REQUEST['sortby']);
		switch($strSortBy)
		{
			// They specified us something valid.
			case 'topic':
			case 'forum':
			case 'author':
			case 'postcount':
			case 'viewcount':
			case 'date':
			{
				break;
			}

			// They don't know what they want. We'll sort by post date.
			default:
			{
				$strSortBy = 'date';
				break;
			}
		}
	}
	else
	{
		// No, so use what was stored in the search result.
		$aSortBy = array('topic', 'forum', 'author', 'postcount', 'viewcount', 'date');
		$strSortBy = $aSortBy[$iSortBy];
		unset($aSortBy);
	}

	// Did they specify a sort order?
	if(isset($_REQUEST['sortorder']))
	{
		// Yes, so use it.
		$strSortOrder = strtoupper($_REQUEST['sortorder']);
		if(($strSortOrder != 'ASC') && ($strSortOrder != 'DESC'))
		{
			// They don't know what they want. Are they sorting by post date?
			if($strSortBy == 'date')
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
	}
	else
	{
		// No, so use the one stored in the search result.
		$strSortOrder = $bSortOrder ? 'DESC' : 'ASC';
	}

	// Get the posts.
	$strPostIDs = implode(', ', $aResults);
	$dbConn->query("SELECT DISTINCT p.id, p.title AS topic, p.icon, p.body, p.author, p.datetime_posted AS date, t.id, t.title, t.icon, t.postcount, t.viewcount, b.id, b.name AS forum, t.closed, t.lpost FROM post AS p LEFT JOIN thread AS t ON (t.id = p.parent) LEFT JOIN board AS b ON (b.id = t.parent) WHERE p.id IN ({$strPostIDs}) AND t.visible=1 ORDER BY {$strSortBy} {$strSortOrder}, t.id {$strSortOrder} LIMIT {$iPostsPerPage} OFFSET {$iOffset}");
	while($aSQLResult = $dbConn->getresult())
	{
		// Store the post information into the master array.
		$iPostID = $aSQLResult[0];
		$aPosts[$iPostID][TITLE] = $aSQLResult[1];
		$aPosts[$iPostID][ICON][URL] = "{$CFG['paths']['posticons']}{$aPostIcons[$aSQLResult[2]]['filename']}";
		$aPosts[$iPostID][ICON][ALT] = $aPostIcons[$aSQLResult[2]]['title'];
		$aPosts[$iPostID][BODY] = substr(RemoveBBCode($aSQLResult[3]), 0, 255);
		$aPosts[$iPostID][AUTHOR] = $aSQLResult[4];
		$aPosts[$iPostID][POSTDATE] = $aSQLResult[5];
		$aPosts[$iPostID][PARENT] = $aSQLResult[6];

		// Store the thread information into the thread array.
		$iThreadID = $aSQLResult[6];
		if(!isset($aThreads[$iThreadID]))
		{
			$aThreads[$iThreadID][TITLE] = $aSQLResult[7];
			$aThreads[$iThreadID][ICON][URL] = "{$CFG['paths']['posticons']}{$aPostIcons[$aSQLResult[8]]['filename']}";
			$aThreads[$iThreadID][ICON][ALT] = $aPostIcons[$aSQLResult[8]]['title'];
			$aThreads[$iThreadID][PCOUNT] = $aSQLResult[9];
			$aThreads[$iThreadID][VCOUNT] = $aSQLResult[10];
			$aThreads[$iThreadID][PARENT] = $aSQLResult[11];
			$aThreads[$iThreadID][ISOPEN] = !$aSQLResult[13];
			$aThreads[$iThreadID][NEWPOSTS] = ((!isset($aViewedThreads[$iThreadID]) && ($aSQLResult[14] > $_SESSION['lastactive'])) || (isset($aViewedThreads[$iThreadID]) && ($aViewedThreads[$iThreadID] < $aSQLResult[14]))) ? TRUE : FALSE;
		}

		// Store the forum in the forum list.
		$iForumID = $aSQLResult[11];
		if(!isset($aForums[$iForumID]))
		{
			$aForums[$iForumID] = $aSQLResult[12];
		}

		// Is there a post title?
		if($aPosts[$iPostID][TITLE] == '')
		{
			// No, so let's use the thread's title.
			$aPosts[$iPostID][TITLE] = $aThreads[$iThreadID][TITLE];
		}

		// Add the post author to our list of users to get names for.
		$aUsers[] = $aPosts[$iPostID][AUTHOR];
	}

	// Get the usernames.
	$aUsernames = GetUsernames($aUsers);
	unset($aUsers);

	// Results page template
	require("./skins/{$CFG['skin']}/search/postresults.tpl.php");

	// Send the page.
	exit;
}

// *************************************************************************** \\

// Creates SQL conditional string for which forum(s) to search in.
function BuildForumString($aForumIDs)
{
	global $aCategory, $aForum;

	foreach($aForumIDs as $iForumID)
	{
		// Sanitize the ID.
		$iForumID = (int)$iForumID;

		// Only continue if we were given something.
		if($iForumID)
		{
			// Is the forum a category?
			if(array_key_exists($iForumID, $aCategory))
			{
				// Yes, so get the forums it contains.
				reset($aForum);
				while(list($iBoardID) = each($aForum))
				{
					// Save it only if it's a child of our forum.
					if($aForum[$iBoardID][0] == $iForumID)
					{
						$aReturn[] = $iBoardID;
					}
				}

				// Build the string.
				$strReturn = implode(', ', $aReturn);

				$strForumSQL .= "thread.parent IN ({$strReturn}) OR ";
			}
			else
			{
				// No.
				$strForumSQL .= "thread.parent={$iForumID} OR ";
			}
		}
	}

	if($strForumSQL != '') {
		// Remove the last OR statement.
		$strForumSQL = substr($strForumSQL, 0, strlen($strForumSQL) - 4);
		return(" AND ({$strForumSQL})");
	}
}

// *************************************************************************** \\

// Create SQL conditional string for whether or not we search in titles.
function BuildInTitlesString($iPart)
{
	switch((int)$iPart)
	{
		// Search only titles.
		case 1:
		{
			return('searchindex.intitle = 1');
		}

		// Search only bodies.
		case 2:
		{
			return('searchindex.intitle = 0');
		}

		// Search both.
		default:
		{
			return('searchindex.intitle = 0 OR searchindex.intitle = 1');
		}
	}
}

// Create SQL conditional string for what date range in which to search.
function BuildDateString($iSearchDate, $bBeforeAfter)
{
	global $CFG;

	// Did they specify a date range to search in?
	if($iSearchDate)
	{
		$tSearchDate = $CFG['globaltime'] - ($iSearchDate * 86400);

		// Newer or older?
		if((bool)$bBeforeAfter)
		{
			return(" post.datetime_posted < {$tSearchDate} AND");
		}
		else
		{
			return(" post.datetime_posted > {$tSearchDate} AND");
		}
	}
}

// Parse the specified text for query terms.
function ParseTerms($text)
{
	global $dbConn;

	// Search terms are supposed to be separated by a space.
	$aWords = array_unique(explode(' ', $text));

	// Go through each "word" and deal with bad characters.
	foreach($aWords as $k => $strWord)
	{
		// Sanitize the word.
		$strWord = $dbConn->sanitize($strWord);

		// Replace first and last character astericks with % for SQL.
		$i = strlen($strWord) - 1;
		if($strWord{0} == '*')
		{
			$asterick = TRUE;
			$strWord{0} = '%';
		}
		if($strWord{$i} == '*')
		{
			$asterick = TRUE;
			$strWord{$i} = '%';
		}

		// Build the searchstring.
		if($asterick)
		{
			$aWords[$k] = "LIKE '{$strWord}'";
		}
		else
		{
			$aWords[$k] = "= '{$strWord}'";
		}

		// Remove blank.
		if($strWord == '')
		{
			unset($aWords[$k]);
		}
	}

	return $aWords;
}

// *************************************************************************** \\

// Removes BB codes from text.
function RemoveBBCode($strText)
{
	$strText = ParseTag('/\[b\](.+?)\[\/b\]/is', '$1', $strText);
	$strText = ParseTag('/\[i\](.+?)\[\/i\]/is', '$1', $strText);
	$strText = ParseTag('/\[u\](.+?)\[\/u\]/is', '$1', $strText);
	$strText = ParseTag('/\[size=((&quot;)?)(.+?)(\\1)\](.+?)\[\/size\]/is', '$5', $strText);
	$strText = ParseTag('/\[font=((&quot;)?)(.+?)(\\1)\](.+?)\[\/font\]/is', '$5', $strText);
	$strText = ParseTag('/\[color=((&quot;)?)(.+?)(\\1)\](.+?)\[\/color\]/is', '$5', $strText);
	$strText = preg_replace('/\[url\](.+?)\[\/url\]/i', '$1', $strText);
	$strText = preg_replace('/\[url=((&quot;)?)(.+?)(\\1)\](.+?)\[\/url\]/i', '$5', $strText);
	$strText = preg_replace('/\[thread=((&quot;)?)(.+?)(\\1)\](.+?)\[\/thread\]/i', '$5', $strText);
	$strText = preg_replace('/\[email\](.+?)\[\/email\]/i', '$1', $strText);
	$strText = preg_replace('/\[email=((&quot;)?)(.+?)(\\1)\](.+?)\[\/email\]/i', '$5', $strText);
	$strText = preg_replace('/\[code\](.+?)\[\/code\]/is', '$1', $strText);
	$strText = preg_replace('/\[php\](.+?)\[\/php\]/is', '$1', $strText);
	$strText = preg_replace('/\[list\](.+?)\[\/list\]/is', '$1', $strText);
	$strText = preg_replace('/\[list=((&quot;)?)(.+?)(\\1)\](.+?)\[\/list\]/is', '$5', $strText);
	$strText = ParseTag('/\[quote\](.+?)\[\/quote\]/is', '$1', $strText);
	$strText = ParseTag('/\[quote=((&quot;)?)(.+?)(\\1)\](.+?)\[\/quote\]/is', '$5', $strText);
	$strText = preg_replace('/\[img\](.+?)\[\/img\]/i', '$1', $strText);
	return $strText;
}
?>