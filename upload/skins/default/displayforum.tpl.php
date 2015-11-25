<?php
	// Header.
	$strPageTitle = htmlsanitize(" :: {$aForum[PARENTNAME]} :. {$aForum[NAME]}");
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<script language="JavaScript" type="text/javascript">
<!--
function showposters(threadid)
{
	window.open("posters.php?threadid="+threadid, "Posters", "resizable=1,scrollbars=1,toolbar=0,width=230,height=300");
}
//-->
</script>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; <a href="forumdisplay.php?forumid=<?php echo($aForum[PARENT]); ?>"><?php echo(htmlsanitize($aForum[PARENTNAME])); ?></a> &gt; <?php echo(htmlsanitize($aForum[NAME])); ?></b></td>
	<td align="right" valign="top"><a href="newthread.php?forumid=<?php echo($iForumID); ?>"><img src="images/newthread.png" border="0" alt="Post New Thread" /></a></td>
</tr>
</table>

<br />

<?php
	if($aForum[THREADCOUNT])
	{
?>

<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellpadding="4" cellspacing="1" border="0" align="center">

<tr class="heading">
	<td class="smaller"><img src="images/space.png" width="16" height="1" alt="" /></td>
	<td class="smaller"><img src="images/space.png" width="16" height="1" alt="" /></td>
	<td align="center" width="60%">
		<table cellpadding="0" cellspacing="0" border="0"><tr>
			<td class="smaller"><a class="heading" href="forumdisplay.php?forumid=<?php echo($iForumID); ?>&amp;perpage=<?php echo($iThreadsPerPage); ?>&amp;page=<?php echo($iPage); ?>&amp;sortby=title&amp;sortorder=<?php echo((($strSortBy == 'title') && ($strSortOrder == 'ASC')) ? 'desc' : 'asc'); ?>&amp;daysprune=<?php echo($iDaysPrune); ?>">Thread</a><?php if($strSortBy == 'title'){echo('&nbsp;');} ?></td>
			<td class="smaller"><?php if($strSortBy == 'title'){echo(($strSortOrder == 'ASC') ? ' <img src="images/sort_asc.png" style="vertical-align: middle;" alt="Ascending" />' : ' <img src="images/sort_desc.png" style="vertical-align: middle;" alt="Descending" />');} ?></td>
		</tr></table>
	</td>
	<td align="center" width="15%" nowrap="nowrap">
		<table cellpadding="0" cellspacing="0" border="0"><tr>
			<td class="smaller" nowrap="nowrap"><a class="heading" href="forumdisplay.php?forumid=<?php echo($iForumID); ?>&amp;perpage=<?php echo($iThreadsPerPage); ?>&amp;page=<?php echo($iPage); ?>&amp;sortby=author&amp;sortorder=<?php echo((($strSortBy == 'author') && ($strSortOrder == 'ASC')) ? 'desc' : 'asc'); ?>&amp;daysprune=<?php echo($iDaysPrune); ?>">Thread Starter</a><?php if($strSortBy == 'author'){echo('&nbsp;');} ?></td>
			<td class="smaller"><?php if($strSortBy == 'author'){echo(($strSortOrder == 'ASC') ? ' <img src="images/sort_asc.png" style="vertical-align: middle;" alt="Ascending" />' : ' <img src="images/sort_desc.png" style="vertical-align: middle;" alt="Descending" />');} ?></td>
		</tr></table>
	</td>
	<td align="center" width="5%">
		<table cellpadding="0" cellspacing="0" border="0"><tr>
			<td class="smaller"><a class="heading" href="forumdisplay.php?forumid=<?php echo($iForumID); ?>&amp;perpage=<?php echo($iThreadsPerPage); ?>&amp;page=<?php echo($iPage); ?>&amp;sortby=postcount&amp;sortorder=<?php echo((($strSortBy == 'postcount') && ($strSortOrder == 'ASC')) ? 'desc' : 'asc'); ?>&amp;daysprune=<?php echo($iDaysPrune); ?>">Replies</a><?php if($strSortBy == 'postcount'){echo('&nbsp;');} ?></td>
			<td class="smaller"><?php if($strSortBy == 'postcount'){echo(($strSortOrder == 'ASC') ? ' <img src="images/sort_asc.png" style="vertical-align: middle;" alt="Ascending" />' : ' <img src="images/sort_desc.png" style="vertical-align: middle;" alt="Descending" />');} ?></td>
		</tr></table>
	</td>
	<td align="center" width="5%">
		<table cellpadding="0" cellspacing="0" border="0"><tr>
			<td class="smaller"><a class="heading" href="forumdisplay.php?forumid=<?php echo($iForumID); ?>&amp;perpage=<?php echo($iThreadsPerPage); ?>&amp;page=<?php echo($iPage); ?>&amp;sortby=viewcount&amp;sortorder=<?php echo((($strSortBy == 'viewcount') && ($strSortOrder == 'ASC')) ? 'desc' : 'asc'); ?>&amp;daysprune=<?php echo($iDaysPrune); ?>">Views</a><?php if($strSortBy == 'viewcount'){echo('&nbsp;');} ?></td>
			<td class="smaller"><?php if($strSortBy == 'viewcount'){echo(($strSortOrder == 'ASC') ? ' <img src="images/sort_asc.png" style="vertical-align: middle;" alt="Ascending" />' : ' <img src="images/sort_desc.png" style="vertical-align: middle;" alt="Descending" />');} ?></td>
		</tr></table>
	</td>
	<td align="center" width="15%">
		<table cellpadding="0" cellspacing="0" border="0"><tr>
			<td class="smaller"><a class="heading" href="forumdisplay.php?forumid=<?php echo($iForumID); ?>&amp;perpage=<?php echo($iThreadsPerPage); ?>&amp;page=<?php echo($iPage); ?>&amp;sortby=lpost&amp;sortorder=<?php echo((($strSortBy == 'lpost') && ($strSortOrder == 'ASC')) ? 'desc' : 'asc'); ?>&amp;daysprune=<?php echo($iDaysPrune); ?>">Last Post</a><?php if($strSortBy == 'lpost'){echo('&nbsp;');} ?></td>
			<td class="smaller"><?php if($strSortBy == 'lpost'){echo(($strSortOrder == 'ASC') ? ' <img src="images/sort_asc.png" style="vertical-align: middle;" alt="Ascending" />' : ' <img src="images/sort_desc.png" style="vertical-align: middle;" alt="Descending" />');} ?></td>
		</tr></table>
	</td>
</tr>

<?php
		// Display the HTML table.
		foreach($aThreads as $iThreadID => $aThread)
		{
			// Copy the thread info. from the master table to easy-to-use variables.
			$strThreadTitle = htmlsanitize($aThread[NAME]);
			$strThreadDesc = htmlsanitize($aThread[DESCRIPTION]);
			$iThreadAuthor = $aThread[AUTHOR];
			$strThreadAuthor = htmlsanitize($aUsernames[$iThreadAuthor]);
			$iNumberPosts = $aThread[POSTCOUNT];
			$iNumberViews = $aThread[VIEWCOUNT];
			$dateLastPost = $aThread[LPOST];
			$iLastPoster = $aThread[LPOSTER];
			$strLastPoster = htmlsanitize($aUsernames[$iLastPoster]);
			$strThreadIcon2URL = $aThread[ICON][0];
			$strThreadIcon2Alt = $aThread[ICON][1];
			$iAttachmentCount = $aThread[ATTACHCOUNT];
			$bHasPoll = $aThread[HASPOLL];
			$bIsSticky = $aThread[ISSTICKY];
			$bIsOpen = $aThread[ISOPEN];
			$bNewPosts = $aThread[NEWPOSTS];

			// Is it open?
			if($bIsOpen)
			{
				// Yes. Is it a hot thread?
				if(($iNumberPosts > 15) || ($iNumberViews > 150))
				{
					// Yes. Are there new posts?
					if($aThread[NEWPOSTS])
					{
						// Yes.
						$strThreadIcon1URL = 'images/thread_new_hot.png';
						$strThreadIcon1Alt = 'Hot Thread w/ New Posts';
					}
					else
					{
						// No.
						$strThreadIcon1URL = 'images/thread_old_hot.png';
						$strThreadIcon1Alt = 'Hot Thread w/ No New Posts';
					}
				}
				else
				{
					// No. Are there new posts?
					if($aThread[NEWPOSTS])
					{
						// Yes.
						$strThreadIcon1URL = 'images/thread_new_cold.png';
						$strThreadIcon1Alt = 'New Posts';
					}
					else
					{
						// No.
						$strThreadIcon1URL = 'images/thread_old_cold.png';
						$strThreadIcon1Alt = 'No New Posts';
					}
				}
			}
			else
			{
				// No.
				$strThreadIcon1URL = 'images/thread_closed.png';
				$strThreadIcon1Alt = 'Closed Thread';
			}

			// Calculate the page that the last post is on.
			$iLastPostPage = ceil($iNumberPosts / $iPostsPerPage);
?>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="smaller" align="center" valign="middle"><img src="<?php echo($strThreadIcon1URL); ?>" alt="<?php echo($strThreadIcon1Alt); ?>" /></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="smaller" align="center" valign="middle"><img src="<?php echo($strThreadIcon2URL); ?>" alt="<?php echo($strThreadIcon2Alt); ?>" /></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" align="left" class="smaller"><span class="medium"><?php if($bNewPosts){echo("<a href=\"thread.php?threadid={$iThreadID}&amp;goto=newest\"><img src=\"images/firstnew.png\" style=\"vertical-align: text-bottom;\" border=\"0\" alt=\"Go to first newest post\" /></a> ");}if($iAttachmentCount){echo("<img src=\"images/paperclip.png\" style=\"vertical-align: text-bottom;\" alt=\"$iAttachmentCount Attachment(s)\" />");}if($bIsSticky){echo('Sticky: ');}if($bHasPoll){echo('Poll: ');}if($bNewPosts){echo('<b>');} ?><a href="thread.php?threadid=<?php echo($iThreadID); ?>"><?php echo($strThreadTitle); ?></a><?php if($bNewPosts){echo('</b>');} ?></span>
<?php
	// Are there more posts in this thread than there are the number of posts we print per thread page?
	if($iNumberPosts > $iPostsPerPage)
	{
		// Yes. Open parenthesis, then multiple pages icon.
		echo(' ( <img src="images/multipage.png" alt="Multiple Pages" />');

		// Print out the pages' links.
		for($i = 0; $i < $iLastPostPage; $i++)
		{
			// Have we already printed out 4 links?
			if($i == 4)
			{
				// Yes, print out some elipses...
				echo(' ... ');

				// ...then a link to the last page.
				echo('<a href="thread.php?threadid='.$iThreadID.'&amp;page='.$iLastPostPage.'">Last page</a>');

				// Break out of this loop.
				break;
			}

			// Page link.
			echo(' <a href="thread.php?threadid='.$iThreadID.'&amp;page='.($i + 1).'">'.($i + 1).'</a>');
		}

		// Close parenthesis.
		echo(' )');
	}
