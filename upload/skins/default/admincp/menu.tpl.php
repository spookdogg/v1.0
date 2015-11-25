<table cellpadding="4" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%">
<tr>
	<td align="center" bgcolor="<?php echo(($strSection == 'general') ? $CFG['style']['table']['cella'] : $CFG['style']['table']['cellb']); ?>" class="smaller"><b><a href="admincp.php?section=general">General Options</a></b></td>
	<td align="center" bgcolor="<?php echo(($strSection == 'style') ? $CFG['style']['table']['cella'] : $CFG['style']['table']['cellb']); ?>" class="smaller"><b><a href="admincp.php?section=style">Style</a></b></td>
	<td align="center" bgcolor="<?php echo(($strSection == 'forums') ? $CFG['style']['table']['cella'] : $CFG['style']['table']['cellb']); ?>" class="smaller"><b><a href="admincp.php?section=forums">Forums</a></b></td>
	<td align="center" bgcolor="<?php echo(($strSection == 'attachments') ? $CFG['style']['table']['cella'] : $CFG['style']['table']['cellb']); ?>" class="smaller"><b><a href="admincp.php?section=attachments">Attachments</a></b></td>
	<td align="center" bgcolor="<?php echo(($strSection == 'usergroups') ? $CFG['style']['table']['cella'] : $CFG['style']['table']['cellb']); ?>" class="smaller"><b><a href="admincp.php?section=usergroups">Usergroups</a></b></td>
	<td align="center" bgcolor="<?php echo(($strSection == 'avatars') ? $CFG['style']['table']['cella'] : $CFG['style']['table']['cellb']); ?>" class="smaller"><b><a href="admincp.php?section=avatars">Avatars</a></b></td>
	<td align="center" bgcolor="<?php echo(($strSection == 'smilies') ? $CFG['style']['table']['cella'] : $CFG['style']['table']['cellb']); ?>" class="smaller"><b><a href="admincp.php?section=smilies">Smilies</a></b></td>
	<td align="center" bgcolor="<?php echo(($strSection == 'posticons') ? $CFG['style']['table']['cella'] : $CFG['style']['table']['cellb']); ?>" class="smaller"><b><a href="admincp.php?section=posticons">Post Icons</a></b></td>
	<td align="center" bgcolor="<?php echo(($strSection == 'censored') ? $CFG['style']['table']['cella'] : $CFG['style']['table']['cellb']); ?>" class="smaller"><b><a href="admincp.php?section=censored">Censored Words</a></b></td>
</tr>
</table>