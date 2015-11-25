<?php
	// Header.
	$strPageTitle = ' :: User Control Panel :. Edit Ignore List';
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; <a href="usercp.php">User Control Panel</a> &gt; Edit Ignore List</b></td>
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
<input type="hidden" name="section" value="ignorelist" />
<table cellpadding="4" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="200" align="center">

<tr class="section"><td align="center" class="medium">Edit Ignore List</td></tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>">
<?php
	// Print out a textbox with the username in it for each buddy on our list.
	if(is_array($aUsernames))
	{
		foreach($aUsernames as $strUsername)
		{
			// Make the username safe to display.
			$strUsername = htmlsanitize($strUsername);

			// Print out the textbox.
			echo("		<input type=\"text\" name=\"ignorelist[]\" value=\"{$strUsername}\" size=\"30\" maxlength=\"{$CFG['maxlen']['username']}\" /><br />\n");
		}
	}
?>
		<input type="text" name="ignorelist[]" size="30" maxlength="<?php echo($CFG['maxlen']['username']); ?>" /><br />
		<input type="text" name="ignorelist[]" size="30" maxlength="<?php echo($CFG['maxlen']['username']); ?>" />
	</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="smaller" style="text-align: justify;">
		1. To remove a user from the list, delete their username.<br /><br />
		2. To add a user to the list, enter their username in one of the empty boxes.<br /><br />
		3. To view the complete Member list, click <a href="memberlist.php">here</a>.
	</td>
</tr>

</table>

<div style="text-align: center;"><br /><input type="submit" name="submit" value="Save Changes" accesskey="s" /></div>
</form>

<?php
	// Footer.
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>