?><br /><?php echo($strThreadDesc); ?></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium" align="center" valign="middle" nowrap="nowrap"><a href="member.php?action=getprofile&amp;userid=<?php echo($iThreadAuthor); ?>"><?php echo($strThreadAuthor); ?></a></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium" align="center" valign="middle"><a href="javascript:showposters(<?php echo($iThreadID); ?>);"><?php echo(number_format($iNumberPosts-1)); ?></a></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium" align="center" valign="middle"><?php echo(number_format($iNumberViews)); ?></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" nowrap="nowrap" valign="middle">
		<table cellpadding="0" cellspacing="0" border="0" align="right">
		<tr>
			<td align="right" class="smaller" nowrap="nowrap"><?php echo(gmtdate('m-d-Y', $dateLastPost)); ?> <span style="color: <?php echo($CFG['style']['table']['timecolor']); ?>;"><?php echo(gmtdate('h:i A', $dateLastPost)); ?></span><br />by <a class="underline" href="member.php?action=getprofile&amp;userid=<?php echo($iLastPoster); ?>"><b><?php echo($strLastPoster); ?></b></a></td>
			<td>&nbsp;<a href="thread.php?threadid=<?php echo($iThreadID); ?>&amp;page=<?php echo($iLastPostPage); ?>#lastpost"><img src="images/lastpost.png" border="0" alt="Go to last post" /></a></td>
		</tr>
		</table>
	</td>
