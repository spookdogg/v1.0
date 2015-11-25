<?php
//***************************************************************************//
//                                                                           //
//  Copyright (c) 2004-2007 Jonathon Freeman                                 //
//  Copyright (c) 2007 Brian Otto                                            //
//  All rights reserved.                                                     //
//                                                                           //
//  This program is free software. You may use, modify, and/or redistribute  //
//  it under the terms of the MIT License.                                   //
//                                                                           //
//***************************************************************************//

function Censor($strText)
{
	global $aCensored;

	// Go through the list.
	foreach($aCensored as $aWord)
	{
		// Replace all instances of the word with its replacement.
		$strText = preg_replace("/\b{$aWord[0]}\b/i", $aWord[1], $strText);
	}

	return $strText;
}

// *************************************************************************** \\

// This is called when a PHP error is generated.
function HandleError($errno, $errmsg, $filename, $linenum, $vars)
{
	global $aGlobalErrors;

	// Types of errors.
	$aErrorType[E_WARNING] = 'Warning';
	$aErrorType[E_NOTICE] = 'Notice';
	$aErrorType[E_USER_ERROR] = 'User Error';
	$aErrorType[E_USER_WARNING] = 'User Warning';
	$aErrorType[E_USER_NOTICE] = 'User Notice';

	// Save the error to the global array.
	if(error_reporting() && ($errno != E_NOTICE))
	{
		$aError = array($aErrorType[$errno], htmlsanitize($errmsg), htmlsanitize($filename), $linenum);
		if(!in_array($aError, $aGlobalErrors))
		{
			$aGlobalErrors[] = $aError;
		}
	}
}

// *************************************************************************** \\

// Display any PHP errors that have thus been made.
function ShowErrors()
{
	global $CFG, $aGlobalErrors;

	if(count($aGlobalErrors))
	{
		echo("<blockquote style=\"overflow: auto; white-space: nowrap; border: inset 1px; background-color: {$CFG['style']['table']['cellb']};\"><ul class=\"medium\">\n");
		foreach($aGlobalErrors as $aError)
		{
			echo("<li><b>{$aError[0]}</b>: {$aError[1]} in <b>{$aError[2]}</b> on line <b>{$aError[3]}</b>.</li>\n");
		}
		echo('</ul></blockquote>');
	}
}

// *************************************************************************** \\

function getmicrotime()
{
	list($ms, $sec) = explode(' ', microtime());
	return ((float)$ms + (float)$sec);
}

// *************************************************************************** \\

// Display the generation statistics of the current page.
function PageStats()
{
	global $CFG, $aQueries, $tStartTime;
	$strDisplay = "<div class=\"small\" style=\"text-align: center; color: {$CFG['style']['stats']};\"><br />Page generated in <span style=\"color: {$CFG['style']['stats_bold']};\"><b>%0.3f</b></span> seconds using <span style=\"color: {$CFG['style']['stats_bold']};\"><b>%u</b></span> database queries.</div>";
	return sprintf($strDisplay, (getmicrotime() - $tStartTime), count($aQueries));
}

// *************************************************************************** \\

// Display the SQL queries that have thus been made.
function ShowQueries()
{
	global $CFG, $aQueries;

	echo("<blockquote style=\"overflow: auto; white-space: nowrap; border: inset 1px; background-color: {$CFG['style']['table']['cellb']};\"><ul class=\"medium\">\n");
	foreach($aQueries as $strQuery)
	{
		$strQuery = htmlsanitize($strQuery);
		echo("<li><code style=\"margin-right: 1em;\">{$strQuery}</code></li>\n");
	}
	echo('</ul></blockquote>');
}

// *************************************************************************** \\

// Our date() replacement that observes the user's time-display settings.
function gmtdate($format, $timestamp)
{
	global $CFG;
	$timestamp = $timestamp + $CFG['time']['display_offset'] + ($CFG['time']['dst'] * $CFG['time']['dst_offset']);
	return gmdate($format, $timestamp);
}

// *************************************************************************** \\

// Displays time information.
function TimeInfo()
{
	global $CFG;

	$hour = floor($CFG['time']['display_offset'] / 3600);
	$minute = ($CFG['time']['display_offset'] - ($hour * 3600)) / 60;
	if($hour > 0)
	{
		$strOffset = sprintf(' +%02u:%02u', $hour, $minute);
	}
	else if($hour < 0)
	{
		$strOffset = sprintf(' %03d:%02u', $hour, $minute);
	}
	$strTimeNow = gmtdate('h:i A', $CFG['globaltime']);

	return("All times are GMT{$strOffset}. The time is now {$strTimeNow}.");
}

// *************************************************************************** \\

function ParseTag($pattern, $replacement, $text)
{
	do
	{
		$text = preg_replace($pattern, $replacement, $text);
	}
	while(preg_match($pattern, $text));

	return $text;
}

// *************************************************************************** \\

// Allows a reverse position search for strings instead of just characters.
function realstrrpos($haystack, $needle)
{
	$i = strpos(strrev($haystack), strrev($needle));
	if($i === FALSE)
	{
		return FALSE;
	}
	$i = strlen($haystack) - strlen($needle) - $i;
	return $i;
}

