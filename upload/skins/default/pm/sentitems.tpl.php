<?php
	// Header.
	$strPageTitle = ' :: Private Messages :. Sent Items';
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; <a href="usercp.php">User Control Panel</a> &gt; <a href="private.php">Private Messages</a> &gt; Sent Items</b></td>
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
			<option value="0">Inbox</option>
			<option value="1" selected="selected">Sent Items</option>
<?php
	// Print out all of the custom folders.
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
</table>

<?php
	// Get the information of each forum.
	list($aCategory, $aForum) = GetForumInfo();

	// Display the messages if there are any.
	if(count($aMessages))
	{
?>

<script language="JavaScript" type="text/javascript">
<!--
function check()
{
	for(var i=0; i < document.theform.elements.length; i++)
	{
		var e = document.theform.elements[i];
		if(e.type == "checkbox")
		{
			e.checked = document.theform.checkall.checked;
		}
	}
}
//-->
</script>

<form name="theform" action="private.php" method="post">
<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellpadding="4" cellspacing="1" border="0" align="center">

<tr class="heading">
	<td class="smaller" width="15"><img src="images/space.png" width="15" height="1" alt="" /></td>
	<td class="smaller" width="15"><img src="images/space.png" width="15" height="1" alt="" /></td>
	<td align="center" class="smaller" width="80%">Message Subject</td>
	<td align="center" class="smaller" width="20%" nowrap="nowrap">Recipient</td>
	<td align="center" class="smaller" nowrap="nowrap">Date/Time Sent</td>
	<td class="smaller" align="center"><input type="checkbox" name="checkall" onclick="check();" /></td>
</tr>

<?php
	foreach($aMessages as $iMessageID => $aMessage)
	{
?>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="smaller" align="center" valign="middle"><img src="images/message_old.png" alt="Read Message" /></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="smaller" align="center" valign="middle"><img src="<?php echo($aMessage[ICON][URL]); ?>" alt="<?php echo($aMessage[ICON][ALT]); ?>" /></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><a href="private.php?action=viewmessage&amp;id=<?php echo($iMessageID); ?>"><?php echo(htmlsanitize($aMessage[SUBJECT])); ?></a></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium" align="center" valign="middle" nowrap="nowrap"><a href="member.php?action=getprofile&amp;userid=<?php echo($aMessage[RECIPIENT]); ?>"><?php echo(htmlsanitize($aUsernames[$aMessage[RECIPIENT]])); ?></a></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="smaller"><?php echo(gmtdate('m-d-Y', $aMessage[DATETIME])); ?><br /><span style="color: <?php echo($CFG['style']['table']['timecolor']); ?>;"><?php echo(gmtdate('h:i A', $aMessage[DATETIME])); ?></span></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="smaller" align="center"><input type="checkbox" name="id[]" value="<?php echo($iMessageID); ?>" /></td>
</tr>

<?php
	}
?>

<tr class="heading"><td class="smaller" colspan="6">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr class="smaller">
		<td align="right" class="smaller" width="100%">Selected message(s):&nbsp;</td>
<?php
	// Print out all of the custom folders.
	if(count($aFolders))
	{
?>
		<td>
			<select name="dest">
<?php
		foreach($aFolders as $iFolderID => $strFolder)
		{
			$strFolder = htmlsanitize($strFolder);
			echo("\t\t\t\t<option value=\"{$iFolderID}\">{$strFolder}</option>\n");
		}
?>
			</select>
		</td>
		<td>&nbsp;</td>
		<td><input class="heading" type="submit" name="action" value="Move" style="font-size: 10px;" /></td>
		<td>&nbsp;or&nbsp;</td>
<?php
	}
?>
		<td><input class="heading" type="submit" name="action" value="Forward" style="font-size: 10px;" /></td>
		<td>&nbsp;or&nbsp;</td>
		<td><input class="heading" type="submit" name="action" value="Delete" style="font-size: 10px;" /></td>
	</tr>
	</table>
</td></tr>

</table>
</form>

<?php
	}
	else
	{
		echo('<div class="medium" align="center"><br /><b>There are no messages in this folder!</b><br /><br /></div>');
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

<br />

<div align="center" class="smaller">
	<img style="vertical-align: middle;" src="images/message_new.png" alt="Unread Message" align="middle" /> <b>Unread message</b>&nbsp;&nbsp;
	<img style="vertical-align: middle;" src="images/message_old.png" alt="Read Message" align="middle" /> <b>Read message</b>&nbsp;&nbsp;
	<img style="vertical-align: middle;" src="images/message_replied.png" alt="Replied To Message" align="middle" /> <b>Replied to message</b>
</div>

<?php
	// Footer.
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>