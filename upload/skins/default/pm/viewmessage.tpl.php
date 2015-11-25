<?php
	// Store the message's information into easy-to-use variables.
	$tDateTime = $aMessage[DATETIME];
	$iAuthorID = $aMessage[AUTHOR];
	$strSubject = htmlsanitize($aMessage[SUBJECT]);
	$strBody = ParseMessage($aMessage[BODY], $aMessage[DSMILIES]);
	$iParentID = $aMessage[PARENT];
	if($aMessage[ICON])
	{
		$strIconURL = "{$CFG['paths']['posticons']}{$aPostIcons[$aMessage[ICON]]['filename']}";
		$strIconAlt = $aPostIcons[$aMessage[ICON]]['title'];
	}

	// Get the information of each forum.
	list($aCategory, $aForum) = GetForumInfo();

	// Store the author's information into easy-to-use variables.
	if($aMessage[IPADDRESS] === NULL)
	{
		$bLoggedIP = FALSE;
	}
	else
	{
		$bLoggedIP = TRUE;
	}
	$strAuthor = htmlsanitize($aAuthor['username']);
	if($aAuthor['title'])
	{
		$strAuthorTitle = htmlsanitize($aAuthor['title']);
	}
	else
	{
		$strAuthorTitle = htmlsanitize($aGroup[$aAuthor['usergroup']]['usertitle']);
	}
	$tAuthorJoined = strtotime($aAuthor['datejoined']);
	$strAuthorLocation = htmlsanitize($aAuthor['residence']);
	$iAuthorPostCount = $aAuthor['postcount'];
	$tLastActive = $aAuthor['lastactive'];
	if((($tLastActive + 300) >= $CFG['globaltime']) && (!$aAuthor['invisible']) && ($aAuthor['loggedin']))
	{
		$bIsOnline = TRUE;
	}
	else
	{
		$bIsOnline = FALSE;
	}
	$strAuthorSignature = ParseMessage($aAuthor['signature'], FALSE);
	$strAuthorWebsite = htmlsanitize($aAuthor['website']); // FIXME!!!

	// Header.
	$strPageTitle = " :: Private Messages :. {$strSubject}";
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; <a href="usercp.php">User Control Panel</a> &gt; <a href="private.php">Private Messages</a> &gt; <a href="private.php?action=viewfolder&amp;id=<?php echo($iParentID); ?>"><?php echo(htmlsanitize($aFolders[$iParentID])); ?></a> &gt; <?php echo($strSubject); ?></b></td>
</tr>
</table>

<br />

<?php
	// User CP menu.
	PrintCPMenu();
?>

<br />

<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">

<tr>
	<td width="100%">
	<form action="private.php" method="post">
		<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td><input type="checkbox" name="id[]" value="<?php echo($iMessageID); ?>" /></td>
			<td class="smaller"><b>Delete?&nbsp;</b></td>
			<td width="100%">&nbsp;<input type="submit" name="action" value="Delete" /></td>
		</tr>
		</table>
	</form>
	<td class="smaller" nowrap="nowrap"><b>Jump to folder</b>:&nbsp;</td>
	<td class="smaller" nowrap="nowrap">
	<form action="private.php" method="post">
		<input type="hidden" name="action" value="viewfolder" />
		<select name="id" onchange="window.location=('private.php?action=viewfolder&amp;id=' + this.options[this.selectedIndex].value);">
<?php
	// Print out all of the folders.
	foreach($aFolders as $iFolderID => $strFolder)
	{
		$strFolder = htmlsanitize($strFolder);
		if($iFolderID == $iParentID)
		{
			echo("\t\t\t<option value=\"{$iFolderID}\" selected=\"selected\">{$strFolder}</option>\n");
		}
		else
		{
			echo("\t\t\t<option value=\"{$iFolderID}\">{$strFolder}</option>\n");
		}
	}
?>
		</select>
		<input style="vertical-align: text-bottom;" name="submit" type="image" src="images/go.png" />
	</form>
	</td>
</tr>

</table>

<br />

