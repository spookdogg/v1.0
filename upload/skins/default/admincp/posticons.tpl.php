<?php
	// Header
	$strPageTitle = ' :: Admin Control Panel :. Post Icons';
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; <a href="admincp.php">Admin Control Panel</a> &gt; Post Icons</b></td>
</tr>
</table><br />

<?php
	// Admin CP menu.
	PrintCPMenu();
?>

<br />

<table cellpadding="4" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" align="center">

<tr class="section">
	<td colspan="5" align="center" class="medium">Post Icons</td>
</tr>

<?php
	$iRowLength = 4;

	// Display the Post Icons table.
	$i = 0;
	foreach($aPostIcons as $iPostIconID => $aPostIcon)
	{
		// Get the icon's properties.
		$strTitle = $aPostIcon['title'];
		$strFilename = $aPostIcon['filename'];

		// Where are we?
		switch($i)
		{
			// First in row?
			case 0:
			{
				// Start a new row AND print out an icon.
?>	<tr>
		<td align="center" bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="small">
			<div class="medium"><?php echo(htmlsanitize($strTitle)); ?></div>
			<div><img src="<?php echo("{$CFG['paths']['posticons']}{$strFilename}"); ?>" alt="" /></div>
			[<a href="admincp.php?section=posticons&amp;action=edit&amp;posticonid=<?php echo($iPostIconID); ?>">Edit</a>] [<a href="admincp.php?section=posticons&amp;action=remove&amp;posticonid=<?php echo($iPostIconID); ?>">Remove</a>]
		</td>
<?php
				break;
			}

			// Last in row?
			case $iRowLength:
			{
				// Print out an icon AND end the row.
?>		<td align="center" bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="small">
			<div class="medium"><?php echo(htmlsanitize($strTitle)); ?></div>
			<div><img src="<?php echo("{$CFG['paths']['posticons']}{$strFilename}"); ?>" alt="" /></div>
			[<a href="admincp.php?section=posticons&amp;action=edit&amp;posticonid=<?php echo($iPostIconID); ?>">Edit</a>] [<a href="admincp.php?section=posticons&amp;action=remove&amp;posticonid=<?php echo($iPostIconID); ?>">Remove</a>]
		</td>
	</tr>
<?php
				break;
			}

			// In the middle?
			default:
			{
				// Just print out an icon.
?>		<td align="center" bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="small">
			<div class="medium"><?php echo(htmlsanitize($strTitle)); ?></div>
			<div><img src="<?php echo("{$CFG['paths']['posticons']}{$strFilename}"); ?>" alt="" /></div>
			[<a href="admincp.php?section=posticons&amp;action=edit&amp;posticonid=<?php echo($iPostIconID); ?>">Edit</a>] [<a href="admincp.php?section=posticons&amp;action=remove&amp;posticonid=<?php echo($iPostIconID); ?>">Remove</a>]
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

<tr class="section"><td align="center" class="smaller" colspan="5"><a class="section" href="admincp.php?section=posticons&amp;action=add">Add New Post Icon</a></td></tr>

</table>

<?php
	// Footer
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>