// Highlights PHP code in the specified text.
function HighlightPHP($strCode)
{
	global $aSmilies;

	// Reverse those calls we made earlier to htmlsanitize() in our ParseMessage() function.
	$strCode = str_replace('<br />', "\n", $strCode);
	$strCode = str_replace('&amp;', '&', $strCode);
	$strCode = str_replace('&quot;', '"', $strCode);
	$strCode = str_replace('&lt;', '<', $strCode);
	$strCode = str_replace('&gt;', '>', $strCode);

	// Remove any smilie tags; they don't belong here.
	foreach($aSmilies as $iSmilieID => $aSmilie)
	{
		$strCode = str_replace("[smilie={$iSmilieID}]", $aSmilie['code'], $strCode);
	}

	// Get rid of the fat.
	$strCode = trim($strCode, "\n\r");

	// Add an opening PHP tag if it doesn't have one.
	if(strpos($strCode, '<?') !== 0)
	{
		$strCode = "<?php\n{$strCode}";
		$bOpenTagged = TRUE;
	}

	// Add a closing PHP tag if it doesn't have one.
	if(realstrrpos($strCode, '?>') !== (strlen($strCode) - 2))
	{
		$strCode = "{$strCode}\n?>";
		$bCloseTagged = TRUE;
	}

	// Highlight the code.
	$strHighlighted = highlight_string($strCode, TRUE);
	$strHighlighted = str_replace(array('<font color="', '</font>'), array('<span style="color: ', '</span>'), $strHighlighted);

	// Remove the opening tag we added earlier.
	if($bOpenTagged)
	{
		$iOpen = strpos($strHighlighted, '&lt;?');
		$strHighlighted = substr($strHighlighted, 0, $iOpen) . substr($strHighlighted, ($iOpen+14));
	}

	// Remove the closing tag we added earlier.
	if($bCloseTagged)
	{
		$iClose = realstrrpos($strHighlighted, '?&gt;');
		$strHighlighted = substr($strHighlighted, 0, ($iClose-42)) . substr($strHighlighted, ($iClose+5));
	}

	// Take care of overflow via CSS.
	$strHighlighted = "<div class=\"php\"><div style=\"float: left;\">{$strHighlighted}</div></div>";

	// Return the highlighted code.
	return("<br /><blockquote class=\"medium\"><small>PHP:</small><hr />{$strHighlighted}<hr /></blockquote>");
}

// *************************************************************************** \\

// Convert BB code list into HTML list.
function ProcessList($strContents, $strType = NULL)
{
	// preg_replace does an addslashes(), so we need to take those out.
	$strContents = stripslashes($strContents);

	// We also need to reverse our newline conversion, so
	// our expressions will find the end of list elements.
	$strContents = str_replace('<br />', "\n", $strContents);

	// Finally, we add a newline to the end, so that if the user does not
	// close the last list element with an ending tag or a newline,
	// it will still be parsed.
	$strContents = "{$strContents}\n";

	// Set the type.
	switch($strType)
	{
		// Ordered list?
		case '1':
		case 'a':
		case 'A':
		case 'i':
		case 'I':
		{
			$strTag = 'ol';
			$strType = " type=\"{$strType}\"";
			break;
		}

		// Unordered list (or unknown)?
		default:
		{
			$strTag = 'ul';
			$strType = '';
			break;
		}
	}

	// Count the elements.
	$iCount = preg_match_all('/\[\*\](.+?)\[\/\*\]/s', $strContents, $blank);
	$iCount += preg_match_all('/\[\*\](.+?)\n/', $strContents, $blank);
	unset($blank);

	// Process all of the elements.
	if($iCount)
	{
		$strContents = preg_replace('/\[\*\](.+?)\[\/\*\]/s', '<li>$1</li>', $strContents);
		$strContents = preg_replace('/\[\*\](.+?)\n/', '<li>$1</li>', $strContents);
	}

	// Get rid of the fat.
	$strContents = trim($strContents);

	// Returned the processed list.
	if($iCount)
	{
		return("<{$strTag}{$strType}>{$strContents}</{$strTag}>");
	}
	else
	{
		return($strContents);
	}
}

// *************************************************************************** \\

// Renders a BB [code] tag.
function ProcessCode($strCode)
{
	global $aSmilies;

	// Remove any smilie tags; they don't belong here.
	foreach($aSmilies as $iSmilieID => $aSmilie)
	{
		$strCode = str_replace("[smilie={$iSmilieID}]", $aSmilie['code'], $strCode);
	}

	// Get rid of the fat.
	$strCode = trim(str_replace('<br />', "\n", $strCode), "\n\r");

	return("<blockquote><small>code:</small><hr /><div class=\"code\"><pre style=\"float: left; margin: 0;\">{$strCode}</pre></div><hr /></blockquote>");
}

// *************************************************************************** \\

function ReplaceTime($tTime)
{
	return gmtdate('m-d-Y h:i A', (int)$tTime);
}

// *************************************************************************** \\

