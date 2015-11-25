<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<link rel="SHORTCUT ICON" href="favicon.ico" />
	<title>Who Posted?</title>
	<link href="style.php" rel="stylesheet" type="text/css" />
</head>
<body bgcolor="<?php echo($CFG['style']['page']['bgcolor']); ?>">

<table bgcolor="<?php echo($CFG['style']['forum']['bgcolor']); ?>" width="100%" cellpadding="10" cellspacing="0" border="0" align="center">
<tr><td width="100%" class="medium">

<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellspacing="1" cellpadding="4" border="0" align="center">

<tr class="section"><td width="100%" align="left" class="medium" colspan="2">Total Posts: <?php echo($iTotalPosts); ?></td></tr>

<tr class="heading">
	<td class="smaller">User</td>
	<td align="center" class="smaller">Posts</td>
</tr>

<?php
	// Display the posters.
	foreach($aPosters as $iPosterID => $aPoster)
	{
		// Set the color.
		$strColor = ($strColor == $CFG['style']['table']['cellb']) ? $CFG['style']['table']['cella'] : $CFG['style']['table']['cellb'];
?>

<tr>
	<td bgcolor="<?php echo($strColor); ?>" class="medium"><a href="member.php?action=getprofile&amp;userid=<?php echo($iPosterID); ?>" target="_blank"><?php echo(htmlsanitize($aPoster[USERNAME])); ?></a></td>
	<td bgcolor="<?php echo($strColor); ?>" align="center" class="medium"><?php echo($aPoster[POSTCOUNT]); ?></td>
</tr>

<?php
	}
?>

<tr class="heading"><td align="center" colspan="2" class="smaller"><a class="heading" style="font-weight: normal;" href="javascript:opener.location=('thread.php?threadid=<?php echo($iThreadID); ?>');self.close();">[Show thread &amp; close window.]</a></td></tr>

</table>

</td></tr>
</table>

</body>
</html>