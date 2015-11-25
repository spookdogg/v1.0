<?php
	// Header
	$strPageTitle = ' :: Admin Control Panel :. Forums';
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; <a href="admincp.php">Admin Control Panel</a> &gt; Forums</b></td>
</tr>
</table><br />

<?php
	// Admin CP menu.
	PrintCPMenu();
?>

<br />

<form name="theform" action="admincp.php" method="post">
<input type="hidden" name="section" value="forums" />
<input type="hidden" name="action" value="update" />
<table cellpadding="4" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" align="center" width="100%">

<tr class="section">
	<td colspan="2" align="center" class="medium">Forums</td>
</tr>

<tr><td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium" style="padding: 10px;">
<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" cellpadding="4" cellspacing="1" border="0" align="center">

<tr class="section">
	<td align="center" class="small">Name</td>
	<td align="center" class="small">Order</td>
	<td align="center" class="small" colspan="2">Actions</td>
</tr>

<?php
	foreach($aCategory as $iCategoryID => $temp)
	{
		$aCategory[$iCategoryID][NAME] = htmlsanitize($aCategory[$iCategoryID][NAME]);

		echo("	<tr>\n");
		echo("		<td bgcolor=\"{$CFG['style']['table']['cellb']}\"><b><a href=\"forumdisplay.php?forumid={$iCategoryID}\">{$aCategory[$iCategoryID][NAME]}</a></b></td>\n");
		echo("		<td bgcolor=\"{$CFG['style']['table']['cellb']}\"><input type=\"text\" name=\"forumid[$iCategoryID]\" size=\"5\" value=\"{$aCategory[$iCategoryID][DISPORDER]}\" /></td>\n");
		echo("		<td bgcolor=\"{$CFG['style']['table']['cellb']}\"><a href=\"admincp.php?section=forums&amp;action=edit&amp;forumid={$iCategoryID}\">Edit</a></td>\n");
		echo("		<td bgcolor=\"{$CFG['style']['table']['cellb']}\"><a href=\"admincp.php?section=forums&amp;action=remove&amp;forumid={$iCategoryID}\">Remove</a></td>\n");
		echo("	</tr>\n");

		foreach($aForum as $iForumID => $temp)
		{
			if($aForum[$iForumID][PARENT] == $iCategoryID)
			{
				$aForum[$iForumID][NAME] = htmlsanitize($aForum[$iForumID][NAME]);
				echo("	<tr>\n");
				echo("		<td bgcolor=\"{$CFG['style']['table']['cellb']}\">&nbsp;&nbsp;&nbsp;-- <b><a href=\"forumdisplay.php?forumid={$iForumID}\">{$aForum[$iForumID][NAME]}</a></b></td>\n");
				echo("		<td bgcolor=\"{$CFG['style']['table']['cellb']}\"><input type=\"text\" name=\"forumid[$iForumID]\" size=\"5\" value=\"{$aForum[$iForumID][DISPORDER]}\" /></td>\n");
				echo("		<td bgcolor=\"{$CFG['style']['table']['cellb']}\"><a href=\"admincp.php?section=forums&amp;action=edit&amp;forumid={$iForumID}\">Edit</a></td>\n");
				echo("		<td bgcolor=\"{$CFG['style']['table']['cellb']}\"><a href=\"admincp.php?section=forums&amp;action=remove&amp;forumid={$iForumID}\">Remove</a></td>\n");
				echo("	</tr>\n");
			}
		}
	}
?>

<tr class="section"><td align="center" class="smaller" colspan="4"><a class="section" href="admincp.php?section=forums&amp;action=add">Add New Forum</a></td></tr>

</table>
</td></tr>

</table>

<div style="text-align: center;"><br /><input type="submit" name="submit" value="Update" accesskey="s" /></div>
</form>

<?php
	// Footer
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>