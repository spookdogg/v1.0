<br />

<form action="newreply.php" method="post">
<input type="hidden" name="threadid" value="<?php echo($iThreadID); ?>" />
<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" cellspacing="1" cellpadding="2" border="0" align="center">
	<tr class="heading"><td align="center" class="small">Quick Reply</td></tr>
	<tr><td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" align="center" class="small">
		<textarea name="message" cols="90" rows="7" class="medium"></textarea><br /><br />
		<input type="submit" name="submit" value="Submit Reply" accesskey="s" /> <input type="submit" name="submit" value="Preview Reply" accesskey="p" /><br /><br />
	</td></tr>
</table>
</form>