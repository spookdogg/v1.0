<?php
	// Header.
	$strPageTitle = htmlsanitize(" :: {$strThreadTitle} :. New Reply");
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<script src="toolbar.inc.js" language="JavaScript" type="text/javascript"></script>
<script src="smilies.inc.js" language="JavaScript" type="text/javascript"></script>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; <a href="forumdisplay.php?forumid=<?php echo($iCategoryID); ?>"><?php echo(htmlsanitize($strCategoryName)); ?></a> &gt; <a href="forumdisplay.php?forumid=<?php echo($iForumID); ?>"><?php echo(htmlsanitize($strForumName)); ?></a> &gt; <a href="thread.php?threadid=<?php echo($iThreadID); ?>"><?php echo(htmlsanitize($strThreadTitle)); ?></a></b></td>
</tr>
</table>

<?php
	if(is_array($aError))
	{
		DisplayErrors($aError);
	}
	else if($_REQUEST['submit'] == 'Preview Reply')
	{
		// Store the posted values. We'll need them now and later.
		$strSubject = $_REQUEST['subject'];
		$iPostIcon = (int)$_REQUEST['icon'];
		$strMessage = $_REQUEST['message'];
		$bParseURLs = (bool)$_REQUEST['parseurls'];
		$bParseEMails = (bool)$_REQUEST['parseemails'];
		$bDisableSmilies = (bool)$_REQUEST['dsmilies'];

		// Make a copy of the message, so we can parse it for the
		// preview, yet still have the original.
		$strParsedMessage = $strMessage;

		// Put [email] tags around suspected e-mail addresses if they want us to.
		if($bParseEMails)
		{
			$strParsedMessage = ParseEMails($strParsedMessage);
		}

		// Parse any BB code in the message.
		$strParsedMessage = ParseMessage($strParsedMessage, $bDisableSmilies);
?>

<br />
<table width="100%" cellpadding="4" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" align="center">
	<tr class="heading"><td align="left" class="smaller">Post Preview</td></tr>
	<tr><td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>">
		<div class="postbit"><div style="float: left; width: 100%;"><?php echo($strParsedMessage); ?></div></div>
	</td></tr>
</table>
<br />

<?php
	}
	else
	{
		echo('<br />');
	}
?>

<form name="theform" action="newreply.php" enctype="multipart/form-data" method="post">
<input type="hidden" name="threadid" value="<?php echo($iThreadID); ?>" />
<table cellpadding="4" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" align="center">

<tr class="heading">
	<td colspan="2" class="medium">Post Reply</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium" nowrap="nowrap"><b>Logged In As</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><?php echo($_SESSION['loggedin'] ? htmlsanitize($_SESSION['username']).' <span class="smaller">[<a href="member.php?action=logout">Logout</a>]</span>' : '<i>Not logged in.</i> <span class="smaller">[<a href="member.php?action=login">Login</a>]</span>'); ?></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium" nowrap="nowrap"><b>Post Subject</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="smaller"><input type="text" name="subject" size="40" maxlength="<?php echo($CFG['maxlen']['subject']); ?>" value="<?php echo(htmlsanitize($strSubject)); ?>" /> (Optional)</td>
</tr>

<tr>
	<td valign="top" bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium" nowrap="nowrap">
		<b>Message Icon</b>
		<div class="smaller"><input type="radio" name="icon" value="0"<?php if(!$iPostIcon) echo(' checked="checked"'); ?> />No icon</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>">
		<div class="smaller">
<?php
	// Display the post icons' radio buttons.
	DisplayPostIcons($aPostIcons, $iPostIcon);
?>		</div>
	</td>
</tr>

<tr>
	<td valign="top" bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium" nowrap="nowrap"><b>BB Code</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><?php ShowToolbar(); ?></td>
</tr>

