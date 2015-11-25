<?php
	// Header
	$strPageTitle = ' :: Admin Control Panel :. General Options';
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; <a href="admincp.php">Admin Control Panel</a> &gt; General Options</b></td>
</tr>
</table><br />

<?php
	// Admin CP menu.
	PrintCPMenu();

	// Display any errors.
	if($aError)
	{
		DisplayErrors($aError);
	}
	else
	{
		echo('<br />');
	}
?>

<form name="theform" action="admincp.php" method="post">
<input type="hidden" name="section" value="general" />
<table cellpadding="4" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" align="center">

<tr class="section">
	<td colspan="2" align="center" class="medium">General Options</td>
</tr>

<tr class="heading"><td colspan="2" align="left" class="medium">Forum Information</td></tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<b>Forum Name</b>
		<div class="smaller">This is name of your forums, which appears all over forum pages.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="text" name="name" size="35" value="<?php echo(htmlsanitize($aOptions['name'])); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium">
		<b>Copyright Notice</b>
		<div class="smaller">This is copyright text that appears at the bottom of forum pages.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="text" name="copyright" size="35" value="<?php echo(htmlsanitize($aOptions['copyright'])); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<b>Administrator's E-Mail</b>
		<div class="smaller">Administrator's e-mail address</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="text" name="email" size="35" value="<?php echo(htmlsanitize($aOptions['email'])); ?>" /></td>
</tr>

