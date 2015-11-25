<?php
	// Header
	$strPageTitle = ' :: Admin Control Panel :. Edit Forum';
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; <a href="admincp.php">Admin Control Panel</a> &gt; <a href="admincp.php?section=forums">Forums</a> &gt; Edit Forum</b></td>
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
<input type="hidden" name="section" value="forums" />
<input type="hidden" name="action" value="edit" />
<input type="hidden" name="forumid" value="<?php echo($aForum['id']); ?>" />
<table cellpadding="4" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" align="center" width="100%">

<tr class="section">
	<td colspan="2" align="center" class="medium">Edit Forum</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Title</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><input type="text" name="title" size="35" maxlength="255" value="<?php echo(htmlsanitize($aForum['title'])); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium">
		<b>Description</b>
		<div class="smaller">You can use HTML.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>"><input type="text" name="description" size="35" maxlength="255" value="<?php echo(htmlsanitize($aForum['description'])); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<b>Display Order</b>
		<div class="smaller">The order in which the forum is displayed relative to its siblings.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><input type="text" name="displayorder" size="5" maxlength="3" value="<?php echo($aForum['displayorder']); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Parent Forum</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>">
		<select name="parent">
			<option value="0">None (category)</option>
<?php
	foreach($aForums as $iForumID => $strForumName)
	{
		$strSelected = ($aForum['parent'] == $iForumID) ? ' selected="selected"' : '';
		$strForumName = htmlsanitize($strForumName);
		echo("			<option value=\"{$iForumID}\"{$strSelected}>{$strForumName}</option>\n");
	}
?>
		</select>
	</td>
</tr>

</table>

<div style="text-align: center;"><br /><input type="submit" name="submit" value="Save Changes" accesskey="s" /></div>
</form>

<?php
	// Footer
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>