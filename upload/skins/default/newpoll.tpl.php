<?php
	// Header.
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; <a href="forumdisplay.php?forumid=<?php echo($iCategoryID); ?>"><?php echo(htmlsanitize($strCategoryName)); ?></a> &gt; <a href="forumdisplay.php?forumid=<?php echo($iForumID); ?>"><?php echo(htmlsanitize($strForumName)); ?></a> &gt; <?php echo(htmlsanitize($strThreadTitle)); ?></b></td>
</tr>
</table>

<?php
	// Are there any errors?
	if(is_array($aError))
	{
		DisplayErrors($aError);
	}
	else
	{
		echo('<br />');
	}

	// Did we post data?
	if(($_REQUEST['submit'] == 'Update Choices') || ($_REQUEST['submit'] == 'Preview Poll') || ($_REQUEST['submit'] == 'Submit Poll'))
	{
		// Store the posted values. We'll need them now and later.
		$strQuestion = $_REQUEST['question'];
		$aChoices = $_REQUEST['choice'];
		$bMultipleChoices = (bool)$_REQUEST['multiplechoices'];
		$iTimeout = (int)$_REQUEST['timeout'];
	}
?>

<form name="theform" action="poll.php" method="post">
<input type="hidden" name="action" value="newpoll" />
<input type="hidden" name="threadid" value="<?php echo($iThreadID); ?>" />

<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellspacing="1" cellpadding="4" border="0" align="center">

<tr class="section"><td colspan="2" class="medium">Post New Poll</td></tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Logged In User</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><?php echo($_SESSION['loggedin'] ? htmlsanitize($_SESSION['username']).' <font class="smaller">[<a href="member.php?action=logout">Logout</a>]</font>' : '<i>Not logged in.</i> <font class="smaller">[<a href="member.php?action=login">Login</a>]</font>'); ?></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Question</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="text" name="question" size="40" maxlength="<?php echo($CFG['maxlen']['pollquestion']); ?>" value="<?php echo(htmlsanitize($strQuestion)); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Number Of Choices</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="text" name="numchoices" size="5" maxlength="2" value="<?php echo(htmlsanitize($iNumberChoices)); ?>" /> <input type="submit" name="submit" value="Update Choices" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Choices</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<table cellpadding="2" cellspacing="0" border="0" align="left">
		<tr><td colspan="2" class="smaller">Remember to keep the poll choices short and to the point.</td></tr>
		<tr><td colspan="2"><img src="images/space.png" width="1" height="5" alt="" /></td></tr>
<?php
	for($i = 0; ($i < $CFG['maxlen']['pollchoices']) && ($i < $iNumberChoices); $i++)
	{
?>		<tr>
			<td class="medium" nowrap="nowrap">Choice <?php echo($i+1); ?>:</td>
			<td width="100%"><input type="text" name="choice[<?php echo($i); ?>]" size="40" maxlength="<?php echo($CFG['maxlen']['pollchoice']); ?>" value="<?php echo(htmlsanitize($aChoices[$i])); ?>" /></td>
		</tr>
<?php
	}
?>
		</table>
	</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Options</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td valign="top"><input type="checkbox" name="parseurls" disabled="disabled"<?php if($bParseURLs){echo(' checked="checked"');} ?> /></td>
			<td width="100%" class="smaller"><b>Automatically parse URLs?</b> This will automatically put [url] and [/url] around Internet addresses.</td>
		</tr>
		<tr><td colspan="2"><img src="images/space.png" width="1" height="3" alt="" /></td></tr>
		<tr>
			<td valign="top"><input type="checkbox" name="multiplechoices"<?php if($bMultipleChoices){echo(' checked="checked"');} ?> /></td>
			<td width="100%" class="smaller"><b>Allow multiple choices?</b> Give users the ablity to select more than one answer.</td>
		</tr>
	</table>
	</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<b>Poll Timeout</b>
		<div class="smaller">Number of days from now that people can vote in this poll.<br />(Set this to 0 if you want people to be able to vote forever.)</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="text" name="timeout" size="5" value="<?php echo((int)$iTimeout); ?>" /> days</td>
</tr>
</table>

<center><br /><input type="submit" name="submit" value="Submit Poll" accesskey="s" /></center>
</form>

<br />

<?php
	// Footer.
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>