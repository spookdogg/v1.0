<?php
	// Header.
	$strPageTitle = ' :: Log In';
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<form action="member.php" method="post">
<input type="hidden" name="action" value="login" />

<br /><br /><br />
<table cellpadding="0" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="500" align="center">
<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>">
		<table width="100%" align="center" cellspacing="5" cellpadding="2" border="0">

		<tr><td width="100%" valign="top" align="center">
			<table align="center" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td width="100%" align="left" class="small">
					<b>Username</b><br />
					<input type="text" name="username" value="<?php echo htmlsanitize($strPostedUsername) ?>" maxlength="<?php echo($CFG['maxlen']['username']); ?>" />
				</td>
			</tr>
			</table>
		</td></tr>

		<tr><td width="100%" valign="top" align="center">
			<table align="center" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td width="100%" align="left" class="small">
					<b>Password</b><br />
					<input type="password" name="password" value="" maxlength="<?php echo($CFG['maxlen']['password']); ?>" />
				</td>
			</tr>
			</table>
		</td></tr>

		<tr>
			<td width="100%" align="right" class="small">
				<a href="member.php?action=forgotdetails">Forget your login details?</a>
				<hr />
			</td>
		</tr>

		<tr>
			<td width="100%" align="center">
				<input type="submit" value="Login" />
			</td>
		</tr>

		</table>
	</td>
</tr>
</table>
<br /><br /><br />

</form>

<?php
	// Footer.
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>