<?php
	// Header
	$strPageTitle = ' :. Send E-Mail';
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; Send E-Mail</b></td>
</tr>
</table>

<?php
	// Display any errors.
	if(is_array($aError))
	{
		DisplayErrors($aError);
	}
	else
	{
		echo('<br />');
	}
?>

<form name="theform" action="member.php" method="post">
<input type="hidden" name="action" value="mailuser" />
<input type="hidden" name="userid" value="<?php echo($aUserInfo[USERID]); ?>" />
<table cellpadding="4" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" align="center">

<tr class="heading">
	<td colspan="2" class="medium">Send e-mail to <a class="heading" href="member.php?action=getprofile&amp;userid=<?php echo($aUserInfo[USERID]); ?>"><?php echo(htmlsanitize($aUserInfo[USERNAME])); ?></a></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium" nowrap="nowrap"><b>Logged In As</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><?php echo($_SESSION['loggedin'] ? htmlsanitize($_SESSION['username']).' <span class="smaller">[<a href="member.php?action=logout">Logout</a>]</span>' : '<i>Not logged in.</i> <span class="smaller">[<a href="member.php?action=login">Login</a>]</span>'); ?></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium" nowrap="nowrap"><b>Subject</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="smaller"><input type="text" name="subject" size="40" maxlength="64" value="<?php echo(htmlsanitize($aMessageInfo[SUBJECT])); ?>" /></td>
</tr>

<tr>
	<td valign="top" bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium" nowrap="nowrap">
		<b>Message</b>
		<div class="smaller"><br />
			Note by using this form,<br />
			your e-mail address will<br />
			become available to the<br />
			person you are contacting.
		</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>">
		<textarea name="body" cols="50" rows="10"><?php echo(htmlsanitize($aMessageInfo[BODY])); ?></textarea>
	</td>
</tr>

</table><br />

<div style="text-align: center;"><input type="submit" name="submit" value="Send E-Mail" accesskey="s" /></div>
</form><br />

<?php
	// Footer.
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>