<tr>
	<td valign="top" bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium" nowrap="nowrap">
		<b>Your Reply</b><br /><br /><br />

		<table cellpadding="3" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" style="border-width: 2px; border-style: outset;" align="center">
			<tr>
				<td colspan="3" align="center" bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="small" style="border-width: 1px; border-style: inset"><b>Smilies</b></td>
			</tr>
<?php
	// Display the Smilie table.
	SmilieTable($aSmilies);
?>
		</table>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>">
		<textarea name="message" cols="70" rows="20"><?php echo(htmlsanitize($strMessage)); ?></textarea>
		<div class="smaller">[<a href="#" onclick="javascript:alert('The maximum permitted length is <?php echo($CFG['maxlen']['messagebody']); ?> characters.\n\nYour message is '+document.theform.message.value.length+' characters long.');">Check message length.</a>]</div>
	</td>
</tr>

<tr>
	<td valign="top" bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium" nowrap="nowrap"><b>Options</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td valign="top"><input type="checkbox" name="parseurls" disabled="disabled"<?php if($bParseURLs){echo(' checked="checked"');} ?> /></td>
			<td width="100%" class="smaller"><b>Automatically parse URLs?</b> This will automatically put [url] and [/url] around Internet addresses.</td>
		</tr>
		<tr><td colspan="2"><img src="images/space.png" width="1" height="3" alt="" /></td></tr>
		<tr>
			<td valign="top"><input type="checkbox" name="parseemails"<?php if($bParseEMails){echo(' checked="checked"');} ?> /></td>
			<td width="100%" class="smaller"><b>Automatically parse e-mail addresses?</b> This will automatically put [email] and [/email] around e-mail addresses.</td>
		</tr>
		<tr><td colspan="2"><img src="images/space.png" width="1" height="3" alt="" /></td></tr>
		<tr>
			<td valign="top"><input type="checkbox" name="dsmilies"<?php if($bDisableSmilies){echo(' checked="checked"');} ?> /></td>
			<td width="100%" class="smaller"><b>Disable smilies in this post?</b> This will disable the automatic parsing of smilie codes, i.e. :cool:, into smilie images.</td>
		</tr>
	</table>
	</td>
</tr>

<tr>
	<td valign="top" bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<b>Attachment</b>
		<div class="smaller">Maximum filesize is <?php echo($CFG['uploads']['maxsize']); ?> bytes.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>">
		<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo($CFG['uploads']['maxsize']); ?>" />
		<input type="file" name="attachment" />
		<div class="smaller">Acceptable file extensions: <?php echo(htmlsanitize(implode(' ', array_keys($CFG['uploads']['oktypes'])))); ?></div>
	</td>
</tr>

</table><br />

<div style="text-align: center;"><input type="submit" name="submit" value="Submit Reply" accesskey="s" /> <input type="submit" name="submit" value="Preview Reply" accesskey="p" /></div>
</form>

<br /><br />

<table cellpadding="4" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" align="center">
<tr class="heading">
	<td align="center" colspan="2" class="medium">Topic Review (Newest First)</td>
</tr>
</table>

<?php
	// Display the a review listing of the posts to the thread.
	foreach($aReview as $iReviewID => $aPost)
	{
		// Set the color.
		$strColor = ($strColor == $CFG['style']['table']['cellb']) ? $CFG['style']['table']['cella'] : $CFG['style']['table']['cellb'];
?>

<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellspacing="1" cellpadding="4" border="0" align="center">
<tr>
	<td bgcolor="<?php echo($strColor); ?>" width="175" align="left" valign="top" nowrap="nowrap"><?php echo(htmlsanitize($aPost[AUTHOR])); ?></td>
	<td bgcolor="<?php echo($strColor); ?>" align="left" valign="top">
		<div class="postbit"><div style="float: left; width: 100%;"><?php echo(ParseMessage($aPost[BODY], $aPost[DSMILIES])); ?></div></div>
	</td>
</tr>
</table>

<?php
	}
?>

<script language="JavaScript" type="text/javascript">
<!--
	document.theform.status.value='';
//-->
</script>

<?php
	// Footer.
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>