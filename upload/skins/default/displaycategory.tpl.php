<?php
	// Header.
	$strPageTitle = htmlsanitize(" :: {$aCategory[NAME]}");
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; <?php echo(htmlsanitize($aCategory[NAME])); ?></b></td>
</tr>
</table>

<br />

<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellspacing="1" cellpadding="4" border="0" align="center">

<tr class="heading">
	<td width="1%"><img src="images/space.png" width="15" height="1" alt="" /></td>
	<td align="left" class="smaller" width="77%">Forum</td>
	<td align="center" class="smaller" width="4%">Posts</td>
	<td align="center" class="smaller" width="5%">Threads</td>
	<td align="center" class="smaller" width="13%" nowrap="nowrap">Last Post</td>
</tr>

<?php
	// Are there any forums to display?
	if(is_array($aChildren))
	{
		// Yes, so display them.
		foreach($aChildren as $iForumID => $aForum)
		{
?>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium" width="78%" align="left" valign="middle" colspan="2">
		<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td valign="top"><img src="images/<?php if(!$aNewPosts[$iForumID]){echo('in');} ?>active.png" alt="<?php echo($aNewPosts[$iForumID] ? 'Active' : 'Inactive'); ?> Forum" /></td>
			<td><img src="images/space.png" width="9" height="1" alt="" /></td>
			<td><a href="forumdisplay.php?forumid=<?php echo($iForumID); ?>"><b><?php echo(htmlsanitize($aForum[NAME])); ?></b></a><br /><span class="smaller"><?php echo($aForum[DESCRIPTION]); ?></span></td>
		</tr>
		</table>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium" width="4%" align="center" valign="middle"><?php echo(number_format($aForum[POSTCOUNT])); ?></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium" width="5%" align="center" valign="middle"><?php echo(number_format($aForum[THREADCOUNT])); ?></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="smaller" width="13%" valign="middle">
<?php if($aForum[POSTCOUNT]): ?>
		<table cellpadding="0" cellspacing="0" border="0" align="right">
		<tr>
			<td align="right" nowrap="nowrap">
				<?php echo(gmtdate('m-d-Y', $aForum[LPOST])); ?> <span style="color: <?php echo($CFG['style']['table']['timecolor']); ?>;"><?php echo(gmtdate('h:i A', $aForum[LPOST])); ?></span><br />
				&nbsp;by <a class="underline" href="member.php?action=getprofile&amp;userid=<?php echo($aForum[LPOSTER]); ?>"><span style="color: <?php echo($CFG['style']['l_normal']['l']); ?>"><b><?php echo(htmlsanitize($aUsernames[$aForum[LPOSTER]])); ?></b></span></a>
			</td>
			<td nowrap="nowrap">
				&nbsp;<a href="thread.php?threadid=<?php echo($aForum[LTHREAD]); ?>&amp;page=<?php echo(ceil($aForum[LTHREADPCOUNT] / $iPostsPerPage)); ?>#lastpost"><img src="images/lastpost.png" border="0" alt="Go to last post" /></a>
			</td>
<?php else: ?>
		<table cellpadding="0" cellspacing="0" border="0" align="center">
		<tr>
			<td align="center" class="smaller">Never</td>
<?php endif; ?>
		</tr>
		</table>
	</td>
</tr>

<?php
		}
	}
?>

</table>

<br /><table cellpadding="0" cellspacing="0" border="0" align="center" width="100%">
<tr>
	<td align="left" class="smaller" nowrap="nowrap" width="50%">
	<form action="forumdisplay.php" method="post">
		<b>Forum Jump</b>:<br />
		<select name="forumid" onchange="window.location=('forumdisplay.php?forumid='+this.options[this.selectedIndex].value);">
			<option>Please select one:</option>
<?php
	// Print out all of the forums.
	foreach($aCategories as $iCategoryID => $aCategory)
	{
		// Print the category.
?>			<option value="<?php echo($iCategoryID); ?>"><?php echo(htmlsanitize($aCategory[NAME])); ?></option>
<?php

		// Print the forums under this category.
		foreach($aForums as $iBoardID => $aForum)
		{
			// Only process this forum if it's under the current category.
			if($aForum[PARENT] == $iCategoryID)
			{
				// Print the forum.
?>			<option value="<?php echo($iBoardID); ?>">-- <?php echo(htmlsanitize($aForum[NAME])); ?></option>
<?php
			}
		}
	}
?>
		</select>
		<input style="vertical-align: text-bottom;" name="submit" type="image" src="images/go.png" />
	</form>
	</td>

	<td align="right" class="smaller" width="50%">
		<table border="0" cellpadding="0" cellspacing="0">
		<tr><td align="left"><b>Search this forum:</b></td></tr>
		<tr><td>
			<form action="search.php" method="post">
				<input type="hidden" name="action" value="query" />
				<input type="hidden" name="whichforum" value="<?php echo($iCategoryID); ?>" />
				<input type="text"  name="keywordsearch" maxlength="<?php echo($CFG['maxlen']['query']); ?>" />
				<input type="image" src="images/go.png" style="vertical-align: text-bottom;" />
			</form>
		</td></tr>
		</table>
	</td>
</tr>
</table>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr><td align="center" class="smaller" style="padding-top: 2em; padding-bottom: 1em;"><?php echo(TimeInfo()); ?></td></tr>
<tr><td align="center" class="smaller">
	<img style="vertical-align: middle;" src="images/active.png" border="0" alt="New Posts" align="middle" /> <b>New posts</b>&nbsp;&nbsp;
	<img style="vertical-align: middle;" src="images/inactive.png" border="0" alt="No New Posts" align="middle" /> <b>No new posts</b>&nbsp;&nbsp;
	<img style="vertical-align: middle;" src="images/closed.png" border="0" alt="Closed Forum" align="middle" /> <b>Closed forum</b>
</td></tr>
</table>

<?php
	// Footer
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>