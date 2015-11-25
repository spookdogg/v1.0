<?php
	// Header.
	$strPageTitle = " :: Calendar :. New{$strType} Event";
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<script src="toolbar.inc.js" language="JavaScript" type="text/javascript"></script>
<script src="smilies.inc.js" language="JavaScript" type="text/javascript"></script>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; <a href="calendar.php">Calendar</a> &gt; New<?php echo($strType); ?> Event</b></td>
</tr>
</table>

<?php
	// Display any errors.
	if(is_array($aError))
	{
		DisplayErrors($aError);
	}
	else if($_REQUEST['submit'] == 'Preview Event')
	{
		// Make a copy of the event information, so we can parse
		// it for the preview, yet still have the original.
		$strParsedInfo = $aEventInfo['body'];

		// Put [email] tags around suspected e-mail addresses if they want us to.
		if($aEventInfo['parseemails'])
		{
			$strParsedInfo = ParseEMails($strParsedInfo);
		}

		// Parse any BB code in the message.
		$strParsedInfo = ParseMessage($strParsedInfo, $aEventInfo['dsmilies']);
?>

<br />
<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" cellspacing="1" cellpadding="4" border="0" align="center">

<tr class="heading">
	<td colspan="2" align="center" class="medium"><?php echo(htmlsanitize($aEventInfo['title'])); ?></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium" valign="top"><b>Type</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><?php echo($aEventInfo['ispublic'] ? 'Public' : 'Private'); ?> Event</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium" valign="top"><b>Date</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><?php echo(date('m-d-Y', strtotime(sprintf('%04d-%02d-%02d', $aEventInfo['year'], $aEventInfo['month'], $aEventInfo['day'])))); ?></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium" valign="top" nowrap="nowrap"><b>Event Information</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><?php echo($strParsedInfo); ?></td>
</tr>

</table>

<br />

<?php
	}
	else
	{
		echo('<br />');
	}
?>

<form name="theform" action="calendar.php" enctype="multipart/form-data" method="post">
<input type="hidden" name="action" value="addevent" />
<input type="hidden" name="type" value="<?php echo($aEventInfo['ispublic']); ?>" />

<table cellpadding="4" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" align="center">

<tr class="heading">
	<td colspan="2" class="medium">New<?php echo($strType); ?> Event</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium" nowrap="nowrap"><b>Logged In As</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><?php if($_SESSION['loggedin']){echo(htmlsanitize($_SESSION['username']).' <span class="smaller">[<a href="member.php?action=logout">Logout</a>]</span>');}else{echo('<i>Not logged in.</i> <span class="smaller">[<a href="member.php?action=login">Login</a>]</span>');} ?></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium" nowrap="nowrap"><b>Date</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="smaller">
		<table cellpadding="2" cellspacing="0" border="0">
		<tr>
			<td align="left" class="smaller">&nbsp;Month</td>
			<td align="left" class="smaller">&nbsp;Day</td>
			<td align="left" class="smaller">&nbsp;Year</td>
		</tr>
		<tr>
			<td>
				<select name="month">
<?php
	// Print out an option for each month of the year.
	foreach($aMonths as $iMonthID => $strMonth)
	{
		$strSelected = ($iMonthID == $aEventInfo['month']) ? ' selected="selected"' : '';
		echo("\t\t\t\t\t<option value=\"{$iMonthID}\"{$strSelected}>{$strMonth}</option>\n");
	}
?>
				</select>
			</td>
			<td>
				<select name="day">
<?php
	// Print out an option for each day in the month.
	for($iDayID = 1; $iDayID < 32; $iDayID++)
	{
		$strSelected = ($iDayID == $aEventInfo['day']) ? ' selected="selected"' : '';
		echo("\t\t\t\t\t<option value=\"{$iDayID}\"{$strSelected}>{$iDayID}</option>\n");
	}
?>
				</select>
			</td>
			<td><input type="text" name="year" size="4" maxlength="4" value="<?php echo($aEventInfo['year']); ?>" /></td>
		</tr>
		</table>
	</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium" nowrap="nowrap"><b>Title</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>"><input type="text" name="title" size="40" maxlength="<?php echo($CFG['maxlen']['subject']); ?>" value="<?php echo(htmlsanitize($aEventInfo['title'])); ?>" /></td>
</tr>

<tr>
	<td valign="top" bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium" nowrap="nowrap"><b>BB Code</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><?php ShowToolbar(); ?></td>
</tr>

<tr>
	<td valign="top" bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium" nowrap="nowrap">
		<b>Event Information</b><br /><br /><br />

		<table cellpadding="3" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" style="border-width: 2px; border-style: outset;" align="center">
			<tr>
				<td colspan="3" align="center" bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="small" style="border-width: 1px; border-style: inset"><b>Smilies</b></td>
			</tr>
<?php
	// Display the Smilie table.
	SmilieTable($aSmilies);
?>
		</table>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>">
		<textarea name="message" cols="70" rows="20"><?php echo(htmlsanitize($aEventInfo['body'])); ?></textarea>
		<div class="smaller">[<a href="#" onclick="javascript:alert('The maximum permitted length is <?php echo($CFG['maxlen']['messagebody']); ?> characters.\n\nYour event information is '+document.theform.message.value.length+' characters long.');">Check length.</a>]</div>
	</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium" nowrap="nowrap"><b>Options</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td valign="top"><input type="checkbox" name="parseurls"<?php if($aEventInfo['parseurls']){echo(' checked="checked"');} ?> disabled="disabled" /></td>
			<td width="100%" class="smaller"><b>Automatically parse URLs?</b> This will automatically put [url] and [/url] around Internet addresses.</td>
		</tr>
		<tr><td colspan="2"><img src="images/space.png" width="1" height="3" alt="" /></td></tr>
		<tr>
			<td valign="top"><input type="checkbox" name="parseemails"<?php if($aEventInfo['parseemails']){echo(' checked="checked"');} ?> /></td>
			<td width="100%" class="smaller"><b>Automatically parse e-mail addresses?</b> This will automatically put [email] and [/email] around e-mail addresses.</td>
		</tr>
		<tr><td colspan="2"><img src="images/space.png" width="1" height="3" alt="" /></td></tr>
		<tr>
			<td valign="top"><input type="checkbox" name="dsmilies"<?php if($aEventInfo['dsmilies']){echo(' checked="checked"');} ?> /></td>
			<td width="100%" class="smaller"><b>Disable smilies in this event?</b> This will disable the automatic parsing of smilie codes into smilie images.</td>
		</tr>
	</table>
	</td>
</tr>

</table>

<br />

<div style="text-align: center;">
	<input type="submit" name="submit" value="Submit Event" accesskey="s" />
	<input type="submit" name="submit" value="Preview Event" accesskey="s" />
</div>

</form>

<br />

<script language="JavaScript" type="text/javascript">
<!--
	document.theform.status.value='';
//-->
</script>

<?php
	// Footer.
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>