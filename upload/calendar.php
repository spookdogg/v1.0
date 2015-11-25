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

	// Build a list of the months.
	$aMonths = array(1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

	// What are they wanting to do?
	switch($_REQUEST['action'])
	{
		case 'viewevent':
		{
			ViewEvent();
		}

		case 'addevent':
		{
			AddEvent();
		}

		case 'editevent':
		{
			EditEvent();
		}

		default:
		{
			ViewCalendar();
		}
	}

// *************************************************************************** \\

// Displays the calendar.
function ViewCalendar()
{
	global $CFG, $dbConn, $aMonths;

	// Constants
	define('DAY',      0);
	define('INMONTH',  1);
	define('ISTODAY',  2);
	define('AUTHOR',  0);
	define('TITLE',   1);
	define('ISPRIVATE',  2);
	define('BIRTHDAY',   0);
	define('USERNAME',   1);

	// Counters
	$iCurrentWeek = 0;
	$iCurrentDay = 1;

	// Does the user have authorization to use the calendar?
	if(!$_SESSION['permissions']['ccalendar'])
	{
		// No. Let them know the bad news.
		Unauthorized();
	}

	// What month do they want?
	$iMonth = (int)$_REQUEST['month'];
	if(($iMonth < 1) || ($iMonth > 12))
	{
		// They don't know what they want. Give them their current month.
		$iMonth = gmtdate('n', $CFG['globaltime']);
	}

	// What year do they want?
	$iYear = abs($_REQUEST['year']);
	if($iYear == 0)
	{
		// They don't know what they want. Give them their current year.
		$iYear = gmtdate('Y', $CFG['globaltime']);
	}

	// Get the start of the week for this user.
	$iStartOfWeek = $_SESSION['weekstart'];

	// Create array containing days of week.
	$aDaysOfWeek = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');

	// UNIX timestamp of the first day of the month in question.
	$tFirstDayMonth = mktime(0, 0, 0, $iMonth, 1, $iYear);

	// How many days does this month have?
	$iDayCount = date('t', $tFirstDayMonth);

	// Retrieve some information about the first day of the month in question.
	$dateComponents = getdate($tFirstDayMonth);
	$strMonthName = $dateComponents['month'];
	$iWeekOffset = $dateComponents['wday'];

	// UNIX timestamp of today as user sees it
	$tToday = mktime(0, 0, 0, gmtdate('n', $CFG['globaltime']), gmtdate('j', $CFG['globaltime']), gmtdate('Y', $CFG['globaltime']));

	// First thing to do is convert the offset of the first day of this month (based on Sunday being
	// first day of week) to our own offset system (based on whatever the user specifies).
	if(($iWeekOffset - $iStartOfWeek) < 0)
	{
		$iWeekOffset = 7 + $iWeekOffset - $iStartOfWeek;
	}
	else
	{
		$iWeekOffset = $iWeekOffset - $iStartOfWeek;
	}

	// If the first day of this month does not land on the first week day, then
	// it means some of the previous month's days are visible on our calendar,
	// and so we need to process them.
	if($iWeekOffset != 0)
	{
		// Get the week offset of the first visible day (of the previous month) in our calendar.
		$iPrevMonthDay = date('d', mktime(0, 0, 0, $iMonth, (1 - $iWeekOffset), $iYear));

		// Subsequently process last month's days, starting with the first visible
		// day (figured above), until we get to this month's first day.
		for($i = 0; $i < $iWeekOffset; $i++)
		{
			// Save the day's information.
			$aWeeks[$iCurrentWeek][$i][DAY] = $iPrevMonthDay;
			$aWeeks[$iCurrentWeek][$i][INMONTH] = FALSE;

			// What is the UNIX timestamp for this day?
			$tPrevMonthDay = mktime(0, 0, 0, ($iMonth - 1), $iPrevMonthDay, $iYear);

			// Is this day today?
			if($tPrevMonthDay == $tToday)
			{
				$aWeeks[$iCurrentWeek][$i][ISTODAY] = TRUE;
			}
			else
			{
				$aWeeks[$iCurrentWeek][$i][ISTODAY] = FALSE;
			}

			// Next day (of previous month) please.
			$iPrevMonthDay++;
		}
	}

	// Process the days of THIS month.
	while($iCurrentDay <= $iDayCount)
	{
		// Save the day's information.
		$aWeeks[$iCurrentWeek][$iWeekOffset][DAY] = $iCurrentDay;
		$aWeeks[$iCurrentWeek][$iWeekOffset][INMONTH] = TRUE;

		// What is the UNIX timestamp for this day?
		$tCurrentDate = mktime(0, 0, 0, $iMonth, $iCurrentDay, $iYear);

		// Is this day today?
		if($tCurrentDate == $tToday)
		{
			$aWeeks[$iCurrentWeek][$iWeekOffset][ISTODAY] = TRUE;
		}
		else
		{
			$aWeeks[$iCurrentWeek][$iWeekOffset][ISTODAY] = FALSE;
		}

		// Next day please.
		$iCurrentDay++;

		// Update the week offset.
		$iWeekOffset++;
		if($iWeekOffset == 7)
		{
			// Reset week offset.
			$iWeekOffset = 0;

			// Increment the week.
			$iCurrentWeek++;
		}
	}

	// If the week offset has not been reset, there are some days of the next month
	// that are visible on our calendar, so we need to process them.
	if($iWeekOffset != 0)
	{
		// How many more days do we have left in this calendar week?
		$iDaysLeft = 7 - $iWeekOffset;

		// Process the remaining days.
		for($iNextMonthDay = 1; $iDaysLeft > 0; $iNextMonthDay++)
		{
			// Store the day's information.
			$aWeeks[$iCurrentWeek][$iWeekOffset][DAY] = $iNextMonthDay;
			$aWeeks[$iCurrentWeek][$iWeekOffset][INMONTH] = FALSE;

			// What is the UNIX timestamp for this day?
			$tCurrentDate = mktime(0, 0, 0, ($iMonth + 1), $iNextMonthDay, $iYear);

			// Is this day today?
			if($tCurrentDate == $tToday)
			{
				$aWeeks[$iCurrentWeek][$iWeekOffset][ISTODAY] = TRUE;
			}
			else
			{
				$aWeeks[$iCurrentWeek][$iWeekOffset][ISTODAY] = FALSE;
			}

			// Update the number of days that are left, as well as the week offset.
			$iDaysLeft--;
			$iWeekOffset++;
		}
	}

	// Figure the UNIX timestamps of the previous month and the next month.
	$tPrevMonth = mktime(0, 0, 0, ($iMonth - 1), 1, $iYear);
	$tNextMonth = mktime(0, 0, 0, ($iMonth + 1), 1, $iYear);

	// make sure our month has a leading zero
	$iMonthWZero = str_pad($iMonth, 2, '0', STR_PAD_LEFT);

	// Get any birthdays that come this month.
	$dbConn->query("SELECT id, birthday, username FROM citizen WHERE birthday LIKE '____-{$iMonthWZero}-__'");
	while($aSQLResult = $dbConn->getresult())
	{
		$iUserID = $aSQLResult[0];
		$iDay = date('j', strtotime($aSQLResult[1]));
		$aBirthdays[$iDay][$iUserID][BIRTHDAY] = $aSQLResult[1];
		$aBirthdays[$iDay][$iUserID][USERNAME] = $aSQLResult[2];
	}

	// Get any events that come this month.
	$dbConn->query("SELECT id, startdate, author, title, private FROM event WHERE startdate LIKE '{$iYear}-{$iMonthWZero}-__'");
	while($aSQLResult = $dbConn->getresult())
	{
		$iEventID = $aSQLResult[0];
		$iDay = date('j', strtotime($aSQLResult[1]));
		$aEvents[$iDay][$iEventID][AUTHOR] = $aSQLResult[2];
		$aEvents[$iDay][$iEventID][TITLE] = $aSQLResult[3];
		$aEvents[$iDay][$iEventID][ISPRIVATE] = $aSQLResult[4];
	}

	// Template
	require("./skins/{$CFG['skin']}/calendar.tpl.php");

	// Send the page.
	exit;
}

// *************************************************************************** \\

// User wants to add a new calendar event.
function AddEvent()
{
	global $CFG, $aMonths, $aSmilies, $aPostIcons;

	// Default values.
	$aEventInfo['month'] = gmtdate('m', $CFG['globaltime']);
	$aEventInfo['day'] = gmtdate('j', $CFG['globaltime']);
	$aEventInfo['year'] = gmtdate('Y', $CFG['globaltime']);
	$aEventInfo['parseurls'] = 0;
	$aEventInfo['parseemails'] = 1;
	$aEventInfo['dsmilies'] = 0;

	// Do they have authorization to add events?
	if(!$_SESSION['permissions']['cmakeevent'])
	{
		// No. Let them know the bad news.
		Unauthorized();
	}

	// What kind of event do they want to create?
	switch($_REQUEST['type'])
	{
		case 'public':
		{
			// Set the event type.
			$strType = ' Public';
			$aEventInfo['ispublic'] = 1;

			// Does the user have authorization to add public events?
			if(!$_SESSION['permissions']['cmakepubevent'])
			{
				// No, so let them know the bad news.
				Unauthorized();
			}

			break;
		}

		case 'private':
		{
			// Set the event type.
			$strType = ' Private';
			$aEventInfo['ispublic'] = 0;

			// Does the user have authorization to add private events?
			if(!$_SESSION['permissions']['cmakeevent'])
			{
				// No, so let them know the bad news.
				Unauthorized();
			}

			break;
		}

		default:
		{
			// Set the event type.
			$aEventInfo['ispublic'] = (int)(bool)$_REQUEST['type'];
			$strType = $aEventInfo['ispublic'] ? ' Public' : ' Private';

			// Does the user have authorization to add events?
			if(($aEventInfo['ispublic'] && !$_SESSION['permissions']['cmakepubevent']) || (!$aEventInfo['ispublic'] && !$_SESSION['permissions']['cmakeevent']))
			{
				// No, so let them know the bad news.
				Unauthorized();
			}

			break;
		}
	}

	// Are they submitting or previewing??
	if(isset($_REQUEST['submit']))
	{
		// Yes, so out with the default values and in with what they submitted.
		$aEventInfo['title'] = trim($_REQUEST['title']);
		$aEventInfo['body'] = trim($_REQUEST['message']);
		$aEventInfo['month'] = (int)$_REQUEST['month'];
		$aEventInfo['day'] = (int)$_REQUEST['day'];
		$aEventInfo['year'] = (int)$_REQUEST['year'];
		$aEventInfo['parseurls'] = (int)(bool)$_REQUEST['parseurls'];
		$aEventInfo['parseemails'] = (int)(bool)$_REQUEST['parseemails'];
		$aEventInfo['dsmilies'] = (int)(bool)$_REQUEST['dsmilies'];

		// Submitting or just previewing?
		if($_REQUEST['submit'] == 'Submit Event')
		{
			// Submitting.
			$aError = AddEventNow($aEventInfo);
		}
	}

	// Template
	require("./skins/{$CFG['skin']}/addevent.tpl.php");

	// Send the page.
	exit;
}

// The user hit the Submit Event button, so that's what we'll try to do.
function AddEventNow($aEventInfo)
{
	global $CFG, $dbConn;

	// Title
	if($aEventInfo['title'] == '')
	{
		// They either put in only whitespace or nothing at all.
		$aError[] = 'You must specify the event title.';
	}
	else if(strlen($aEventInfo['title']) > $CFG['maxlen']['subject'])
	{
		// The title they specified is too long.
		$aError[] = "The title you specified is longer than {$CFG['maxlen']['subject']} characters.";
	}
	$strTitle = $dbConn->sanitize($aEventInfo['title']);

	// Event Information
	if($aEventInfo['body'] == '')
	{
		// They either put in only whitespace or nothing at all.
		$aError[] = 'You must specify the event information.';
	}
	else if(strlen($aEventInfo['body']) > $CFG['maxlen']['messagebody'])
	{
		// The event information they specified is too long.
		$aError[] = "The event information you specified is longer than {$CFG['maxlen']['messagebody']} characters.";
	}
	if($aEventInfo['parseemails'])
	{
		$strEventInfo = $dbConn->sanitize(ParseEMails($aEventInfo['body']));
	}
	else
	{
		$strEventInfo = $dbConn->sanitize($aEventInfo['body']);
	}

	// Date
	if(!checkdate($aEventInfo['month'], $aEventInfo['day'], $aEventInfo['year']))
	{
		// They specified an invalid Gregorian date.
		$aError[] = 'The date you specified is invalid. The month, day, and year are all required.';
	}
	$strDate = sprintf('%04d-%02d-%02d', $aEventInfo['year'], $aEventInfo['month'], $aEventInfo['day']);

	// If there was an error, let's return it.
	if($aError)
	{
		return $aError;
	}

	$bIsPrivate = ($aEventInfo['ispublic']) ? 0 : 1;
	// Add the event into the event table.
	$dbConn->query("INSERT INTO event(author, startdate, title, body, private, dsmilies, ipaddress) VALUES({$_SESSION['userid']}, '{$strDate}', '{$strTitle}', '{$strEventInfo}', {$bIsPrivate}, {$aEventInfo['dsmilies']}, {$_SESSION['userip']})");

	// Finally, we need to get the ID of the event we just created.
	$iEventID = $dbConn->getinsertid('event');

	// Let the user know it was a success.
	Msg("<b>The event was successfully added.</b><br /><br /><span class=\"smaller\">You should be redirected momentarily. Click <a href=\"calendar.php?action=viewevent&amp;eventid={$iEventID}\">here</a> if you do not want to wait any longer or if you are not redirected.</span>", "calendar.php?action=viewevent&eventid={$iEventID}");
}

// *************************************************************************** \\

// User wants to edit a calendar event.
function EditEvent()
{
	global $CFG, $dbConn, $aMonths, $aSmilies, $aPostIcons;

	// What event do they want to edit?
	$iEventID = (int)$_REQUEST['eventid'];

	// Get the event information.
	$dbConn->query("SELECT startdate, title, body, private, dsmilies FROM event WHERE id={$iEventID} AND author={$_SESSION['userid']} LIMIT 1");
	if(!$aEventInfo = $dbConn->getresult(TRUE))
	{
		Msg('Invalid event specified.');
	}

	// Default values.
	list($aEventInfo['year'], $aEventInfo['month'], $aEventInfo['day']) = sscanf($aEventInfo['startdate'], '%04d-%02d-%02d');
	$aEventInfo['parseurls'] = 0;
	$aEventInfo['parseemails'] = 1;

	// Are they submitting or previewing??
	if(isset($_REQUEST['submit']))
	{
		// Yes, so out with the default values and in with what they submitted.
		$aEventInfo['title'] = trim($_REQUEST['title']);
		$aEventInfo['body'] = trim($_REQUEST['message']);
		$aEventInfo['month'] = (int)$_REQUEST['month'];
		$aEventInfo['day'] = (int)$_REQUEST['day'];
		$aEventInfo['year'] = (int)$_REQUEST['year'];
		$aEventInfo['parseurls'] = (int)(bool)$_REQUEST['parseurls'];
		$aEventInfo['parseemails'] = (int)(bool)$_REQUEST['parseemails'];
		$aEventInfo['dsmilies'] = (int)(bool)$_REQUEST['dsmilies'];

		// Submitting or just previewing?
		if($_REQUEST['submit'] == 'Save Changes')
		{
			// Submitting.
			$aError = EditEventNow($iEventID, $aEventInfo);
		}
	}

	// Template
	require("./skins/{$CFG['skin']}/editevent.tpl.php");

	// Send the page.
	exit;
}

// The user hit the Save Changes button, so that's what we'll try to do.
function EditEventNow($iEventID, $aEventInfo)
{
	global $CFG, $dbConn;

	// Title
	if($aEventInfo['title'] == '')
	{
		// They either put in only whitespace or nothing at all.
		$aError[] = 'You must specify the event title.';
	}
	else if(strlen($aEventInfo['title']) > $CFG['maxlen']['subject'])
	{
		// The title they specified is too long.
		$aError[] = "The title you specified is longer than {$CFG['maxlen']['subject']} characters.";
	}
	$strTitle = $dbConn->sanitize($aEventInfo['title']);

	// Event Information
	if($aEventInfo['body'] == '')
	{
		// They either put in only whitespace or nothing at all.
		$aError[] = 'You must specify the event information.';
	}
	else if(strlen($aEventInfo['body']) > $CFG['maxlen']['messagebody'])
	{
		// The event information they specified is too long.
		$aError[] = "The event information you specified is longer than {$CFG['maxlen']['messagebody']} characters.";
	}
	if($aEventInfo['parseemails'])
	{
		$strEventInfo = $dbConn->sanitize(ParseEMails($aEventInfo['body']));
	}
	else
	{
		$strEventInfo = $dbConn->sanitize($aEventInfo['body']);
	}

	// Date
	if(!checkdate($aEventInfo['month'], $aEventInfo['day'], $aEventInfo['year']))
	{
		// They specified an invalid Gregorian date.
		$aError[] = 'The date you specified is invalid. The month, day, and year are all required.';
	}
	$strDate = sprintf('%04d-%02d-%02d', $aEventInfo['year'], $aEventInfo['month'], $aEventInfo['day']);

	// If there was an error, let's return it.
	if($aError)
	{
		return $aError;
	}

	$bIsPrivate = ($aEventInfo['private']) ? 1 : 0;
	// Add the event into the event table.
	$dbConn->query("UPDATE event SET startdate='{$strDate}', title='{$strTitle}', body='{$strEventInfo}', dsmilies={$aEventInfo['dsmilies']}, ipaddress={$_SESSION['userip']} WHERE id={$iEventID}");

	// Let the user know it was a success.
	Msg("<b>The event was successfully saved.</b><br /><br /><span class=\"smaller\">You should be redirected momentarily. Click <a href=\"calendar.php?action=viewevent&amp;eventid={$iEventID}\">here</a> if you do not want to wait any longer or if you are not redirected.</span>", "calendar.php?action=viewevent&eventid={$iEventID}");
}

// *************************************************************************** \\

// Displays an event's information.
function ViewEvent()
{
	global $CFG, $dbConn, $aSmilies;

	// Does the user have authorization to use the calendar?
	if(!$_SESSION['permissions']['ccalendar'])
	{
		// No. Let them know the bad news.
		Unauthorized();
	}

	// What event do they want to view?
	$iEventID = (int)$_REQUEST['eventid'];

	// Get the information for this event.
	$dbConn->query("SELECT author, startdate, title, body, private, dsmilies FROM event WHERE id={$iEventID}");
	if(!(list($iAuthor, $strDate, $strTitle, $strEventInfo, $bPrivate, $bDisableSmilies) = $dbConn->getresult()))
	{
		Msg("Invalid event specified.{$CFG['msg']['invalidlink']}");
	}

	$bPublic = !$bPrivate;

	// Are they allowed to view this event?
	if((!$bPublic) && ($iAuthor != $_SESSION['userid']))
	{
		// Nope. Give them the Unauthorized page.
		Unauthorized();
	}

	// Parse the message.
	$strEventInfo = ParseMessage($strEventInfo, $bDisableSmilies);

	// Template
	require("./skins/{$CFG['skin']}/viewevent.tpl.php");

	// Send the page.
	exit;
}
?>