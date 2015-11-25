<?php
	// Header.
	$strPageTitle = ' :: Member List';
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; Member List</b></td>
</tr>
</table><br />

<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellspacing="1" cellpadding="0" border="0" align="center">
<tr><td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" width="100%">
	<table width="100%" cellspacing="0" cellpadding="2" border="0">
	<tr>
		<td align="center"<?php if($_REQUEST['letter'] == '#'){echo(" bgcolor=\"{$CFG['style']['table']['cella']}\"");} ?> class="medium"><a href="memberlist.php?letter=%23"><?php echo(($_REQUEST['letter'] == '#') ? '<b>#</b>' : '#'); ?></a></td>
<?php
	// A-Z
	for($ch = 97; $ch < 123; $ch++)
	{
		$strBackground = ($_REQUEST['letter'] == chr($ch)) ? " bgcolor=\"{$CFG['style']['table']['cella']}\"" : '';
		$strChar = ($_REQUEST['letter'] == chr($ch)) ? '<b>'.strtoupper(chr($ch)).'</b>' : strtoupper(chr($ch));
?>
		<td align="center"<?php echo($strBackground); ?> class="medium"><a href="memberlist.php?letter=<?php echo(chr($ch)); ?>"><?php echo($strChar); ?></a></td>
<?php
	}
?>
	</tr>
	</table>
</td></tr>
</table>

<br />

<?php
	// Only display the table if there are some members to display.
	if($iNumberPages)
	{
?>
<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellspacing="1" cellpadding="4" border="0" align="center">

<tr class="section"><td width="100%" align="left" colspan="5" class="medium"><?php echo(htmlsanitize($CFG['general']['name'])); ?></td></tr>

<tr class="heading">
	<td width="40%" align="left" valign="middle" colspan="2">
		<table cellspacing="0" cellpadding="0" border="0"><tr>
			<td><img src="images/space.png" width="15" height="1" alt="" /></td>
			<td><img src="images/space.png" width="9" height="1" alt="" /></td>
			<td align="left" class="smaller"><a class="heading" href="memberlist.php?letter=<?php echo(urlencode($_REQUEST['letter'])); ?>&amp;perpage=<?php echo($iUsersPerPage); ?>&amp;page=<?php echo($iPage); ?>&amp;sortby=username&amp;sortorder=<?php if(($strSortOrder=='ASC')&&($strSortBy=='username')){echo('desc');}else{echo('asc');} ?>">Username</a><?php if($strSortBy == 'username'){echo('&nbsp;');} ?></td>
			<td class="smaller"><?php if($strSortBy == 'username'){if($strSortOrder=='ASC'){echo(' <img src="images/sort_asc.png" style="vertical-align: middle;" alt="Ascending" />');}else{echo(' <img src="images/sort_desc.png" style="vertical-align: middle;" alt="Descending" />');}} ?></td>
		</tr></table>
	</td>
	<td width="20%" align="center" class="smaller">Web Site</td>
	<td width="20%" align="center">
		<table cellpadding="0" cellspacing="0" border="0"><tr>
			<td class="smaller"><a class="heading" href="memberlist.php?letter=<?php echo(urlencode($_REQUEST['letter'])); ?>&amp;perpage=<?php echo($iUsersPerPage); ?>&amp;page=<?php echo($iPage); ?>&amp;sortby=datejoined&amp;sortorder=<?php if(($strSortOrder=='ASC')&&($strSortBy=='datejoined')){echo('desc');}else{echo('asc');} ?>">Join Date</a><?php if($strSortBy == 'datejoined'){echo('&nbsp;');} ?></td>
			<td class="smaller"><?php if($strSortBy == 'datejoined'){if($strSortOrder=='ASC'){echo(' <img src="images/sort_asc.png" style="vertical-align: middle;" alt="Ascending" />');}else{echo(' <img src="images/sort_desc.png" style="vertical-align: middle;" alt="Descending" />');}} ?></td>
		</tr></table>
	</td>
	<td width="20%" align="center" nowrap="nowrap">
		<table cellpadding="0" cellspacing="0" border="0"><tr>
			<td class="smaller"><a class="heading" href="memberlist.php?letter=<?php echo(urlencode($_REQUEST['letter'])); ?>&amp;perpage=<?php echo($iUsersPerPage); ?>&amp;page=<?php echo($iPage); ?>&amp;sortby=postcount&amp;sortorder=<?php if(($strSortOrder=='ASC')&&($strSortBy=='postcount')){echo('desc');}else{echo('asc');} ?>">Post Count</a><?php if($strSortBy == 'postcount'){echo('&nbsp;');} ?></td>
			<td class="smaller"><?php if($strSortBy == 'postcount'){if($strSortOrder=='ASC'){echo(' <img src="images/sort_asc.png" style="vertical-align: middle;" alt="Ascending" />');}else{echo(' <img src="images/sort_desc.png" style="vertical-align: middle;" alt="Descending" />');}} ?></td>
		</tr></table>
	</td>
</tr>
<?php
		// Display the members.
		foreach($aMembers as $iMemberID => $aMember)
		{
			// Do some value preparation.
			$aMember[USERNAME] = htmlsanitize($aMember[USERNAME]);
			$aMember[WEBSITE] = htmlsanitize($aMember[WEBSITE]);
			$aMember[ONLINE] = $aMember[ONLINE] ? 'online' : 'offline';

			// Set the color.
			$strColor = ($strColor == $CFG['style']['table']['cella']) ? $CFG['style']['table']['cellb'] : $CFG['style']['table']['cella'];

?>
<tr>
	<td bgcolor="<?php echo($strColor); ?>" width="40%" align="left" valign="middle" colspan="2">
	<table cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td align="center" valign="middle"><img src="images/<?php if($aMember[ONLINE] == 'offline'){echo('in');} ?>active.png" align="middle" alt="<?php echo($aMember[USERNAME]); ?> is <?php echo($aMember[ONLINE]); ?>" /></td>
		<td><img src="images/space.png" width="9" height="17" alt="" /></td>
		<td align="left" valign="middle" class="medium"><a href="member.php?action=getprofile&amp;userid=<?php echo($iMemberID); ?>"><?php echo($aMember[USERNAME]); ?></a></td>
	</tr>
	</table>
	</td>
	<td bgcolor="<?php echo($strColor); ?>" width="20%" align="center"><?php if($aMember[WEBSITE]){echo('<a href="'.$aMember[WEBSITE].'" target="_blank"><img src="images/user_www.png" alt="Visit '.$aMember[USERNAME].'\'s Web site" border="0" /></a>');} ?></td>
	<td bgcolor="<?php echo($strColor); ?>" width="20%" align="center" class="medium"><?php echo(gmtdate('m-d-Y', $aMember[JOINDATE])); ?></td>
	<td bgcolor="<?php echo($strColor); ?>" width="20%" align="center" class="medium"><?php echo($aMember[POSTCOUNT]); ?></td>
</tr>
<?php
		}
?>

</table>

<div class="small" align="center"><br />

<?php
	Paginate('memberlist.php?letter='.urlencode($_REQUEST['letter']), $iNumberPages, $iPage, $iUsersPerPage, $strSortBy, strtolower($strSortOrder));
?>

</div>

<?php
	}
	else
	{
		echo('<div align="center" class="medium"><b>There are no members with usernames like that.</b></div>');
	}

	// Footer.
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>