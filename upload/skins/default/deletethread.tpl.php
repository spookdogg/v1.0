<?php
	// Header
	$strPageTitle = ' :: Moderation :. Delete Thread';
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; <a href="forumdisplay.php?forumid=<?php echo($iCategoryID); ?>"><?php echo(htmlsanitize($strCategoryName)); ?></a> &gt; <a href="forumdisplay.php?forumid=<?php echo($iForumID); ?>"><?php echo(htmlsanitize($strForumName)); ?></a> &gt; <a href="thread.php?threadid=<?php echo($iThreadID); ?>"><?php echo(htmlsanitize($strThreadTitle)); ?></a></b></td>
</tr>
</table>

<br />

<form action="mod.php" method="post">
<input type="hidden" name="action" value="deletethread" />
<input type="hidden" name="threadid" value="<?php echo($iThreadID); ?>" />
<table cellpadding="4" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" align="center">

<tr class="heading">
	<td class="medium">Delete Thread</td>
</tr>

<tr><td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>">
	<table cellpadding="0" cellspacing="4" border="0">
	<tr>
		<td class="medium" nowrap="nowrap"><input type="checkbox" name="delete" /><b>Delete?&nbsp;</b></td>
		<td class="medium" width="100%">
			To delete this thread, check the box to the left and click the button to the right.
			<div class="smaller">Note that deleting this thread will result in the removal of all posts it contains.</div>
		</td>
		<td><input type="submit" name="submit" value="Delete Now" /></td>
	</tr>
	</table>
</td></tr>

</table>
</form>

<p align="center">Click <b><a href="mod.php?action=deleteposts&amp;threadid=<?php echo($iThreadID); ?>">here</a></b> to selectively delete posts within this thread.</p>

<?php
	// Footer
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>