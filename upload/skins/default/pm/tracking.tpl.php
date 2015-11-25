<?php
	// Header.
	$strPageTitle = ' :: Private Messages :. Message Tracking';
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; <a href="usercp.php">User Control Panel</a> &gt; <a href="private.php">Private Messages</a> &gt; Message Tracking</b></td>
</tr>
</table><br />

<?php
	// User CP menu.
	PrintCPMenu();
?>

<br />

<table cellpadding="0" cellspacing="1" border="0" width="100%">
<tr>
	<td align="right" class="smaller" width="100%"><b>Jump to folder</b>:&nbsp;</td>
	<td class="smaller" nowrap="nowrap">
	<form action="private.php" method="post">
		<input type="hidden" name="action" value="viewfolder" />
		<select name="id" onchange="window.location=('private.php?action=viewfolder&amp;id=' + this.options[this.selectedIndex].value);">
<?php
	// Print out all of the folders.
	foreach($aFolders as $iFolderID => $strFolder)
	{
		$strFolder = htmlsanitize($strFolder);
		echo("\t\t\t<option value=\"{$iFolderID}\">{$strFolder}</option>\n");
	}
?>
		</select>
		<input style="vertical-align: text-bottom;" name="submit" type="image" src="images/go.png" />
	</form>
	</td>
</tr>
</table><br />

<?php
	// Get the information of each forum.
	list($aCategory, $aForum) = GetForumInfo();

	// Are there messages being tracked?
	if(isset($aUnread) || isset($aRead))
	{
		// Display the tracking information for any unread messages we've sent.
		if(is_array($aUnread))
		{
?>

<form action="private.php" method="post">
<input type="hidden" name="section" value="viewfolder" />
<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellpadding="4" cellspacing="1" border="0" align="center">

<tr class="section"><td colspan="6" class="medium">
	Unread by Recipient
	<div class="smaller" style="font-weight: normal;">These messages have yet to be read by the person to whom they were sent; you can still delete them if you wish.</div>
</td></tr>

<tr class="heading">
	<td class="smaller" width="15"><img src="images/space.png" width="15" height="1" alt="" /></td>
	<td class="smaller" width="15"><img src="images/space.png" width="15" height="1" alt="" /></td>
	<td align="center" width="50%" class="smaller">Message Subject</td>
	<td align="center" width="20%" class="smaller" nowrap="nowrap">Recipient</td>
	<td align="center" width="30%" class="smaller" nowrap="nowrap">Date/Time Sent</td>
</tr>

<?php
			foreach($aUnread as $iMessageID => $aMessage)
			{
				$tDateTime = $aMessage[DATETIME];
				$iRecipientID = $aMessage[RECIPIENT];
				$strRecipient = htmlsanitize($aUsernames[$iRecipientID]);
				$strMessageSubject = htmlsanitize($aMessage[SUBJECT]);
				$strStatusIconURL = 'images/message_new.png';
				$strStatusIconAlt = 'Unread Message';
				$strMessageIconURL = $aMessage[ICON][URL];
				$strMessageIconAlt = $aMessage[ICON][ALT];
?>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="smaller" align="center" valign="middle"><img src="<?php echo($strStatusIconURL); ?>" alt="<?php echo($strStatusIconAlt); ?>" /></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="smaller" align="center" valign="middle"><img src="<?php echo($strMessageIconURL); ?>" alt="<?php echo($strMessageIconAlt); ?>" /></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><?php echo($strMessageSubject); ?></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium" align="center" valign="middle" nowrap="nowrap"><a href="member.php?action=getprofile&amp;userid=<?php echo($iRecipientID); ?>"><?php echo($strRecipient); ?></a></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="smaller" align="center"><?php echo(gmtdate('m-d-Y', $tDateTime)); ?> <span style="color: <?php echo($CFG['style']['table']['timecolor']); ?>;"><?php echo(gmtdate('h:i A', $tDateTime)); ?></span></td>
</tr>

<?php
			}
?>

</table>
</form>

<br />

<?php
		}

		// Display the tracking information for any read messages we've sent.
		if(is_array($aRead))
		{
?>

<form action="private.php" method="post">
<input type="hidden" name="section" value="viewfolder" />
<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellpadding="4" cellspacing="1" border="0" align="center">

<tr class="section"><td colspan="6" class="medium">
	Read by Recipient
	<div class="smaller" style="font-weight: normal;">These messages have been received and read by the person to whom they were sent.</div>
</td></tr>

<tr class="heading">
	<td class="smaller" width="15"><img src="images/space.png" width="15" height="1" alt="" /></td>
	<td class="smaller" width="15"><img src="images/space.png" width="15" height="1" alt="" /></td>
	<td align="center" class="smaller" width="50%">Message Subject</td>
	<td align="center" class="smaller" width="20%">Recipient</td>
	<td align="center" class="smaller" width="15%" nowrap="nowrap">Date/Time Sent</td>
	<td align="center" class="smaller" width="15%" nowrap="nowrap">Date/Time Read</td>
</tr>

<?php
			foreach($aRead as $iMessageID => $aMessage)
			{
				$tDateTime = $aMessage[DATETIME];
				$iRecipientID = $aMessage[RECIPIENT];
				$strRecipient = htmlsanitize($aUsernames[$iRecipientID]);
				$strMessageSubject = htmlsanitize($aMessage[SUBJECT]);
				$strStatusIconURL = 'images/message_old.png';
				$strStatusIconAlt = 'Read Message';
				$strMessageIconURL = $aMessage[ICON][URL];
				$strMessageIconAlt = $aMessage[ICON][ALT];
				$tReadTime = $aMessage[READTIME];
?>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="smaller" align="center" valign="middle"><img src="<?php echo($strStatusIconURL); ?>" alt="<?php echo($strStatusIconAlt); ?>" /></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="smaller" align="center" valign="middle"><img src="<?php echo($strMessageIconURL); ?>" alt="<?php echo($strMessageIconAlt); ?>" /></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><?php echo($strMessageSubject); ?></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium" align="center" valign="middle" nowrap="nowrap"><a href="member.php?action=getprofile&amp;userid=<?php echo($iRecipientID); ?>"><?php echo($strRecipient); ?></a></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="smaller" align="center" nowrap="nowrap"><?php echo(gmtdate('m-d-Y', $tDateTime)); ?> <span style="color: <?php echo($CFG['style']['table']['timecolor']); ?>;"><?php echo(gmtdate('h:i A', $tDateTime)); ?></span></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="smaller" align="center" nowrap="nowrap"><?php echo(gmtdate('m-d-Y', $tReadTime)); ?> <span style="color: <?php echo($CFG['style']['table']['timecolor']); ?>;"><?php echo(gmtdate('h:i A', $tReadTime)); ?></span></td>
</tr>

<?php
			}
?>

</table>
</form>

<?php
		}

	}
	else
	{
		echo('<div class="medium" align="center"><br /><b>There are currently no messages for which you have Tracking enabled.</b><br /><br /></div>');
	}
