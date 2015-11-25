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

	// Initialize OvBB.
	require('./includes/init.inc.php');

	// What are they wanting to do?
	switch($_REQUEST['action'])
	{
		// Show voting results.
		case 'showresults':
		{
			ShowResults();
		}

		// Cast vote.
		case 'vote':
		{
			Vote();
		}

		// Create new poll.
		case 'newpoll':
		{
			NewPoll();
		}
	}

// *************************************************************************** \\

// Displays results of a specified poll.
function ShowResults()
{
	global $CFG, $dbConn;

	// What poll do they want?
	$iPollID = (int)$_REQUEST['pollid'];

	// Get the poll information.
	$dbConn->query("SELECT question, answers, timeout, datetime FROM poll WHERE id={$iPollID}");
	if(!(list($strPollQuestion, $strPollAnswers, $iTimeout, $tPosted) = $dbConn->getresult()))
	{
		Msg("Invalid poll specified.{$CFG['msg']['invalidlink']}");
	}
	$aPollAnswers = unserialize($strPollAnswers);
	$bClosed = ($iTimeout && ($CFG['globaltime'] > ($tPosted + ($iTimeout * 86400)))) ? TRUE : FALSE;

	// Get the votes.
	$dbConn->query("SELECT ownerid, vote FROM pollvote WHERE parent={$iPollID}");
	while(list($iOwnerID, $iAnswerID) = $dbConn->getresult())
	{
		// Tally the vote.
		$aVotes[$iAnswerID]++;

		// Increment the vote counter.
		$iVoteCount++;

		// Is this our vote?
		if($iOwnerID == $_SESSION['userid'])
		{
			// Yes.
			$bHasVoted = TRUE;
		}
	}

	// Get our forum name as well as the ID and name of the category we belong to.
	$dbConn->query("SELECT board.id, board.name, cat.id, cat.name, thread.title FROM board JOIN board AS cat ON (board.parent = cat.id) LEFT JOIN thread ON (thread.parent = board.id) WHERE thread.id={$iPollID}");
	list($iForumID, $strForumName, $iCategoryID, $strCategoryName, $strThreadTitle) = $dbConn->getresult();

	// Template
	require("./skins/{$CFG['skin']}/pollresults.tpl.php");

	// Send the page.
	exit;
}

// *************************************************************************** \\

// Submits a user's vote.
function Vote()
{
	global $CFG, $dbConn;

	// Do they have authorization to vote in polls?
	if(!$_SESSION['permissions']['cvotepolls'])
	{
		// No. Let them know the bad news.
		Unauthorized();
	}

	// What poll are they voting in?
	$iPollID = (int)$_REQUEST['pollid'];

	// Get the information of the poll.
	$dbConn->query("SELECT answers, multiplechoices, timeout, datetime FROM poll WHERE id={$iPollID}");
	if(!(list($strAnswers, $bMultipleChoices, $iTimeout, $tPosted) = $dbConn->getresult()))
	{
		Msg("Invalid poll specified.{$CFG['msg']['invalidlink']}");
	}
	else if($iTimeout && ($CFG['globaltime'] > ($tPosted + ($iTimeout * 86400))))
	{
		Msg('This poll is closed.');
	}

	// What answer(s) are they giving?
	if($bMultipleChoices)
	{
		$aAnswerID = $_REQUEST['answer'];
	}
	else
	{
		$iAnswerID = (int)$_REQUEST['answer'];
	}

	// Extract the answers.
	$aAnswers = unserialize($strAnswers);

	// Have we already voted in this poll?
	$dbConn->query("SELECT id FROM pollvote WHERE ownerid={$_SESSION['userid']} AND parent={$iPollID}");
	if($dbConn->getresult())
	{
		// Yes. Let them know the bad news.
		Msg('You have already voted in this poll.');
	}

	// Is the specified answer(s) valid?
	if($bMultipleChoices)
	{
		while(list($iAnswerID) = each($aAnswerID))
		{
			if(array_key_exists($iAnswerID, $aAnswers))
			{
				// Cast the vote.
				$dbConn->query("INSERT INTO pollvote(parent, ownerid, vote, votedate) VALUES({$iPollID}, {$_SESSION['userid']}, {$iAnswerID}, {$CFG['globaltime']})");

			}
		}
	}
	else
	{
		if(array_key_exists($iAnswerID, $aAnswers))
		{
			// Cast the vote.
			$dbConn->query("INSERT INTO pollvote(parent, ownerid, vote, votedate) VALUES({$iPollID}, {$_SESSION['userid']}, {$iAnswerID}, {$CFG['globaltime']})");
		}
	}

	// Render the page
	Msg("<b>Thank you for voting.</b><br /><br /><span class=\"smaller\">You should be redirected momentarily. Click <a href=\"thread.php?threadid={$iPollID}\">here</a> if you do not want to wait any longer or if you are not redirected.</span>", "thread.php?threadid={$iPollID}");
}

