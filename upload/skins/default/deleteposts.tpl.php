<?php
	// Header
	$strPageTitle = ' :: Moderation :. Delete Posts';
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
<input type="hidden" name="action" value="deleteposts" />
<input type="hidden" name="threadid" value="<?php echo($iThreadID); ?>" />

<table cellpadding="4" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" align="center">

<tr class="heading">
	<td width="10%" align="center" valign="middle" class="smaller">Delete?</td>
	<td width="90%" align="center" valign="middle" class="smaller">Post</td>
</tr>

<?php
	// Display the posts.
	foreach($aPosts as $iPostID => $aPost)
	{
		// Store the post information temporarily.
		$iAuthorID = $aPost[AUTHOR];
		$strAuthor = htmlsanitize($aUsernames[$aPost[AUTHOR]]);
		$tPostDate = $aPost[POSTDATE];
		$strPost = ParseMessage($aPost[BODY], TRUE, TRUE);

		// Set the color.
		$strColor = ($strColor == $CFG['style']['table']['cella']) ? $CFG['style']['table']['cellb'] : $CFG['style']['table']['cella'];
?>

<tr>
	<td bgcolor="<?php echo($strColor); ?>" class="smaller" align="center" valign="middle">
		<input type="checkbox" name="postid[]" value="<?php echo($iPostID); ?>" checked="checked" />
	</td>
	<td bgcolor="<?php echo($strColor); ?>" align="left" valign="top">
		<span class="smaller" style="padding-bottom: 3px; border-bottom: solid 1px;">Posted by <a href="member.php?action=getprofile&amp;userid=<?php echo($iAuthorID); ?>"><?php echo($strAuthor); ?></a> on <?php echo(gmtdate('m-d-Y', $tPostDate)); ?> at <?php echo(gmtdate('h:i A', $tPostDate)); ?></span>
		<div class="medium" style="padding-top: 0.75em; overflow: auto;"><?php echo($strPost); ?></div>
	</td>
</tr>

<?php
	}
?>

</table><br />

<div style="text-align: center;"><input type="submit" name="submit" value="Delete Posts" /></div>
</form>

<?php
	// Footer
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>