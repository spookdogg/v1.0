<?php
	// Header
	$strPageTitle = ' :: Admin Control Panel :. Censored Words';
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; <a href="admincp.php">Admin Control Panel</a> &gt; Censored Words</b></td>
</tr>
</table><br />

<?php
	// Admin CP menu.
	PrintCPMenu();
?>

<br />

<table cellpadding="4" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" align="center" width="100%">

<tr class="section">
	<td colspan="5" align="center" class="medium">Censored Words</td>
</tr>

<tr><td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium" style="padding: 10px;">
<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" cellpadding="4" cellspacing="1" border="0" align="center">

<tr class="section">
	<td align="center" class="small">Word</td>
	<td align="center" class="small">Replacement</td>
	<td align="center" class="small" colspan="2">Actions</td>
</tr>

<form action="admincp.php" method="post">
<input type="hidden" name="section" value="censored">
<input type="hidden" name="action" value="Add">
<tr>
	<td bgcolor="<?php echo $CFG['style']['table']['cellb']; ?>"><input type="input" name="word" value=""></td>
	<td bgcolor="<?php echo $CFG['style']['table']['cellb']; ?>"><input type="input" name="replacement" value=""></td>
	<td bgcolor="<?php echo $CFG['style']['table']['cellb']; ?>" align="center"><input type="submit" value="Add New Word"></td>
</tr>
</form>

<?php while(list($key, $aCensoredWord) = each($aCensored)) { ?>
<form action="admincp.php" method="post">
<input type="hidden" name="section" value="censored">
<input type="hidden" name="word" value="<?php echo $aCensoredWord[0]; ?>">
<tr>
	<td bgcolor="<?php echo $CFG['style']['table']['cellb']; ?>"><?php echo $aCensoredWord[0]; ?></td>
	<td bgcolor="<?php echo $CFG['style']['table']['cellb']; ?>"><input type="input" name="replacement" value="<?php echo $aCensoredWord[1]; ?>"></td>
	<td bgcolor="<?php echo $CFG['style']['table']['cellb']; ?>"><input type="submit" name="action" value="Update">&nbsp;<input type="submit" name="action" value="Remove"></td>
</tr>
</form>
<?php } ?>

</table>
</td></tr>

</table>

<?php
	// Footer
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>