// *************************************************************************** \\

// Make a new poll.
function NewPoll()
{
	global $CFG, $dbConn;

	// Do they have authorization to make polls?
	if(!$_SESSION['permissions']['cmakepolls'])
	{
		// No. Let them know the bad news.
		Unauthorized();
	}

	// Default values.
	$bParseURLs = FALSE;
	$bMultipleChoices = FALSE;

	// What thread is this poll going to?
	$iThreadID = (int)$_REQUEST['threadid'];

	// How many choices do they want?
	$iNumberChoices = (int)$_REQUEST['numchoices'];
	if(($iNumberChoices < 2) || ($iNumberChoices > $CFG['maxlen']['pollchoices']))
	{
		// Number of choices they want isn't in the acceptable range. Give them a default of four.
		$iNumberChoices = 4;
	}

	// Are they submitting?
	if($_REQUEST['submit'] == 'Submit Poll')
	{
		// Validate and post poll.
		$aError = ValidatePoll($iThreadID);
	}

	// Get information on the thread.
	$dbConn->query("SELECT thread.author, thread.title, thread.visible, thread.closed, thread.poll, board.id, board.name, cat.id, cat.name FROM thread LEFT JOIN board ON (thread.parent=board.id) LEFT JOIN board AS cat ON (board.parent = cat.id) WHERE thread.id={$iThreadID}");
	if(!(list($iThreadAuthorID, $strThreadTitle, $bThreadVisible, $bThreadClosed, $bHasPoll, $iForumID, $strForumName, $iCategoryID, $strCategoryName) = $dbConn->getresult()))
	{
		Msg("Invalid thread specified.{$CFG['msg']['invalidlink']}");
	}

	// Make sure we're the author and the thread is marked for a poll.
	if(($iThreadAuthorID != $_SESSION['userid']) || (!$bHasPoll))
	{
		Unauthorized();
	}

	// Make sure the thread doesn't already have a poll.
	$dbConn->query("SELECT COUNT(*) FROM poll WHERE id={$iThreadID}");
	list($bReallyHasPoll) = $dbConn->getresult();
	if($bReallyHasPoll)
	{
		Msg('The thread specified already has a poll.');
	}

	// Template
	require("./skins/{$CFG['skin']}/newpoll.tpl.php");

	// Send the page.
	exit;
}

// *************************************************************************** \\

