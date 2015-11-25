<?php
	// Header.
	$strPageTitle = htmlsanitize(" :: {$aUserInfo[USERNAME]}'s Profile");
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<table cellpadding="4" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" align="center">

<tr class="heading">
	<td width="50%" align="left" class="medium">Profile for <?php echo(htmlsanitize($aUserInfo[USERNAME])); ?></td>
	<td width="50%" align="right" class="smaller"><a class="heading" style="font-weight: normal;" href="search.php?action=finduser&amp;userid=<?php echo($aUserInfo[USERID]); ?>">Search for all posts by <b><?php echo(htmlsanitize($aUserInfo[USERNAME])); ?></b>.</a></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Date Registered</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><?php echo(gmtdate('m-d-Y', strtotime($aUserInfo[JOINDATE]))); ?></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Status</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><?php echo(htmlsanitize($aUserInfo[TITLE])); ?> <img src="avatar.php?userid=<?php echo($aUserInfo[USERID]); ?>" align="middle" alt="<?php echo(htmlsanitize($aUserInfo[USERNAME])); ?>'s avatar" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Total Posts</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><?php echo(number_format($aUserInfo[POSTCOUNT])); ?> (<?php echo(number_format(round($aUserInfo[POSTCOUNT] / $aUserStats[DAYSOLD], 2), 2)); ?> posts per day)</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Last Post</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium">
<?php
	// Display the member's last post.
	if(isset($aUserStats[LASTPOST]))
	{
		echo(gmtdate('m-d-Y h:i A', $aUserStats[LASTPOST][POSTDATE])); ?><br />
		<a href="thread.php?threadid=<?php echo($aUserStats[LASTPOST][THREAD]); ?>&amp;postid=<?php echo($aUserStats[LASTPOST][POSTID]); ?>#post<?php echo($aUserStats[LASTPOST][POSTID]); ?>"><?php echo(htmlsanitize($aUserStats[LASTPOST][TITLE])); ?></a>
<?php
	}
	else
	{
		echo('Never');
	}
?>	</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Contact <?php echo(htmlsanitize($aUserInfo[USERNAME])); ?></b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<a href="member.php?action=mailuser&amp;userid=<?php echo($aUserInfo[USERID]); ?>">Click here to e-mail <b><?php echo(htmlsanitize($aUserInfo[USERNAME])); ?></b>.</a>
		<?php if($aUserInfo[ENABLEPMS]){ ?><br /><a href="private.php?action=newmessage&amp;userid=<?php echo($aUserInfo[USERID]); ?>">Click here to send <b><?php echo(htmlsanitize($aUserInfo[USERNAME])); ?></b> a private message.</a><?php } ?>
	</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Web Site</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><?php if($aUserInfo[WEBSITE]){ ?><a href="<?php echo(htmlsanitize($aUserInfo[WEBSITE])); ?>" target="_blank"><?php echo(htmlsanitize($aUserInfo[WEBSITE])); ?></a><?php } ?></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>AIM Handle</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><?php echo(htmlsanitize($aUserInfo[AIM])); ?></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>ICQ Number</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><?php echo(htmlsanitize($aUserInfo[ICQ])); ?></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>MSN Messenger Handle</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><?php echo(htmlsanitize($aUserInfo[MSN])); ?></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Yahoo! Messenger Handle</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><?php echo(htmlsanitize($aUserInfo[YAHOO])); ?></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Birthday</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><?php echo(htmlsanitize($aUserInfo[BIRTHDAY])); ?></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Biography</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><?php echo(htmlsanitize($aUserInfo[BIO])); ?></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Location</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><?php echo(htmlsanitize($aUserInfo[LOCATION])); ?></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Interests</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><?php echo(htmlsanitize($aUserInfo[INTERESTS])); ?></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Occupation</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><?php echo(htmlsanitize($aUserInfo[OCCUPATION])); ?></td>
</tr>

<tr class="heading">
	<td align="center" colspan="2" class="smaller">
		<?php if(!$aUserInfo[BUDDY]) { ?><a class="heading" style="font-weight: normal; padding-right: 1em;" href="usercp.php?section=buddylist&amp;action=add&amp;userid=<?php echo($aUserInfo[USERID]); ?>">Add <b><?php echo(htmlsanitize($aUserInfo[USERNAME])); ?></b> to your Buddy list.</a><?php }else if($aUserInfo[BUDDY]) { ?><a class="heading" style="font-weight: normal; padding-right: 1em;" href="usercp.php?section=buddylist&amp;action=remove&amp;userid=<?php echo($aUserInfo[USERID]); ?>">Remove <b><?php echo(htmlsanitize($aUserInfo[USERNAME])); ?></b> from your Buddy list.</a><?php } if(!$aUserInfo[IGNORED]) { ?><a class="heading" style="font-weight: normal;" href="usercp.php?section=ignorelist&amp;action=add&amp;userid=<?php echo($aUserInfo[USERID]); ?>">Add <b><?php echo(htmlsanitize($aUserInfo[USERNAME])); ?></b> to your Ignore list.</a><?php }else if($aUserInfo[IGNORED]) { ?><a class="heading" style="font-weight: normal;" href="usercp.php?section=ignorelist&amp;action=remove&amp;userid=<?php echo($aUserInfo[USERID]); ?>">Remove <b><?php echo(htmlsanitize($aUserInfo[USERNAME])); ?></b> from your Ignore list.</a><?php } ?>
	</td>
</tr>

</table>

<div class="smaller" align="left"><br /><?php echo(TimeInfo()); ?></div>

<?php
	// Footer
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>