?>

<br />
<div align="center" class="smaller"><a href="private.php?action=newmessage"><img src="images/newpm.png" alt="Compose and send a new private message" border="0" /></a> <a href="private.php?action=track"><img src="images/pmtracking.png" alt="Track messages you have sent" border="0" /></a> <a href="private.php?action=editfolders"><img src="images/folders.png" alt="Manage your custom folders" border="0" /></a></div>
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
		echo("			<option value=\"{$iCategoryID}\">{$aCategory[$iCategoryID]}</option>\n");

		// Print the forums under this category.
		reset($aForum);
		while(list($iForumID) = each($aForum))
		{
			// Only process this forum if it's under the current category.
			if($aForum[$iForumID][0] == $iCategoryID)
			{
				// Print the forum.
				$aForum[$iForumID][1] = htmlsanitize($aForum[$iForumID][1]);
				echo("			<option value=\"{$iForumID}\">-- {$aForum[$iForumID][1]}</option>\n");
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

<br />

<div align="center" class="smaller">
	<img style="vertical-align: middle;" src="images/message_new.png" border="0" alt="Unread Message" align="middle" /> <b>Unread message</b>&nbsp;&nbsp;
	<img style="vertical-align: middle;" src="images/message_old.png" border="0" alt="Read Message" align="middle" /> <b>Read message</b>
</div>

<?php
	// Footer.
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>