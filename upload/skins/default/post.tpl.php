<?php
	// Header.
	$strPageTitle = ' :: Show Single Post';
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; <a href="forumdisplay.php?forumid=<?php echo($iCategoryID); ?>"><?php echo(htmlsanitize($strCategoryName)); ?></a> &gt; <a href="forumdisplay.php?forumid=<?php echo($aThread[PARENT]); ?>"><?php echo(htmlsanitize($strForumName)); ?></a> &gt; <a href="thread.php?threadid=<?php echo($aPost[PARENT]); ?>&amp;postid=<?php echo($iPostID); ?>#post<?php echo($iPostID); ?>"><?php echo(htmlsanitize($strThreadTitle)); ?></a> &gt; Show Single Post</b></td>
</tr>
</table>

<br />

<?php
	// Copy the post info. into easy-to-use variables.
	$iThreadID = $aPost[PARENT];
	$iPostAuthor = $aPost[AUTHOR];
	$strPostAuthor = htmlsanitize($aAuthor[USERNAME]);
	$dateAuthorJoined = strtotime($aAuthor[JOINDATE]);
	$iIcon = $aPost[ICON];
	$strIconURL = "{$CFG['paths']['posticons']}{$aPostIcons[$iIcon]['filename']}";
	$strIconAlt = $aPostIcons[$iIcon]['title'];
	$strAuthorTitle = htmlsanitize($aAuthor[TITLE]);
	$strAuthorLocation = htmlsanitize($aAuthor[RESIDENCE]);
	$iAuthorPostCount = $aAuthor[POSTCOUNT];
	$strAuthorSignature = $aAuthor[SIGNATURE];
	$strAuthorWebsite = htmlsanitize($aAuthor[WWW]); // FIXME!!!
	$dateAuthorLastActive = $aAuthor[LASTACTIVE];
	$bInvisible = $aAuthor[INVISIBLE];
	$datePosted = $aPost[DT_POSTED];
	$dateEdited = $aPost[DT_EDITED];
	$strPostTitle = htmlsanitize($aPost[TITLE]);
	$strPostBody = $aPost[BODY];
	$bDisableSmilies = $aPost[DSMILIES];
	$bLoggedIP = $aPost[LOGGEDIP];
	$strReadStatus = ($aPost[DT_POSTED] > $tLastViewed) ? 'new.png' : 'old.png';

	// Set the status flag.
	$bIsOnline = ((($dateAuthorLastActive + 300) >= $CFG['globaltime']) && (!$bInvisible) && ($aAuthor[ONLINE])) ? TRUE : FALSE;

	// For guests.
	if($iPostAuthor == 0)
	{
		$strAuthorTitle = $aGroup[0]['usertitle'];
		list($strPostAuthor, $strPostBody) = explode("\n", $strPostBody);
		$strPostAuthor = htmlsanitize($strPostAuthor);
	}

	// Parse the message.
	$strPostBody = ParseMessage($strPostBody, $bDisableSmilies);

	// Parse the signature.
	$strAuthorSignature = ParseMessage($strAuthorSignature, FALSE);
?>

<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellspacing="1" cellpadding="4" border="0" align="center">
<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" width="175" align="left" valign="top" class="smaller" nowrap="nowrap">
		<div class="medium"><b><?php echo($strPostAuthor); ?></b></div>
		<div class="smaller"><?php echo($strAuthorTitle); ?></div>
		<img src="avatar.php?userid=<?php echo($iPostAuthor); ?>" border="0" alt="" /><br /><br />
		<div class="smaller">
			Registered: <?php echo(gmtdate('M Y', $dateAuthorJoined)); ?>
			<?php if($strAuthorLocation) { ?><br />Location: <?php echo($strAuthorLocation); } ?>
			<br />Posts: <?php echo($iAuthorPostCount); ?>
		</div>
	</td>

	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" valign="top" align="left" rowspan="1">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
<?php
	if($iIcon || $strPostTitle)
	{
?>
		<tr><td class="smaller">
			<table cellpadding="0" cellspacing="0" border="0">
			<tr><?php
		if($iIcon)
		{
			echo("<td class=\"smaller\" valign=\"middle\"><img src=\"{$strIconURL}\" alt=\"{$strIconAlt}\" style=\"vertical-align: text-bottom;\" />&nbsp;</td>");
		}

		if($strPostTitle)
		{
			echo("<td class=\"smaller\" valign=\"middle\"><b>{$strPostTitle}</b></td>");
		}
?></tr>
			<tr><td class="smaller">&nbsp;</td></tr>
			</table>
		</td></tr>
<?php
	}
?>
		<tr><td>
			<div class="medium" style="width: 100%; padding-bottom: 1em; overflow: auto;"><?php echo($strPostBody); ?></div>
		</td></tr>
