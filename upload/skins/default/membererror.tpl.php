<?php
	// Get the information of each forum, for our Forum Jump later.
	list($aCategory, $aForum) = GetForumInfo();

	// Header.
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<br /><br /><br />

<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="70%" cellspacing="1" cellpadding="4" border="0" align="center">
	<tr class="heading"><td class="medium">OvBB Message</td></tr>
	<tr><td class="medium" bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" style="text-align: justify;"><?php echo($strError); ?></td></tr>
</table>

<br />

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
	// Footer.
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>