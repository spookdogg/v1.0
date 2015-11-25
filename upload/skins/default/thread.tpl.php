<?php
	// Header.
	$strPageTitle = htmlsanitize(" :: {$strForumName} :. {$aThread[TITLE]}");
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; <a href="forumdisplay.php?forumid=<?php echo($iCategoryID); ?>"><?php echo(htmlsanitize($strCategoryName)); ?></a> &gt; <a href="forumdisplay.php?forumid=<?php echo($aThread[PARENT]); ?>"><?php echo(htmlsanitize($strForumName)); ?></a> &gt; <?php echo(htmlsanitize($aThread[TITLE])); ?></b></td>
</tr>
</table>

<br />

<?php
	// Display the poll if there is one.
	if($aThread[POLL])
	{
		// The user either has already voted or is not logged in,
		// therefore they can only view the results.
		if($bHasVoted || $bClosed || !$_SESSION['permissions']['cvotepolls'])
		{
?>

<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellspacing="1" cellpadding="4" border="0" align="center">
<tr class="heading">
	<td colspan="4" class="medium" width="100%" align="center">
		<?php echo(htmlsanitize($strPollQuestion)); ?>
		<div class="smaller" style="font-weight: normal;"><?php if($bHasVoted){echo('You have already voted in this poll.');} else if($bClosed){echo('The poll is closed.');} else{echo('You do not have permission to vote in this poll.');} ?></div>
	</td>
</tr>

<?php
			// Print out the information for each choice.
			foreach($aPollAnswers as $iAnswerID => $strAnswer)
			{
				// Sanitize the answer.
				$strAnswer = htmlsanitize($strAnswer);

				// Figure the percentage.
				if($iVoteCount)
				{
					$iPercentage = ((int)$aVotes[$iAnswerID] / $iVoteCount) * 100;
				}
				else
				{
					$iPercentage = 0;
				}
?>

<tr>
	<td align="right" class="medium" bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><?php echo($strAnswer); ?></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><div class="pollbar" style="width: <?php echo(round($iPercentage)*2); ?>px;"></div></td>
	<td align="center" class="medium" bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><?php echo((int)$aVotes[$iAnswerID]); ?></td>
	<td align="center" class="medium" bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><?php echo(round($iPercentage, 2)); ?>%</td>

</tr>

<?php
			}
?>

<tr class="heading">
	<td width="80%" colspan="2" class="medium" align="right">Total:</td>
	<td width="10%" class="medium" align="center"><?php echo((int)$iVoteCount); ?> votes</td>
	<td width="10%" class="medium" align="center">100%</td>
</tr>
</table>

<br />

<?php
		}
		else
		{
?>

<form name="theform" action="poll.php" method="post">
<input type="hidden" name="action" value="vote" />
<input type="hidden" name="pollid" value="<?php echo($iPollID); ?>" />
<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellspacing="1" cellpadding="4" border="0" align="center">
<tr class="section">
	<td colspan="2" class="medium" width="100%" align="center"><?php echo(htmlsanitize($strPollQuestion)); ?></td>
</tr>

<?php
			// Print out an option for each choice.
			foreach($aPollAnswers as $iAnswerID => $strAnswer)
			{
				// Sanitize the answer.
				$strAnswer = htmlsanitize($strAnswer);
?>

<tr>
	<td align="center" bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><?php if($bMultipleChoices){echo("<input type=\"checkbox\" name=\"answer[$iAnswerID]\" />");}else{echo("<input type=\"radio\" name=\"answer\" value=\"{$iAnswerID}\" />");} ?></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium" width="95%"><?php echo($strAnswer); ?></td>
</tr>

<?php
			}
?>

</table>
<input type="submit" name="submit" value="Vote!" /> <a href="poll.php?action=showresults&amp;pollid=<?php echo($iPollID); ?>">View Results</a>
</form>

<br />

<?php
		}
	}

	// If this thread consists of more than one page, display some navigation links.
	if($iNumberPages > 1)
	{
?>

<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td align="left" class="small">
<?php
	Paginate("thread.php?threadid={$iThreadID}", $iNumberPages, $iPage, $iPostsPerPage);
?>
	</td>
</tr>
</table>

<?php
	}
?>

<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" class="smaller" width="100%" cellspacing="1" cellpadding="4" border="0" align="center">

<tr class="heading">
	<td width="175" align="left" valign="middle">Author</td>
	<td align="left" valign="middle">
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td align="left" valign="middle" class="smaller">Post</td>
		<td align="right" valign="middle"><a href="newthread.php?forumid=<?php echo($aThread[PARENT]); ?>"><img src="images/newthread.png" border="0" alt="Post New Thread" /></a><img src="images/space.png" width="8" height="1" alt="" /><a href="newreply.php?threadid=<?php echo($iThreadID); ?>"><img src="images/newreply<?php if($aThread[CLOSED]){echo('_closed');} ?>.png" border="0" alt="Post A Reply" /></a></td>
	</tr>
	</table>
	</td>
