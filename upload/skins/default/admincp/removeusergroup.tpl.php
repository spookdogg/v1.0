<?php
	// Header
	$strPageTitle = ' :: Admin Control Panel :. Remove Usergroup';
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; <a href="admincp.php">Admin Control Panel</a> &gt; <a href="admincp.php?section=usergroups">Usergroups</a> &gt; Remove Usergroup</b></td>
</tr>
</table><br />

<?php
	// Admin CP menu.
	PrintCPMenu();
?>

<br />

<form action="admincp.php" method="post">
<input type="hidden" name="section" value="usergroups" />
<input type="hidden" name="action" value="remove" />
<input type="hidden" name="usergroupid" value="<?php echo($iUsergroupID); ?>" />
<table cellpadding="4" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" align="center">

<tr class="heading">
	<td colspan="2" class="medium">Remove Usergroup</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>">
		<b>Move Users</b>
		<div class="smaller">Select which existing usergroup you want the members of this usergroup to be moved to.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>">
		<select name="newgroupid">
<?php
	foreach($aGroup as $iGroupID => $temp)
	{
		// Don't include if the usergroup is the one we're deleting.
		if($iGroupID != $iUsergroupID)
		{
			$strGroupName = htmlsanitize($aGroup[$iGroupID]['groupname']);
			echo("			<option value=\"{$iGroupID}\">{$strGroupName}</option>\n");
		}
	}
?>
		</select>
	</td>
</tr>

<tr><td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" colspan="2">
	<table cellpadding="0" cellspacing="4" border="0">
	<tr>
		<td class="medium" nowrap="nowrap"><input type="checkbox" name="removegroup" /><b>Remove?&nbsp;</b></td>
		<td class="medium" width="100%">
			To remove this usergroup, check the box to the left and click the button to the right.
			<div class="smaller">Note that removing this usergroup will cause all its members to be moved into the usergroup you select above.</div>
		</td>
		<td><input type="submit" name="submit" value="Remove Now" /></td>
	</tr>
	</table>
</td></tr>

</table>
</form>

<?php
	// Footer
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>