// Parses the BB code in any given message.
function ParseMessage($strMessage, $bDisableSmilies, $bDisableCodes = FALSE)
{
	global $CFG, $aSmilies;

	// Are smilies disabled here?
	if(!$bDisableSmilies)
	{
		// Parse the smilies.
		foreach($aSmilies as $iSmilieID => $aSmilie)
		{
			$strMessage = str_replace($aSmilie['code'], "[smilie={$iSmilieID}]", $strMessage);
		}
	}

	// Make the message safe to display.
	$strMessage = htmlsanitize($strMessage);

	// Take care of the newlines.
	$strMessage = str_replace("\r\n", '<br />', $strMessage);
	$strMessage = str_replace("\n", '<br />', $strMessage);
	$strMessage = str_replace("\r", '<br />', $strMessage);

	// Parse the BB codes.
	if(!$bDisableCodes)
	{
		$strMessage = ParseTag('/\[b\](.+?)\[\/b\]/is', '<b>$1</b>', $strMessage);
		$strMessage = ParseTag('/\[i\](.+?)\[\/i\]/is', '<i>$1</i>', $strMessage);
		$strMessage = ParseTag('/\[u\](.+?)\[\/u\]/is', '<u>$1</u>', $strMessage);
		$strMessage = ParseTag('/\[sup\](.+?)\[\/sup\]/i', '<sup>$1</sup>', $strMessage);
		$strMessage = ParseTag('/\[sub\](.+?)\[\/sub\]/i', '<sub>$1</sub>', $strMessage);
		$strMessage = ParseTag('/\[size=((&quot;)?)(.+?)(\\1)\](.+?)\[\/size\]/is', '<font size="$3">$5</font>', $strMessage);
		$strMessage = ParseTag('/\[font=((&quot;)?)(.+?)(\\1)\](.+?)\[\/font\]/is', '<span style="font-family: $3;">$5</span>', $strMessage);
		$strMessage = ParseTag('/\[color=((&quot;)?)(.+?)(\\1)\](.+?)\[\/color\]/is', '<span style="color: $3;">$5</span>', $strMessage);
		$strMessage = preg_replace('/\[url\](.+?)\[\/url\]/i', '<a href="$1" target="_blank">$1</a>', $strMessage);
		$strMessage = preg_replace('/\[url=((&quot;)?)(.+?)(\\1)\](.+?)\[\/url\]/i', '<a href="$3" target="_blank">$5</a>', $strMessage);
		$strMessage = preg_replace('/\[thread=((&quot;)?)(.+?)(\\1)\](.+?)\[\/thread\]/i', '<a href="thread.php?threadid=$3">$5</a>', $strMessage);
		$strMessage = preg_replace('/\[email\](.+?)\[\/email\]/i', '<a href="mailto:$1">$1</a>', $strMessage);
		$strMessage = preg_replace('/\[email=((&quot;)?)(.+?)(\\1)\](.+?)\[\/email\]/i', '<a href="mailto:$3">$5</a>', $strMessage);
		$strMessage = preg_replace('/\[code\](.+?)\[\/code\]/eis', 'ProcessCode(\'$1\')', $strMessage);
		$strMessage = preg_replace('/\[php\](.+?)\[\/php\]/eis', 'HighlightPHP(\'$1\')', $strMessage);
		$strMessage = preg_replace('/\[list\](.+?)\[\/list\]/eis', 'ProcessList(\'$1\')', $strMessage);
		$strMessage = preg_replace('/\[list=((&quot;)?)(.+?)(\\1)\](.+?)\[\/list\]/eis', 'ProcessList(\'$5\', \'$3\')', $strMessage);
		$strMessage = ParseTag('/\[quote\](.+?)\[\/quote\]/is', '<blockquote><small>quote:</small><hr /><div class="quote"><div style="float: left;">$1</div></div><hr /></blockquote>', $strMessage);
		$strMessage = ParseTag('/\[quote=((&quot;)?)(.+?)(\\1)\](.+?)\[\/quote\]/is', '<blockquote><small>quote:</small><hr /><div class="quote"><div style="float: left;"><i>Originally posted by $3</i><br /><b>$5</b></div></div><hr /></blockquote>', $strMessage);
		$strMessage = preg_replace('/\[dt=(.+?)\]/ei', 'ReplaceTime(\'$1\')', $strMessage);
		if($CFG['parsing']['showimages'])
		{
			$strMessage = preg_replace('/\[img\](.+?)\[\/img\]/i', '<img src="$1" alt="" border="0" />', $strMessage);
		}
		else
		{
			$strMessage = preg_replace('/\[img\](.+?)\[\/img\]/i', '<a href="$1" target="_blank">$1</a>', $strMessage);
		}
	}

	// Parse the smilies.
	if(!$bDisableSmilies)
	{
		foreach($aSmilies as $iSmilieID => $aSmilie)
		{
			$strMessage = str_replace("[smilie={$iSmilieID}]", "<img src=\"{$CFG['paths']['smilies']}{$aSmilie['filename']}\" style=\"vertical-align: middle;\" alt=\"\" />", $strMessage);
		}
	}

	// Censor bad words.
	$strMessage = Censor($strMessage);

	return $strMessage;
}

// *************************************************************************** \\

// Puts [email] tags around all suspected e-mail addresses in specified text.
function ParseEMails($strMessage)
{
	// Remove all existing mail tags. This is our hack at fixing the "double-tag problem".
	$strMessage = preg_replace('/\[email\](.+?)\[\/email\]/i', '$1', $strMessage);

	// Now parse e-mails.
	return ereg_replace("([a-zA-Z0-9_\-])+(\.([a-zA-Z0-9_\-])+)*@((\[(((([0-1])?([0-9])?[0-9])|(2[0-4][0-9])|(2[0-5][0-5])))\.(((([0-1])?([0-9])?[0-9])|(2[0-4][0-9])|(2[0-5][0-5])))\.(((([0-1])?([0-9])?[0-9])|(2[0-4][0-9])|(2[0-5][0-5])))\.(((([0-1])?([0-9])?[0-9])|(2[0-4][0-9])|(2[0-5][0-5]))\]))|((([a-zA-Z0-9])+(([\-])+([a-zA-Z0-9])+)*\.)+([a-zA-Z])+(([\-])+([a-zA-Z0-9])+)*))", '[email]\\0[/email]', $strMessage);
}