<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellspacing="1" cellpadding="4" border="0" align="center">
<tr class="heading">
	<td width="20%" align="left" class="smaller" valign="middle">Author</td>
	<td width="80%" align="left" class="smaller" valign="middle">Post</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" width="20%" align="left" valign="top" class="smaller" nowrap="nowrap">
		<div class="medium"><b><?php echo($strAuthor); ?></b></div>
		<div class="smaller"><?php echo($strAuthorTitle); ?></div>
		<img src="avatar.php?userid=<?php echo($iAuthorID); ?>" alt="" /><br /><br />
		<div class="smaller">
			Registered: <?php echo(gmtdate('M Y', $tAuthorJoined)); ?>
			<?php if($strAuthorLocation) { ?><br />Location: <?php echo($strAuthorLocation); } ?>
			<br />Posts: <?php echo($iAuthorPostCount); ?>
		</div>
	</td>

	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" valign="top" align="left" rowspan="1">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr><td class="smaller">
			<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<?php if($aMessage[ICON]){echo("<td class=\"smaller\" valign=\"middle\"><img src=\"{$strIconURL}\" alt=\"{$strIconAlt}\" style=\"vertical-align: text-bottom;\" />&nbsp;</td>");} ?>
				<?php if($strSubject){echo("<td class=\"smaller\" valign=\"middle\"><b>{$strSubject}</b></td>");} ?>
			</tr>
			<tr><td class="smaller">&nbsp;</td></tr>
			</table>
		</td></tr>
<?php
	echo("<tr><td class=\"medium\" style=\"padding-bottom: 1em;\">{$strBody}</td></tr>");

	// Display the signature.
	if($strAuthorSignature && $_SESSION['showsigs'])
	{
		echo("\t\t<tr><td class=\"medium\"><hr align=\"left\" style=\"margin-bottom: 1px; width: 20em;\" />{$strAuthorSignature}</td></tr>\n");
	}
?>		<tr><td align="right" class="smaller" style="padding-top: 1em;"><a href="#">Warn moderators about post</a> | IP: <?php if($bLoggedIP){ ?><a href="mod.php?action=getip&amp;messageid=<?php echo($iMessageID); ?>">Logged</a><?php }else{ ?>Not Logged<?php } ?></td></tr>
	</table>
	</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" width="20%" align="left" valign="middle" class="smaller"><?php echo(gmtdate('m-d-Y', $tDateTime)); ?> <span style="color: <?php echo($CFG['style']['table']['timecolor']); ?>;"><?php echo(gmtdate('h:i A', $tDateTime)); ?></span></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" width="80%" align="left" valign="middle" class="smaller"><img src="images/<?php if(!$bIsOnline){echo('in');} ?>active.png" align="middle" alt="<?php echo($strAuthor); ?> is <?php if($bIsOnline){echo('online');}else{echo('offline');} ?>" /><img src="images/space.png" width="5" height="1" alt="" /><a href="member.php?action=getprofile&amp;userid=<?php echo($iAuthorID); ?>"><img src="images/user_profile.png" border="0" align="middle" alt="View <?php echo($strAuthor); ?>'s profile" /></a><?php if($iAuthorID!=$_SESSION['userid']){echo("<img src=\"images/space.png\" width=\"3\" height=\"1\" alt=\"\" /><a href=\"private.php?action=newmessage&amp;userid={$iAuthorID}\"><img src=\"images/user_msg.png\" border=\"0\" align=\"middle\" alt=\"Send {$strAuthor} a private message\" /></a>");} if($strAuthorWebsite){echo('<img src="images/space.png" width="3" height="1" alt="" /><a href="'.$strAuthorWebsite.'" target="_blank"><img src="images/user_www.png" border="0" align="middle" alt="Visit '.$strAuthor.'\'s Web site" /></a>');} if($iAuthorID!=$_SESSION['userid']){echo("<img src=\"images/space.png\" width=\"3\" height=\"1\" alt=\"\" /><a href=\"search.php?action=finduser&amp;userid={$iAuthorID}\"><img src=\"images/user_search.png\" border=\"0\" align=\"middle\" alt=\"Find more posts by {$strAuthor}\" /></a><img src=\"images/space.png\" width=\"3\" height=\"1\" alt=\"\" /><a href=\"usercp.php?section=buddylist&amp;action=add&amp;userid={$iAuthorID}\"><img src=\"images/user_buddy.png\" border=\"0\" align=\"middle\" alt=\"Add {$strAuthor} to your Buddy list\" /></a>");}?></td>
</tr>

<tr class="heading"><td width="100%" colspan="2" class="smaller">&nbsp;</td></tr>
</table>

<br />
<div align="center" class="smaller"><?php if($iAuthorID != $_SESSION['userid']){echo("<a href=\"private.php?action=reply&amp;id=$iMessageID\"><img src=\"images/sendreply.png\" border=\"0\" alt=\"Send Reply\" /></a> ");} ?><a href="private.php?action=newmessage"><img src="images/newpm.png" alt="Compose and send a new private message" border="0" /></a> <a href="private.php?action=track"><img src="images/pmtracking.png" alt="Track messages you have sent" border="0" /></a></div>
<br />

<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%">
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

	<td class="smaller" align="right"><?php echo(TimeInfo()); ?></td></tr>
</table>

<?php
	// Footer.
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>