</tr>

<?php
		}
?>

<tr class="heading">
	<td colspan="7" align="center" valign="middle" class="smaller">
	<form action="forumdisplay.php" method="post">
	<input type="hidden" name="forumid" value="<?php echo($iForumID); ?>" />
	<input type="hidden" name="page" value="<?php echo($iPage); ?>" />
	<b>Showing threads <?php echo(number_format($iShowFrom)); ?> to <?php echo(number_format($iShowTo)); ?> of <?php echo(number_format($aForum[THREADCOUNT])); ?>, sorted by</b>
	<select name="sortby" class="small">
			<option value="title"<?php if($strSortBy=='title'){echo(' selected="selected"');} ?>>thread title</option>
			<option value="author"<?php if($strSortBy=='author'){echo(' selected="selected"');} ?>>thread starter</option>
			<option value="postcount"<?php if($strSortBy=='postcount'){echo(' selected="selected"');} ?>>number of replies</option>
			<option value="viewcount"<?php if($strSortBy=='viewcount'){echo(' selected="selected"');} ?>>number of views</option>
			<option value="lpost"<?php if($strSortBy=='lpost'){echo(' selected="selected"');} ?>>last post time</option>
		</select>
		<b>in</b>
		<select name="sortorder" class="small">
			<option value="asc"<?php if($strSortOrder=='ASC'){echo(' selected="selected"');} ?>>ascending</option>
			<option value="desc"<?php if($strSortOrder=='DESC'){echo(' selected="selected"');} ?>>descending</option>
		</select>
		<b>order, from</b>
		<select name="daysprune" class="small">
			<option value="1"<?php if($iDaysPrune==1){echo(' selected="selected"');} ?>>last day</option>
			<option value="2"<?php if($iDaysPrune==2){echo(' selected="selected"');} ?>>last 2 days</option>
			<option value="5"<?php if($iDaysPrune==5){echo(' selected="selected"');} ?>>last 5 days</option>
			<option value="10"<?php if($iDaysPrune==10){echo(' selected="selected"');} ?>>last 10 days</option>
			<option value="20"<?php if($iDaysPrune==20){echo(' selected="selected"');} ?>>last 20 days</option>
			<option value="30"<?php if($iDaysPrune==30){echo(' selected="selected"');} ?>>last 30 days</option>
			<option value="45"<?php if($iDaysPrune==45){echo(' selected="selected"');} ?>>last 45 days</option>
			<option value="60"<?php if($iDaysPrune==60){echo(' selected="selected"');} ?>>last 60 days</option>
			<option value="75"<?php if($iDaysPrune==75){echo(' selected="selected"');} ?>>last 75 days</option>
			<option value="100"<?php if($iDaysPrune==100){echo(' selected="selected"');} ?>>last 100 days</option>
			<option value="365"<?php if($iDaysPrune==365){echo(' selected="selected"');} ?>>last year</option>
			<option value="1000"<?php if($iDaysPrune==1000){echo(' selected="selected"');} ?>>forever</option>
		</select>
		<input style="vertical-align: text-bottom;" type="image" src="images/go.png" />
	</form>
	</td>
