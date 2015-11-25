<?php
	// Header.
	$strPageTitle = htmlsanitize(" :: Search :. {$strQueryString}");
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
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; <a href="search.php">Search</a> &gt; Search Results</b></td>
</tr>
</table>

<br />

<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellpadding="4" cellspacing="1" border="0" align="center">

<tr class="heading">
	<td class="smaller" width="16"><img src="images/space.png" width="15" height="1" alt="" /></td>
	<td class="smaller" width="16"><img src="images/space.png" width="15" height="1" alt="" /></td>
	<td align="center" width="42%">
		<table cellpadding="0" cellspacing="0" border="0"><tr>
			<td class="smaller"><a class="heading" href="search.php?action=showresult&amp;searchid=<?php echo($iResultID); ?>&amp;perpage=<?php echo($iThreadsPerPage); ?>&amp;page=<?php echo($iPage); ?>&amp;sortby=title&amp;sortorder=<?php echo((($strSortBy == 'title') && ($strSortOrder == 'ASC')) ? 'desc' : 'asc'); ?>">Thread</a><?php if($strSortBy == 'title'){echo('&nbsp;');} ?></td>
			<td class="smaller"><?php if($strSortBy == 'title'){echo(($strSortOrder == 'ASC') ? ' <img src="images/sort_asc.png" style="vertical-align: middle;" alt="Ascending" />' : ' <img src="images/sort_desc.png" style="vertical-align: middle;" alt="Descending" />');} ?></td>
		</tr></table>
	</td>
	<td align="center" width="13%" nowrap="nowrap">
		<table cellpadding="0" cellspacing="0" border="0"><tr>
			<td class="smaller" nowrap="nowrap"><a class="heading" href="search.php?action=showresult&amp;searchid=<?php echo($iResultID); ?>&amp;perpage=<?php echo($iThreadsPerPage); ?>&amp;page=<?php echo($iPage); ?>&amp;sortby=author&amp;sortorder=<?php echo((($strSortBy == 'author') && ($strSortOrder == 'ASC')) ? 'desc' : 'asc'); ?>">Thread Starter</a><?php if($strSortBy == 'author'){echo('&nbsp;');} ?></td>
			<td class="smaller"><?php if($strSortBy == 'author'){echo(($strSortOrder == 'ASC') ? ' <img src="images/sort_asc.png" style="vertical-align: middle;" alt="Ascending" />' : ' <img src="images/sort_desc.png" style="vertical-align: middle;" alt="Descending" />');} ?></td>
		</tr></table>
	</td>
	<td align="center" width="13%" nowrap="nowrap">
		<table cellpadding="0" cellspacing="0" border="0"><tr>
			<td class="smaller" nowrap="nowrap"><a class="heading" href="search.php?action=showresult&amp;searchid=<?php echo($iResultID); ?>&amp;perpage=<?php echo($iThreadsPerPage); ?>&amp;page=<?php echo($iPage); ?>&amp;sortby=forum&amp;sortorder=<?php echo((($strSortBy == 'forum') && ($strSortOrder == 'ASC')) ? 'desc' : 'asc'); ?>">Forum</a><?php if($strSortBy == 'forum'){echo('&nbsp;');} ?></td>
			<td class="smaller"><?php if($strSortBy == 'forum'){echo(($strSortOrder == 'ASC') ? ' <img src="images/sort_asc.png" style="vertical-align: middle;" alt="Ascending" />' : ' <img src="images/sort_desc.png" style="vertical-align: middle;" alt="Descending" />');} ?></td>
		</tr></table>
	</td>
	<td align="center">
		<table cellpadding="0" cellspacing="0" border="0"><tr>
			<td class="smaller"><a class="heading" href="search.php?action=showresult&amp;searchid=<?php echo($iResultID); ?>&amp;perpage=<?php echo($iThreadsPerPage); ?>&amp;page=<?php echo($iPage); ?>&amp;sortby=postcount&amp;sortorder=<?php echo((($strSortBy == 'postcount') && ($strSortOrder == 'ASC')) ? 'desc' : 'asc'); ?>">Replies</a><?php if($strSortBy == 'postcount'){echo('&nbsp;');} ?></td>
			<td class="smaller"><?php if($strSortBy == 'postcount'){echo(($strSortOrder == 'ASC') ? ' <img src="images/sort_asc.png" style="vertical-align: middle;" alt="Ascending" />' : ' <img src="images/sort_desc.png" style="vertical-align: middle;" alt="Descending" />');} ?></td>
		</tr></table>
	</td>
	<td align="center">
		<table cellpadding="0" cellspacing="0" border="0"><tr>
			<td class="smaller"><a class="heading" href="search.php?action=showresult&amp;searchid=<?php echo($iResultID); ?>&amp;perpage=<?php echo($iThreadsPerPage); ?>&amp;page=<?php echo($iPage); ?>&amp;sortby=viewcount&amp;sortorder=<?php echo((($strSortBy == 'viewcount') && ($strSortOrder == 'ASC')) ? 'desc' : 'asc'); ?>">Views</a><?php if($strSortBy == 'viewcount'){echo('&nbsp;');} ?></td>
			<td class="smaller"><?php if($strSortBy == 'viewcount'){echo(($strSortOrder == 'ASC') ? ' <img src="images/sort_asc.png" style="vertical-align: middle;" alt="Ascending" />' : ' <img src="images/sort_desc.png" style="vertical-align: middle;" alt="Descending" />');} ?></td>
		</tr></table>
	</td>
	<td align="center" width="13%">
		<table cellpadding="0" cellspacing="0" border="0"><tr>
			<td class="smaller"><a class="heading" href="search.php?action=showresult&amp;searchid=<?php echo($iResultID); ?>&amp;perpage=<?php echo($iThreadsPerPage); ?>&amp;page=<?php echo($iPage); ?>&amp;sortby=lpost&amp;sortorder=<?php echo((($strSortBy == 'lpost') && ($strSortOrder == 'ASC')) ? 'desc' : 'asc'); ?>">Last Post</a><?php if($strSortBy == 'lpost'){echo('&nbsp;');} ?></td>
			<td class="smaller"><?php if($strSortBy == 'lpost'){echo(($strSortOrder == 'ASC') ? ' <img src="images/sort_asc.png" style="vertical-align: middle;" alt="Ascending" />' : ' <img src="images/sort_desc.png" style="vertical-align: middle;" alt="Descending" />');} ?></td>
		</tr></table>
	</td>
