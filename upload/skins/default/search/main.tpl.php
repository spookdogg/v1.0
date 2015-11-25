<?php
	// Header.
	$strPageTitle = ' :: Search';
	require("./skins/{$CFG['skin']}/header.tpl.php");
?>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" valign="top"><a href="index.php"><img src="images/ovbb.png" align="middle" border="0" alt="<?php echo(htmlsanitize($CFG['general']['name'])); ?> :: Powered by OvBB" /></a></td>
	<td width="100%" align="left" valign="top" class="medium"><b><a href="index.php"><?php echo(htmlsanitize($CFG['general']['name'])); ?></a> &gt; Search</b></td>
</tr>
</table><br />

<form action="search.php" method="post">
<input type="hidden" name="action" value="query" />
<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellspacing="1" cellpadding="4" border="0" align="center">

<tr class="section"><td width="100%" align="left" colspan="2" class="medium"><?php echo(htmlsanitize($CFG['general']['name'])); ?> Search Engine</td></tr>

<tr class="heading">
	<td width="75%" class="smaller">Search by keyword</td>
	<td width="25%" class="smaller">Search by username</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" valign="top" class="smaller"><br />
		<input type="text" name="keywordsearch" size="35" maxlength="<?php echo($CFG['maxlen']['query']); ?>" /><br /><br />
		<b>Basic Query:</b> Separate your search terms with spaces.<br />
		<b>Advanced Query:</b> Add asterisks as wildcards in your search. (<i>*vB*</i> matches <i>OvBB</i>)<br /><br />
	</td>

	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" valign="top" class="smaller"><br />
		<input type="text" name="usersearch" size="25" maxlength="<?php echo($CFG['maxlen']['username']); ?>" /><br /><br />
		<input type="radio" name="exactname" value="1" />Match exact username<br />
		<input type="radio" name="exactname" value="0" checked="checked" />Match partial username
	</td>
</tr>

</table><br />

<table bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellspacing="1" cellpadding="4" border="0" align="center">

<tr class="section"><td width="100%" align="left" colspan="3" class="medium">Search Options</td></tr>

<tr class="heading">
	<td width="45%" class="smaller">Search forum...</td>
	<td width="25%" class="smaller">Search for posts from...</td>
	<td width="30%" class="smaller">Sort results by...</td>
</tr>

<tr>
	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" valign="top" class="smaller"><br />
		<table cellpadding="0" cellspacing="0" border="0"><tr><td>
		<select name="whichforum[]" multiple="multiple" size="5">
			<option value="0" selected="selected">Search All Open Forums</option>
<?php
	// Print out all of the forums.
	reset($aCategory);
	while(list($iCategoryID) = each($aCategory))
	{
		// Print the category.
		$aCategory[$iCategoryID] = htmlsanitize($aCategory[$iCategoryID]);
		echo("\t\t\t<option value=\"{$iCategoryID}\">{$aCategory[$iCategoryID]}</option>\n");

		// Print the forums under this category.
		reset($aForum);
		while(list($iBoardID) = each($aForum))
		{
			// Only process this forum if it's under the current category.
			if($aForum[$iBoardID][0] == $iCategoryID)
			{
				// Print the forum.
				$aForum[$iBoardID][1] = htmlsanitize($aForum[$iBoardID][1]);
				echo("\t\t\t<option value=\"{$iBoardID}\">-- {$aForum[$iBoardID][1]}</option>\n");
			}
		}
	}
?>
		</select>
		</td><td>

		<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="smaller" valign="top">
				<input type="radio" name="whichpart" value="0" checked="checked" />Search titles &amp; bodies<br />
				<input type="radio" name="whichpart" value="1" />Search titles only<br />
				<input type="radio" name="whichpart" value="2" />Search bodies only
			</td>

			<td class="smaller">&nbsp;&nbsp;&nbsp;</td>

			<td class="smaller" valign="top">
				<input type="radio" value="1" name="showposts" />Show results as posts<br />
				<input type="radio" value="0" name="showposts" checked="checked" />Show results as threads
			</td>
		</tr>
		</table>
		</td></tr></table>
	</td>

	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" valign="top" class="smaller"><br />
		<select name="searchdate">
			<option value="0">any date</option>
			<option value="1">yesterday</option>
			<option value="7">a week ago</option>
			<option value="14">2 weeks ago</option>
			<option value="30">a month ago</option>
			<option value="90">3 months ago</option>
			<option value="180">6 months ago</option>
			<option value="365">a year ago</option>
		</select><br /><br />

		<input type="radio" name="beforeafter" value="0" checked="checked" />and newer<br />
		<input type="radio" name="beforeafter" value="1" />and older<br /><br />
	</td>

	<td bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" valign="top" class="smaller"><br />
		<select name="sortby">
			<option value="0">title</option>
			<option value="1">number of replies</option>
			<option value="2">number of views</option>
			<option value="3" selected="selected">last posting date</option>
			<option value="4">poster</option>
			<option value="5">forum</option>
		</select><br /><br />

		<input type="radio" name="sortorder" value="0" checked="checked" />in ascending order<br />
		<input type="radio" name="sortorder" value="1" />in descending order<br /><br />
	</td>
</tr>

</table><br />

<div style="text-align: center;"><input type="submit" name="submit" value="Perform Search" accesskey="s" /></div>
</form>

<?php
	// Footer.
	require("./skins/{$CFG['skin']}/footer.tpl.php");
?>