<?php
	// Header.
	$strPageTitle = ' :: Private Messages :. Manage Folders';
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; <a href="usercp.php">User Control Panel</a> &gt; <a href="private.php">Private Messages</a> &gt; Manage Custom Folders</b></td>
</tr>
</table><br />

<?php
	// User CP menu.
	PrintCPMenu();
?>

<br />

<form name="theform" action="private.php" method="post">
<input type="hidden" name="action" value="editfolders" />
<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellpadding="4" cellspacing="1" border="0" align="center">

<tr class="heading"><td colspan="2" width="100%" align="center" class="medium">Manage Custom Folders</td></tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium" valign="top"><b>Current Folders</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium">
<?php
	// Print out a textbox for each custom folder.
	$iCount = 2;
	if(count($aFolders))
	{
		while(list($iFolderID) = each($aFolders))
		{
			$strFolder = htmlsanitize($aFolders[$iFolderID]);
			echo('		'.($iCount-1).". <input type=\"text\" name=\"curfolders[$iFolderID]\" size=\"40\" maxlength=\"{$CFG['maxlen']['folder']}\" value=\"{$strFolder}\" /><br />\n");
			$iCount++;
		}
	}
	else
	{
		echo('<i>None</i>');
	}
?>
	</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium" valign="top"><b>Add Folders</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		1. <input type="text" name="newfolders[]" size="40" maxlength="<?php echo($CFG['maxlen']['folder']); ?>" value="" /><br />
		2. <input type="text" name="newfolders[]" size="40" maxlength="<?php echo($CFG['maxlen']['folder']); ?>" value="" /><br />
		3. <input type="text" name="newfolders[]" size="40" maxlength="<?php echo($CFG['maxlen']['folder']); ?>" value="" /><br />
	</td>
</tr>

<tr><td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="smaller" colspan="2">
<table cellpadding="0" cellspacing="0" border="0">
	<tr><td class="smaller" align="right" valign="top">1.&nbsp;</td><td class="smaller" width="100%">To <b>delete</b> a folder, remove the folder's name from the respective textbox. All messages in this folder will be moved to your Inbox.</td></tr>
	<tr><td colspan="2"><img src="images/space.png" width="1" height="3" alt="" /></td></tr>
	<tr><td class="smaller" align="right" valign="top">2.&nbsp;</td><td class="smaller" width="100%">To <b>rename</b> a folder, edit its name in the textbox.</td></tr>
	<tr><td colspan="2"><img src="images/space.png" width="1" height="3" alt="" /></td></tr>
	<tr><td class="smaller" align="right" valign="top">3.&nbsp;</td><td class="smaller" width="100%">To <b>add</b> a folder, enter the name in one of the empty textboxes at the end of the list.</td></tr>
</table>
</td></tr>

</table><br />

<div style="text-align: center;"><input type="submit" name="submit" value="Save Changes" accesskey="s" /></div>
</form>

<br />

<?php
	// Footer.
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>