</tr>

<?php
	// Display the HTML table.
	foreach($aThreads as $iThreadID => $aThread)
	{
		// Copy the thread info. into easy-to-use variables.
		$iPostID = $aThread[PID];
		$iForumID = $aThread[BID];
		$strForumName = htmlsanitize($aThread[BNAME]);
		$strThreadTitle = htmlsanitize($aThread[TITLE]);
		$strThreadDesc = htmlsanitize($aThread[DESC]);
		$iThreadAuthor = $aThread[AUTHOR];
		$strThreadAuthor = htmlsanitize($aUsernames[$iThreadAuthor]);
		$iNumberPosts = $aThread[PCOUNT];
		$iNumberViews = $aThread[VCOUNT];
		$dateLastPost = $aThread[LPOST];
		$iLastPoster = $aThread[LPOSTER];
		$strLastPoster = htmlsanitize($aUsernames[$iLastPoster]);
		$strThreadIconURL = $aThread[ICON][URL];
		$strThreadIconAlt = $aThread[ICON][ALT];
		$iAttachmentCount = $aThread[ACOUNT];
		$bHasPoll = $aThread[POLL];
		$bIsSticky = $aThread[STICKY];
		$bIsClosed = $aThread[CLOSED];
		$bNewPosts = $aThread[NEWPOSTS];

		// Set the status icon. Is the thread open?
		if(!$aThread[CLOSED])
		{
			// Yes. Is it a hot thread?
			if(($aThread[PCOUNT] > 15) || ($aThread[VCOUNT] > 150))
			{
				// Yes. Are there new posts?
				if($aThread[NEWPOSTS])
				{
					// Yes.
					$strStatusIconURL = 'images/thread_new_hot.png';
					$strStatusIconAlt = 'Hot Thread w/ New Posts';
				}
				else
				{
					// No.
					$strStatusIconURL = 'images/thread_old_hot.png';
					$strStatusIconAlt = 'Hot Thread w/ No New Posts';
				}
			}
			else
			{
				// No. Are there new posts?
				if($aThread[NEWPOSTS])
				{
					// Yes.
					$strStatusIconURL = 'images/thread_new_cold.png';
					$strStatusIconAlt = 'New Posts';
				}
				else
				{
					// No.
					$strStatusIconURL = 'images/thread_old_cold.png';
					$strStatusIconAlt = 'No New Posts';
				}
			}
		}
		else
		{
			// No.
			$strStatusIconURL = 'images/thread_closed.png';
			$strStatusIconAlt = 'Closed Thread';
		}

		// Calculate the page that the last post is on.
		$iLastPostPage = ceil($iNumberPosts / $iPostsPerPage);
?>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="smaller" align="center" valign="middle"><img src="<?php echo($strStatusIconURL); ?>" alt="<?php echo($strStatusIconAlt); ?>" /></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="smaller" align="center" valign="middle"><img src="<?php echo($strThreadIconURL); ?>" alt="<?php echo($strThreadIconAlt); ?>" /></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium" align="left"><?php if($iAttachmentCount){echo("<img src=\"images/paperclip.png\" style=\"vertical-align: text-bottom;\" alt=\"{$iAttachmentCount} Attachment(s)\" />");}if($bIsSticky){echo('Sticky: ');}if($bHasPoll){echo('Poll: ');} ?><a href="thread.php?threadid=<?php echo($iThreadID); ?>&amp;postid=<?php echo($iPostID); ?>#post<?php echo($iPostID); ?>"><?php echo($strThreadTitle); ?></a>
<?php
		// Are there more posts in this thread than there are the number of posts we print per thread page?
		if($iNumberPosts > $iPostsPerPage)
		{
			// Yes. Open parenthesis, then multiple pages icon.
			echo(' <span class="smaller">( <img src="images/multipage.png" alt="Multiple Pages" />');

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

					// Break out of this for() loop.
					break;
				}

				// Page link.
				echo(' <a href="thread.php?threadid='.$iThreadID.'&amp;page='.($i + 1).'">'.($i + 1).'</a>');
			}

			// Close parenthesis.
			echo(' )</span>');
		}
