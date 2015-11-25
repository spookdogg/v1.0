<?php
	// Header.
	$strPageTitle = ' :: Forgot Member Details';
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<form action="member.php" method="post">
<input type="hidden" name="action" value="request" />

<br /><br />
<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="600" cellspacing="1" cellpadding="4" border="0" align="center">
	<tr class="heading"><td>Member Details Recovery</td></tr>
	<tr><td class="medium" bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" style="text-align: justify;">To receive your username and instructions on how to reset your password,  specify the e-mail address on file for your membership.</td></tr>
	<tr><td align="center" class="medium" bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><b>Your e-mail address:</b> <input type="text" name="email" size="32" maxlength="<?php echo($CFG['maxlen']['email']); ?>" /></td></tr>
</table><br />

<div style="text-align: center;"><input type="submit" name="submit" value="Request Username/Password Now" /></div>
</form><br /><br />

<?php
	// Footer.
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>