function ValidatePoll($iThreadID)
{
	global $CFG, $dbConn;

	// Get the values from the user.
	$strQuestion = $_REQUEST['question'];
	$aChoices = (array)$_REQUEST['choice'];
	$bMultipleChoices = (int)(bool)$_REQUEST['multiplechoices'];
	$iTimeout = (int)$_REQUEST['timeout'];

	// Question
	if(trim($strQuestion) == '')
	{
		// They either put in only whitespace or nothing at all.
		$aError[] = 'You must specify a question.';
	}
	else if(strlen($strQuestion) > $CFG['maxlen']['pollquestion'])
	{
		// The question they specified is too long.
		$aError[] = "The question you specified is longer than {$CFG['maxlen']['pollquestion']} characters.";
	}
	$strQuestion = $dbConn->sanitize($strQuestion);

	// Choices
	if(count($aChoices))
	{
		// Clean up the list of choices.
		while(list($iChoiceID) = each($aChoices))
		{
			$aChoices[$iChoiceID] = trim($aChoices[$iChoiceID]);
			if($aChoices[$iChoiceID] != '')
			{
				if(strlen($aChoices[$iChoiceID]) < $CFG['maxlen']['pollchoice'])
				{
					$aTemp[] = $aChoices[$iChoiceID];
				}
				else
				{
					// The choice they specified is too long.
					$aError[] = "A choice you specified is longer than {$CFG['maxlen']['pollchoice']} characters.";
				}
			}
		}
		$aChoices = $aTemp;
		unset($aTemp);

		// Right number?
		if(count($aChoices) < 2)
		{
			// Not enough choices given.
			$aError[] = 'You must specify at least two choices.';
		}
		else if(count($aChoices) > $CFG['maxlen']['pollchoices'])
		{
			// Too many choices given.
			$aError[] = "The maximum number of choices is {$CFG['maxlen']['pollchoices']}.";
		}
		else
		{
			$strChoices = $dbConn->sanitize(serialize($aChoices));
		}
	}
	else
	{
		// No choices given.
		$aError[] = 'You must specify at least two choices.';
	}

	// Timeout
	if(($iTimeout < 0) || ($iTimeout > 65535))
	{
		// They don't know what timeout they want. We'll give them none.
		$iTimeout = 0;
	}

	// If there was an error, let's return it.
	if(is_array($aError))
	{
		return $aError;
	}

	// Get information on the thread.
	$dbConn->query("SELECT author, visible, closed, poll FROM thread WHERE id={$iThreadID}");
	if(!(list($iThreadAuthorID, $bThreadVisible, $bThreadClosed, $bHasPoll) = $dbConn->getresult()))
	{
		Msg("Invalid thread specified.{$CFG['msg']['invalidlink']}");
	}

	// Make sure we're the author and the thread is marked for a poll.
	if(($iThreadAuthorID != $_SESSION['userid']) || (!$bHasPoll))
	{
		Unauthorized();
	}

	// Make sure the thread doesn't already have a poll.
	$dbConn->query("SELECT COUNT(*) FROM poll WHERE id={$iThreadID}");
	list($bReallyHasPoll) = $dbConn->getresult();
	if($bReallyHasPoll)
	{
		Msg('The thread specified already has a poll.');
	}

	// What is the forum we're in?
	$dbConn->query("SELECT parent FROM thread WHERE id={$iThreadID}");
	list($iForumID) = $dbConn->getresult();

	// Save the poll to the database.
	$dbConn->query("INSERT INTO poll(id, datetime, question, answers, multiplechoices, timeout) VALUES({$iThreadID}, {$CFG['globaltime']}, '{$strQuestion}', '{$strChoices}', {$bMultipleChoices}, {$iTimeout})");

	// Finish "submitting" the thread this poll belongs to.
	$dbConn->query("UPDATE thread SET poll=1, closed=0, visible=1 WHERE id={$iThreadID}");
	$dbConn->query("UPDATE board SET postcount=postcount+1, threadcount=threadcount+1, lpost={$CFG['globaltime']}, lposter={$_SESSION['userid']}, lthread={$iThreadID}, lthreadpcount=1 WHERE id={$iForumID}");
	$dbConn->query("UPDATE citizen SET postcount=postcount+1 WHERE id={$_SESSION['userid']}");

	// Update the forum stats.
	$dbConn->query("UPDATE stats SET content=content+1 WHERE name IN ('postcount', 'threadcount')");

	// Render page.
	Msg("<b>Thank you for posting.</b><br /><br /><span class=\"smaller\">You should be redirected momentarily. Click <a href=\"thread.php?threadid={$iThreadID}\">here</a> if you do not want to wait any longer or if you are not redirected.</span>", "thread.php?threadid={$iThreadID}");
}
?>