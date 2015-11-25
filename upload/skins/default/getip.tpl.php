<?php
	// Header
	$strPageTitle = ' :: Moderation :. View IP Information';
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<br /><br /><br />
<table cellpadding="0" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" align="center">
<tr><td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium" style="padding: 10px; text-align: <?php echo($strAlign); ?>;">
	<div class="small">IP information of the user who made the <?php echo($strWhat); ?>:</div><hr />
	<p><b>IP Address</b>: <code><?php echo($strIP); ?></code><br />
	<b>Host Name</b>: <?php echo(gethostbyaddr($strIP)); ?></p>
	<p class="small" style="padding-top: 1em;">Click <a href="<?php echo(htmlsanitize($strBackURL)); ?>">here</a> to return to the <?php echo($strWhat); ?>.</p>
</td></tr>
</table><br />

<table cellpadding="0" cellspacing="0" border="0" align="center">
<tr>
	<td align="left" class="smaller" nowrap="nowrap">
	<form action="forumdisplay.php" method="post">
		<b>Forum Jump</b>:<br />
		<select name="forumid" onchange="window.location=('forumdisplay.php?forumid='+this.options[this.selectedIndex].value);">
			<option>Please select one:</option>
<?php
	// Print out all of the forums.
	reset($aCategory);
	while(list($iCategoryID) = each($aCategory))
	{
		// Print the category.
		$aCategory[$iCategoryID] = htmlsanitize($aCategory[$iCategoryID]);
		echo("\t\t\t<option value=\"{$iCategoryID}\">{$aCategory[$iCategoryID]}</option>\n");

		// Print the forums under this category.
		reset($aForum);
		while(list($iForumID) = each($aForum))
		{
			// Only process this forum if it's under the current category.
			if($aForum[$iForumID][0] == $iCategoryID)
			{
				// Print the forum.
				$aForum[$iForumID][1] = htmlsanitize($aForum[$iForumID][1]);
				echo("\t\t\t<option value=\"{$iForumID}\">-- {$aForum[$iForumID][1]}</option>\n");
			}
		}
	}
?>
		</select>
		<input style="vertical-align: text-bottom;" name="submit" type="image" src="images/go.png" />
	</form>
	</td>
</tr>
</table>

<br /><br /><br />

<?php
	// Footer
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>