// *************************************************************************** \\

// Displays the error message the user receives when they're trying to do something they're not supposed to be doing.
function Unauthorized()
{
	global $CFG;

	// Get the information of each forum.
	list($aCategory, $aForum) = GetForumInfo();

	// Template
	require("./skins/{$CFG['skin']}/unauthorized.tpl.php");

	// Send the page.
	exit;
}

// *************************************************************************** \\

// Displays a message to the user.
function Msg($strMessage, $strRedirect = '', $strAlign = 'center')
{
	global $CFG;

	// Are we redirecting?
	if($strRedirect)
	{
		// Yes, so set the redirected flag.
		$_SESSION['redirected'] = TRUE;

		// Append the session ID to the redirect URL (if cookies are disabled).
		if(SID)
		{
			// Parse the redirect URL.
			$aURL = parse_url($strRedirect);

			// Handle the fragment.
			if($aURL['fragment'])
			{
				$aURL['fragment'] = "#{$aURL['fragment']}";
			}

			// Handle the query.
			$strRedirect = $aURL['query'] ? "{$aURL['path']}?{$aURL['query']}&amp;".stripslashes(SID).$aURL['fragment'] : "{$aURL['path']}?".stripslashes(SID).$aURL['fragment'];
			unset($aURL);
		}
	}

	// Template
	require("./skins/{$CFG['skin']}/sysmessage.tpl.php");

	// Send the page.
	exit;
}

// *************************************************************************** \\

// Displays the available post icons.
function DisplayPostIcons($aPostIcons, $icon)
{
	global $CFG;

	// Figure how we're going to display the post icons. How many are there?
	$iIconCount = count($aPostIcons) - 1;

	// Is there going to be more than one row?
	if($iIconCount > 8)
	{
		// Yes. How long will the longest row(s) be?
		if($iIconCount > 15)
		{
			// The maximum we allow: 8.
			$iRowLength = 8;
		}
		else if($iIconCount < 11)
		{
			// The minimum we allow: 5.
			$iRowLength = 5;
		}
		else
		{
			// The icon count halved (and ceil'd).
			$iRowLength = ceil($iIconCount / 2);
		}
	}
	else
	{
		// No.
		$iRowLength = $iIconCount;
	}

	// Display it.
	$iIndex = 0;
	next($aPostIcons);
	while(list($i, $aIcon) = each($aPostIcons))
	{
?>		<input type="radio" name="icon" value="<?php echo($i);?>"<?php if($icon == $i){echo(' checked="checked"');} ?> /> <img src="<?php echo("{$CFG['paths']['posticons']}{$aIcon['filename']}"); ?>" align="middle" alt="<?php echo($aIcon['title']); ?>" /><?php
		$iIndex++;
		if($iIndex == $iRowLength)
		{
			echo("<br />\n");
			$iIndex = 0;
		}
		else
		{
			echo("&nbsp;&nbsp;&nbsp;\n");
		}
	}
}

// *************************************************************************** \\

// Displays the available smilies.
function SmilieTable($aSmilies)
{
	global $CFG;

	// Figure out how wide the table should be.
	if(($iSquare = floor(sqrt(count($aSmilies)))) <= 5)
	{
		$iRowLength = $iSquare - 1;
	}
	else
	{
		$iRowLength = 4;
	}

	// Display the Smilies table.
	$i = 0;
	foreach($aSmilies as $aSmilie)
	{
		// Get the smile's properties.
		$strSmilieTitle = $aSmilie['title'];
		$strSmilieCode = $aSmilie['code'];
		$strSmilieFilename = $aSmilie['filename'];

		// Where are we?
		switch($i)
		{
			// First in row?
			case 0:
			{
				// Start a new row AND print out a smilie.
?>			<tr>
				<td valign="middle"><a href="javascript:smilie('<?php echo($strSmilieCode); ?>');"><img src="<?php echo("{$CFG['paths']['smilies']}{$strSmilieFilename}"); ?>" border="0" alt="<?php echo($strSmilieCode); ?>" /></a><img src="images/space.png" width="5" height="1" alt="" /></td>
<?php
				break;
			}

			// Last in row?
			case $iRowLength:
			{
				// Print out a smilie AND end the row.
?>				<td valign="middle"><a href="javascript:smilie('<?php echo($strSmilieCode); ?>');"><img src="<?php echo("{$CFG['paths']['smilies']}{$strSmilieFilename}"); ?>" border="0" alt="<?php echo($strSmilieCode); ?>" /></a></td>
			</tr>
<?php
				break;
			}

			// In the middle?
			default:
			{
				// Just print out a smilie.
?>				<td valign="middle"><a href="javascript:smilie('<?php echo($strSmilieCode); ?>');"><img src="<?php echo("{$CFG['paths']['smilies']}{$strSmilieFilename}"); ?>" border="0" alt="<?php echo($strSmilieCode); ?>" /></a><img src="images/space.png" width="5" height="1" alt="" /></td>
<?php
				break;
			}
		}

		// Update the position.
		if($i != $iRowLength)
		{
			$i++;
		}
		else
		{
			$i = 0;
		}
	}

	// Clean-up.
	if(($i > 0) && ($i < ++$iRowLength))
	{
		// Last smilie was in the middle, so we need to end the left-over row.
		echo("\t\t\t</tr>");
	}
}

// *************************************************************************** \\

