<?php
	// Header.
	$strPageTitle = htmlsanitize(" :: Calendar :. {$strTitle}");
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; <a href="calendar.php">Calendar</a> &gt; <?php echo(htmlsanitize($strTitle)); ?></b>
</tr>
</table>

<br /><br /><br />

<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" cellspacing="1" cellpadding="4" border="0" align="center">

<tr class="heading">
	<td colspan="2" align="center" class="medium"><?php echo(htmlsanitize($strTitle)); ?></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium" valign="top"><b>Type</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><?php echo($bPublic ? 'Public' : 'Private'); ?> Event</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium" valign="top"><b>Date</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><?php echo(date('m-d-Y', strtotime($strDate))); ?></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium" valign="top" nowrap="nowrap"><b>Event Information</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><?php echo($strEventInfo); ?></td>
</tr>

<?php
	if($_SESSION['userid'] == $iAuthor)
	{
?>

<tr class="heading">
	<td colspan="2" align="center" class="smaller"><a class="heading" href="calendar.php?action=editevent&amp;eventid=<?php echo($iEventID); ?>">Edit</a></td>
</tr>

<?php
	}
?>

</table>

<br /><br />

<?php
	// Footer.
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>