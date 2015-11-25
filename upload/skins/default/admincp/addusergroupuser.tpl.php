<?php
	// Header
	$strPageTitle = ' :: Admin Control Panel :. Add User To Usergroup';
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; <a href="admincp.php">Admin Control Panel</a> &gt; <a href="admincp.php?section=usergroups">Usergroups</a> &gt; Add User To Usergroup</b></td>
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
<input type="hidden" name="section" value="usergroups" />
<input type="hidden" name="action" value="adduser" />
<table cellpadding="4" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" align="center" width="100%">

<tr class="section">
	<td colspan="2" align="center" class="medium">Add User To Usergroup</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Username</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><input type="text" name="username" size="35" maxlength="255" value="<?php echo(htmlsanitize($strUsername)); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Usergroup</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>">
		<select name="usergroupid">
<?php
	foreach($aGroup as $iGroupID => $temp)
	{
		if($iGroupID == $iUsergroupID)
		{
			$strSelected = ' selected="selected"';
		}
		$strUsergroup = htmlsanitize($aGroup[$iGroupID]['groupname']);
		echo("			<option value=\"{$iGroupID}\"{$strSelected}>{$strUsergroup}</option>\n");
		unset($strSelected);
	}
?>
		</select>
	</td>
</tr>

</table>

<div style="text-align: center;"><br /><input type="submit" name="submit" value="Add User" accesskey="s" /></div>
</form>

<?php
	// Footer
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>