<?php
	// Header
	$strPageTitle = ' :: Admin Control Panel :. Smilies';
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; <a href="admincp.php">Admin Control Panel</a> &gt; Smilies</b></td>
</tr>
</table><br />

<?php
	// Admin CP menu.
	PrintCPMenu();
?>

<br />

<table cellpadding="4" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" align="center">

<tr class="section">
	<td colspan="5" align="center" class="medium">Smilies</td>
</tr>

<?php
	$iRowLength = 4;

	// Display the Smilies table.
	$i = 0;
	foreach($aSmilies as $iSmilieID => $aSmilie)
	{
		// Get the avatar's properties.
		$strTitle = $aSmilie['title'];
		$strCode = $aSmilie['code'];
		$strFilename = $aSmilie['filename'];

		// Where are we?
		switch($i)
		{
			// First in row?
			case 0:
			{
				// Start a new row AND print out a smilie.
?>	<tr>
		<td align="center" bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="small">
			<div class="medium"><?php echo(htmlsanitize($strTitle)); ?></div>
			<div style="padding: 5px;"><table cellpadding="2" cellspacing="0" border="0" height="35" style="vertical-align: middle"><tr><td style="vertical-align: middle"><img src="<?php echo("{$CFG['paths']['smilies']}{$strFilename}"); ?>" alt="" /></td><td style="vertical-align: middle"><b><?php echo(htmlsanitize($strCode)); ?></b></td></tr></table></div>
			[<a href="admincp.php?section=smilies&amp;action=edit&amp;smilieid=<?php echo($iSmilieID); ?>">Edit</a>] [<a href="admincp.php?section=smilies&amp;action=remove&amp;smilieid=<?php echo($iSmilieID); ?>">Remove</a>]
		</td>
<?php
				break;
			}

			// Last in row?
			case $iRowLength:
			{
				// Print out a smilie AND end the row.
?>		<td align="center" bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="small">
			<div class="medium"><?php echo(htmlsanitize($strTitle)); ?></div>
			<div style="padding: 5px;"><table cellpadding="2" cellspacing="0" border="0" height="35" style="vertical-align: middle"><tr><td style="vertical-align: middle"><img src="<?php echo("{$CFG['paths']['smilies']}{$strFilename}"); ?>" alt="" /></td><td style="vertical-align: middle"><b><?php echo(htmlsanitize($strCode)); ?></b></td></tr></table></div>
			[<a href="admincp.php?section=smilies&amp;action=edit&amp;smilieid=<?php echo($iSmilieID); ?>">Edit</a>] [<a href="admincp.php?section=smilies&amp;action=remove&amp;smilieid=<?php echo($iSmilieID); ?>">Remove</a>]
		</td>
	</tr>
<?php
				break;
			}

			// In the middle?
			default:
			{
				// Just print out a smilie.
?>		<td align="center" bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="small">
			<div class="medium"><?php echo(htmlsanitize($strTitle)); ?></div>
			<div style="padding: 5px;"><table cellpadding="2" cellspacing="0" border="0" height="35" style="vertical-align: middle"><tr><td style="vertical-align: middle"><img src="<?php echo("{$CFG['paths']['smilies']}{$strFilename}"); ?>" alt="" /></td><td style="vertical-align: middle"><b><?php echo(htmlsanitize($strCode)); ?></b></td></tr></table></div>
			[<a href="admincp.php?section=smilies&amp;action=edit&amp;smilieid=<?php echo($iSmilieID); ?>">Edit</a>] [<a href="admincp.php?section=smilies&amp;action=remove&amp;smilieid=<?php echo($iSmilieID); ?>">Remove</a>]
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

<tr class="section"><td align="center" class="smaller" colspan="5"><a class="section" href="admincp.php?section=smilies&amp;action=add">Add New Smilie</a></td></tr>

</table>

<?php
	// Footer
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>