</tr>

</table>

<br />

<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td><a href="newthread.php?forumid=<?php echo($iForumID); ?>"><img src="images/newthread.png" border="0" alt="Post New Thread" /></a></td>

<?php
		// If this forum consists of more than one page, display the pagination links.
		if($iNumberPages > 1)
		{
			echo('<td align="right" class="smaller">');
			Paginate("forumdisplay.php?forumid={$iForumID}", $iNumberPages, $iPage, $iThreadsPerPage, $strSortBy, strtolower($strSortOrder), $iDaysPrune);
			echo('</td>');
		}
?>

</tr>
</table>

<?php
	}
	else
	{
?>

<div class="medium" align="center"><b>There have been no posts in the last <?php echo($iDaysPrune); ?> days in this forum.</b><br /><br /></div>

<table align="center" border="0" cellpadding="0" cellspacing="0">
<tr>
	<td align="left" valign="middle" class="medium">
	<form action="forumdisplay.php" method="post">
	<input type="hidden" name="forumid" value="<?php echo($iForumID); ?>" />
	<b>Show threads from the</b>
		<select name="daysprune" class="small">
			<option value="1"<?php if($iDaysPrune==1){echo(' selected="selected"');} ?>>last day</option>
			<option value="2"<?php if($iDaysPrune==2){echo(' selected="selected"');} ?>>last 2 days</option>
			<option value="5"<?php if($iDaysPrune==5){echo(' selected="selected"');} ?>>last 5 days</option>
			<option value="10"<?php if($iDaysPrune==10){echo(' selected="selected"');} ?>>last 10 days</option>
			<option value="20"<?php if($iDaysPrune==20){echo(' selected="selected"');} ?>>last 20 days</option>
			<option value="30"<?php if($iDaysPrune==30){echo(' selected="selected"');} ?>>last 30 days</option>
			<option value="45"<?php if($iDaysPrune==45){echo(' selected="selected"');} ?>>last 45 days</option>
			<option value="60"<?php if($iDaysPrune==60){echo(' selected="selected"');} ?>>last 60 days</option>
			<option value="75"<?php if($iDaysPrune==75){echo(' selected="selected"');} ?>>last 75 days</option>
			<option value="100"<?php if($iDaysPrune==100){echo(' selected="selected"');} ?>>last 100 days</option>
			<option value="365"<?php if($iDaysPrune==365){echo(' selected="selected"');} ?>>last year</option>
			<option value="1000"<?php if($iDaysPrune==1000){echo(' selected="selected"');} ?>>beginning</option>
		</select>
		<input style="vertical-align: text-bottom;" type="image" src="images/go.png" />
	</form>
	</td>
</tr>
</table>

<?php
	}
