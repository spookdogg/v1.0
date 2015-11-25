<?php
	// Header
	$strPageTitle = ' :: Admin Control Panel :. Remove Forum';
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; <a href="admincp.php">Admin Control Panel</a> &gt; <a href="admincp.php?section=forums">Forums</a> &gt; Remove Forum</b></td>
</tr>
</table><br />

<?php
	// Admin CP menu.
	PrintCPMenu();
?>

<br />

<form action="admincp.php" method="post">
<input type="hidden" name="section" value="forums" />
<input type="hidden" name="action" value="remove" />
<input type="hidden" name="forumid" value="<?php echo($iForumID); ?>" />
<table cellpadding="4" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" align="center">

<tr class="heading">
	<td class="medium">Remove Forum</td>
</tr>

<tr><td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>">
	<table cellpadding="0" cellspacing="4" border="0">
	<tr>
		<td class="medium" nowrap="nowrap"><input type="checkbox" name="removeforum" /><b>Remove?&nbsp;</b></td>
		<td class="medium" width="100%">
			To remove this forum, check the box to the left and click the button to the right.
			<div class="smaller">Note that removing this forum will result in the removal of all posts, threads, forums, attachments, and polls it contains.</div>
		</td>
		<td><input type="submit" name="submit" value="Remove Now" /></td>
	</tr>
	</table>
</td></tr>

</table>
</form>

<?php
	// Footer
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>