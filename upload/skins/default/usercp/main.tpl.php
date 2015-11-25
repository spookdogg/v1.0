<?php
	// Header.
	$strPageTitle = ' :: User Control Panel';
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; User Control Panel</b></td>
</tr>
</table>

<br />

<?php
	// User CP menu.
	PrintCPMenu();
?>

<br />

<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
<tr>
	<td valign="top">
	<table cellpadding="4" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" align="center">

	<tr class="section"><td align="left" class="medium">Buddy List</td></tr>

	<tr><td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" align="center" width="100%">
	<table width="100%" border="0" cellpadding="2" cellspacing="0">
		<tr><td colspan="3" width="100%">
			<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr>
				<td width="50%"><hr /></td>
				<td class="smaller" nowrap="nowrap">&nbsp;<b>Online</b>&nbsp;</td>
				<td width="50%"><hr /></td>
			</tr></table>
		</td></tr>
<?php
	// Print out the buddies in our Online list.
	if(is_array($aOnlineBuddies))
	{
		foreach($aOnlineBuddies as $iBuddyID => $strBuddyName)
		{
?>		<tr>
			<td><img src="images/active.png" alt="<?php echo(htmlsanitize($strBuddyName)); ?> is online" /></td>
			<td width="100%" class="medium" nowrap="nowrap"><a href="member.php?action=getprofile&amp;userid=<?php echo($iBuddyID); ?>"><?php echo(htmlsanitize($strBuddyName)); ?></a></td>
			<td class="medium" nowrap="nowrap"><a href="private.php?action=newmessage&amp;userid=<?php echo($iBuddyID); ?>">PM</a> <a href="usercp.php?section=buddylist&amp;action=remove&amp;userid=<?php echo($iBuddyID); ?>">X</a></td>
		</tr>
<?php
		}
	}
?>		<tr><td colspan="3"><img src="images/space.png" width="1" height="5" alt="" /></td></tr>

		<tr><td colspan="3" width="100%">
			<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr>
				<td width="50%"><hr /></td>
				<td class="smaller" nowrap="nowrap">&nbsp;<b>Offline</b>&nbsp;</td>
				<td width="50%"><hr /></td>
			</tr></table>
		</td></tr>
<?php
	// Print out the buddies in our Offline list.
	if(is_array($aOfflineBuddies))
	{
		foreach($aOfflineBuddies as $iBuddyID => $strBuddyName)
		{
?>		<tr>
			<td><img src="images/inactive.png" alt="<?php echo(htmlsanitize($strBuddyName)); ?> is offline" /></td>
			<td width="100%" class="medium" nowrap="nowrap"><a href="member.php?action=getprofile&amp;userid=<?php echo($iBuddyID); ?>"><?php echo(htmlsanitize($strBuddyName)); ?></a></td>
			<td class="medium" nowrap="nowrap"><a href="private.php?action=newmessage&amp;userid=<?php echo($iBuddyID); ?>">PM</a> <a href="usercp.php?section=buddylist&amp;action=remove&amp;userid=<?php echo($iBuddyID); ?>">X</a></td>
		</tr>
<?php
		}
	}
?>	</table>
	</td></tr>

	<tr class="heading"><td align="center" class="smaller" nowrap="nowrap">&nbsp;&nbsp;&nbsp;<a class="heading" style="font-weight: normal;" href="#">Send PM to buddies.</a>&nbsp;&nbsp;&nbsp;</td></tr>

	</table>
	</td>

	<td><img src="images/space.png" width="7" height="1" alt="" /></td>

	<td class="medium" valign="top" width="100%">
