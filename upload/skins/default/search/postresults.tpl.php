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
	<td align="center" width="60%">
		<table cellpadding="0" cellspacing="0" border="0"><tr>
			<td class="smaller"><a class="heading" href="search.php?action=showresult&amp;searchid=<?php echo($iResultID); ?>&amp;perpage=<?php echo($iPostsPerPage); ?>&amp;page=<?php echo($iPage); ?>&amp;sortby=topic&amp;sortorder=<?php echo((($strSortBy == 'topic') && ($strSortOrder == 'ASC')) ? 'desc' : 'asc'); ?>">Topic</a><?php if($strSortBy == 'topic'){echo('&nbsp;');} ?></td>
			<td class="smaller"><?php if($strSortBy == 'topic'){echo(($strSortOrder == 'ASC') ? ' <img src="images/sort_asc.png" style="vertical-align: middle;" alt="Ascending" />' : ' <img src="images/sort_desc.png" style="vertical-align: middle;" alt="Descending" />');} ?></td>
		</tr></table>
	</td>
	<td align="center" width="15%" nowrap="nowrap">
		<table cellpadding="0" cellspacing="0" border="0"><tr>
			<td class="smaller" nowrap="nowrap"><a class="heading" href="search.php?action=showresult&amp;searchid=<?php echo($iResultID); ?>&amp;perpage=<?php echo($iPostsPerPage); ?>&amp;page=<?php echo($iPage); ?>&amp;sortby=forum&amp;sortorder=<?php echo((($strSortBy == 'forum') && ($strSortOrder == 'ASC')) ? 'desc' : 'asc'); ?>">Forum</a><?php if($strSortBy == 'forum'){echo('&nbsp;');} ?></td>
			<td class="smaller"><?php if($strSortBy == 'forum'){echo(($strSortOrder == 'ASC') ? ' <img src="images/sort_asc.png" style="vertical-align: middle;" alt="Ascending" />' : ' <img src="images/sort_desc.png" style="vertical-align: middle;" alt="Descending" />');} ?></td>
		</tr></table>
	</td>
	<td align="center" width="15%" nowrap="nowrap">
		<table cellpadding="0" cellspacing="0" border="0"><tr>
			<td class="smaller" nowrap="nowrap"><a class="heading" href="search.php?action=showresult&amp;searchid=<?php echo($iResultID); ?>&amp;perpage=<?php echo($iPostsPerPage); ?>&amp;page=<?php echo($iPage); ?>&amp;sortby=author&amp;sortorder=<?php echo((($strSortBy == 'author') && ($strSortOrder == 'ASC')) ? 'desc' : 'asc'); ?>">Author</a><?php if($strSortBy == 'author'){echo('&nbsp;');} ?></td>
			<td class="smaller"><?php if($strSortBy == 'author'){echo(($strSortOrder == 'ASC') ? ' <img src="images/sort_asc.png" style="vertical-align: middle;" alt="Ascending" />' : ' <img src="images/sort_desc.png" style="vertical-align: middle;" alt="Descending" />');} ?></td>
		</tr></table>
	</td>
	<td align="center">
		<table cellpadding="0" cellspacing="0" border="0"><tr>
			<td class="smaller"><a class="heading" href="search.php?action=showresult&amp;searchid=<?php echo($iResultID); ?>&amp;perpage=<?php echo($iPostsPerPage); ?>&amp;page=<?php echo($iPage); ?>&amp;sortby=postcount&amp;sortorder=<?php echo((($strSortBy == 'postcount') && ($strSortOrder == 'ASC')) ? 'desc' : 'asc'); ?>">Replies</a><?php if($strSortBy == 'postcount'){echo('&nbsp;');} ?></td>
			<td class="smaller"><?php if($strSortBy == 'postcount'){echo(($strSortOrder == 'ASC') ? ' <img src="images/sort_asc.png" style="vertical-align: middle;" alt="Ascending" />' : ' <img src="images/sort_desc.png" style="vertical-align: middle;" alt="Descending" />');} ?></td>
		</tr></table>
	</td>
	<td align="center">
		<table cellpadding="0" cellspacing="0" border="0"><tr>
			<td class="smaller"><a class="heading" href="search.php?action=showresult&amp;searchid=<?php echo($iResultID); ?>&amp;perpage=<?php echo($iPostsPerPage); ?>&amp;page=<?php echo($iPage); ?>&amp;sortby=viewcount&amp;sortorder=<?php echo((($strSortBy == 'viewcount') && ($strSortOrder == 'ASC')) ? 'desc' : 'asc'); ?>">Views</a><?php if($strSortBy == 'viewcount'){echo('&nbsp;');} ?></td>
			<td class="smaller"><?php if($strSortBy == 'viewcount'){echo(($strSortOrder == 'ASC') ? ' <img src="images/sort_asc.png" style="vertical-align: middle;" alt="Ascending" />' : ' <img src="images/sort_desc.png" style="vertical-align: middle;" alt="Descending" />');} ?></td>
		</tr></table>
	</td>
	<td align="center" width="10%" nowrap="nowrap">
		<table cellpadding="0" cellspacing="0" border="0"><tr>
			<td class="smaller"><a class="heading" href="search.php?action=showresult&amp;searchid=<?php echo($iResultID); ?>&amp;perpage=<?php echo($iPostsPerPage); ?>&amp;page=<?php echo($iPage); ?>&amp;sortby=date&amp;sortorder=<?php echo((($strSortBy == 'date') && ($strSortOrder == 'ASC')) ? 'desc' : 'asc'); ?>">Date</a><?php if($strSortBy == 'date'){echo('&nbsp;');} ?></td>
			<td class="smaller"><?php if($strSortBy == 'date'){echo(($strSortOrder == 'ASC') ? ' <img src="images/sort_asc.png" style="vertical-align: middle;" alt="Ascending" />' : ' <img src="images/sort_desc.png" style="vertical-align: middle;" alt="Descending" />');} ?></td>
		</tr></table>
	</td>