// Displays the available avatars.
function AvatarTable($iAvatar, $aAvatars)
{
	global $CFG;

	echo("\n\n<table cellpadding=\"10\" cellspacing=\"1\" border=\"0\" bgcolor=\"{$CFG['style']['table']['bgcolor']}\" align=\"center\">\n");

	$iRowLength = 4;

	// Display the Avatars table.
	$i = 0;
	foreach($aAvatars as $iAvatarID => $aAvatar)
	{
		// Get the avatar's properties.
		$strTitle = $aAvatar['title'];
		$strFilename = $aAvatar['filename'];

		// Where are we?
		switch($i)
		{
			// First in row?
			case 0:
			{
				// Start a new row AND print out a avatar.
?>	<tr>
		<td align="center" bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium">
			<img src="<?php echo("{$CFG['paths']['avatars']}{$strFilename}"); ?>" alt="" /><br />
			<input type="radio" name="avatarid" value="<?php echo($iAvatarID); ?>"<?php if($iAvatar==$iAvatarID){echo(' checked="checked"');} ?> /><?php echo(htmlsanitize($strTitle)); ?>
		</td>
<?php
				break;
			}

			// Last in row?
			case $iRowLength:
			{
				// Print out a avatar AND end the row.
?>		<td align="center" bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium">
			<img src="<?php echo("{$CFG['paths']['avatars']}{$strFilename}"); ?>" alt="" /><br />
			<input type="radio" name="avatarid" value="<?php echo($iAvatarID); ?>"<?php if($iAvatar==$iAvatarID){echo(' checked="checked"');} ?> /><?php echo(htmlsanitize($strTitle)); ?>
		</td>
	</tr>
<?php
				break;
			}

			// In the middle?
			default:
			{
				// Just print out a avatar.
?>		<td align="center" bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class="medium">
			<img src="<?php echo("{$CFG['paths']['avatars']}{$strFilename}"); ?>" alt="" /><br />
			<input type="radio" name="avatarid" value="<?php echo($iAvatarID); ?>"<?php if($iAvatar==$iAvatarID){echo(' checked="checked"');} ?> /><?php echo(htmlsanitize($strTitle)); ?>
		</td>
<?php
				break;
			}
		}

		// Update the position.
		if($i != $iRowLength)
		{
			$i++;
		}
		else
		{
			$i = 0;
		}
	}

	// Clean-up.
	if(($i > 0) && ($i < ++$iRowLength))
	{
		// Last avatar was in the middle, so we need to end the left-over row.
		for($x = $i; $x < $iRowLength; $x++)
		{
			echo("\t<td align=\"center\" bgcolor=\"{$CFG['style']['table']['cella']}\" class=\"medium\">&nbsp;</td>\n");
		}
		echo("</tr>\n");
	}

	echo("\n</table>\n\n");
}

// *************************************************************************** \\

// Displays a list of specified errors.
function DisplayErrors($aError)
{
	global $CFG;

	echo("<ul class=\"small\" style=\"font-weight: bold; color: {$CFG['style']['errors']};\">\n");
	foreach($aError as $strError)
	{
		echo("<li>{$strError}</li>\n");
	}
	echo("</ul>");
}

// *************************************************************************** \\

// Adds the specified message to the search indexing system.
function AddSearchIndex($iPostID, $strTitle, $strBody)
{
	global $dbConn;

	// Extract the words.
	$aTitleWords = ExtractWords($strTitle);
	$aBodyWords = ExtractWords($strBody);

	// Get the words already in the searchwords table, and add a searchindex for each one.
	if(count($aBodyWords))
	{
		$aAbsentWords = $aBodyWords;
		$strCleanWords = implode("', '", array_map(array($dbConn, 'sanitize'), $aBodyWords));
		$dbConn->query("SELECT wordid, word FROM searchword WHERE word IN ('{$strCleanWords}')");
		$aSQLAllResults = $dbConn->getall();
		while(list($key, $aSearchWord) = each($aSQLAllResults))
		{
			list($iWordID, $strWord) = $aSearchWord;

			// Remove the word from the list of absent words.
			unset($aAbsentWords[array_search($strWord, $aAbsentWords)]);

			// Is the word in the title also?
			if($k = array_search($strWord, $aTitleWords))
			{
				$iInTitle = 1;
				unset($aTitleWords[$k]);
			}
			else
			{
				$iInTitle = 0;
			}

			// Insert key for this instance.
			$dbConn->query("INSERT INTO searchindex(postid, wordid, intitle) VALUES({$iPostID}, {$iWordID}, {$iInTitle})");
		}

		// Add the absent words into the searchwords table, and add a searchindex for each one.
		foreach($aAbsentWords as $strWord)
		{
			$strCleanWord = $dbConn->sanitize($strWord);
			$dbConn->query("INSERT INTO searchword(word) VALUES('{$strCleanWord}')");
			$iWordID = $dbConn->getinsertid('searchword');

			// Is the word in the title also?
			if($k = array_search($strWord, $aTitleWords))
			{
				$iInTitle = 1;
				unset($aTitleWords[$k]);
			}
			else
			{
				$iInTitle = 0;
			}

			// Insert key for this instance.
			$dbConn->query("INSERT INTO searchindex(postid, wordid, intitle) VALUES({$iPostID}, {$iWordID}, {$iInTitle})");
		}
	}

	// Get the in-title words already in the searchwords table.
	if(count($aTitleWords))
	{
		$aAbsentWords = $aTitleWords;
		$strCleanWords = implode("', '", array_map(array($dbConn, 'sanitize'), $aTitleWords));
		$dbConn->query("SELECT wordid, word FROM searchword WHERE word IN ('{$strCleanWords}')");
		$aSQLAllResults = $dbConn->getall();
		while(list($key, $aSearchWord) = each($aSQLAllResults))
		{
			list($iWordID, $strWord) = $aSearchWord;

			// Remove the word from the list of absent words.
			unset($aAbsentWords[array_search($strWord, $aAbsentWords)]);

			// Insert key for this instance.
			$dbConn->query("INSERT INTO searchindex(postid, wordid, intitle) VALUES({$iPostID}, {$iWordID}, 1)");
		}

		// Add the absent words into the searchwords table, and add a searchindex for each one.
		foreach($aAbsentWords as $strWord)
		{
			$strCleanWord = $dbConn->sanitize($strWord);
			$dbConn->query("INSERT INTO searchword(word) VALUES('{$strWord}')");
			$iWordID = $dbConn->getinsertid('searchword');

			// Is the word in the title also?
			if($k = array_search($strWord, $aTitleWords))
			{
				$iInTitle = 1;
				unset($aTitleWords[$k]);
			}
			else
			{
				$iInTitle = 0;
			}

			// Insert key for this instance.
			$dbConn->query("INSERT INTO searchindex(postid, wordid, intitle) VALUES({$iPostID}, {$iWordID}, 1)");
		}
	}
}