</tr>

</table>

<?php
	// Display the HTML table.
	$iCount = count($aPosts);
	$iIndex = 1;
	foreach($aPosts as $iPostID => $aPost)
	{
		// Copy the post info. into easy-to-use variables.
		$iPostAuthor = $aPost[AUTHOR];
		$strPostAuthor = htmlsanitize($aUsers[$iPostAuthor][USERNAME]);
		$dateAuthorJoined = strtotime($aUsers[$iPostAuthor][JOINDATE]);
		$iIcon = $aPost[ICON];
		$strIconURL = "{$CFG['paths']['posticons']}{$aPostIcons[$iIcon]['filename']}";
		$strIconAlt = $aPostIcons[$iIcon]['title'];
		$strAuthorTitle = htmlsanitize($aUsers[$iPostAuthor][TITLE]);
		$strAuthorLocation = htmlsanitize($aUsers[$iPostAuthor][RESIDENCE]);
		$iAuthorPostCount = $aUsers[$iPostAuthor][POSTCOUNT];
		$strAuthorSignature = $aUsers[$iPostAuthor][SIGNATURE];
		$strAuthorWebsite = htmlsanitize($aUsers[$iPostAuthor][WWW]); // FIXME!!!
		$dateAuthorLastActive = $aUsers[$iPostAuthor][LASTACTIVE];
		$bInvisible = $aUsers[$iPostAuthor][INVISIBLE];
		$datePosted = $aPost[DT_POSTED];
		$dateEdited = $aPost[DT_EDITED];
		$strPostTitle = htmlsanitize($aPost[TITLE]);
		$strPostBody = $aPost[BODY];
		$bDisableSmilies = $aPost[DSMILIES];
		$bLoggedIP = $aPost[LOGGEDIP];
		$strReadStatus = ($aPost[DT_POSTED] > $tLastViewed) ? 'new.png' : 'old.png';

		// Set the color.
		$strColor = ($strColor == $CFG['style']['table']['cellb']) ? $CFG['style']['table']['cella'] : $CFG['style']['table']['cellb'];

		// Is the author on our Ignore list?
		if(in_array($iPostAuthor, $_SESSION['ignorelist']))
		{
?>

<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellspacing="1" cellpadding="4" border="0" align="center" id="post<?php echo($iPostID); ?>">
<tr>
	<td bgcolor="<?php echo($strColor); ?>" width="175" align="left" valign="top" class="medium" nowrap="nowrap">
		<b><?php echo($strPostAuthor); ?></b>
		<div class="smaller"><?php echo(gmtdate('m-d-Y', $datePosted)); ?> <span style="color: <?php echo($CFG['style']['table']['timecolor']); ?>;"><?php echo(gmtdate('h:i A', $datePosted)); ?></span></div>
	</td>

	<td bgcolor="<?php echo($strColor); ?>" class="medium" valign="top" align="left">
		This person is on your <b><a href="usercp.php?section=ignorelist">Ignore list</a></b>. Click <b><a href="thread.php?action=showpost&amp;postid=<?php echo($iPostID); ?>" onclick="javascript:window.open('thread.php?action=showpost&amp;postid=<?php echo($iPostID); ?>', '_blank', 'resizable=1,scrollbars=1,toolbar=0,width=720,height=300'); return false;">here</a></b> to view the post.
	</td>
</tr>
</table>

<?php
		}
		else
		{
			// Set the status flag.
			if((($dateAuthorLastActive + 300) >= $CFG['globaltime']) && (!$bInvisible) && ($aUsers[$iPostAuthor][ONLINE]))
			{
				$bIsOnline = TRUE;
			}
			else
			{
				$bIsOnline = FALSE;
			}

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

<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellspacing="1" cellpadding="4" border="0" align="center" id="post<?php echo($iPostID); ?>">
<tr>
	<td bgcolor="<?php echo($strColor); ?>" width="175" align="left" valign="top" class="smaller">
		<div class="medium"><b><?php echo($strPostAuthor); ?></b></div>
		<div class="smaller"><?php echo($strAuthorTitle); ?></div>
		<img src="avatar.php?userid=<?php echo($iPostAuthor); ?>" border="0" alt="" /><br /><br />
		<div class="smaller">
			Registered: <?php echo(gmtdate('M Y', $dateAuthorJoined)); ?>
			<?php if($strAuthorLocation) { ?><br />Location: <?php echo($strAuthorLocation); } ?>
			<br />Posts: <?php echo($iAuthorPostCount); ?>
		</div>
	</td>

	<td bgcolor="<?php echo($strColor); ?>" valign="top" align="left" rowspan="1">
	<table cellpadding="0" cellspacing="0" border="0" width="100%"<?php if(($iIndex == $iCount) && ($iNumberPages == $iPage)){echo(' id="lastpost"');} ?>>
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
		<tr><td class="medium">
			<div class="postbit"><div style="float: left; width: 100%;"><?php echo($strPostBody); ?></div></div>
		</td></tr>
<?php
	// Display the attachment link(s).
	if(isset($aAttachments[$iPostID]))
	{
		// Print out the attachments this post has.
		foreach(array_keys($aAttachments[$iPostID]) as $iAttachmentID)
		{
			// Get the attachment information, and store it into easy-to-read variables.
			$strAttachment = $aAttachments[$iPostID][$iAttachmentID][0];
			$iViewCount = $aAttachments[$iPostID][$iAttachmentID][1];
			$strExtension = strtolower(substr(strrchr($strAttachment, '.'), 1));
			$strAttachmentIcon = isset($CFG['uploads']['oktypes'][$strExtension]) ? "images/attach/{$CFG['uploads']['oktypes'][$strExtension][0]}" : 'images/attach/unknown.png';
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
	<td bgcolor="<?php echo($strColor); ?>" width="175" align="left" valign="middle" class="smaller"><img src="images/<?php echo($strReadStatus); ?>" alt="" /> <?php echo(gmtdate('m-d-Y', $datePosted)); ?> <span style="color: <?php echo($CFG['style']['table']['timecolor']); ?>;"><?php echo(gmtdate('h:i A', $datePosted)); ?></span></td>

	<td bgcolor="<?php echo($strColor); ?>" align="left" valign="middle">
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
		}

		// Increment the index.
		$iIndex++;
	}
?>

<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellspacing="1" cellpadding="4" border="0" align="center">
<tr class="heading">
	<td width="100%" colspan="2">
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td align="left" valign="middle" class="smaller"><?php echo(TimeInfo()); ?></td>
		<td align="right" valign="middle"><a href="newthread.php?forumid=<?php echo($aThread[PARENT]); ?>"><img src="images/newthread.png" border="0" alt="Post New Thread" /></a><img src="images/space.png" width="8" height="1" alt="" /><a href="newreply.php?threadid=<?php echo($iThreadID); ?>"><img src="images/newreply<?php if($aThread[CLOSED]){echo('_closed');} ?>.png" border="0" alt="Post A Reply" /></a></td>
	</tr>
	</table>
	</td>
</tr>

</table>

<?php
	// If this thread consists of more than one page, display the navigation thingy.
	if($iNumberPages > 1)
	{
?>

<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td align="left" class="small">
<?php
	Paginate("thread.php?threadid={$iThreadID}", $iNumberPages, $iPage, $iPostsPerPage);
?>
	</td>
</tr>
</table>

<?php
	}

	// Is Quick Reply enabled?
	if($CFG['general']['quickreply'])
	{
		include("./skins/{$CFG['skin']}/quickreply.tpl.php");
	}
?>

<br />

<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%">
<tr>
	<td align="left" class="smaller" nowrap="nowrap" width="50%">
	<form action="forumdisplay.php" method="post">
		<b>Forum Jump</b>:<br />
		<select name="forumid" onchange="window.location=('forumdisplay.php?forumid='+this.options[this.selectedIndex].value);">
			<option>Please select one:</option>
<?php
	// Print out all of the forums.
	foreach($aCategories as $iCategoryID => $strCategory)
	{
		// Print the category.
		$strCategory = htmlsanitize($strCategory);
		echo("\t\t\t<option value=\"{$iCategoryID}\">{$strCategory}</option>\n");

		// Print the category's children forums.
		foreach($aBoards as $iBoardID => $aBoard)
		{
			// Only process if it's a child forum.
			if($aBoard[0] == $iCategoryID)
			{
				$aBoard[1] = htmlsanitize($aBoard[1]);
				echo("\t\t\t<option value=\"{$iBoardID}\">-- {$aBoard[1]}</option>\n");
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
	<tr><td align="left"><b>Admin Options:</b></td></tr>
	<tr><td>
		<form action="mod.php" method="post">
			<input type="hidden" name="threadid" value="<?php echo($iThreadID); ?>" />
			<select name="action">
				<option value="<?php echo($aThread[CLOSED] ? 'openthread' : 'closethread'); ?>">Open / Close Thread</option>
				<option value="<?php echo($aThread[STICKY] ? 'unstickthread' : 'stickthread'); ?>">Stick / Unstick Thread</option>
				<option value="movethread">Move / Copy Thread</option>
				<option value="deletethread">Delete Thread / Posts</option>
			</select>
			<input style="vertical-align: text-bottom;" name="submit" type="image" src="images/go.png" />
		</form>
	</td></tr>
	</table>
	</td>
</tr>
</table>

<?php
	// Footer.
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>