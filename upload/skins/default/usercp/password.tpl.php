<?php
	// Header.
	$strPageTitle = ' :: User Control Panel :. Edit Password';
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; <a href="usercp.php">User Control Panel</a> &gt; Edit Password</b></td>
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
<input type="hidden" name="section" value="password" />
	<table cellpadding="4" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" align="center">

	<tr class="section"><td colspan="2" align="center" class="medium">Edit Password</td></tr>

	<tr>
		<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Present Password</b></td>
		<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="smaller"><input type="password" name="presentpw" size="30" maxlength="<?php echo($CFG['maxlen']['password']); ?>" />&nbsp;&nbsp;<a href="member.php?action=forgotdetails">Forget your password?</a></td>
	</tr>

	<tr>
		<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>New Password</b></td>
		<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><input type="password" name="newpwa" size="30" maxlength="<?php echo($CFG['maxlen']['password']); ?>" /></td>
	</tr>

	<tr>
		<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Confirm New Password</b></td>
		<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><input type="password" name="newpwb" size="30" maxlength="<?php echo($CFG['maxlen']['password']); ?>" /></td>
	</tr>

	</table>

	<div style="text-align: center;"><br /><input type="submit" name="submit" value="Save Changes" accesskey="s" /></div>
</form>

<?php
	// Footer.
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>