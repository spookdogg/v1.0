<?php
	// Build a list of the months.
	$aMonths = array(1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

	// Header.
	$strPageTitle = ' :: User Control Panel :. Edit Profile';
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; <a href="usercp.php">User Control Panel</a> &gt; Edit Profile</b></td>
</tr>
</table><br />

<?php
	// User CP menu.
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

<form name="theform" action="usercp.php" method="post">
<input type="hidden" name="section" value="profile" />
<table cellpadding="4" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" align="center">

<tr class="section">
	<td colspan="2" align="center" class="medium">Edit Profile</td>
</tr>

<tr class="heading"><td colspan="2" align="left" class="medium">
	Required Information
	<div class="smaller" style="font-weight: normal;">All fields are required.</div>
</td></tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>E-Mail Address</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><input type="text" name="emaila" size="25" maxlength="<?php echo($CFG['maxlen']['email']); ?>" value="<?php echo(htmlsanitize($aUserInfo['emaila'])); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<b>E-Mail Address Again</b>
		<div class="smaller">Enter your e-mail address again for confirmation.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><input type="text" name="emailb" size="25" maxlength="<?php echo($CFG['maxlen']['email']); ?>" value="<?php echo(htmlsanitize($aUserInfo['emailb'])); ?>" /></td>
</tr>

<tr class="heading"><td colspan="2" align="left" class="medium">
	Optional Information
	<div class="smaller" style="font-weight: normal;">Everything here will be public.</div>
</td></tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Web Site</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><input type="text" name="website" size="25" maxlength="<?php echo($CFG['maxlen']['website']); ?>" value="<?php echo(htmlsanitize($aUserInfo['website'])); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>AIM Handle</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>"><input type="text" name="aim" size="25" maxlength="<?php echo($CFG['maxlen']['aim']); ?>" value="<?php echo(htmlsanitize($aUserInfo['aim'])); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>ICQ Number</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><input type="text" name="icq" size="25" maxlength="<?php echo($CFG['maxlen']['icq']); ?>" value="<?php echo(htmlsanitize($aUserInfo['icq'])); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>MSN Messenger Handle</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>"><input type="text" name="msn" size="25" maxlength="<?php echo($CFG['maxlen']['msn']); ?>" value="<?php echo(htmlsanitize($aUserInfo['msn'])); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Yahoo! Messenger Handle</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><input type="text" name="yahoo" size="25" maxlength="<?php echo($CFG['maxlen']['yahoo']); ?>" value="<?php echo(htmlsanitize($aUserInfo['yahoo'])); ?>" /></td>
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
					<option value="0"<?php if($aUserInfo['birthmonth']==0){echo(' selected="selected"');} ?>></option>
<?php
	// Print out an option for each month of the year.
	foreach($aMonths as $iMonthID => $strMonth)
	{
		$strSelected = ($iMonthID == $aUserInfo['birthmonth']) ? ' selected="selected"' : '';
		echo("\t\t\t\t\t<option value=\"{$iMonthID}\"{$strSelected}>{$strMonth}</option>\n");
	}
?>
				</select>
			</td>
			<td>
				<select name="birthdate">
					<option value="0"<?php if($aUserInfo['birthdate']==0){echo(' selected="selected"');} ?>></option>
<?php
	// Print out an option for each day in the month.
	for($iDayID = 1; $iDayID < 32; $iDayID++)
	{
		$strSelected = ($iDayID == $aUserInfo['birthdate']) ? ' selected="selected"' : '';
		echo("\t\t\t\t\t<option value=\"{$iDayID}\"{$strSelected}>{$iDayID}</option>\n");
	}
?>
				</select>
			</td>
			<td><input type="text" name="birthyear" size="4" maxlength="4" value="<?php if($aUserInfo['birthyear']){echo($aUserInfo['birthyear']);} ?>" /></td>
		</tr>
		</table>
	</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<b>Biography</b>
		<div class="smaller">A few details about yourself</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><input type="text" name="bio" size="25" maxlength="<?php echo($CFG['maxlen']['bio']); ?>" value="<?php echo(htmlsanitize($aUserInfo['bio'])); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium">
		<b>Location</b>
		<div class="smaller">Where are you currently residing?</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>"><input type="text" name="residence" size="25" maxlength="<?php echo($CFG['maxlen']['location']); ?>" value="<?php echo(htmlsanitize($aUserInfo['residence'])); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Interests</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><input type="text" name="interests" size="25" maxlength="<?php echo($CFG['maxlen']['interests']); ?>" value="<?php echo(htmlsanitize($aUserInfo['interests'])); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Occupation</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>"><input type="text" name="occupation" size="25" maxlength="<?php echo($CFG['maxlen']['occupation']); ?>" value="<?php echo(htmlsanitize($aUserInfo['occupation'])); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<b>Signature</b>
		<div class="smaller">This will appear at the bottom of each of your posts.<br /><br /><a href="#">BB Code</a> is <b>on</b>.<br />[img] tags are <b>on</b>.<br /><a href="#">Smilies</a> are <b>on</b>.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>">
		<textarea class="medium" name="signature" rows="5" cols="35"><?php echo(htmlsanitize($aUserInfo['signature'])); ?></textarea>
		<div class="smaller">[<a href="#" onclick="javascript:alert('The maximum permitted length is 255 characters.\n\nYour signature is '+document.theform.signature.value.length+' characters long.');">Check signature length.</a>]</div>
	</td>
</tr>

</table>

<div style="text-align: center;"><br /><input type="submit" name="submit" value="Save Changes" accesskey="s" /></div>
</form>

<?php
	// Footer.
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>