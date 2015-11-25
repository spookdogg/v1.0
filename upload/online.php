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
	define('USERNAME',    0);
	define('LOCATION',    1);
	define('LASTACTIVE',  2);
	define('IPADDRESS',   3);

	// Variables
	$aUsers = array();
	$aGuests = array();

	// Do they have authorization to view Who's Online?
	if(!$_SESSION['permissions']['cviewonline'])
	{
		// No. Let them know the bad news.
		Unauthorized();
	}

	// Get the online users.
	$strInvisible = $_SESSION['permissions']['cviewinvisible'] ? '' : ' AND (invisible = 0)';
	$dbConn->query("SELECT id, username, lastactive, lastlocation, lastrequest, ipaddress FROM citizen WHERE ((lastactive + 300) >= {$CFG['globaltime']}) AND (loggedin = 1){$strInvisible} ORDER BY lastactive DESC");
	while($aSQLResult = $dbConn->getresult(TRUE))
	{
		// Get the data.
		$strLastLocation = $aSQLResult['lastlocation'];
		$aRequest = unserialize($aSQLResult['lastrequest']);

		// Store the user's information.
		$iUserID = $aSQLResult['id'];
		$aUsers[$iUserID][USERNAME] = $aSQLResult['username'];
		$aUsers[$iUserID][LOCATION] = GetLocation($strLastLocation, $aRequest);
		$aUsers[$iUserID][LASTACTIVE] = $aSQLResult['lastactive'];
		if($_SESSION['permissions']['cviewips'] && $CFG['iplogging'])
		{
			$aUsers[$iUserID][IPADDRESS] = gethostbyaddr(long2ip($aSQLResult['ipaddress']));
		}
		else
		{
			$aUsers[$iUserID][IPADDRESS] = NULL;
		}
	}

	// Get the online guests.
	$dbConn->query("SELECT lastactive, lastlocation, lastrequest, ipaddress FROM guest WHERE (lastactive + 300) >= {$CFG['globaltime']} ORDER BY lastactive DESC");
	for($iIndex = 0; $aSQLResult = $dbConn->getresult(TRUE); $iIndex++)
	{
		// Get the data.
		$strLastLocation = $aSQLResult['lastlocation'];
		$aRequest = unserialize($aSQLResult['lastrequest']);

		// Store the user's information.
		$aGuests[$iIndex][LOCATION] = GetLocation($strLastLocation, $aRequest);
		$aGuests[$iIndex][LASTACTIVE] = $aSQLResult['lastactive'];
		if($_SESSION['permissions']['cviewips'] && $CFG['iplogging'])
		{
			$aGuests[$iIndex][IPADDRESS] = gethostbyaddr(long2ip($aSQLResult['ipaddress']));
		}
		else
		{
			$aGuests[$iIndex][IPADDRESS] = NULL;
		}
	}

	// Free memory.
	unset($aSQLResult);
	unset($aLocations);
	unset($aRequest);

	// Get the information of each forum.
	list($aCategory, $aForum) = GetForumInfo();

	// Template
	require("./skins/{$CFG['skin']}/online.tpl.php");

// *************************************************************************** \\

