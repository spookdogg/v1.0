<?php
	// Header.
	$strPageTitle = " :: Calendar :. {$strMonthName} {$iYear}";
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="50%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; <a href="calendar.php">Calendar</a> &gt; <?php echo("{$strMonthName} {$iYear}"); ?></b></td>
	<td width="50%" align="right" valign="top"><a href="calendar.php?action=addevent&amp;type=public"><img src="images/public_event.png" border="0" alt="Add a public event" /></a><img src="images/space.png" width="8" height="1" alt="" /><a href="calendar.php?action=addevent&amp;type=private"><img src="images/private_event.png" border="0" alt="Add a private event" /></a></td>
</tr>
</table><br />

<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" cellpadding="4" cellspacing="1" border="0" width="100%">

<tr class="section">
	<td width="100%" align="left" colspan="7" class="medium"><?php echo(htmlsanitize($CFG['general']['name'])); ?> Calendar</td>
</tr>

<tr class="heading">
<?php
	// Display the days of the week.
	for($i = $iStartOfWeek; $i < 7; $i++)
	{
		echo("\t<td width=\"14%\" align=\"center\" class=\"smaller\">{$aDaysOfWeek[$i]}</td>\n");
	}
	if($iStartOfWeek != 0)
	{
		for($i = 0; $i < $iStartOfWeek; $i++)
		{
			echo("\t<td width=\"14%\" align=\"center\" class=\"smaller\">{$aDaysOfWeek[$i]}</td>\n");
		}
	}
?>
</tr>

<?php
	// Display each week in the month.
	foreach($aWeeks as $aWeek)
	{
		// Start a new week row.
		echo("<tr>\n");

		// Display each day in this week.
		foreach($aWeek as $aDay)
		{
			// Store the day information temporarily.
			$iDay = $aDay[DAY];
			$bInMonth = $aDay[INMONTH];
			$bIsToday = $aDay[ISTODAY];

			// Set the colors.
			if($bIsToday)
			{
				// Day is today.
				$strBGColor = $CFG['style']['calcolor']['today']['bgcolor'];
				$strFGColor = $CFG['style']['calcolor']['today']['txtcolor'];
			}
			else if($bInMonth)
			{
				// Day isn't today, but is in the month.
				$strBGColor = $CFG['style']['calcolor']['dateb']['bgcolor'];
				$strFGColor = $CFG['style']['calcolor']['dateb']['txtcolor'];
			}
			else
			{
				// Day isn't today, nor is it in the month.
				$strBGColor = $CFG['style']['calcolor']['datea']['bgcolor'];
				$strFGColor = $CFG['style']['calcolor']['datea']['txtcolor'];
			}
?>	<td align="left" valign="top" height="100" bgcolor="<?php echo($strBGColor); ?>" class="medium"<?php if($bIsToday){echo(' style="border: 2px; border-style: outset;"');} ?>>
		<span style="color: <?php echo($strFGColor); ?>;"><?php echo($iDay); ?></span>
<?php
			// Display any birthdays for this day.
			if(isset($aBirthdays[$iDay]) && $bInMonth)
			{
?>		<div class="smaller" style="margin: 3px;">
<?php
				foreach($aBirthdays[$iDay] as $iUserID => $aBirthday)
				{
					// Calculate their age.
					list($year) = sscanf($aBirthday[BIRTHDAY], '%04u-%02u-%02u');
					if($year)
					{
						$iAge = $iYear - $year;
					}
?>			- <a href="member.php?action=getprofile&amp;userid=<?php echo($iUserID); ?>"><?php echo(htmlsanitize($aBirthday[USERNAME])); ?>'s birthday</a><?php if($iAge > 0){echo(" ($iAge)");} ?><br />
<?php
					// Reset the age for the next guy.
					$iAge = NULL;
				}
?>		</div>
<?php
			}

			// Display any events for this day.
			if(isset($aEvents[$iDay]) && $bInMonth)
			{
?>		<div class="smaller" style="margin: 3px;">
<?php
				foreach($aEvents[$iDay] as $iEventID => $aEvent)
				{
					// Only display if it's either public or if it's private and the user that created it is logged in.
					if(($aEvent[ISPRIVATE] == 0) || (($aEvent[ISPRIVATE] == 1) && ($_SESSION['userid'] == $aEvent[AUTHOR])))
					{
?>			- <a href="calendar.php?action=viewevent&amp;eventid=<?php echo($iEventID); ?>"><?php echo(htmlsanitize($aEvent[TITLE])); ?></a><br />
<?php
					}
				}
?>		</div>
<?php
			}
?>
	</td>
<?php
		}

		// End the week row.
		echo("</tr>\n");
	}
?>

</table><br />

<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" cellpadding="4" cellspacing="1" border="0" width="100%">
<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" width="34%" align="center" valign="middle" class="smaller"><a href="calendar.php?action=addevent&amp;type=public"><img src="images/public_event.png" border="0" alt="Add a public event" /></a><img src="images/space.png" width="8" height="1" alt="" /><a href="calendar.php?action=addevent&amp;type=private"><img src="images/private_event.png" border="0" alt="Add a private event" /></a></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" width="33%" align="center" valign="middle" class="smaller">
	<form action="calendar.php" method="get">
	<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td valign="middle"><select name="month">
<?php
	// Print out an option for each month of the year.
	foreach($aMonths as $iMonthID => $strMonth)
	{
		$strSelected = ($iMonthID == $iMonth) ? ' selected="selected"' : '';
		echo("\t\t\t<option value=\"{$iMonthID}\"{$strSelected}>{$strMonth}</option>\n");
	}
?>
		</select> <select name="year">
<?php
	for($i = $iYear - 3; $i <= ($iYear + 3); $i++)
	{
?>			<option value="<?php echo($i); ?>"<?php if($i == $iYear){echo(' selected="selected"');} ?>><?php echo($i); ?></option>
<?php
	}
?>		</select></td>
		<td class="smaller" valign="middle">&nbsp;<input type="image" src="images/go.png" /></td>
	</tr>
	</table>
	</form>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" width="33%" align="center" valign="middle" class="smaller"><img style="vertical-align: middle;" src="images/larrow.png" align="middle" alt="" /> <b><a href="calendar.php?month=<?php echo(date('n', $tPrevMonth)); ?>&amp;year=<?php echo(date('Y', $tPrevMonth)); ?>"><?php echo(date('F Y', $tPrevMonth)); ?></a></b> | <b><a href="calendar.php?month=<?php echo(date('n', $tNextMonth)); ?>&amp;year=<?php echo(date('Y', $tNextMonth)); ?>"><?php echo(date('F Y', $tNextMonth)); ?></a></b> <img style="vertical-align: middle;" src="images/rarrow.png" align="middle" alt="" /></td>
</tr>
</table>

<?php
	// Footer.
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>