<tr class="heading"><td colspan="2" align="left" class="medium">Page Output</td></tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<b>Enable Compression?</b>
		<div class="smaller">If you select Yes, pages will be compressed before they are sent to the client. This can help save server bandwidth, but requires additional processing and memory.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="radio" name="enablegzip" value="1"<?php if($aOptions['enablegzip']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="enablegzip" value="0"<?php if(!$aOptions['enablegzip']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium">
		<b>Compression Level</b>
		<div class="smaller">This is the level used when page compression is enabled.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium">
		<select name="gziplevel">
			<option value="0"<?php if($aOptions['gziplevel']==0){echo(' selected="selected"');} ?>>0 - None</option>
			<option value="1"<?php if($aOptions['gziplevel']==1){echo(' selected="selected"');} ?>>1</option>
			<option value="2"<?php if($aOptions['gziplevel']==2){echo(' selected="selected"');} ?>>2</option>
			<option value="3"<?php if($aOptions['gziplevel']==3){echo(' selected="selected"');} ?>>3</option>
			<option value="4"<?php if($aOptions['gziplevel']==4){echo(' selected="selected"');} ?>>4</option>
			<option value="5"<?php if($aOptions['gziplevel']==5){echo(' selected="selected"');} ?>>5 - Medium</option>
			<option value="6"<?php if($aOptions['gziplevel']==6){echo(' selected="selected"');} ?>>6</option>
			<option value="7"<?php if($aOptions['gziplevel']==7){echo(' selected="selected"');} ?>>7</option>
			<option value="8"<?php if($aOptions['gziplevel']==8){echo(' selected="selected"');} ?>>8</option>
			<option value="9"<?php if($aOptions['gziplevel']==9){echo(' selected="selected"');} ?>>9 - Highest</option>
		</select>
	</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<b>Enable Buffering?</b>
		<div class="smaller">If you select Yes, page output will be buffered until it is ready to be sent to the client. This can decrease page generation time considerably if you have Compression disabled, however it can consume more memory.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="radio" name="bufferoutput" value="1"<?php if($aOptions['bufferoutput']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="bufferoutput" value="0"<?php if(!$aOptions['bufferoutput']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr class="heading"><td colspan="2" align="left" class="medium">Forum Time</td></tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<b>Default Time Offset</b>
		<div class="smaller">This is the offset used for unregistered users or members not logged in. The time is now <?php echo(gmdate('g:ia')); ?> GMT. Select the offset that most of your users observe.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>">
		<select name="timeoffset" class="small">
			<option value="-43200"<?php if($aOptions['timeoffset'] == -43200){echo(' selected="selected"');} ?>>[GMT -12:00] International Date Line West</option>
			<option value="-39600"<?php if($aOptions['timeoffset'] == -39600){echo(' selected="selected"');} ?>>[GMT -11:00] Midway Islands, Samoa</option>
			<option value="-36000"<?php if($aOptions['timeoffset'] == -36000){echo(' selected="selected"');} ?>>[GMT -10:00] Hawaii-Aleutian Time</option>
			<option value="-32400"<?php if($aOptions['timeoffset'] == -32400){echo(' selected="selected"');} ?>>[GMT -09:00] Alaska Time</option>
			<option value="-28800"<?php if($aOptions['timeoffset'] == -28800){echo(' selected="selected"');} ?>>[GMT -08:00] Pacific Time (US &amp; Canada)</option>
			<option value="-25200"<?php if($aOptions['timeoffset'] == -25200){echo(' selected="selected"');} ?>>[GMT -07:00] Mountain Time (US &amp; Canada)</option>
			<option value="-21600"<?php if($aOptions['timeoffset'] == -21600){echo(' selected="selected"');} ?>>[GMT -06:00] Central Time (US &amp; Canada)</option>
			<option value="-18000"<?php if($aOptions['timeoffset'] == -18000){echo(' selected="selected"');} ?>>[GMT -05:00] Eastern Time (US &amp; Canada)</option>
			<option value="-14400"<?php if($aOptions['timeoffset'] == -14400){echo(' selected="selected"');} ?>>[GMT -04:00] Atlantic Time (Canada)</option>
			<option value="-12600"<?php if($aOptions['timeoffset'] == -12600){echo(' selected="selected"');} ?>>[GMT -03:30] Newfoundland Time</option>
			<option value="-10800"<?php if($aOptions['timeoffset'] == -10800){echo(' selected="selected"');} ?>>[GMT -03:00] Brasilia, Buenos Aires, Greenland</option>
			<option value="-7200"<?php if($aOptions['timeoffset'] == -7200){echo(' selected="selected"');} ?>>[GMT -02:00] Mid-Atlantic Time</option>
			<option value="-3600"<?php if($aOptions['timeoffset'] == -3600){echo(' selected="selected"');} ?>>[GMT -01:00] Azores, Cape Verde Island</option>
			<option value="0"<?php if($aOptions['timeoffset'] == 0){echo(' selected="selected"');} ?>>[GMT +00:00] Western Europe Time</option>
			<option value="3600"<?php if($aOptions['timeoffset'] == 3600){echo(' selected="selected"');} ?>>[GMT +01:00] Central Europe Time</option>
			<option value="7200"<?php if($aOptions['timeoffset'] == 7200){echo(' selected="selected"');} ?>>[GMT +02:00] Eastern Europe Time</option>
			<option value="10800"<?php if($aOptions['timeoffset'] == 10800){echo(' selected="selected"');} ?>>[GMT +03:00] Eastern Africa Time</option>
			<option value="12600"<?php if($aOptions['timeoffset'] == 12600){echo(' selected="selected"');} ?>>[GMT +03:30] Middle East Time</option>
			<option value="14400"<?php if($aOptions['timeoffset'] == 14400){echo(' selected="selected"');} ?>>[GMT +04:00] Near East Time</option>
			<option value="16200"<?php if($aOptions['timeoffset'] == 16200){echo(' selected="selected"');} ?>>[GMT +04:30] Kabul Time</option>
			<option value="18000"<?php if($aOptions['timeoffset'] == 18000){echo(' selected="selected"');} ?>>[GMT +05:00] Pakistan-Lahore Time</option>
			<option value="19800"<?php if($aOptions['timeoffset'] == 19800){echo(' selected="selected"');} ?>>[GMT +05:30] India Time</option>
			<option value="20700"<?php if($aOptions['timeoffset'] == 20700){echo(' selected="selected"');} ?>>[GMT +05:45] Kathmandu Time</option>
			<option value="21600"<?php if($aOptions['timeoffset'] == 21600){echo(' selected="selected"');} ?>>[GMT +06:00] Bangladesh Time</option>
			<option value="25200"<?php if($aOptions['timeoffset'] == 25200){echo(' selected="selected"');} ?>>[GMT +07:00] Christmas Island Time</option>
			<option value="28800"<?php if($aOptions['timeoffset'] == 28800){echo(' selected="selected"');} ?>>[GMT +08:00] China-Taiwan Time</option>
			<option value="32400"<?php if($aOptions['timeoffset'] == 32400){echo(' selected="selected"');} ?>>[GMT +09:00] Japan Time</option>
			<option value="34200"<?php if($aOptions['timeoffset'] == 34200){echo(' selected="selected"');} ?>>[GMT +09:30] Australia Central Time</option>
			<option value="36000"<?php if($aOptions['timeoffset'] == 36000){echo(' selected="selected"');} ?>>[GMT +10:00] Australia Eastern Time</option>
			<option value="39600"<?php if($aOptions['timeoffset'] == 39600){echo(' selected="selected"');} ?>>[GMT +11:00] Soloman Time</option>
			<option value="43200"<?php if($aOptions['timeoffset'] == 43200){echo(' selected="selected"');} ?>>[GMT +12:00] New Zealand Time</option>
		</select>
	</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium">
		<b>Daylight Saving Time/Summer Time?</b>
		<div class="smaller">Adjust this setting if the forum times appear off, despite the Time Offset being correct.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium">
		<input type="radio" name="dst" value="0"<?php if(!$aOptions['dst']){echo(' checked="checked"');} ?> />No. &nbsp; <input type="radio" name="dst" value="1"<?php if($aOptions['dst']){echo(' checked="checked"');} ?> />Yes. Adjustment is <input style="font-size: 11px; text-align: right;" type="text" name="dsth" size="1" maxlength="2" value="<?php echo($aOptions['dsth']); ?>" />:<input style="font-size: 11px;" type="text" name="dstm" size="2" maxlength="2" value="<?php printf('%02u', $aOptions['dstm']); ?>" /> hours.
	</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<b>Start Of The Week</b>
		<div class="smaller">This is the starting day of the week used in displaying the calendar for guests and members not logged in.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>">
		<select name="weekstart" class="small">
			<option value="0"<?php if($aOptions['weekstart']==0){echo(' selected="selected"');} ?>>Sunday</option>
			<option value="1"<?php if($aOptions['weekstart']==1){echo(' selected="selected"');} ?>>Monday</option>
			<option value="2"<?php if($aOptions['weekstart']==2){echo(' selected="selected"');} ?>>Tuesday</option>
			<option value="3"<?php if($aOptions['weekstart']==3){echo(' selected="selected"');} ?>>Wednesday</option>
			<option value="4"<?php if($aOptions['weekstart']==4){echo(' selected="selected"');} ?>>Thursday</option>
			<option value="5"<?php if($aOptions['weekstart']==5){echo(' selected="selected"');} ?>>Friday</option>
			<option value="6"<?php if($aOptions['weekstart']==6){echo(' selected="selected"');} ?>>Saturday</option>
		</select>
	</td>
</tr>

<tr class="heading"><td colspan="2" align="left" class="medium">File Paths</td></tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<b>Public Avatars</b>
		<div class="smaller">This is the path to the public avatars folder, relative to your forum's directory.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="text" name="avatarspath" size="35" value="<?php echo(htmlsanitize($aOptions['avatarspath'])); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium">
		<b>Smilies</b>
		<div class="smaller">This is the path to the smilies folder, relative to your forum's directory.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="text" name="smiliespath" size="35" value="<?php echo(htmlsanitize($aOptions['smiliespath'])); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<b>Post Icons</b>
		<div class="smaller">This is the path to the post icons folder, relative to your forum's directory.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="text" name="posticonspath" size="35" value="<?php echo(htmlsanitize($aOptions['posticonspath'])); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium">
		<b>Cookies</b>
		<div class="smaller">This is the path on the server (relative to your domain) in which session cookies will be available.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="text" name="cookiespath" size="35" value="<?php echo(htmlsanitize($aOptions['cookiespath'])); ?>" /></td>
</tr>

<tr class="heading"><td colspan="2" align="left" class="medium">Defaults</td></tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<b>Thread View</b>
		<div class="smaller">For guests and members not logged in, only threads that have been active within this number of days will be listed in forums.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="text" name="threadview" size="5" value="<?php echo($aOptions['threadview']); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium">
		<b>Posts Per Page</b>
		<div class="smaller">This is the number of posts to display per page for guests and members not logged in.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="text" name="postsperpage" size="5" value="<?php echo($aOptions['postsperpage']); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<b>Threads Per Page</b>
		<div class="smaller">This is the number of threads to display per page for guests and members not logged in.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="text" name="threadsperpage" size="5" value="<?php echo($aOptions['threadsperpage']); ?>" /></td>
</tr>

<tr class="heading"><td colspan="2" align="left" class="medium">Miscellaneous</td></tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Enable Quick Reply?</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="radio" name="quickreply" value="1"<?php if($aOptions['quickreply']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="quickreply" value="0"<?php if(!$aOptions['quickreply']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium">
		<b>Spam Protection</b>
		<div class="smaller">Select which form(s) of spam protection, if any, you want enabled on new user registrations.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium">
		<select name="captcha">
			<option value="0"<?php echo(($aOptions['captcha'] == 0) ? ' selected="selected"' : ''); ?>>None</option>
			<option value="1"<?php echo(($aOptions['captcha'] == 1) ? ' selected="selected"' : ''); ?>>CAPTCHA Image</option>
			<option value="2"<?php echo(($aOptions['captcha'] == 2) ? ' selected="selected"' : ''); ?>>E-Mail Validation</option>
			<option value="3"<?php echo(($aOptions['captcha'] == 3) ? ' selected="selected"' : ''); ?>>Both</option>
		</select>
	</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<b>Display Images Inline?</b>
		<div class="smaller">If you select Yes, [img] tags will display the image inline, instead of linking to it.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="radio" name="showimages" value="1"<?php if($aOptions['showimages']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="showimages" value="0"<?php if(!$aOptions['showimages']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium">
		<b>Show Queries?</b>
		<div class="smaller">If you select Yes, SQL queries will be shown at the bottom of pages. This is useful for debugging.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="radio" name="showqueries" value="1"<?php if($aOptions['showqueries']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="showqueries" value="0"<?php if(!$aOptions['showqueries']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<b>Show Errors?</b>
		<div class="smaller">If you select Yes, PHP errors (if encountered) will be shown at the bottom of pages. This is useful for debugging.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="radio" name="showerrors" value="1"<?php if($aOptions['showerrors']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="showerrors" value="0"<?php if(!$aOptions['showerrors']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium">
		<b>Log IP Addresses?</b>
		<div class="smaller">If you select Yes, users' IP addresses will be stored with their posts &amp; threads, searches, private messages, etc. Only users with adequate permissions can view logged IP addresses.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="radio" name="iplogging" value="1"<?php if($aOptions['iplogging']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="iplogging" value="0"<?php if(!$aOptions['iplogging']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<b>Flood Check</b>
		<div class="smaller">The minimum amount of time (in seconds) a user must wait to post another message.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="text" name="floodcheck" size="5" value="<?php echo($aOptions['floodcheck']); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium">
		<b>Invalid Link Text</b>
		<div class="smaller">This is the text that will be appended on &quot;Invalid...&quot; messages users get when they request an invalid thread, forum, etc.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><textarea class="medium" name="invalidlink" rows="5" cols="50"><?php echo(htmlsanitize($aOptions['invalidlink'])); ?></textarea></td>
</tr>

</table>

<div style="text-align: center;"><br /><input type="submit" name="submit" value="Save Changes" accesskey="s" /></div>
</form>

<?php
	// Footer
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>