// Extracts the words, removing bad characters and discarding long words.
function ExtractWords($text)
{
	require('./includes/commonwords.inc.php');

	$state = FALSE;
	$aWords = array();

	// Get rid of the fat.
	$text = trim($text);

	for($i = 0; $i < strlen($text); $i++)
	{
		$ch = $text{$i};

		if($state == FALSE)
		{
			if(ctype_alpha($ch))
			{
				$strWord = $ch;
				$state = TRUE;
			}
		}
		else if($state == TRUE)
		{
			if(ctype_alpha($ch))
			{
				$strWord = $strWord . $ch;
			}
			else
			{
				if(strlen($strWord) <= 255)
				{
					$aWords[] = strtolower($strWord);
				}
				$state = FALSE;
			}
		}
	}

	if(($state == TRUE) && (strlen($strWord) <= 255))
	{
		$aWords[] = strtolower($strWord);
	}

	// Remove duplicates.
	$aWords = array_unique($aWords);
	foreach($aWords as $iWordID => $strWord)
	{
		if(in_array($strWord, $aCommonWords))
		{
			unset($aWords[$iWordID]);
		}
	}

	return $aWords;
}

// *************************************************************************** \\

// Displays the BB code formatting toolbar.
function ShowToolbar()
{
	global $CFG;
?>		<input type="button" onclick="bbcode('b');" onmouseover="document.theform.status.value='Insert bold text.';" onmouseout="document.theform.status.value='';" value=" B " /><input type="button" onclick="bbcode('i');" onmouseover="document.theform.status.value='Insert italic text.';" onmouseout="document.theform.status.value='';" value=" I " /><input type="button" onclick="bbcode('u');" onmouseover="document.theform.status.value='Insert underlined text.';" onmouseout="document.theform.status.value='';" value=" U " />
		<select name="tsize" onchange="bbcode2('size', this.options[this.selectedIndex].value);" onmouseover="document.theform.status.value='Alter the size of your text.';" onmouseout="document.theform.status.value='';">
			<option value="0">SIZE</option>
			<option value="1">Small</option>
			<option value="3">Large</option>
			<option value="4">Huge</option>
		</select><select name="tfont" onchange="bbcode2('font', this.options[this.selectedIndex].value);" onmouseover="document.theform.status.value='Alter the font of your text.';" onmouseout="document.theform.status.value='';">
			<option value="0">FONT</option>
			<option value="arial">Arial</option>
			<option value="courier">Courier</option>
			<option value="times new roman">Times New Roman</option>
		</select><select name="tcolor" onchange="bbcode2('color', this.options[this.selectedIndex].value);" onmouseover="document.theform.status.value='Alter the color of your text.';" onmouseout="document.theform.status.value='';">
			<option value="0">COLOR</option>
			<option value="skyblue" style="color: skyblue">Sky Blue</option>
			<option value="royalblue" style="color: royalblue">Royal Blue</option>
			<option value="blue" style="color: blue">Blue</option>
			<option value="darkblue" style="color: darkblue">Dark Blue</option>
			<option value="orange" style="color: orange">Orange</option>
			<option value="orangered" style="color: orangered">Orange-Red</option>
			<option value="crimson" style="color: crimson">Crimson</option>
			<option value="red" style="color: red">Red</option>
			<option value="firebrick" style="color: firebrick">Firebrick</option>
			<option value="darkred" style="color: darkred">Dark Red</option>
			<option value="green" style="color: green">Green</option>
			<option value="limegreen" style="color: limegreen">Lime Green</option>
			<option value="seagreen" style="color: seagreen">Sea Green</option>
			<option value="deeppink" style="color: deeppink">Deep Pink</option>
			<option value="tomato" style="color: tomato">Tomato</option>
			<option value="coral" style="color: coral">Coral</option>
			<option value="purple" style="color: purple">Purple</option>
			<option value="indigo" style="color: indigo">Indigo</option>
			<option value="burlywood" style="color: burlywood">Burlywood</option>
			<option value="sandybrown" style="color: sandybrown">Sandy Brown</option>
			<option value="sienna" style="color: sienna">Sienna</option>
			<option value="chocolate" style="color: chocolate">Chocolate</option>
			<option value="teal" style="color: teal">Teal</option>
			<option value="silver" style="color: silver">Silver</option>
		</select><br />
		<input type="button" onclick="bbcode('url');" onmouseover="document.theform.status.value='Insert a hypertext link.';" onmouseout="document.theform.status.value='';" value="http://" /><input type="button" onclick="bbcode('email');" onmouseover="document.theform.status.value='Insert an e-mail link.';" onmouseout="document.theform.status.value='';" value=" @ " /><input type="button" onclick="bbcode('img');" onmouseover="document.theform.status.value='Insert a linked image.';" onmouseout="document.theform.status.value='';" value="Image" />
		<input type="button" onclick="bbcode('code');" onmouseover="document.theform.status.value='Insert source code or monospaced text.';" onmouseout="document.theform.status.value='';" value="Code" /><input type="button" onclick="bbcode('php');" onmouseover="document.theform.status.value='Insert text with PHP syntax highlighting.';" onmouseout="document.theform.status.value='';" value="PHP" /><input type="button" onclick="makelist();" onmouseover="document.theform.status.value='Insert an ordered list.';" onmouseout="document.theform.status.value='';" value="List" /><input type="button" onclick="bbcode('quote');" onmouseover="document.theform.status.value='Insert a quotation.';" onmouseout="document.theform.status.value='';" value="Quote" /><br />
		<input style="color: <?php echo($CFG['style']['forum']['txtcolor']); ?>; border-width: 0px; border-style: hidden; font-family: verdana, arial, helvetica, sans-serif; font-size: 10px; background-color: <?php echo($CFG['style']['table']['cellb']); ?>;" type="text" name="status" value="This toolbar requires JavaScript." size="48" readonly="readonly" />
<?php
}

