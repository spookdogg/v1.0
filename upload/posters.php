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
	define('POSTCOUNT',  1);

	// What thread do they want?
	$iThreadID = (int)$_REQUEST['threadid'];

	// Get each poster in the thread and their number of posts in the thread.
	$dbConn->query("SELECT citizen.id, citizen.username, COUNT(post.id) as postcount FROM citizen INNER JOIN post ON (post.author = citizen.id) WHERE post.parent={$iThreadID} GROUP BY citizen.id, citizen.username ORDER BY postcount ASC");
	while($aSQLResult = $dbConn->getresult())
	{
		$iPosterID = $aSQLResult[0];
		$aPosters[$iPosterID][USERNAME] = $aSQLResult[1];
		$aPosters[$iPosterID][POSTCOUNT] = $aSQLResult[2];

		// Add the posts to the total count.
		$iTotalPosts = $iTotalPosts + $aSQLResult[2];
	}

	// Is it a valid thread?
	if(!isset($aPosters))
	{
		Msg("Invalid thread specified.{$CFG['msg']['invalidlink']}");
	}

	// Template
	require("./skins/{$CFG['skin']}/showposters.tpl.php");
?>