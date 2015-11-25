<?php
	// Build a list of the months.
	$aMonths = array(1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

	// Header.
	$strPageTitle = ' :: New User Registration';
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; Register</b></td>
</tr>
</table>

<?php
	// If there are any errors, display them to the user.
	if(is_array($aError))
	{
		DisplayErrors($aError);
	}
	else
	{
		echo('<br />');
	}
?>

<form name="theform" action="register.php" method="post">
<table cellpadding="4" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" align="center">

<tr class="heading"><td colspan="2" align="left" class="medium">
	Required Information
	<div class="smaller" style="font-weight: normal;">Passwords are case-sensitive. Only your username will be public by default.</div>
</td></tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Desired Username</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><input type="text" name="username" size="25" maxlength="<?php echo($CFG['maxlen']['username']); ?>" value="<?php echo(htmlsanitize($aReg['username'])); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Password</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>"><input type="password" name="passworda" size="25" maxlength="<?php echo($CFG['maxlen']['password']); ?>" value="<?php echo(htmlsanitize($aReg['passworda'])); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Password Again</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>"><input type="password" name="passwordb" size="25" maxlength="<?php echo($CFG['maxlen']['password']); ?>" value="<?php echo(htmlsanitize($aReg['passwordb'])); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>E-Mail Address</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><input type="text" name="emaila" size="25" maxlength="<?php echo($CFG['maxlen']['email']); ?>" value="<?php echo(htmlsanitize($aReg['emaila'])); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<b>E-Mail Address Again</b>
		<div class="smaller">Enter your e-mail address again for confirmation.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><input type="text" name="emailb" size="25" maxlength="<?php echo($CFG['maxlen']['email']); ?>" value="<?php echo(htmlsanitize($aReg['emailb'])); ?>" /></td>
</tr>

<?php
	// Display the CAPTCHA if Image Verification is enabled.
	if($CFG['reg']['verify_img'])
	{
?>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium">
		<b>Verification Image</b>
		<div class="smaller">Enter the text to verify that this registration is not being performed by an automated process.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>">
		<img src="regimage.php<?php if(SID){echo('?'.stripslashes(SID));} ?>" alt="Verification Image" /><br />
		<img src="images/space.png" width="1" height="3" alt="" /><br />
		<input type="text" name="verifyimg" size="10" maxlength="7" style="width: 210px; text-align: center" />
	</td>
</tr>

<?php
	}
?>

<tr class="heading"><td colspan="2" align="left" class="medium">
	Optional Information
	<div class="smaller" style="font-weight: normal;">Everything here will be public.</div>
</td></tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Web Site</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>"><input type="text" name="website" size="25" maxlength="<?php echo($CFG['maxlen']['website']); ?>" value="<?php echo(isset($_REQUEST['submit']) ? htmlsanitize($aReg['website']) : 'http://'); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>AIM Handle</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><input type="text" name="aim" size="25" maxlength="<?php echo($CFG['maxlen']['aim']); ?>" value="<?php echo(htmlsanitize($aReg['aim'])); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>ICQ Number</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>"><input type="text" name="icq" size="25" maxlength="<?php echo($CFG['maxlen']['icq']); ?>" value="<?php echo(htmlsanitize($aReg['icq'])); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>MSN Messenger Handle</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><input type="text" name="msn" size="25" maxlength="<?php echo($CFG['maxlen']['msn']); ?>" value="<?php echo(htmlsanitize($aReg['msn'])); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Yahoo! Messenger Handle</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>"><input type="text" name="yahoo" size="25" maxlength="<?php echo($CFG['maxlen']['yahoo']); ?>" value="<?php echo(htmlsanitize($aReg['yahoo'])); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<b>Referrer</b>
		<div class="smaller">Enter the username of the <?php echo(htmlsanitize($CFG['general']['name'])); ?> member who referrered you here (if any).</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><input type="text" name="referrer" size="25" maxlength="<?php echo($CFG['maxlen']['username']); ?>" value="<?php echo(htmlsanitize($aReg['referrer'])); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Birthday</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>">
		<table cellpadding="2" cellspacing="0" border="0">
		<tr>
			<td align="left" class="smaller">&nbsp;Month</td>
			<td align="left" class="smaller">&nbsp;Day</td>
			<td align="left" class="smaller">&nbsp;Year</td>
		</tr>
		<tr>
			<td>
				<select name="birthmonth">
					<option value="0"<?php if($aReg['birthmonth']==0){echo(' selected="selected"');} ?>></option>
<?php
	// Print out an option for each month of the year.
	foreach($aMonths as $iMonthID => $strMonth)
	{
		$strSelected = ($iMonthID == $aReg['birthmonth']) ? ' selected="selected"' : '';
		echo("\t\t\t\t\t<option value=\"{$iMonthID}\"{$strSelected}>{$strMonth}</option>\n");
	}
?>
				</select>
			</td>
			<td>
				<select name="birthdate">
					<option value="0"<?php if($aReg['birthdate']==0){echo(' selected="selected"');} ?>></option>
<?php
	// Print out an option for each day in the month.
	for($iDayID = 1; $iDayID < 32; $iDayID++)
	{
		$strSelected = ($iDayID == $aReg['birthdate']) ? ' selected="selected"' : '';
		echo("\t\t\t\t\t<option value=\"{$iDayID}\"{$strSelected}>{$iDayID}</option>\n");
	}
?>
				</select>
			</td>
			<td><input type="text" name="birthyear" size="4" maxlength="4" value="<?php if($aReg['birthyear']){echo($aReg['birthyear']);} ?>" /></td>
		</tr>
		</table>
	</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<b>Biography</b>
		<div class="smaller">A few details about yourself</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><input type="text" name="bio" size="25" maxlength="<?php echo($CFG['maxlen']['bio']); ?>" value="<?php echo(htmlsanitize($aReg['bio'])); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium">
		<b>Location</b>
		<div class="smaller">Where are you currently residing?</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>"><input type="text" name="residence" size="25" maxlength="<?php echo($CFG['maxlen']['location']); ?>" value="<?php echo(htmlsanitize($aReg['residence'])); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Interests</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><input type="text" name="interests" size="25" maxlength="<?php echo($CFG['maxlen']['interests']); ?>" value="<?php echo(htmlsanitize($aReg['interests'])); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Occupation</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>"><input type="text" name="occupation" size="25" maxlength="<?php echo($CFG['maxlen']['occupation']); ?>" value="<?php echo(htmlsanitize($aReg['occupation'])); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<b>Signature</b>
		<div class="smaller">This will appear at the bottom of each of your posts.<br /><br /><a href="#">BB Code</a> is <b>on</b>.<br />[img] tags are <b>on</b>.<br /><a href="#">Smilies</a> are <b>on</b>.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>">
		<textarea class="medium" name="signature" rows="5" cols="35"><?php echo(htmlsanitize($aReg['signature'])); ?></textarea>
		<div class="smaller">[<a href="#" onclick="javascript:alert('The maximum permitted length is <?php echo($CFG['maxlen']['signature']); ?> characters.\n\nYour signature is '+document.theform.signature.value.length+' characters long.');">Check signature length.</a>]</div>
	</td>
</tr>

<tr class="heading"><td colspan="2" align="left" class="medium">Account Preferences</td></tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Allow administrators and moderators to send you e-mail notices?</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="radio" name="allowmail" value="1"<?php if($aReg['allowmail']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="allowmail" value="0"<?php if(!$aReg['allowmail']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<b>Browse the forums in Invisible Mode?</b>
		<div class="smaller">If you select Yes, only administrators will be able to tell if you are online or not.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="radio" name="invisible" value="1"<?php if($aReg['invisible']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="invisible" value="0"<?php if(!$aReg['invisible']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium">
		<b>Make your e-mail address public?</b>
		<div class="smaller">If you select Yes, users will be able to see your e-mail address (in your profile).</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="radio" name="publicemail" value="1"<?php if($aReg['publicemail']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="publicemail" value="0"<?php if(!$aReg['publicemail']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Automatically login when you return?</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="radio" name="autologin" value="1"<?php if($aReg['autologin']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="autologin" value="0"<?php if(!$aReg['autologin']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium">
		<b>Enable private messages?</b>
		<div class="smaller">If you select Yes, you will be able to send and receive private messages to and from other <?php echo(htmlsanitize($CFG['general']['name'])); ?> members.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="radio" name="enablepms" value="1"<?php if($aReg['enablepms']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="enablepms" value="0"<?php if(!$aReg['enablepms']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Notify you via e-mail when new private messages are received?</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="radio" name="pmnotifya" value="1"<?php if($aReg['pmnotifya']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="pmnotifya" value="0"<?php if(!$aReg['pmnotifya']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium">
		<b>Pop up a box when new private messages become available?</b>
		<div class="smaller">If you select Yes, while browsing the forums a warning box will pop up on your screen when new private messages are available.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="radio" name="pmnotifyb" value="1"<?php if($aReg['pmnotifyb']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="pmnotifyb" value="0"<?php if(!$aReg['pmnotifyb']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Show users' signatures with their posts?</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="radio" name="showsigs" value="1"<?php if($aReg['showsigs']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="showsigs" value="0"<?php if(!$aReg['showsigs']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Show users' avatars with their posts?</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="radio" name="showavatars" value="1"<?php if($aReg['showavatars']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="showavatars" value="0"<?php if(!$aReg['showavatars']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Default Thread View</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>">
		<select name="threadview" class="small">
			<option value="0"<?php if($aReg['threadview']==0){echo(' selected="selected"');} ?>>Use forum default.</option>
			<option value="1"<?php if($aReg['threadview']==1){echo(' selected="selected"');} ?>>Show threads from the last day.</option>
			<option value="2"<?php if($aReg['threadview']==2){echo(' selected="selected"');} ?>>Show threads from the last 2 days.</option>
			<option value="5"<?php if($aReg['threadview']==5){echo(' selected="selected"');} ?>>Show threads from the last 5 days.</option>
			<option value="10"<?php if($aReg['threadview']==10){echo(' selected="selected"');} ?>>Show threads from the last 10 days.</option>
			<option value="20"<?php if($aReg['threadview']==20){echo(' selected="selected"');} ?>>Show threads from the last 20 days.</option>
			<option value="30"<?php if($aReg['threadview']==30){echo(' selected="selected"');} ?>>Show threads from the last 30 days.</option>
			<option value="45"<?php if($aReg['threadview']==45){echo(' selected="selected"');} ?>>Show threads from the last 45 days.</option>
			<option value="60"<?php if($aReg['threadview']==60){echo(' selected="selected"');} ?>>Show threads from the last 60 days.</option>
			<option value="75"<?php if($aReg['threadview']==75){echo(' selected="selected"');} ?>>Show threads from the last 75 days.</option>
			<option value="100"<?php if($aReg['threadview']==100){echo(' selected="selected"');} ?>>Show threads from the last 100 days.</option>
			<option value="365"<?php if($aReg['threadview']==365){echo(' selected="selected"');} ?>>Show threads from the last year.</option>
			<option value="1000"<?php if($aReg['threadview']==1000){echo(' selected="selected"');} ?>>Show all threads.</option>
		</select>
	</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium">
		<b>Default Posts Per Page</b>
		<div class="smaller">The number of posts that are shown on each page of thread</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>">
		<select name="postsperpage" class="small">
			<option value="0"<?php if($aReg['postsperpage']==0){echo(' selected="selected"');} ?>>Use forum default.</option>
			<option value="5"<?php if($aReg['postsperpage']==5){echo(' selected="selected"');} ?>>Show 5 posts per page.</option>
			<option value="10"<?php if($aReg['postsperpage']==10){echo(' selected="selected"');} ?>>Show 10 posts per page.</option>
			<option value="20"<?php if($aReg['postsperpage']==20){echo(' selected="selected"');} ?>>Show 20 posts per page.</option>
			<option value="30"<?php if($aReg['postsperpage']==30){echo(' selected="selected"');} ?>>Show 30 posts per page.</option>
			<option value="40"<?php if($aReg['postsperpage']==40){echo(' selected="selected"');} ?>>Show 40 posts per page.</option>
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
			<option value="0"<?php if($aReg['threadsperpage']==0){echo(' selected="selected"');} ?>>Use forum default.</option>
			<option value="5"<?php if($aReg['threadsperpage']==5){echo(' selected="selected"');} ?>>Show 5 threads per page.</option>
			<option value="10"<?php if($aReg['threadsperpage']==10){echo(' selected="selected"');} ?>>Show 10 threads per page.</option>
			<option value="20"<?php if($aReg['threadsperpage']==20){echo(' selected="selected"');} ?>>Show 20 threads per page.</option>
			<option value="30"<?php if($aReg['threadsperpage']==30){echo(' selected="selected"');} ?>>Show 30 threads per page.</option>
			<option value="40"<?php if($aReg['threadsperpage']==40){echo(' selected="selected"');} ?>>Show 40 threads per page.</option>
		</select>
	</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium">
		<b>Start Of The Week</b>
		<div class="smaller">Select the day on which weeks start in your culture so that the forum calendar will appear correct for you.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>">
		<select name="weekstart" class="small">
			<option value="0"<?php if($aReg['weekstart']==0){echo(' selected="selected"');} ?>>Sunday</option>
			<option value="1"<?php if($aReg['weekstart']==1){echo(' selected="selected"');} ?>>Monday</option>
			<option value="2"<?php if($aReg['weekstart']==2){echo(' selected="selected"');} ?>>Tuesday</option>
			<option value="3"<?php if($aReg['weekstart']==3){echo(' selected="selected"');} ?>>Wednesday</option>
			<option value="4"<?php if($aReg['weekstart']==4){echo(' selected="selected"');} ?>>Thursday</option>
			<option value="5"<?php if($aReg['weekstart']==5){echo(' selected="selected"');} ?>>Friday</option>
			<option value="6"<?php if($aReg['weekstart']==6){echo(' selected="selected"');} ?>>Saturday</option>
		</select>
	</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<b>Time Offset</b>
		<div class="smaller">The time is now <?php echo(gmdate('g:ia', $CFG['globaltime'])); ?> GMT. Select the appropriate offset so the displayed times will be correct for you.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>">
		<select name="timeoffset" class="small">
			<option value="-43200"<?php if($aReg['timeoffset'] == -43200){echo(' selected="selected"');} ?>>[GMT -12:00] International Date Line West</option>
			<option value="-39600"<?php if($aReg['timeoffset'] == -39600){echo(' selected="selected"');} ?>>[GMT -11:00] Midway Islands, Samoa</option>
			<option value="-36000"<?php if($aReg['timeoffset'] == -36000){echo(' selected="selected"');} ?>>[GMT -10:00] Hawaii-Aleutian Time</option>
			<option value="-32400"<?php if($aReg['timeoffset'] == -32400){echo(' selected="selected"');} ?>>[GMT -09:00] Alaska Time</option>
			<option value="-28800"<?php if($aReg['timeoffset'] == -28800){echo(' selected="selected"');} ?>>[GMT -08:00] Pacific Time (US &amp; Canada)</option>
			<option value="-25200"<?php if($aReg['timeoffset'] == -25200){echo(' selected="selected"');} ?>>[GMT -07:00] Mountain Time (US &amp; Canada)</option>
			<option value="-21600"<?php if($aReg['timeoffset'] == -21600){echo(' selected="selected"');} ?>>[GMT -06:00] Central Time (US &amp; Canada)</option>
			<option value="-18000"<?php if($aReg['timeoffset'] == -18000){echo(' selected="selected"');} ?>>[GMT -05:00] Eastern Time (US &amp; Canada)</option>
			<option value="-14400"<?php if($aReg['timeoffset'] == -14400){echo(' selected="selected"');} ?>>[GMT -04:00] Atlantic Time (Canada)</option>
			<option value="-12600"<?php if($aReg['timeoffset'] == -12600){echo(' selected="selected"');} ?>>[GMT -03:30] Newfoundland Time</option>
			<option value="-10800"<?php if($aReg['timeoffset'] == -10800){echo(' selected="selected"');} ?>>[GMT -03:00] Brasilia, Buenos Aires, Greenland</option>
			<option value="-7200"<?php if($aReg['timeoffset'] == -7200){echo(' selected="selected"');} ?>>[GMT -02:00] Mid-Atlantic Time</option>
			<option value="-3600"<?php if($aReg['timeoffset'] == -3600){echo(' selected="selected"');} ?>>[GMT -01:00] Azores, Cape Verde Island</option>
			<option value="0"<?php if($aReg['timeoffset'] == 0){echo(' selected="selected"');} ?>>[GMT +00:00] Western Europe Time</option>
			<option value="3600"<?php if($aReg['timeoffset'] == 3600){echo(' selected="selected"');} ?>>[GMT +01:00] Central Europe Time</option>
			<option value="7200"<?php if($aReg['timeoffset'] == 7200){echo(' selected="selected"');} ?>>[GMT +02:00] Eastern Europe Time</option>
			<option value="10800"<?php if($aReg['timeoffset'] == 10800){echo(' selected="selected"');} ?>>[GMT +03:00] Eastern Africa Time</option>
			<option value="12600"<?php if($aReg['timeoffset'] == 12600){echo(' selected="selected"');} ?>>[GMT +03:30] Middle East Time</option>
			<option value="14400"<?php if($aReg['timeoffset'] == 14400){echo(' selected="selected"');} ?>>[GMT +04:00] Near East Time</option>
			<option value="16200"<?php if($aReg['timeoffset'] == 16200){echo(' selected="selected"');} ?>>[GMT +04:30] Kabul Time</option>
			<option value="18000"<?php if($aReg['timeoffset'] == 18000){echo(' selected="selected"');} ?>>[GMT +05:00] Pakistan-Lahore Time</option>
			<option value="19800"<?php if($aReg['timeoffset'] == 19800){echo(' selected="selected"');} ?>>[GMT +05:30] India Time</option>
			<option value="20700"<?php if($aReg['timeoffset'] == 20700){echo(' selected="selected"');} ?>>[GMT +05:45] Kathmandu Time</option>
			<option value="21600"<?php if($aReg['timeoffset'] == 21600){echo(' selected="selected"');} ?>>[GMT +06:00] Bangladesh Time</option>
			<option value="25200"<?php if($aReg['timeoffset'] == 25200){echo(' selected="selected"');} ?>>[GMT +07:00] Christmas Island Time</option>
			<option value="28800"<?php if($aReg['timeoffset'] == 28800){echo(' selected="selected"');} ?>>[GMT +08:00] China-Taiwan Time</option>
			<option value="32400"<?php if($aReg['timeoffset'] == 32400){echo(' selected="selected"');} ?>>[GMT +09:00] Japan Time</option>
			<option value="34200"<?php if($aReg['timeoffset'] == 34200){echo(' selected="selected"');} ?>>[GMT +09:30] Australia Central Time</option>
			<option value="36000"<?php if($aReg['timeoffset'] == 36000){echo(' selected="selected"');} ?>>[GMT +10:00] Australia Eastern Time</option>
			<option value="39600"<?php if($aReg['timeoffset'] == 39600){echo(' selected="selected"');} ?>>[GMT +11:00] Soloman Time</option>
			<option value="43200"<?php if($aReg['timeoffset'] == 43200){echo(' selected="selected"');} ?>>[GMT +12:00] New Zealand Time</option>
		</select>
	</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium">
		<b>Are you currently observing Daylight Saving Time/Summer Time?</b>
		<div class="smaller">Adjust this setting if the forum times appear off, despite the Time Offset being correct.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium">
		<input type="radio" name="dst" value="0"<?php if(!$aReg['dst']){echo(' checked="checked"');} ?> />No. &nbsp; <input type="radio" name="dst" value="1"<?php if($aReg['dst']){echo(' checked="checked"');} ?> />Yes. Adjustment is <input style="font-size: 11px; text-align: right;" type="text" name="dsth" size="1" maxlength="2" value="<?php echo($aReg['dsth']); ?>" />:<input style="font-size: 11px;" type="text" name="dstm" size="2" maxlength="2" value="<?php printf('%02u', $aReg['dstm']); ?>" /> hours.
	</td>
</tr>

</table>

<div style="text-align: center;"><br /><input type="submit" name="submit" value="Submit" /></div>
</form>

<?php
	// Footer.
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>