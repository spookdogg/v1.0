<?php
	// Header
	$strPageTitle = ' :: Admin Control Panel :. Attachments';
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; <a href="admincp.php">Admin Control Panel</a> &gt; Attachments</b></td>
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
<input type="hidden" name="section" value="attachments" />
<table cellpadding="4" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" align="center">

<tr class="section">
	<td colspan="2" align="center" class="medium">Attachments</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium">
		<b>Maximum File Size</b>
		<div class="smaller">This is the maximum allowable file size (in bytes).</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="text" name="maxsize" size="15" value="<?php echo($aOptions['maxsize']); ?>" /></td>
</tr>

</table>

<div style="text-align: center;"><br /><input type="submit" name="submit" value="Save Changes" accesskey="s" /></div>
</form>

<br /><br />

<table cellpadding="4" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" align="center" width="100%">

<tr class="section">
	<td colspan="2" align="center" class="medium">Acceptable File Types</td>
</tr>

<tr><td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium" style="padding: 10px;">
<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" cellpadding="5" cellspacing="1" border="0" align="center">

<tr class="section">
	<td align="center" class="small">Extension</td>
	<td align="center" class="small">Icon</td>
	<td align="center" class="small">MIME Type</td>
	<td align="center" class="small" colspan="2">Actions</td>
</tr>

<?php
	foreach($CFG['uploads']['oktypes'] as $strExtension => $aType)
	{
		// Sanitize the file type's information.
		$strExtA = htmlsanitize($strExtension);
		$strExtB = urlencode($strExtension);
		$strIcon = urlencode($aType[0]);
		$strMIME = htmlsanitize($aType[1]);

		// Display the information.
		echo("<tr>\n");
		echo("\t<td align=\"center\" bgcolor=\"{$CFG['style']['table']['cellb']}\">{$strExtA}</td>\n");
		echo("\t<td align=\"center\" bgcolor=\"{$CFG['style']['table']['cellb']}\"><img src=\"images/attach/{$strIcon}\" alt=\"\" /></td>\n");
		echo("\t<td align=\"center\" bgcolor=\"{$CFG['style']['table']['cellb']}\">{$strMIME}</td>\n");
		echo("\t<td bgcolor=\"{$CFG['style']['table']['cellb']}\"><a href=\"admincp.php?section=attachments&amp;action=edit&amp;type={$strExtB}\">Edit</a></td>\n");
		echo("\t<td bgcolor=\"{$CFG['style']['table']['cellb']}\"><a href=\"admincp.php?section=attachments&amp;action=remove&amp;type={$strExtB}\">Remove</a></td>\n");
		echo("</tr>\n");
	}
?>

<tr class="section"><td align="center" class="smaller" colspan="5"><a class="section" href="admincp.php?section=attachments&amp;action=add">Add New File Type</a></td></tr>

</table>
</td></tr>
</table>

<?php
	// Footer
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>