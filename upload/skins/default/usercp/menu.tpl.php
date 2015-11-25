<table cellpadding="4" cellspacing="1" border="0" bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%">
<tr>
	<td align="center" bgcolor="<?php if($strSection=='index'){echo($CFG['style']['table']['cella']);}else{echo($CFG['style']['table']['cellb']);} ?>" class="smaller"><b><a href="usercp.php">My OvBB Home</a></b></td>
	<td align="center" bgcolor="<?php if($strSection=='profile'){echo($CFG['style']['table']['cella']);}else{echo($CFG['style']['table']['cellb']);} ?>" class="smaller"><b><a href="usercp.php?section=profile">Edit Profile</a></b></td>
	<td align="center" bgcolor="<?php if($strSection=='options'||$strSection=='avatar'){echo($CFG['style']['table']['cella']);}else{echo($CFG['style']['table']['cellb']);} ?>" class="smaller"><b><a href="usercp.php?section=options">Edit Options</a></b></td>
	<td align="center" bgcolor="<?php if($strSection=='password'){echo($CFG['style']['table']['cella']);}else{echo($CFG['style']['table']['cellb']);} ?>" class="smaller"><b><a href="usercp.php?section=password">Edit Password</a></b></td>
	<td align="center" bgcolor="<?php if($strSection=='buddylist'){echo($CFG['style']['table']['cella']);}else{echo($CFG['style']['table']['cellb']);} ?>" class="smaller"><b><a href="usercp.php?section=buddylist">Edit Buddy List</a></b></td>
	<td align="center" bgcolor="<?php if($strSection=='ignorelist'){echo($CFG['style']['table']['cella']);}else{echo($CFG['style']['table']['cellb']);} ?>" class="smaller"><b><a href="usercp.php?section=ignorelist">Edit Ignore List</a></b></td>
	<td align="center" bgcolor="<?php if($strSection=='pm'){echo($CFG['style']['table']['cella']);}else{echo($CFG['style']['table']['cellb']);} ?>" class="smaller"><b><a href="private.php">Private Messages</a></b></td>
</tr>
</table>