</tr>

<?php
	// Display the HTML table.
	foreach($aPosts as $iPostID => $aPost)
	{
		// Save the post information.
		$strPostIconURL = $aPost[ICON][URL];
		$strPostIconAlt = $aPost[ICON][ALT];
		$strPostTitle = htmlsanitize($aPost[TITLE]);
		$strPostBody = ParseMessage($aPost[BODY], TRUE, TRUE);
		$iPostAuthor = $aPost[AUTHOR];
		$strPostAuthor = htmlsanitize($aUsernames[$iPostAuthor]);
		$datePost = $aPost[POSTDATE];
		$iThreadID = $aPost[PARENT];

		// Save the thread information.
		$strThreadTitle = htmlsanitize($aThreads[$iThreadID][TITLE]);
		$strThreadIconURL = $aThreads[$iThreadID][ICON][URL];
		$strThreadIconAlt = $aThreads[$iThreadID][ICON][ALT];
		$iPostCount = $aThreads[$iThreadID][PCOUNT];
		$iViewCount = $aThreads[$iThreadID][VCOUNT];
		$iForumID = $aThreads[$iThreadID][PARENT];
		$bIsOpen = $aThreads[$iThreadID][ISOPEN];
		$bNewPosts = $aThreads[$iThreadID][NEWPOSTS];
		$strForumName = htmlsanitize($aForums[$iForumID]);

		// Set the status icon. Is the thread open?
		if($bIsOpen)
		{
			// Yes. Is it a hot thread?
			if(($iPostCount > 15) || ($iViewCount > 150))
			{
				// Yes. Are there new posts?
				if($bNewPosts)
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
				if($bNewPosts)
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
?>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>">
		<table class="smaller" cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td class="smaller"><table cellpadding="2" cellspacing="1" border="0" width="100%">
			<tr>
				<td align="center" valign="middle" width="16"><img src="<?php echo($strStatusIconURL); ?>" alt="<?php echo($strStatusIconAlt); ?>" /></td>
				<td align="center" valign="middle" width="16"><img src="<?php echo($strThreadIconURL); ?>" alt="<?php echo($strThreadIconAlt); ?>" /></td>
				<td align="left" valign="middle" nowrap="nowrap">Thread: <a href="thread.php?threadid=<?php echo($iThreadID); ?>"><?php echo($strThreadTitle); ?></a></td>
			</tr>
			<tr>
				<td align="center" valign="middle" width="16"><img src="images/space.png" width="16" height="16" alt="" /></td>
				<td align="center" valign="middle" width="16"><img src="<?php echo($strPostIconURL); ?>" alt="<?php echo($strPostIconAlt); ?>" /></td>
				<td align="left" valign="middle" nowrap="nowrap">Post: <a href="thread.php?threadid=<?php echo($iThreadID); ?>&amp;postid=<?php echo($iPostID); ?>#post<?php echo($iPostID); ?>"><?php echo($strPostTitle); ?></a></td>
			</tr>
			</table></td>
		</tr>
		<tr>
			<td><table cellpadding="2" cellspacing="1" border="0" width="100%">
			<tr>
				<td align="right" valign="top">Preview:</td>
				<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" style="border-width: 1px; border-style: inset;" width="100%">
					<div style="overflow: hidden;"><div style="float: left;"><?php echo($strPostBody); ?></div></div>
				</td>
			</tr>
			</table></td>
		</tr>
		</table>
	</td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium" align="center" valign="middle" nowrap="nowrap"><a href="forumdisplay.php?forumid=<?php echo($iForumID); ?>"><?php echo($strForumName); ?></a></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium" align="center" valign="middle" nowrap="nowrap"><a href="member.php?action=getprofile&amp;userid=<?php echo($iPostAuthor); ?>"><?php echo($strPostAuthor); ?></a></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium" align="center" valign="middle"><a href="javascript:showposters(<?php echo($iThreadID); ?>);"><?php echo(number_format($iPostCount-1)); ?></a></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class="medium" align="center" valign="middle"><?php echo(number_format($iViewCount)); ?></td>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="smaller" align="right" valign="middle" nowrap="nowrap"><?php echo(gmtdate('m-d-Y', $datePost)); ?><br /><span style="color: <?php echo($CFG['style']['table']['timecolor']); ?>;"><?php echo(gmtdate('h:i A', $datePost)); ?></span></td>
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
		Paginate("search.php?action=showresult&amp;searchid={$iResultID}", $iNumberPages, $iPage, $iPostsPerPage, $strSortBy, strtolower($strSortOrder));
?>
</div>
<?php
	}
?>

<div align="left" class="smaller"><br /><?php echo(TimeInfo()); ?></div>

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