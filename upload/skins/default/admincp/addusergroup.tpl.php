<?php
	// Header
	$strPageTitle = ' :: Admin Control Panel :. Add Usergroup';
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; <a href="admincp.php">Admin Control Panel</a> &gt; <a href="admincp.php?section=usergroups">Usergroups</a> &gt; Add New Usergroup</b></td>
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
<input type="hidden" name="section" value="usergroups" />
<input type="hidden" name="action" value="add" />
<table cellpadding="4" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" align="center" width="100%">

<tr class="section">
	<td colspan="2" align="center" class="medium">Add New Usergroup</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Usergroup Name</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><input type="text" name="groupname" size="35" maxlength="255" value="<?php echo(htmlsanitize($aUsergroup['groupname'])); ?>" /></td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>User Status</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>"><input type="text" name="usertitle" size="35" maxlength="255" value="<?php echo(htmlsanitize($aUsergroup['usertitle'])); ?>" /></td>
</tr>

<tr class="section">
	<td colspan="2" class="medium">User Permissions</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Can view attachments?</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="radio" name="cviewattachments" value="1"<?php if($aUsergroup['cviewattachments']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="cviewattachments" value="0"<?php if(!$aUsergroup['cviewattachments']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Can view the Calendar?</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="radio" name="ccalendar" value="1"<?php if($aUsergroup['ccalendar']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="ccalendar" value="0"<?php if(!$aUsergroup['ccalendar']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Can make private events?</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="radio" name="cmakeevent" value="1"<?php if($aUsergroup['cmakeevent']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="cmakeevent" value="0"<?php if(!$aUsergroup['cmakeevent']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Can make public events?</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="radio" name="cmakepubevent" value="1"<?php if($aUsergroup['cmakepubevent']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="cmakepubevent" value="0"<?php if(!$aUsergroup['cmakepubevent']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Can edit own posts?</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="radio" name="ceditposts" value="1"<?php if($aUsergroup['ceditposts']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="ceditposts" value="0"<?php if(!$aUsergroup['ceditposts']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Can edit others' posts?</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="radio" name="cmeditposts" value="1"<?php if($aUsergroup['cmeditposts']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="cmeditposts" value="0"<?php if(!$aUsergroup['cmeditposts']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Can view member profiles?</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="radio" name="cviewprofiles" value="1"<?php if($aUsergroup['cviewprofiles']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="cviewprofiles" value="0"<?php if(!$aUsergroup['cviewprofiles']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Can view the Memberlist?</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="radio" name="cviewmembers" value="1"<?php if($aUsergroup['cviewmembers']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="cviewmembers" value="0"<?php if(!$aUsergroup['cviewmembers']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Can view user IP addresses?</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="radio" name="cviewips" value="1"<?php if($aUsergroup['cviewips']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="cviewips" value="0"<?php if(!$aUsergroup['cviewips']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Can reply to open threads?</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="radio" name="creply" value="1"<?php if($aUsergroup['creply']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="creply" value="0"<?php if(!$aUsergroup['creply']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Can reply to closed threads?</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="radio" name="creplyclosed" value="1"<?php if($aUsergroup['creplyclosed']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="creplyclosed" value="0"<?php if(!$aUsergroup['creplyclosed']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Can post new threads?</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="radio" name="cmakethreads" value="1"<?php if($aUsergroup['cmakethreads']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="cmakethreads" value="0"<?php if(!$aUsergroup['cmakethreads']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Can create new polls?</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="radio" name="cmakepolls" value="1"<?php if($aUsergroup['cmakepolls']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="cmakepolls" value="0"<?php if(!$aUsergroup['cmakepolls']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Can vote in polls?</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="radio" name="cvotepolls" value="1"<?php if($aUsergroup['cvotepolls']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="cvotepolls" value="0"<?php if(!$aUsergroup['cvotepolls']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Can view online users?</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="radio" name="cviewonline" value="1"<?php if($aUsergroup['cviewonline']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="cviewonline" value="0"<?php if(!$aUsergroup['cviewonline']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Can view invisible users?</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="radio" name="cviewinvisible" value="1"<?php if($aUsergroup['cviewinvisible']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="cviewinvisible" value="0"<?php if(!$aUsergroup['cviewinvisible']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Can search the forums?</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="radio" name="csearch" value="1"<?php if($aUsergroup['csearch']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="csearch" value="0"<?php if(!$aUsergroup['csearch']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Can Open/Close threads?</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="radio" name="cmopenclosethreads" value="1"<?php if($aUsergroup['cmopenclosethreads']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="cmopenclosethreads" value="0"<?php if(!$aUsergroup['cmopenclosethreads']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Can move/copy threads?</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="radio" name="cmovethreads" value="1"<?php if($aUsergroup['cmovethreads']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="cmovethreads" value="0"<?php if(!$aUsergroup['cmovethreads']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Can Stick/Unstick threads?</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="radio" name="cmstickythreads" value="1"<?php if($aUsergroup['cmstickythreads']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="cmstickythreads" value="0"<?php if(!$aUsergroup['cmstickythreads']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Can delete others' threads?</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="radio" name="cmdeletethreads" value="1"<?php if($aUsergroup['cmdeletethreads']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="cmdeletethreads" value="0"<?php if(!$aUsergroup['cmdeletethreads']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Can delete others' posts?</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="radio" name="cmdeleteposts" value="1"<?php if($aUsergroup['cmdeleteposts']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="cmdeleteposts" value="0"<?php if(!$aUsergroup['cmdeleteposts']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><b>Can access Admin CP?</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium"><input type="radio" name="cviewadmincp" value="1"<?php if($aUsergroup['cviewadmincp']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="cviewadmincp" value="0"<?php if(!$aUsergroup['cviewadmincp']){echo(' checked="checked"');} ?> />No.</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><b>Can bypass the forum's floodcheck feature?</b></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium"><input type="radio" name="cbypassflood" value="1"<?php if($aUsergroup['cbypassflood']){echo(' checked="checked"');} ?> />Yes. &nbsp; <input type="radio" name="cbypassflood" value="0"<?php if(!$aUsergroup['cbypassflood']){echo(' checked="checked"');} ?> />No.</td>
</tr>

</table>

<div style="text-align: center;"><br /><input type="submit" name="submit" value="Add Usergroup" accesskey="s" /></div>
</form>

<?php
	// Footer
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>