// *************************************************************************** \\

// Saves the specified user information to the user's session.
function LoadUser($aUserInfo)
{
	global $CFG;

	// Store the member information into the session.
	$_SESSION['loggedin'] = TRUE;
	$_SESSION['userid'] = (int)$aUserInfo['id'];
	$_SESSION['username'] = $aUserInfo['username'];
	$_SESSION['passphrase'] = $aUserInfo['passphrase'];
	$_SESSION['autologin'] = (bool)$aUserInfo['autologin'];
	$_SESSION['enablepms'] = (bool)$aUserInfo['enablepms'];
	$_SESSION['pmnotifyb'] = (bool)$aUserInfo['pmnotifyb'];
	$_SESSION['rejectpms'] = (bool)$aUserInfo['rejectpms'];
	$_SESSION['showsigs'] = (bool)$aUserInfo['showsigs'];
	$_SESSION['showavatars'] = (bool)$aUserInfo['showavatars'];
	$_SESSION['threadview'] = (int)($aUserInfo['threadview'] ? $aUserInfo['threadview'] : $CFG['default']['threadview']);
	$_SESSION['postsperpage'] = (int)($aUserInfo['postsperpage'] ? $aUserInfo['postsperpage'] : $CFG['default']['postsperpage']);
	$_SESSION['threadsperpage'] = (int)($aUserInfo['threadsperpage'] ? $aUserInfo['threadsperpage'] : $CFG['default']['threadsperpage']);
	$_SESSION['weekstart'] = (int)$aUserInfo['weekstart'];
	$_SESSION['timeoffset'] = (int)$aUserInfo['timeoffset'];
	$_SESSION['dst'] = (bool)$aUserInfo['dst'];
	$_SESSION['dstoffset'] = (int)$aUserInfo['dstoffset'];
	$_SESSION['lastactive'] = (int)$aUserInfo['lastactive'];
	$_SESSION['usergroup'] = (int)$aUserInfo['usergroup'];
	$_SESSION['ignorelist'] = $aUserInfo['ignorelist'] ? explode(',', $aUserInfo['ignorelist']) : array();
	$_SESSION['email'] = $aUserInfo['email'];

	// Make sure the user has a last active.
	if($_SESSION['lastactive'] === NULL)
	{
		$_SESSION['lastactive'] = $CFG['globaltime'];
	}

	// Store the user's time information.
	$CFG['time']['display_offset'] = $_SESSION['timeoffset'];
	$CFG['time']['dst'] = $_SESSION['dst'];
	$CFG['time']['dst_offset'] = $_SESSION['dstoffset'];
}

// *************************************************************************** \\

