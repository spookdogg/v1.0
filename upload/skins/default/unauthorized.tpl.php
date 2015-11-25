<?php
	// Header.
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<br /><br />

<table cellpadding="3" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="65%" align="center">

<tr class="heading"><td>OvBB Message</td></tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium" style="padding: 10px; text-align: justify;">
		You do not have permission to access this page. This could be due to one of several reasons:
		<ol style="padding-right: 20px;">
<?php
	// Are they logged in?
	if(!$_SESSION['loggedin'])
	{
?>			<li style="text-align: justify; padding-bottom: 5px;">You are not logged in. Fill in the form at the bottom of this page and try again.</li>
<?php
	}
?>			<li style="text-align: justify; padding-bottom: 5px;">You are trying to edit someone else's post or access other administrative features.</li>
			<li style="text-align: justify;">If you are trying to post, the administrator may have disabled your account, or it may be awaiting activation.</li>
		</ol>

<?php
	if(!$_SESSION['loggedin'])
	{
		// Save the request so we can adequately redirect them to where they were going once they're logged in.
		$_SESSION['request'] = $_REQUEST;
?>
		<form action="member.php" method="post">
		<input type="hidden" name="action" value="login" />
		<input type="hidden" name="redirect" value="<?php echo(urlencode($CFG['currentpage'])); ?>" />
		<br /><table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" cellspacing="1" cellpadding="4" border="0" align="center">
			<tr>
				<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Your Username:</b></td>
				<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="smaller"><input type="text" name="username" maxlength="<?php echo($CFG['maxlen']['username']); ?>" />&nbsp;&nbsp;&nbsp;&nbsp;<a href="register.php">Want to register?</a></td>
			</tr>
			<tr>
				<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Your Password:</b></td>
				<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="smaller"><input type="password" name="password" maxlength="<?php echo($CFG['maxlen']['password']); ?>" />&nbsp;&nbsp;&nbsp;&nbsp;<a href="member.php?action=forgotdetails">Forgot your password?</a></td>
			</tr>
		</table><br />
		<div style="text-align: center;"><input type="submit" name="submit" value="Login" /></div>
		</form>
<?php
	}
	else
	{
?>
		<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" cellspacing="1" cellpadding="4" border="0" align="center">
		<tr>
			<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Logged In As:</b></td>
			<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><?php echo(htmlsanitize($_SESSION['username'])); ?> <font class="smaller">[<a href="member.php?action=logout">Logout</a>]</font></td>
		</tr>
		</table><br />
<?php
	}
?>	</td>
</tr>

</table><br />

<table cellpadding="0" cellspacing="0" border="0" align="center">
<tr>
	<td class="smaller" nowrap="nowrap">
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

<br /><br />

<?php
	// Footer.
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>