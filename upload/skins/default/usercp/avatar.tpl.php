<?php
	// Header.
	$strPageTitle = ' :: User Control Panel :. Edit Avatar';
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; <a href="usercp.php">User Control Panel</a> &gt; Edit Avatar</b></td>
</tr>
</table><br />

<?php
	// User CP menu.
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

<form enctype="multipart/form-data" name="theform" action="usercp.php" method="post">
<input type="hidden" name="section" value="avatar" />
<table cellpadding="4" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" align="center">

<tr class="section"><td colspan="2" align="center" class="medium">Edit Avatar</td></tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<b>Use Avatar</b>
		<div class="smaller">Avatars are small graphics that are displayed under your username whenever you post.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="radio" name="avatarid" value="-2"<?php if($strAvatarData==NULL){echo(' checked="checked"');} ?> />No</td>
</tr>

<?php
	// Display any public avatars.
	if(count($aAvatars))
	{
?>
<tr class="section"><td colspan="2" class="medium">Public Avatars</td></tr>
<tr><td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" colspan="2"><?php AvatarTable($iAvatarID, $aAvatars); ?></td></tr>
<?php
	}
?>

<tr class="section"><td colspan="2" class="medium">Custom Avatar</td></tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium">
		<b>Use Custom Avatar</b>
		<div class="smaller">Note: The maximum size of your custom avatar is <?php echo($CFG['avatars']['maxdims']); ?>x<?php echo($CFG['avatars']['maxdims']); ?> pixels/<?php echo($CFG['avatars']['maxsize']); ?> bytes.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium">
		<input type="radio" name="avatarid" value="-1" id="customavatar"<?php if($strFilename){echo(' checked="checked"');} ?> />Yes
<?php
	if($strFilename)
	{
?>		<div class="smaller">(The database currently has the following custom avatar in your name: <?php if($strFilename){echo("<img src=\"avatar.php?userid={$_SESSION['userid']}\" alt=\"\" />");} ?><br /> If you want to keep it as it is, leave the fields below as they are.)</div>
<?php
	}
?>	</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium">
		<b>Save your avatar from a remote server:</b>
		<div class="smaller">Note: The file will be stored locally on this server.</div>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="text" name="avatarurl" value="http://" onchange="theform.customavatar.checked=true;" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Upload your avatar from your computer:</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium">
		<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo($CFG['avatars']['maxsize']); ?>" />
		<input type="file" name="avatarfile" onchange="theform.customavatar.checked=true;" />
	</td>
</tr>

</table>

<div style="text-align: center;"><br /><input type="submit" name="submit" value="Save Changes" accesskey="s" /></div>
</form>

<?php
	// Footer.
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>