function GetLocation($strLastLocation, $aRequest)
{
	global $CFG;

	// Sanitize the request array.
	$aRequest = array_map('urlencode', $aRequest);

	// Location descriptions
	$aLocations['admincp.php'][NULL] = 'Administrating...';
	$aLocations['attachment.php'][NULL] = 'Viewing Attachment';
	$aLocations['calendar.php'][NULL] = 'Viewing <a href="calendar.php">Calendar</a>';
	$aLocations['calendar.php']['action=addevent'] = 'Adding Event to the <a href="calendar.php">Calendar</a>';
	$aLocations['calendar.php']['action=viewevent'] = 'Viewing a Calendar Event';
	$aLocations['editpost.php'][NULL] = 'Editing Post';
	$aLocations['forumdisplay.php'][NULL] = 'Viewing <a href="forumdisplay.php?forumid={$aRequest[forumid]}">Forum</a>';
	$aLocations['index.php'][NULL] = htmlsanitize($CFG['general']['name']).' <a href="index.php">Main Index</a>';
	$aLocations['member.php'][NULL] = 'Recovering Member Details';
	$aLocations['member.php']['action=getprofile'] = 'Viewing Profile of a Forum Member';
	$aLocations['member.php']['action=login'] = 'Logging In';
	$aLocations['member.php']['action=logout'] = 'Logging Out';
	$aLocations['member.php']['action=request'] = 'Recovering Member Details';
	$aLocations['member.php']['action=reset'] = 'Resetting Member Details';
	$aLocations['member.php']['action=mailuser'] = 'E-Mailing a Forum Member';
	$aLocations['memberlist.php'][NULL] = 'Viewing <a href="memberlist.php">Memberlist</a>';
	$aLocations['mod.php'][NULL] = 'Moderating';
	$aLocations['newreply.php'][NULL] = 'Replying to <a href="thread.php?threadid={$aRequest[threadid]}">Thread</a>';
	$aLocations['newthread.php'][NULL] = 'Posting New Thread';
	$aLocations['online.php'][NULL] = 'Viewing <a href="online.php">Who\'s Online</a>';
	$aLocations['poll.php'][NULL] = 'Using the Polling System';
	$aLocations['poll.php']['action=newpoll'] = 'Posting New Poll';
	$aLocations['poll.php']['action=vote'] = 'Voting in Poll';
	$aLocations['poll.php']['action=showresults'] = 'Viewing Results of <a href="poll.php?action=showresults&amp;pollid={$aRequest[pollid]}">Poll</a>';
	$aLocations['posters.php'][NULL] = 'Viewing Who Posted in Thread';
	$aLocations['private.php'][NULL] = 'Using the Private Messaging System';
	$aLocations['private.php']['action=viewmessage'] = 'Reading a Private Message';
	$aLocations['private.php']['action=newmessage'] = 'Sending a Private Message';
	$aLocations['private.php']['action=reply'] = 'Replying to a Private Message';
	$aLocations['register.php'][NULL] = 'Registering...';
	$aLocations['search.php'][NULL] = 'Searching Forums';
	$aLocations['thread.php'][NULL] = 'Viewing <a href="thread.php?threadid={$aRequest[threadid]}">Thread</a>';
	$aLocations['thread.php']['action=showpost'] = 'Viewing <a href="thread.php?action=showpost&amp;postid={$aRequest[postid]}">Post</a>';
	$aLocations['usercp.php'][NULL] = 'Viewing User Control Panel';
	$aLocations['usercp.php']['section=profile'] = 'Editing Forum Profile';
	$aLocations['usercp.php']['section=options'] = 'Editing Forum Options';
	$aLocations['usercp.php']['section=avatar'] = 'Updating User Avatar';
	$aLocations['usercp.php']['section=password'] = 'Editing User Password';
	$aLocations['usercp.php']['section=buddylist'] = 'Editing Buddy List';
	$aLocations['usercp.php']['section=ignorelist'] = 'Editing Ignore List';

	// Are they viewing a page that has more than one location description entry?
	if(count($aLocations[$strLastLocation]) > 1)
	{
		// Yes. Look for the entry that has a querystring that matches the user's location.
		foreach($aLocations[$strLastLocation] as $strQueryString => $v)
		{
			// Extract the querystring.
			parse_str($strQueryString, $x);

			// Parse the querystring.
			foreach($x as $k => $v)
			{
				if($aRequest[$k] != $v)
				{
					$bNoMatch = TRUE;
					break;
				}
			}

			// Do the querystrings match?
			if(!$bNoMatch)
			{
				// Yes, use that location description.
				$strLocationDesc = $aLocations[$strLastLocation][$strQueryString];
			}
			else
			{
				// Unset the flag.
				unset($bNoMatch);
			}
		}

		// Did we find a location description?
		if(!$strLocationDesc)
		{
			// No, so they must be viewing the root page.
			$strLocationDesc = $aLocations[$strLastLocation][NULL];
		}
	}
	else
	{
		// No.
		$strLocationDesc = $aLocations[$strLastLocation][NULL];
	}

	// Parse the location description.
	$strLocationDesc = str_replace('"', '\"', $strLocationDesc);
	@eval("\$strLocationDesc = \"$strLocationDesc\";");

	// Return the location description.
	return $strLocationDesc;
}
?>