<?php
	// Header.
	$strPageTitle = ' :: Powered by OvBB';
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="50%" align="left" valign="top" class="medium"><b><?php echo(htmlsanitize($CFG['general']['name'])); ?></b></td>
	<td width="50%" align="right" valign="top" class="smaller"><?php echo($_SESSION['loggedin'] ? 'Welcome back, <b>'.htmlsanitize($_SESSION['username']).'</b>.<br /><b><a href="search.php?action=getnew">View New Posts</a></b>' : '<b><a href="search.php?action=getdaily">View Today\'s Active Threads</a></b><br />&nbsp;'); ?></td>
</tr>
</table>

<?php
	// Greet the user if they are logged in.
	if(!$_SESSION['loggedin'])
	{
?>

<div class="middle"><b>Welcome to the <?php echo(htmlsanitize($CFG['general']['name'])); ?>.</b></div>
<div class="smaller">If this is your first visit, you may have to <b><a href="register.php">register</a></b> before you can post: click the register link above to proceed. To start viewing messages, select the forum that you want to visit from the selection below.</div>

<hr />

<?php
	}
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" class="smaller">
		<b>Members</b>: <?php echo(number_format($aStats['membercount'])); ?> / <b>Threads</b>: <?php echo(number_format($aStats['threadcount'])); ?> / <b>Posts</b>: <?php echo(number_format($aStats['postcount'])); ?><br />
		<?php if($aStats['newestmember']){echo("Welcome our newest member: <a href=\"member.php?action=getprofile&amp;userid={$aStats['newestmember']}\">".htmlsanitize($aUsernames[$aStats['newestmember']]).'</a>');} ?>
	</td>
	<td align="right" class="smaller"><?php echo(($_SESSION['loggedin'] && $_SESSION['lastactive']) ? 'The time is now '.gmtdate('h:i A', $CFG['globaltime']).'.<br />You last visited on '.gmtdate('m-d-Y', $_SESSION['lastactive']).' at '.gmtdate('h:i A', $_SESSION['lastactive']).'.' : '&nbsp;<br />&nbsp;'); ?></td>
</tr>
</table><br />

<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellspacing="1" cellpadding="4" border="0" align="center">

<tr class="heading">
	<td width="1%"><img src="images/space.png" width="15" height="1" alt="" /></td>
	<td align="left" class="smaller" width="77%">Forum</td>
	<td align="center" class="smaller" width="4%">Posts</td>
	<td align="center" class="smaller" width="5%">Threads</td>
	<td align="center" class="smaller" width="13%" nowrap="nowrap">Last Post</td>
</tr>

<?php
	// Display each category and its children.
	foreach($aCategories as $iCategoryID => $aCategory)
	{

?>

<tr class="section">
	<td align="center" class="medium" colspan="5" width="100%"><a class="section" href="forumdisplay.php?forumid=<?php echo($iCategoryID); ?>"><?php echo(htmlsanitize($aCategory[NAME])); ?></a><?php if($aCategory[DESCRIPTION]){echo("<div class=\"smaller\" style=\"font-weight: normal;\">{$aCategory[DESCRIPTION]}</div>");} ?></td>
</tr>

<?php
		// Display each child forum in this category.
		foreach($aCategory[CHILDREN] as $iForumID => $aForum)
		{
?>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" width="1%" align="center" valign="top"><img src="images/<?php if(!$aNewPosts[$iForumID]){echo('in');} ?>active.png" alt="<?php echo($aNewPosts[$iForumID] ? 'Active' : 'Inactive'); ?> Forum" /></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium" width="76%" align="left" valign="middle"><a href="forumdisplay.php?forumid=<?php echo($iForumID); ?>"><b><?php echo(htmlsanitize($aForum[NAME])); ?></b></a><br /><span class="smaller"><?php echo($aForum[DESCRIPTION]); ?></span></td>
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

<tr class="section">
	<td class="medium" width="100%" align="left" colspan="5">
		<a class="section" href="online.php">Currently Active Users</a>: <?php echo(number_format($iOnlineUsers)); ?>
	</td>
</tr>

<tr>
	<td align="left" bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="smaller" colspan="5" width="100%">
	There are currently <?php echo(number_format($iOnlineMembers)); ?> members and <?php echo(number_format($iOnlineGuests)); ?> guests on the forums.
	| Most users ever online was <?php echo $iMostUsersCount; ?> on <?php echo gmtdate('m-d-Y', $iMostUsersDate); ?> at <?php echo gmtdate('h:i A', $iMostUsersDate); ?><br />
<?php
	// Print out the online members' usernames.
	if(isset($aOnline) && is_array($aOnline))
	{
		$i = 1;
		foreach($aOnline as $iUserID => $strUsername)
		{
			// Print the username/link out.
			$strUsername = htmlsanitize($strUsername);
			echo("<a href=\"member.php?action=getprofile&amp;userid={$iUserID}\">{$strUsername}</a>");

			// Are there more usernames left?
			if($i < count($aOnline))
			{
				// Yes. Print out a comma and space for separation.
				echo(', ');
			}

			// Increment the counter.
			$i++;
		}
	}
?>
	</td>
</tr>

<?php
	if($_SESSION['loggedin'] && $_SESSION['enablepms'])
	{
?>

<tr class="section">
	<td class="medium" width="100%" align="left" colspan="5">
		<a class="section" href="private.php">Private Messages</a>
	</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" width="1%" align="center" valign="top"><img src="images/<?php if(!$aPMInfo['newcount']){echo('in');} ?>active.png" alt="" /></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" colspan="4" class="smaller" width="99%" align="left" valign="middle">
		<b><?php echo(htmlsanitize($_SESSION['username'])); ?></b> - You have <?php echo($aPMInfo['newcount']); ?> new message(s) since your last visit.<br />
		(You have <?php echo($aPMInfo['unreadcount']); ?> unread message(s) and <?php echo($aPMInfo['totalcount']); ?> total message(s) in all of your folders.)
	</td>
</tr>

<?php
	}
?>

</table>

<br />

<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td align="left" class="smaller"><?php echo(TimeInfo()); ?></td>
<?php
	if(!$_SESSION['loggedin'])
	{
?>
	<td align="right" class="smaller">
	<form action="member.php" method="post">
	<input type="hidden" name="action" value="login" />
		Login with username and password:<br />
		<input type="text" name="username" size="8" maxlength="<?php echo($CFG['maxlen']['username']); ?>" /> <input type="password" name="password" size="8" maxlength="<?php echo($CFG['maxlen']['password']); ?>" /> <input type="submit" name="submit" value="Login" />
	</form>
	</td>
<?php
	}
	else
	{
?>

	<td align="right" class="smaller"><a href="member.php?action=logout">Logout</a> | <a href="member.php?action=markread">Mark All Forums Read</a></td>

<?php
	}
?>
</tr>
</table>

<div class="smaller" style="text-align: center;"><br />
	<img style="vertical-align: middle;" src="images/active.png" border="0" alt="New Posts" align="middle" /> <b>New posts</b>&nbsp;&nbsp;
	<img style="vertical-align: middle;" src="images/inactive.png" border="0" alt="No New Posts" align="middle" /> <b>No new posts</b>&nbsp;&nbsp;
	<img style="vertical-align: middle;" src="images/closed.png" border="0" alt="Closed Forum" align="middle" /> <b>Closed forum</b>
</div>

<?php
	// Footer.
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>