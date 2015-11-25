<?php
	// Header.
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; <a href="forumdisplay.php?forumid=<?php echo($iCategoryID); ?>"><?php echo(htmlsanitize($strCategoryName)); ?></a> &gt; <a href="forumdisplay.php?forumid=<?php echo($iForumID); ?>"><?php echo(htmlsanitize($strForumName)); ?></a> &gt; <a href="thread.php?threadid=<?php echo($iPollID); ?>"><?php echo(htmlsanitize($strThreadTitle)); ?></a></b></td>
</tr>
</table>

<br />

<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellspacing="1" cellpadding="4" border="0" align="center">
<tr class="heading">
	<td colspan="4" class="medium" width="100%" align="center">
		<?php echo(htmlsanitize($strPollQuestion)); ?>
		<div class="smaller" style="font-weight: normal;"><?php if($bHasVoted){echo('You have already voted in this poll.');} else if($bClosed){echo('The poll is closed.');} else if($_SESSION['permissions']['cvotepolls']){echo('You have not voted in this poll.');} else{echo('You do not have permission to vote in this poll.');} ?></div>
	</td>
</tr>

<?php
			foreach($aPollAnswers as $iAnswerID => $strAnswer)
			{
				// Sanitize the answer.
				$strAnswer = htmlsanitize($strAnswer);

				// Figure the percentage.
				if($iVoteCount)
				{
					$iPercentage = ((int)$aVotes[$iAnswerID] / $iVoteCount) * 100;
				}
				else
				{
					$iPercentage = 0;
				}
?>

<tr>
	<td align="right" class="medium" bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><?php echo($strAnswer); ?></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><div class="pollbar" style="width: <?php echo(round($iPercentage)*2); ?>px;"></div></td>
	<td align="center" class="medium" bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><?php echo((int)$aVotes[$iAnswerID]); ?></td>
	<td align="center" class="medium" bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><?php echo(round($iPercentage, 2)); ?>%</td>

</tr>

<?php
			}
?>

<tr class="heading">
	<td width="80%" colspan="2" class="medium" align="right">Total:</td>
	<td width="10%" class="medium" align="center"><?php echo((int)$iVoteCount); ?> votes</td>
	<td width="10%" class="medium" align="center">100%</td>
</tr>
</table>

<?php
	// Footer.
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>