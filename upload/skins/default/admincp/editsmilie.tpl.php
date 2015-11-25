<?php
	// Header
	$strPageTitle = ' :: Admin Control Panel :. Edit Smilie';
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; <a href="admincp.php">Admin Control Panel</a> &gt; <a href="admincp.php?section=smilies">Smilies</a> &gt; Edit Smilie</b></td>
</tr>
</table><br />

<?php
	// Admin CP menu.
	PrintCPMenu();

	// Display any errors.
	if($aError)
	{
		DisplayErrors($aError);
	}
	else
	{
		echo('<br />');
	}
?>

<form name="theform" action="admincp.php" method="post">
<input type="hidden" name="section" value="smilies" />
<input type="hidden" name="action" value="edit" />
<input type="hidden" name="smilieid" value="<?php echo($aSmilie['id']); ?>" />
<table cellpadding="4" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" align="center" width="100%">

<tr class="section">
	<td colspan="2" align="center" class="medium">Edit Smilie</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Title</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><input type="text" name="title" size="35" maxlength="255" value="<?php echo(htmlsanitize($aSmilie['title'])); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Code</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>"><input type="text" name="code" size="35" maxlength="255" value="<?php echo(htmlsanitize($aSmilie['code'])); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<b>File Name</b>
		<div class="smaller">This is the name of the smilie image (located in &quot;<b><?php echo($CFG['paths']['smilies']); ?></b>&quot;).</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><input type="text" name="filename" size="35" maxlength="255" value="<?php echo(htmlsanitize($aSmilie['filename'])); ?>" /></td>
</tr>

</table>

<div style="text-align: center;"><br /><input type="submit" name="submit" value="Save Changes" accesskey="s" /></div>
</form>

<?php
	// Footer
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>