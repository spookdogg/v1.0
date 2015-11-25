<?php
	// Header
	$strPageTitle = ' :: Admin Control Panel :. Skins';
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; <a href="admincp.php">Admin Control Panel</a> &gt; Skins</b></td>
</tr>
</table><br />

<?php
	// Admin CP menu.
	PrintCPMenu();
?>

<br />

<table cellpadding="4" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" align="center" width="100%">

<tr class="section">
	<td colspan="2" align="center" class="medium">Skins</td>
</tr>

<tr><td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium" style="padding: 10px;">
<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" cellpadding="5" cellspacing="1" border="0" align="center">

<tr class="section">
	<td align="center" class="small">Title</td>
	<td align="center" class="small" colspan="2">Actions</td>
</tr>

<?php
	foreach($aSkins as $iSkinID => $aSkin)
	{
		$strSkinTitle = htmlsanitize($aSkin['title']);

		echo("	<tr>\n");
		echo("		<td bgcolor=\"{$CFG['style']['table']['cellb']}\">{$strSkinTitle}</td>\n");
		echo("		<td bgcolor=\"{$CFG['style']['table']['cellb']}\"><a href=\"admincp.php?section=skins&amp;action=edit&amp;skinid={$iSkinID}\">Edit</a></td>\n");
		echo("		<td bgcolor=\"{$CFG['style']['table']['cellb']}\"><a href=\"admincp.php?section=skins&amp;action=remove&amp;skinid={$iSkinID}\">Remove</a></td>\n");
		echo("	</tr>\n");
	}
?>

<tr class="section"><td align="center" class="smaller" colspan="4"><a class="section" href="admincp.php?section=skins&amp;action=add">Add New Skin</a></td></tr>

</table>
</td></tr>

</table>

<?php
	// Footer
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>