<?php
	// Print out the PM section if PMs are enabled.
	if($_SESSION['enablepms'])
	{
?>
		<table cellpadding="4" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" align="center">
		<tr class="section">
			<td<?php if(is_array($aMessages)){echo(' colspan="5"');} ?>>
			<table cellpadding="0" cellspacing="0" border="0" width="100%"><tr>
				<td align="left" class="medium"><a class="section" href="private.php">New Private Messages</a></td>
				<td align="right" class="smaller"><a class="section" style="font-weight: normal;" href="private.php">View all private messages.</a></td>
			</tr></table>
			</td>
		</tr>
<?php
		// Print out any new PMs the user's received.
		if(is_array($aMessages))
		{
?>
		<tr class="heading">
			<td class="smaller"><img src="images/space.png" width="15" height="1" alt="" /></td>
			<td class="smaller"><img src="images/space.png" width="15" height="1" alt="" /></td>
			<td align="center" class="smaller" width="80%">Message Subject</td>
			<td align="center" class="smaller" width="20%" nowrap="nowrap">From</td>
			<td align="center" class="smaller" nowrap="nowrap">Date/Time Received</td>
		</tr>
<?php
			foreach($aMessages as $iMessageID => $aMessage)
			{
?>
		<tr>
			<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>"><img src="images/message_new.png" alt="Unread Message" /></td>
			<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><img src="<?php echo($aMessage[4]); ?>" alt="<?php echo($aMessage[5]); ?>" /></td>
			<td align="left" bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium" nowrap="nowrap">

<?php
	// Is the message from someone on our Ignore list?
	if($aMessage[7])
	{
?>

			This person is on your <b><a href="usercp.php?section=ignorelist">Ignore list</a></b>. Click <b><a href="private.php?action=viewmessage&amp;id=<?php echo($iMessageID); ?>">here</a></b> to view the message.

<?php
	}
	else
	{
?>

			<a href="private.php?action=viewmessage&amp;id=<?php echo($iMessageID); ?>"><?php echo(htmlsanitize($aMessage[3])); ?></a><?php if($aMessage[6]){echo(" <span class=\"smaller\">[<a href=\"private.php?action=viewmessage&amp;id={$iMessageID}&amp;noreceipt=1\">Deny receipt.</a>]</span>");} ?>
<?php
	}
?>

			</td>
			<td align="center" bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium" nowrap="nowrap"><a href="member.php?action=getprofile&amp;userid=<?php echo($aMessage[1]); ?>"><?php echo(htmlsanitize($aMessage[2])); ?></a></td>
			<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="smaller" nowrap="nowrap"><?php echo(gmtdate('m-d-Y', $aMessage[0])); ?> <span style="color: <?php echo($CFG['style']['table']['timecolor']); ?>;"><?php echo(gmtdate('h:i A', $aMessage[0])); ?></span></td>
		</tr>
<?php
			}
		}
		else
		{
			echo("\t\t<tr><td bgcolor=\"{$CFG['style']['table']['cellb']}\" align=\"left\" class=\"medium\">You have no new private messages.</td></tr>");
		}
?>
		</table>

		<br />
<?php
	}
?>
		<table cellpadding="4" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" align="center">
		<tr class="section"><td colspan="4" class="medium">Your Last 10 Posts</td></tr>
		<tr class="heading">
			<td align="center" class="smaller">Title</td>
			<td align="center" class="smaller">Forum</td>
			<td align="center" class="smaller">Last Poster</td>
			<td align="center" class="smaller">Last Post</td>
		</tr>

<?php
	// Print out the stats of the last ten posts of the user.
	if(is_array($aLastPosts))
	{
		foreach($aLastPosts as $iPostID => $aPost)
		{
			$strPostTitle = htmlsanitize($aPost[TITLE]);
			$iThreadID = $aPost[PARENT];
			$strThreadTitle = htmlsanitize($aLastThreads[$iThreadID][TITLE]);
			$iForumID = $aLastThreads[$iThreadID][BID];
			$strForumName = htmlsanitize($aLastThreads[$iThreadID][BNAME]);
			$tLastPost = $aLastThreads[$iThreadID][LPOST];
			$iLastPosterID = $aLastThreads[$iThreadID][LPOSTER];
			$strLastPoster = htmlsanitize($aLastThreads[$iThreadID][LPOSTERNAME]);
?>
		<tr>
			<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><?php echo("<a href=\"thread.php?threadid={$iThreadID}&amp;postid={$iPostID}#post{$iPostID}\">{$strThreadTitle}</a>"); ?></td>
			<td align="center" bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><?php echo("<a href=\"forumdisplay.php?forumid={$iForumID}\">{$strForumName}</a>"); ?></td>
			<td align="center" bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><?php echo("<a href=\"member.php?action=getprofile&amp;userid={$iLastPosterID}\">{$strLastPoster}</a>"); ?></td>
			<td align="center" bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><?php echo(gmtdate('m-d-Y', $tLastPost)); ?> <span style="color: <?php echo($CFG['style']['table']['timecolor']); ?>;"><?php echo(gmtdate('h:i A', $tLastPost)); ?></span></td>
		</tr>
<?php
		}
	}
?>		</table>
	</td>
</tr>
</table>

<?php
	// Footer.
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>