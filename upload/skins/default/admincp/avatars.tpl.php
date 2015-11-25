<?php
	// Header
	$strPageTitle = ' :: Admin Control Panel :. Avatars';
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; <a href="admincp.php">Admin Control Panel</a> &gt; Public Avatars</b></td>
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
<input type="hidden" name="section" value="avatars" />
<table cellpadding="4" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" align="center">

<tr class="section">
	<td colspan="2" align="center" class="medium">Custom Avatars</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<b>Maximum Dimensions</b>
		<div class="smaller">This is the maximum allowable dimensions, in pixels, of user-added avatars.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="text" name="maxdims" size="10" value="<?php echo($aOptions['maxdims']); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium">
		<b>Maximum File Size</b>
		<div class="smaller">This is the maximum allowable file size, in bytes, of user-added avatars.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="text" name="maxsize" size="10" value="<?php echo($aOptions['maxsize']); ?>" /></td>
</tr>

</table>

<div style="text-align: center;"><br /><input type="submit" name="submit" value="Save Changes" accesskey="s" /></div>
</form>

<br /><br />

<table cellpadding="4" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" align="center">

<tr class="section">
	<td colspan="5" align="center" class="medium">Public Avatars</td>
</tr>

<?php
	$iRowLength = 4;

	// Display the Avatars table.
	$i = 0;
	foreach($aAvatars as $iAvatarID => $aAvatar)
	{
		// Get the avatar's properties.
		$strTitle = $aAvatar['title'];
		$strFilename = $aAvatar['filename'];

		// Where are we?
		switch($i)
		{
			// First in row?
			case 0:
			{
				// Start a new row AND print out a avatar.
?>	<tr>
		<td align="center" bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="small">
			<img src="<?php echo("{$CFG['paths']['avatars']}{$strFilename}"); ?>" alt="" /><br />
			[<a href="admincp.php?section=avatars&amp;action=edit&amp;avatarid=<?php echo($iAvatarID); ?>">Edit</a>] [<a href="admincp.php?section=avatars&amp;action=remove&amp;avatarid=<?php echo($iAvatarID); ?>">Remove</a>]
		</td>
<?php
				break;
			}

			// Last in row?
			case $iRowLength:
			{
				// Print out a avatar AND end the row.
?>		<td align="center" bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="small">
			<img src="<?php echo("{$CFG['paths']['avatars']}{$strFilename}"); ?>" alt="" /><br />
			[<a href="admincp.php?section=avatars&amp;action=edit&amp;avatarid=<?php echo($iAvatarID); ?>">Edit</a>] [<a href="admincp.php?section=avatars&amp;action=remove&amp;avatarid=<?php echo($iAvatarID); ?>">Remove</a>]
		</td>
	</tr>
<?php
				break;
			}

			// In the middle?
			default:
			{
				// Just print out a avatar.
?>		<td align="center" bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="small">
			<img src="<?php echo("{$CFG['paths']['avatars']}{$strFilename}"); ?>" alt="" /><br />
			[<a href="admincp.php?section=avatars&amp;action=edit&amp;avatarid=<?php echo($iAvatarID); ?>">Edit</a>] [<a href="admincp.php?section=avatars&amp;action=remove&amp;avatarid=<?php echo($iAvatarID); ?>">Remove</a>]
		</td>
<?php
				break;
			}
		}

		// Update the position.
		if($i != $iRowLength)
		{
			$i++;
		}
		else
		{
			$i = 0;
		}
	}

	// Clean-up.
	if(($i > 0) && ($i < ++$iRowLength))
	{
		// Last avatar was in the middle, so we need to end the left-over row.
		for($x = $i; $x < $iRowLength; $x++)
		{
			echo("\t<td align=\"center\" bgcolor=\"{$CFG['style']['table']['cella']}\" class=\"medium\">&nbsp;</td>\n");
		}
		echo("</tr>\n");
	}
?>

<tr class="section"><td align="center" class="smaller" colspan="5"><a class="section" href="admincp.php?section=avatars&amp;action=add">Add New Avatar</a></td></tr>

</table>

<?php
	// Footer
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>