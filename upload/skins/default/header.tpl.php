<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<?php
	// Are we redirecting?
	if($strRedirect)
	{
		// Print out the meta redirect.
		echo("\t<meta http-equiv=\"Refresh\" content=\"1; URL={$strRedirect}\" />\n");
	}
?>
	<link rel="SHORTCUT ICON" href="favicon.ico" />
	<link href="style.php" rel="stylesheet" type="text/css" />
	<title><?php echo(htmlsanitize($CFG['general']['name']).$strPageTitle); ?></title>
<?php
	if($CFG['newpm'] && !$_SESSION['redirected'])
	{
?>

	<script type="text/javascript">
	<!--
		function newpm()
		{
			if(confirm('You have a new private message. Click OK to view it or Cancel to hide this prompt.'))
			{
				if(confirm('Open in new window?\n\n(Press Cancel to open your Inbox in the current window.)'))
				{
					window.open('private.php');
				}
				else
				{
					window.location = 'private.php';
				}
			}
		}
	// -->
	</script>
<?php
	}
?>
</head>

<body<?php echo(($CFG['newpm'] && !$_SESSION['redirected']) ? ' onload="javascript:newpm();"' : ''); ?>>

<table align="center" border="0" cellpadding="0" cellspacing="0" width="<?php echo($CFG['style']['table']['width']); ?>">
<tr>
	<td align="left" valign="bottom" nowrap="nowrap"><a href="index.php"><img src="images/logo.png" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?>" /></a></td>
	<td align="right" valign="bottom" width="100%" nowrap="nowrap">
<?php
	if($_SESSION['loggedin'])
	{
		if($_SESSION['permissions']['cviewadmincp'])
		{
?>		<a href="admincp.php"><img src="images/menu_admincp.png" border="0" alt="Admin Control Panel" /></a>
<?php
		}
?>		<a href="usercp.php"><img src="images/menu_usercp.png" border="0" alt="User Control Panel" /></a>
		<a href="member.php?action=logout"><img src="images/menu_logout.png" border="0" alt="Logout" /></a>
<?php
	}
	else
	{
?>		<a href="register.php"><img src="images/menu_register.png" border="0" alt="Register" /></a>
		<a href="member.php?action=login"><img src="images/menu_login.png" border="0" alt="Login" /></a>
<?php
	}
?>		<a href="calendar.php"><img src="images/menu_calendar.png" border="0" alt="Calendar" /></a>
		<a href="memberlist.php"><img src="images/menu_members.png" border="0" alt="Members" /></a>
		<a href="search.php"><img src="images/menu_search.png" border="0" alt="Search" /></a>
		<a href="index.php"><img src="images/menu_home.png" border="0" alt="Home" /></a>&nbsp;
	</td>
</tr>
</table>


<table bgcolor="<?php echo($CFG['style']['forum']['bgcolor']); ?>" width="<?php echo($CFG['style']['table']['width']); ?>" cellpadding="10" cellspacing="0" border="0" align="center"><tr><td width="100%">
<table width="<?php echo($CFG['style']['content_table']['width']); ?>" cellpadding="0" cellspacing="0" border="0" align="center">
<tr><td class="medium">