<?php
	// Display the attachment link(s).
	if($aAttachments != NULL)
	{
		// Print out the attachments this post has.
		foreach($aAttachments as $iAttachmentID => $v)
		{
			// Get the attachment information, and store it into easy-to-read variables.
			$strAttachment = $aAttachments[$iAttachmentID][0];
			$iViewCount = $aAttachments[$iAttachmentID][1];
			$strExtension = strtolower(substr(strrchr($strAttachment, "."), 1));
			$strAttachmentIcon = isset($CFG['uploads']['oktypes'][$strExtension]) ? "images/attach/{$CFG['uploads']['oktypes'][$strExtension]}" : 'images/attach/unknown.png';
?>
		<tr><td class="medium">
			<img src="<?php echo($strAttachmentIcon); ?>" alt="" align="top" /><img src="images/space.png" width="2" height="16" alt="" align="top" />Attachment: <a href="attachment.php?id=<?php echo($iAttachmentID); ?>" target="_blank"><?php echo($strAttachment); ?></a><br />
			<div class="smaller">This has been downloaded <?php echo($iViewCount); ?> time<?php if($iViewCount != 1){echo('s');} ?>.</div>
		</td></tr>
		<tr><td class="medium">&nbsp;</td></tr>
<?php
		}
	}

	// Display the signature.
	if($strAuthorSignature && $_SESSION['showsigs'])
	{
		echo("\t\t<tr><td class=\"medium\"><hr align=\"left\" style=\"margin-bottom: 1px; width: 20em;\" />{$strAuthorSignature}</td></tr>\n");

	}
?>		<tr><td align="right" class="smaller" style="padding-top: 1em;"><a href="#">Warn moderators about post</a> | IP: <?php if($bLoggedIP){ ?><a href="mod.php?action=getip&amp;postid=<?php echo($iPostID); ?>">Logged</a><?php }else{ ?>Not Logged<?php } ?></td></tr>
	</table>
	</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" width="175" align="left" valign="middle" class="smaller"><img src="images/<?php echo($strReadStatus); ?>" alt="" /> <?php echo(gmtdate('m-d-Y', $datePosted)); ?> <span style="color: <?php echo($CFG['style']['table']['timecolor']); ?>;"><?php echo(gmtdate('h:i A', $datePosted)); ?></span></td>

	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" align="left" valign="middle">
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td align="left" valign="middle" class="smaller"><?php if($iPostAuthor){ ?><img src="images/<?php if(!$bIsOnline){echo('in');} ?>active.png" align="middle" alt="<?php echo($strPostAuthor); ?> is <?php echo($bIsOnline ? 'online' : 'offline'); ?>" /><img src="images/space.png" width="5" height="1" alt="" /><a href="member.php?action=getprofile&amp;userid=<?php echo($iPostAuthor); ?>"><img src="images/user_profile.png" border="0" align="middle" alt="View <?php echo($strPostAuthor); ?>'s profile" /></a><?php if($iPostAuthor!=$_SESSION['userid']){echo("<img src=\"images/space.png\" width=\"3\" height=\"1\" alt=\"\" /><a href=\"private.php?action=newmessage&amp;userid={$iPostAuthor}\"><img src=\"images/user_msg.png\" border=\"0\" align=\"middle\" alt=\"Send {$strPostAuthor} a private message\" /></a>");} if($strAuthorWebsite){echo("<img src=\"images/space.png\" width=\"3\" height=\"1\" alt=\"\" /><a href=\"{$strAuthorWebsite}\" target=\"_blank\"><img src=\"images/user_www.png\" border=\"0\" align=\"middle\" alt=\"Visit {$strPostAuthor}'s Web site\" /></a>");}} if($iPostAuthor!=$_SESSION['userid']){echo("<img src=\"images/space.png\" width=\"3\" height=\"1\" alt=\"\" /><a href=\"search.php?action=finduser&amp;userid={$iPostAuthor}\"><img src=\"images/user_search.png\" border=\"0\" align=\"middle\" alt=\"Find more posts by {$strPostAuthor}\" /></a><img src=\"images/space.png\" width=\"3\" height=\"1\" alt=\"\" /><a href=\"usercp.php?section=buddylist&amp;action=add&amp;userid={$iPostAuthor}\"><img src=\"images/user_buddy.png\" border=\"0\" align=\"middle\" alt=\"Add {$strPostAuthor} to your Buddy list\" /></a>");} ?></td>
		<td align="right" valign="middle"><a href="editpost.php?postid=<?php echo($iPostID); ?>"><img src="images/user_editpost.png" border="0" alt="Edit or delete message" /></a><img src="images/space.png" width="3" height="1" alt="" /><a href="newreply.php?threadid=<?php echo($iThreadID); ?>&amp;postid=<?php echo($iPostID); ?>"><img src="images/user_quote.png" border="0" alt="Reply with quote" /></a></td>
	</tr>
	</table>
	</td>
</tr>
</table>

<?php
	// Footer.
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>