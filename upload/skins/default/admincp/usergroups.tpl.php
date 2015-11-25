<?php
	// Header
	$strPageTitle = ' :: Admin Control Panel :. Usergroups';
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; <a href="admincp.php">Admin Control Panel</a> &gt; Usergroups</b></td>
</tr>
</table><br />

<?php
	// Admin CP menu.
	PrintCPMenu();
?>

<br />

<table cellpadding="4" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" align="center" width="100%">

<tr class="section">
	<td colspan="2" align="center" class="medium">Usergroups</td>
</tr>

<tr><td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium" style="padding: 10px;">
<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" cellpadding="5" cellspacing="1" border="0" align="center">

<tr class="section">
	<td align="center" class="small">Name</td>
	<td align="center" class="small" colspan="3">Actions</td>
</tr>

<?php
	foreach($aGroup as $iUsergroupID => $temp)
	{
		$strUsergroup = htmlsanitize($aGroup[$iUsergroupID]['groupname']);

		echo("\t<tr>\n");
		echo("\t\t<td bgcolor=\"{$CFG['style']['table']['cellb']}\">{$strUsergroup}</td>\n");
		echo("\t\t<td bgcolor=\"{$CFG['style']['table']['cellb']}\"><a href=\"admincp.php?section=usergroups&amp;action=edit&amp;usergroupid={$iUsergroupID}\">Edit</a></td>\n");
		echo("\t\t<td bgcolor=\"{$CFG['style']['table']['cellb']}\"><a href=\"admincp.php?section=usergroups&amp;action=remove&amp;usergroupid={$iUsergroupID}\">Remove</a></td>\n");
		echo("\t\t<td bgcolor=\"{$CFG['style']['table']['cellb']}\"><a href=\"admincp.php?section=usergroups&amp;action=adduser&amp;usergroupid={$iUsergroupID}\">Add User</a></td>\n");
		echo("\t</tr>\n");
	}
?>

<tr class="section"><td align="center" class="smaller" colspan="4"><a class="section" href="admincp.php?section=usergroups&amp;action=add">Add New Usergroup</a></td></tr>

</table>
</td></tr>

</table>

<?php
	// Footer
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>