?>

<br />

<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%">
<tr>
	<td align="left" class="smaller" nowrap="nowrap" width="50%">
	<form action="forumdisplay.php" method="post">
		<b>Forum Jump</b>:<br />
		<select name="forumid" onchange="window.location=('forumdisplay.php?forumid='+this.options[this.selectedIndex].value);">
			<option>Please select one:</option>
<?php
	// Print out all of the forums.
	foreach($aCategories as $iCategoryID => $aCategory)
	{
		// Print the category.
?>			<option value="<?php echo($iCategoryID); ?>"><?php echo(htmlsanitize($aCategory[NAME])); ?></option>
<?php

		// Print the forums under this category.
		foreach($aForums as $iBoardID => $aForum)
		{
			// Only process this forum if it's under the current category.
			if($aForum[PARENT] == $iCategoryID)
			{
				// Print the forum.
?>			<option value="<?php echo($iBoardID); ?>">-- <?php echo(htmlsanitize($aForum[NAME])); ?></option>
<?php
			}
		}
	}
?>
		</select>
		<input style="vertical-align: text-bottom;" name="submit" type="image" src="images/go.png" />
	</form>
	</td>

	<td align="right" class="smaller" width="50%">
	<table border="0" cellpadding="0" cellspacing="0">
	<tr><td align="left"><b>Search this forum:</b></td></tr>
	<tr><td>
		<form action="search.php" method="post">
			<input type="hidden" name="action" value="query" />
			<input type="hidden" name="whichforum" value="<?php echo($iForumID); ?>" />
			<input type="text"  name="keywordsearch" maxlength="<?php echo($CFG['maxlen']['query']); ?>" />
			<input style="vertical-align: text-bottom;" name="submit" type="image" src="images/go.png" />
		</form>
	</td></tr>
	</table>
	</td>
</tr>
</table>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr><td align="center" class="smaller" style="padding-top: 2em; padding-bottom: 1em;"><?php echo(TimeInfo()); ?></td></tr>
<tr><td align="center" class="smaller">
	<img style="vertical-align: middle;" src="images/thread_new_cold.png" border="0" alt="New Posts" align="middle" /> <b>New posts</b>&nbsp;&nbsp;
	<img style="vertical-align: middle;" src="images/thread_new_hot.png" border="0" alt="Hot Thread w/ New Posts" align="middle" /> <b>New posts w/ more than 15 replies or 150 views</b>&nbsp;&nbsp;
	<img style="vertical-align: middle;" src="images/thread_closed.png" border="0" alt="Closed Thread" align="middle" /> <b>Closed thread</b><br />
	<img style="vertical-align: middle;" src="images/thread_old_cold.png" border="0" alt="No New Posts" align="middle" /> <b>No new posts</b>&nbsp;&nbsp;
	<img style="vertical-align: middle;" src="images/thread_old_hot.png" border="0" alt="Hot Thread w/ No New Posts" align="middle" /> <b>No new posts w/ more than 15 replies or 150 views</b>
</td></tr>
</table>

<?php
	// Footer
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>