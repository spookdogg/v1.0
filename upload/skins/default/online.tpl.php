<?php
	// Header.
	$strPageTitle = ' :: Who\'s Online?';
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; Who's Online?</b></td>
</tr>
</table>

<br />

<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellspacing="1" cellpadding="4" border="0" align="center">

<tr class="section"><td width="100%" align="left" colspan="5"><?php echo(htmlsanitize($CFG['general']['name'])); ?> - Who's Online @ <?php echo(gmtdate('h:i A', $CFG['globaltime'])); ?></td></tr>

<tr class="heading">
	<td align="center" class="smaller">Username</td>
	<td align="center" class="smaller">Last Activity</td>
	<td align="center" class="smaller">Last Active</td>
	<td align="center" class="smaller">PM</td>
	<?php if($_SESSION['permissions']['cviewips'] && $CFG['iplogging']) { ?><td align="center" class="smaller">IP</td><?php } ?>
</tr>
<?php
	// Display the online users.
	foreach($aUsers as $iUserID => $aUser)
	{
?>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" align="left" class="medium"><a href="member.php?action=getprofile&amp;userid=<?php echo($iUserID); ?>"><?php echo(htmlsanitize($aUser[USERNAME])); ?></a></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" align="left" class="medium"><?php echo($aUser[LOCATION]); ?></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" align="center" class="medium"><?php echo(gmtdate('g:i A', $aUser[LASTACTIVE])); ?></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" align="center"><a href="private.php?action=newmessage&amp;userid=<?php echo($iUserID); ?>"><img src="images/user_msg.png" border="0" alt="Send <?php echo(htmlsanitize($aUser[USERNAME])); ?> a private message" /></a></td>
	<?php if($_SESSION['permissions']['cviewips'] && $CFG['iplogging']) { ?><td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" align="left" class="medium"><?php echo($aUser[IPADDRESS]); ?></td><?php } ?>
</tr>

<?php
	}

	// Display the online guests.
	foreach($aGuests as $aGuest)
	{
?>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" align="left" class="medium">Guest</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" align="left" class="medium"><?php echo($aGuest[LOCATION]); ?></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" align="center" class="medium"><?php echo(gmtdate('g:i A', $aGuest[LASTACTIVE])); ?></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" align="center"></td>
	<?php if($_SESSION['permissions']['cviewips'] && $CFG['iplogging']) { ?><td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" align="left" class="medium"><?php echo($aGuest[IPADDRESS]); ?></td><?php } ?>
</tr>

<?php
	}
?>

</table>

<br />

<table cellpadding="0" cellspacing="0" border="0" align="center">
<tr>
	<td align="left" valign="middle" class="smaller" width="100%">[<a href="online.php">Reload this page.</a>]</td>
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
</tr>
</table>

<?php

	// Footer.
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>