// Prints out a pagination linkset.
function Paginate($strLocation, $iNumberPages, $iPage, $iPerPage, $strSortBy = NULL, $strSortOrder = NULL, $iDaysPrune = NULL)
{
	// Build the sort string.
	if($strSortBy)
	{
		$strSort = "&amp;sortby={$strSortBy}&amp;sortorder={$strSortOrder}";
	}

	// Build the day prune string.
	if($iDaysPrune)
	{
		$strDaysPrune = "&amp;daysprune={$iDaysPrune}";
	}

	// Pagination heading.
	echo('<b>Pages</b> ('.number_format($iPage).' of '.number_format($iNumberPages)."):\n<b>");

	// Put a link to the first page and some elipses if the first page we list isn't 1.
	if(($iPage - 3) > 1)
	{
		echo(" <a href=\"{$strLocation}&amp;perpage={$iPerPage}&amp;page=1{$strSort}{$strDaysPrune}\">&laquo; First</a> ...");
	}

	// Show a left arrow if there are pages before us.
	if($iPage > 1)
	{
		echo(" <a href=\"{$strLocation}&amp;perpage={$iPerPage}&amp;page=".($iPage-1)."{$strSort}{$strDaysPrune}\">&laquo;</a>");
	}

	// Put up the numbers before us, if any.
	for($i = ($iPage - 3); $i < $iPage; $i++)
	{
		// Only print out the number if it's a valid page.
		if($i > 0)
		{
			echo(" <a href=\"{$strLocation}&amp;perpage={$iPerPage}&amp;page={$i}{$strSort}{$strDaysPrune}\">".number_format($i).'</a>');
		}
	}

	// Display our page number as a non-link in brackets.
	echo(" <span class=\"medium\">[$iPage]</span> ");

	// Put up the numbers after us, if any.
	for($i = ($iPage + 1); $i < ($iPage + 4); $i++)
	{
		// Only print out the number if it's a valid page.
		if($i <= $iNumberPages)
		{
			echo(" <a href=\"{$strLocation}&amp;perpage={$iPerPage}&amp;page={$i}{$strSort}{$strDaysPrune}\">".number_format($i).'</a>');
		}
	}

	// Show a right arrow if there are pages after us.
	if($iNumberPages > $iPage)
	{
		echo(" <a href=\"{$strLocation}&amp;perpage={$iPerPage}&amp;page=".($iPage+1)."{$strSort}{$strDaysPrune}\">&raquo;</a>");
	}

	// Put some elipses and a link to the last page if the last page we list isn't the last.
	if(($iPage + 3) < $iNumberPages)
	{
		echo(" ... <a href=\"{$strLocation}&amp;perpage={$iPerPage}&amp;page={$iNumberPages}{$strSort}{$strDaysPrune}\">Last &raquo;</a>");
	}

	// Finish it up.
	echo("</b>\n");
}

// *************************************************************************** \\

// Updates the statistics of the specified forum to what's current.
function UpdateForumStats($iForumID)
{
	global $dbConn;

	// Get the live statistics for the forum.
	$dbConn->query("SELECT post.author, post.datetime_posted, post.parent FROM post LEFT JOIN thread ON (post.parent = thread.id) LEFT JOIN board ON (thread.parent = board.id) WHERE board.id={$iForumID} AND thread.visible=1 ORDER BY post.datetime_posted DESC LIMIT 1");
	list($iLastPoster, $tLastPosted, $iLastThread) = $dbConn->getresult();

	// Are there any more threads left in the forum?
	if(!$iLastPoster)
	{
		// No, so set the values to NULL.
		$tLastPosted = 'NULL';
		$iLastPoster = 'NULL';
		$iLastThread = 'NULL';
		$iLastThreadPCount = 'NULL';
	}
	else
	{
		// Yes, get the post count of the thread with the newest post.
		$dbConn->query("SELECT postcount FROM thread WHERE id={$iLastThread}");
		list($iLastThreadPCount) = $dbConn->getresult();
	}

	// Update the forum's record.
	$dbConn->query("UPDATE board SET lpost={$tLastPosted}, lposter={$iLastPoster}, lthread={$iLastThread}, lthreadpcount={$iLastThreadPCount} WHERE id={$iForumID}");
}

// *************************************************************************** \\

// Get the information of every forum.
function GetForumInfo()
{
	global $dbConn;

	$aCategory = array();
	$aForum = array();

	$dbConn->query("SELECT id, displaydepth, parent, disporder, name FROM board WHERE displaydepth IN (0, 1) ORDER BY disporder ASC");
	while($aSQLResult = $dbConn->getresult(TRUE))
	{
		// Is this a 'Level 0' or a 'Level 1' forum?
		switch($aSQLResult['displaydepth'])
		{
			// Level 0: Category
			case 0:
			{
				// Store the category information into the Category array.
				$iCategoryID = $aSQLResult['id'];
				$aCategory[$iCategoryID] = $aSQLResult['name'];
				break;
			}

			// Level 1: Forum
			case 1:
			{
				// Store the forum information into the Forum array.
				$iForumID = $aSQLResult['id'];
				$aForum[$iForumID][0] = $aSQLResult['parent'];
				$aForum[$iForumID][1] = $aSQLResult['name'];
				break;
			}
		}
	}

	return array($aCategory, $aForum);
}

// *************************************************************************** \\

// str_split() except it sanitizes each element for SQL insertion.
function sqlsplit($string, $length = 1)
{
	global $dbConn;

	$aData = array();
	while(strlen($string) > 0)
	{
		$aData[] = $dbConn->sanitize(substr($string, 0, $length));
		$string = substr($string, $length);
	}
	return $aData;
}

// *************************************************************************** \\

// Given a list of user IDs, returns an array of usernames.
function GetUsernames($aUsers)
{
	global $dbConn;

	$aUsernames = array();

	// Did we get a list of user IDs?
	if(is_array($aUsers) && count($aUsers))
	{
		// Yes, remove any duplicates and prepare for SQL insertion.
		$strUsers = implode(', ', array_unique($aUsers));

		// Query the database to get the usernames.
		$dbConn->query("SELECT id, username FROM citizen WHERE id IN ($strUsers)");
		while(list($iUserID, $strUsername) = $dbConn->getresult())
		{
			$aUsernames[$iUserID] = $strUsername;
		}
	}

	// Return the list of usernames.
	return $aUsernames;
}

// *************************************************************************** \\

// Sanitizes a given string for output in UTF-8 encoded HTML.
function htmlsanitize($str)
{
	return htmlspecialchars($str, ENT_COMPAT, 'UTF-8');
}
?>