?>
<div class="smaller"><?php echo($strThreadDesc); ?></div></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium" align="center" valign="middle" nowrap="nowrap"><a href="member.php?action=getprofile&amp;userid=<?php echo($iThreadAuthor); ?>"><?php echo($strThreadAuthor); ?></a></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium" align="center" valign="middle" nowrap="nowrap"><a href="forumdisplay.php?forumid=<?php echo($iForumID); ?>"><?php echo($strForumName); ?></a></td>
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

</table>

<?php
	// If this forum consists of more than one page, display the pagination links.
	if($iNumberPages > 1)
	{
?>

<div align="center" class="smaller"><br />
<?php
	Paginate("search.php?action=showresult&amp;searchid={$iResultID}", $iNumberPages, $iPage, $iThreadsPerPage, $strSortBy, strtolower($strSortOrder));
?>
</div>
<?php
	}
?>

<div class="smaller" align="left"><br /><?php echo(TimeInfo()); ?></div>

<div align="center" class="smaller"><img src="images/space.png" width="1" height="10" alt="" /><br />
	<img style="vertical-align: middle;" src="images/thread_new_cold.png" border="0" alt="New Posts" align="middle" /> <b>New posts</b>&nbsp;&nbsp;
	<img style="vertical-align: middle;" src="images/thread_new_hot.png" border="0" alt="Hot Thread w/ New Posts" align="middle" /> <b>New posts w/ more than 15 replies or 150 views</b>&nbsp;&nbsp;
	<img style="vertical-align: middle;" src="images/thread_closed.png" border="0" alt="Closed Thread" align="middle" /> <b>Closed thread</b><br />
	<img style="vertical-align: middle;" src="images/thread_old_cold.png" border="0" alt="No New Posts" align="middle" /> <b>No new posts</b>&nbsp;&nbsp;
	<img style="vertical-align: middle;" src="images/thread_old_hot.png" border="0" alt="Hot Thread w/ No New Posts" align="middle" /> <b>No new posts w/ more than 15 replies or 150 views</b>
</div>

<?php
	// Footer
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>