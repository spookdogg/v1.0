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
	define('USERNAME',   0);
	define('WEBSITE',    1);
	define('JOINDATE',   2);
	define('POSTCOUNT',  3);
	define('ONLINE',     4);

	// Are they authorized to view the member list?
	if(!$_SESSION['permissions']['cviewmembers'])
	{
		// No. Let them know the bad news.
		Unauthorized();
	}

	// What do they want to sort by?
	$strSortBy = strtolower($_REQUEST['sortby']);
	switch($strSortBy)
	{
		// They specified us something valid.
		case 'username':
		case 'datejoined':
		case 'postcount':
		{
			break;
		}

		// They don't know what they want. We'll sort by username.
		default:
		{
			$strSortBy = 'username';
			break;
		}
	}

	// What order do they want it sorted in?
	$strSortOrder = strtoupper($_REQUEST['sortorder']);
	if(($strSortOrder != 'ASC') && ($strSortOrder != 'DESC'))
	{
		// They don't know what they want. We'll sort ascending.
		$strSortOrder = 'ASC';
	}

	// How many users per page do they want to view?
	$iUsersPerPage = (int)$_REQUEST['perpage'];
	if($iUsersPerPage < 1)
	{
		// They don't know what they want. Give them 15 users per page.
		$iUsersPerPage = 15;
	}

	// What page do they want to view?
	$iPage = (int)$_REQUEST['page'];
	if($iPage < 1)
	{
		// They don't know what they want. Give them the first page.
		$iPage = 1;
	}

	// Calculate the offsets.
	$iOffset = ($iPage * $iUsersPerPage) - $iUsersPerPage;

	// Initial characterization?
	if(ctype_alpha($_REQUEST['letter']) && (strlen($_REQUEST['letter']) == 1))
	{
		$strWhereClause = " AND username LIKE '{$_REQUEST['letter']}%'";
	}
	else if($_REQUEST['letter'] == '#')
	{
		$strWhereClause = " AND SUBSTRING(username FROM 1 FOR 1) NOT IN ('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z')";
	}

	// Get the total number of members.
	$dbConn->query("SELECT COUNT(id) FROM citizen WHERE reghash IS NULL{$strWhereClause}");
	list($iNumberMembers) = $dbConn->getresult();

	// Calculate the number of pages.
	$iNumberPages = ceil($iNumberMembers / $iUsersPerPage);

	// Is the page they asked for out of range?
	if($iPage > $iNumberPages)
	{
		// Yes, give them the last page and recalculate offset.
		$iPage = $iNumberPages;
		$iOffset = ($iPage * $iUsersPerPage) - $iUsersPerPage;
	}

	// Get the members and all their information.
	if($iNumberMembers)
	{
		$dbConn->query("SELECT id, username, lastactive, loggedin, website, datejoined, postcount, invisible FROM citizen WHERE reghash IS NULL{$strWhereClause} ORDER BY {$strSortBy} {$strSortOrder}, id DESC LIMIT {$iUsersPerPage} OFFSET {$iOffset}");
		while(($aSQLResult = $dbConn->getresult(TRUE)) && ($i < $iUsersPerPage))
		{
			// Get the member information.
			$iMemberID = $aSQLResult['id'];
			$aMembers[$iMemberID][USERNAME] = $aSQLResult['username'];
			$aMembers[$iMemberID][WEBSITE] = $aSQLResult['website'];
			$aMembers[$iMemberID][JOINDATE] = strtotime($aSQLResult['datejoined']);
			$aMembers[$iMemberID][POSTCOUNT] = $aSQLResult['postcount'];

			// Is this member online or offline?
			// (Is their last activity within the last 300 seconds [5 minutes]?)
			if((($aSQLResult['lastactive'] + 300) >= $CFG['globaltime']) && ($aSQLResult['loggedin']) && (!$aSQLResult['invisible']))
			{
				// Yes, they are online.
				$aMembers[$iMemberID][ONLINE] = TRUE;
			}
			else
			{
				// No, they are offline (or invisible).
				$aMembers[$iMemberID][ONLINE] = FALSE;
			}
		}
	}

	// Template
	require("./skins/{$CFG['skin']}/memberlist.tpl.php");
?>