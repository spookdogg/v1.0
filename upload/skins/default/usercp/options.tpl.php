<?php
	// Header.
	$strPageTitle = ' :: User Control Panel :. Edit Options';
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; <a href="usercp.php">User Control Panel</a> &gt; Edit Options</b></td>
</tr>
</table><br />

<?php
	// User CP menu.
	PrintCPMenu();
?>

<br />

<form name="theform" action="usercp.php" method="post">
<input type="hidden" name="section" value="options" />
<table cellpadding="4" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" align="center">

<tr class="section">
	<td colspan="2" align="center" class="medium">Edit Options</td>
</tr>

<tr class="heading"><td colspan="2" align="left" class="medium">Login &amp; Privacy</td></tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<b>Browse the forums in Invisible Mode?</b>
		<div class="smaller">If you select Yes, only administrators will be able to tell if you are online or not.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="radio" name="invisible" value="1"<?php if($aUserInfo['invisible']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="invisible" value="0"<?php if(!$aUserInfo['invisible']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Automatically login when you return?</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="radio" name="autologin" value="1"<?php if($aUserInfo['autologin']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="autologin" value="0"<?php if(!$aUserInfo['autologin']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<b>Make your e-mail address public?</b>
		<div class="smaller">If you select Yes, users will be able to see your e-mail address (in your profile).</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="radio" name="publicemail" value="1"<?php if($aUserInfo['publicemail']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="publicemail" value="0"<?php if(!$aUserInfo['publicemail']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr class="heading"><td colspan="2" align="left" class="medium">Messaging &amp; Notification</td></tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Allow administrators and moderators to send you e-mail notices?</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="radio" name="allowmail" value="1"<?php if($aUserInfo['allowmail']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="allowmail" value="0"<?php if(!$aUserInfo['allowmail']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium">
		<b>Enable private messages?</b>
		<div class="smaller">If you select Yes, you will be able to send and receive private messages to and from other <?php echo(htmlsanitize($CFG['general']['name'])); ?> members.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="radio" name="enablepms" value="1"<?php if($aUserInfo['enablepms']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="enablepms" value="0"<?php if(!$aUserInfo['enablepms']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Notify you via e-mail when new private messages are received?</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="radio" name="pmnotifya" value="1"<?php if($aUserInfo['pmnotifya']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="pmnotifya" value="0"<?php if(!$aUserInfo['pmnotifya']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium">
		<b>Pop up a box when new private messages become available?</b>
		<div class="smaller">If you select Yes, while browsing the forums a warning box will pop up on your screen when new private messages are available.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="radio" name="pmnotifyb" value="1"<?php if($aUserInfo['pmnotifyb']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="pmnotifyb" value="0"<?php if(!$aUserInfo['pmnotifyb']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<b>PMs received from users on your Ignore list</b>
		<div class="smaller">How do you want to handle private messages received from those users on your <a href="usercp.php?section=ignorelist">Ignore list</a>?</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="radio" name="rejectpms" value="1"<?php if($aUserInfo['rejectpms']){echo(' checked="checked"');} ?> />Reject. &nbsp; <input type="radio" name="rejectpms" value="0"<?php if(!$aUserInfo['rejectpms']){echo(' checked="checked"');} ?> />Accept, but mask the messages like posts.</td>
</tr>

<tr class="heading"><td colspan="2" align="left" class="medium">Thread View Options</td></tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Show users' signatures with their posts?</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="radio" name="showsigs" value="1"<?php if($aUserInfo['showsigs']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="showsigs" value="0"<?php if(!$aUserInfo['showsigs']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Show users' avatars with their posts?</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="radio" name="showavatars" value="1"<?php if($aUserInfo['showavatars']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="showavatars" value="0"<?php if(!$aUserInfo['showavatars']){echo(' checked');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Default Thread View</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>">
		<select name="threadview" class="small">
			<option value="0"<?php if($aUserInfo['threadview']==0){echo(' selected="selected"');} ?>>Use forum default.</option>
			<option value="1"<?php if($aUserInfo['threadview']==1){echo(' selected="selected"');} ?>>Show threads from the last day.</option>
			<option value="2"<?php if($aUserInfo['threadview']==2){echo(' selected="selected"');} ?>>Show threads from the last 2 days.</option>
			<option value="5"<?php if($aUserInfo['threadview']==5){echo(' selected="selected"');} ?>>Show threads from the last 5 days.</option>
			<option value="10"<?php if($aUserInfo['threadview']==10){echo(' selected="selected"');} ?>>Show threads from the last 10 days.</option>
			<option value="20"<?php if($aUserInfo['threadview']==20){echo(' selected="selected"');} ?>>Show threads from the last 20 days.</option>
			<option value="30"<?php if($aUserInfo['threadview']==30){echo(' selected="selected"');} ?>>Show threads from the last 30 days.</option>
			<option value="45"<?php if($aUserInfo['threadview']==45){echo(' selected="selected"');} ?>>Show threads from the last 45 days.</option>
			<option value="60"<?php if($aUserInfo['threadview']==60){echo(' selected="selected"');} ?>>Show threads from the last 60 days.</option>
			<option value="75"<?php if($aUserInfo['threadview']==75){echo(' selected="selected"');} ?>>Show threads from the last 75 days.</option>
			<option value="100"<?php if($aUserInfo['threadview']==100){echo(' selected="selected"');} ?>>Show threads from the last 100 days.</option>
			<option value="365"<?php if($aUserInfo['threadview']==365){echo(' selected="selected"');} ?>>Show threads from the last year.</option>
			<option value="1000"<?php if($aUserInfo['threadview']==1000){echo(' selected="selected"');} ?>>Show all threads.</option>
		</select>
	</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium">
		<b>Default Posts Per Page</b>
		<div class="smaller">The number of posts that are shown on each page of a thread</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>">
		<select name="postsperpage" class="small">
			<option value="0"<?php if($aUserInfo['postsperpage']==0){echo(' selected="selected"');} ?>>Use forum default.</option>
			<option value="5"<?php if($aUserInfo['postsperpage']==5){echo(' selected="selected"');} ?>>Show 5 posts per page.</option>
			<option value="10"<?php if($aUserInfo['postsperpage']==10){echo(' selected="selected"');} ?>>Show 10 posts per page.</option>
			<option value="20"<?php if($aUserInfo['postsperpage']==20){echo(' selected="selected"');} ?>>Show 20 posts per page.</option>
			<option value="30"<?php if($aUserInfo['postsperpage']==30){echo(' selected="selected"');} ?>>Show 30 posts per page.</option>
			<option value="40"<?php if($aUserInfo['postsperpage']==40){echo(' selected="selected"');} ?>>Show 40 posts per page.</option>
		</select>
	</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<b>Default Threads Per Page</b>
		<div class="smaller">The number of threads that are shown on each page of a forum or search result</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>">
		<select name="threadsperpage" class="small">
			<option value="0"<?php if($aUserInfo['threadsperpage']==0){echo(' selected="selected"');} ?>>Use forum default.</option>
			<option value="5"<?php if($aUserInfo['threadsperpage']==5){echo(' selected="selected"');} ?>>Show 5 threads per page.</option>
			<option value="10"<?php if($aUserInfo['threadsperpage']==10){echo(' selected="selected"');} ?>>Show 10 threads per page.</option>
			<option value="20"<?php if($aUserInfo['threadsperpage']==20){echo(' selected="selected"');} ?>>Show 20 threads per page.</option>
			<option value="30"<?php if($aUserInfo['threadsperpage']==30){echo(' selected="selected"');} ?>>Show 30 threads per page.</option>
			<option value="40"<?php if($aUserInfo['threadsperpage']==40){echo(' selected="selected"');} ?>>Show 40 threads per page.</option>
		</select>
	</td>
</tr>

<tr class="heading"><td colspan="2" align="left" class="medium">Date &amp; Time Options</td></tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<b>Start Of The Week</b>
		<div class="smaller">Select the day on which weeks start in your culture so that the forum calendar will appear correct for you.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>">
		<select name="weekstart" class="small">
			<option value="0"<?php if($aUserInfo['weekstart']==0){echo(' selected="selected"');} ?>>Sunday</option>
			<option value="1"<?php if($aUserInfo['weekstart']==1){echo(' selected="selected"');} ?>>Monday</option>
			<option value="2"<?php if($aUserInfo['weekstart']==2){echo(' selected="selected"');} ?>>Tuesday</option>
			<option value="3"<?php if($aUserInfo['weekstart']==3){echo(' selected="selected"');} ?>>Wednesday</option>
			<option value="4"<?php if($aUserInfo['weekstart']==4){echo(' selected="selected"');} ?>>Thursday</option>
			<option value="5"<?php if($aUserInfo['weekstart']==5){echo(' selected="selected"');} ?>>Friday</option>
			<option value="6"<?php if($aUserInfo['weekstart']==6){echo(' selected="selected"');} ?>>Saturday</option>
		</select>
	</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium">
		<b>Time Offset</b>
		<div class="smaller">The time is now <?php echo(gmdate('g:ia')); ?> GMT. Select the appropriate offset so the displayed times will be correct for you.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>">
		<select name="timeoffset" class="small">
			<option value="-43200"<?php if($aUserInfo['timeoffset'] == -43200){echo(' selected="selected"');} ?>>[GMT -12:00] International Date Line West</option>
			<option value="-39600"<?php if($aUserInfo['timeoffset'] == -39600){echo(' selected="selected"');} ?>>[GMT -11:00] Midway Islands, Samoa</option>
			<option value="-36000"<?php if($aUserInfo['timeoffset'] == -36000){echo(' selected="selected"');} ?>>[GMT -10:00] Hawaii-Aleutian Time</option>
			<option value="-32400"<?php if($aUserInfo['timeoffset'] == -32400){echo(' selected="selected"');} ?>>[GMT -09:00] Alaska Time</option>
			<option value="-28800"<?php if($aUserInfo['timeoffset'] == -28800){echo(' selected="selected"');} ?>>[GMT -08:00] Pacific Time (US &amp; Canada)</option>
			<option value="-25200"<?php if($aUserInfo['timeoffset'] == -25200){echo(' selected="selected"');} ?>>[GMT -07:00] Mountain Time (US &amp; Canada)</option>
			<option value="-21600"<?php if($aUserInfo['timeoffset'] == -21600){echo(' selected="selected"');} ?>>[GMT -06:00] Central Time (US &amp; Canada)</option>
			<option value="-18000"<?php if($aUserInfo['timeoffset'] == -18000){echo(' selected="selected"');} ?>>[GMT -05:00] Eastern Time (US &amp; Canada)</option>
			<option value="-14400"<?php if($aUserInfo['timeoffset'] == -14400){echo(' selected="selected"');} ?>>[GMT -04:00] Atlantic Time (Canada)</option>
			<option value="-12600"<?php if($aUserInfo['timeoffset'] == -12600){echo(' selected="selected"');} ?>>[GMT -03:30] Newfoundland Time</option>
			<option value="-10800"<?php if($aUserInfo['timeoffset'] == -10800){echo(' selected="selected"');} ?>>[GMT -03:00] Brasilia, Buenos Aires, Greenland</option>
			<option value="-7200"<?php if($aUserInfo['timeoffset'] == -7200){echo(' selected="selected"');} ?>>[GMT -02:00] Mid-Atlantic Time</option>
			<option value="-3600"<?php if($aUserInfo['timeoffset'] == -3600){echo(' selected="selected"');} ?>>[GMT -01:00] Azores, Cape Verde Island</option>
			<option value="0"<?php if($aUserInfo['timeoffset'] == 0){echo(' selected="selected"');} ?>>[GMT +00:00] Western Europe Time</option>
			<option value="3600"<?php if($aUserInfo['timeoffset'] == 3600){echo(' selected="selected"');} ?>>[GMT +01:00] Central Europe Time</option>
			<option value="7200"<?php if($aUserInfo['timeoffset'] == 7200){echo(' selected="selected"');} ?>>[GMT +02:00] Eastern Europe Time</option>
			<option value="10800"<?php if($aUserInfo['timeoffset'] == 10800){echo(' selected="selected"');} ?>>[GMT +03:00] Eastern Africa Time</option>
			<option value="12600"<?php if($aUserInfo['timeoffset'] == 12600){echo(' selected="selected"');} ?>>[GMT +03:30] Middle East Time</option>
			<option value="14400"<?php if($aUserInfo['timeoffset'] == 14400){echo(' selected="selected"');} ?>>[GMT +04:00] Near East Time</option>
			<option value="16200"<?php if($aUserInfo['timeoffset'] == 16200){echo(' selected="selected"');} ?>>[GMT +04:30] Kabul Time</option>
			<option value="18000"<?php if($aUserInfo['timeoffset'] == 18000){echo(' selected="selected"');} ?>>[GMT +05:00] Pakistan-Lahore Time</option>
			<option value="19800"<?php if($aUserInfo['timeoffset'] == 19800){echo(' selected="selected"');} ?>>[GMT +05:30] India Time</option>
			<option value="20700"<?php if($aUserInfo['timeoffset'] == 20700){echo(' selected="selected"');} ?>>[GMT +05:45] Kathmandu Time</option>
			<option value="21600"<?php if($aUserInfo['timeoffset'] == 21600){echo(' selected="selected"');} ?>>[GMT +06:00] Bangladesh Time</option>
			<option value="25200"<?php if($aUserInfo['timeoffset'] == 25200){echo(' selected="selected"');} ?>>[GMT +07:00] Christmas Island Time</option>
			<option value="28800"<?php if($aUserInfo['timeoffset'] == 28800){echo(' selected="selected"');} ?>>[GMT +08:00] China-Taiwan Time</option>
			<option value="32400"<?php if($aUserInfo['timeoffset'] == 32400){echo(' selected="selected"');} ?>>[GMT +09:00] Japan Time</option>
			<option value="34200"<?php if($aUserInfo['timeoffset'] == 34200){echo(' selected="selected"');} ?>>[GMT +09:30] Australia Central Time</option>
			<option value="36000"<?php if($aUserInfo['timeoffset'] == 36000){echo(' selected="selected"');} ?>>[GMT +10:00] Australia Eastern Time</option>
			<option value="39600"<?php if($aUserInfo['timeoffset'] == 39600){echo(' selected="selected"');} ?>>[GMT +11:00] Soloman Time</option>
			<option value="43200"<?php if($aUserInfo['timeoffset'] == 43200){echo(' selected="selected"');} ?>>[GMT +12:00] New Zealand Time</option>
		</select>
	</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<b>Are you currently observing Daylight Saving Time/Summer Time?</b>
		<div class="smaller">Adjust this setting if the forum times appear off, despite the Time Offset being correct.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<input type="radio" name="dst" value="0"<?php if(!$aUserInfo['dst']){echo(' checked="checked"');} ?> />No. &nbsp; <input type="radio" name="dst" value="1"<?php if($aUserInfo['dst']){echo(' checked="checked"');} ?> />Yes. Adjustment is <input style="font-size: 11px; text-align: right;" type="text" name="dsth" size="1" maxlength="2" value="<?php echo($aUserInfo['dsth']); ?>" />:<input style="font-size: 11px;" type="text" name="dstm" size="2" maxlength="2" value="<?php printf('%02u', $aUserInfo['dstm']); ?>" /> hours.
	</td>
</tr>

<tr class="heading"><td colspan="2" align="left" class="medium">Other Options</td></tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Avatar</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="small"><img src="avatar.php?userid=<?php echo($_SESSION['userid']); ?>" align="middle" alt="" /> <input type="submit" name="editavatar" value="Change Avatar" /></td>
</tr>

</table>

<div style="text-align: center;"><br /><input type="submit" name="submit" value="Save Changes" accesskey="s" /></div>
</form>

<?php
	// Footer.
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>