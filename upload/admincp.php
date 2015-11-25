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

	// Is the user authorized to access the Admin CP?
	if(!$_SESSION['permissions']['cviewadmincp'])
	{
		Unauthorized();
	}

	// What section do they want to view?
	switch($_REQUEST['section'])
	{
		case 'style':
		{
			$strSection = $_REQUEST['section'];
			Style();
		}

		case 'skins':
		{
			$strSection = $_REQUEST['section'];
			Skins();
		}

		case 'forums':
		{
			$strSection = $_REQUEST['section'];
			Forums();
		}

		case 'attachments':
		{
			$strSection = $_REQUEST['section'];
			Attachments();
		}

		case 'usergroups':
		{
			$strSection = $_REQUEST['section'];
			Usergroups();
		}

		case 'avatars':
		{
			$strSection = $_REQUEST['section'];
			Avatars();
		}

		case 'smilies':
		{
			$strSection = $_REQUEST['section'];
			Smilies();
		}

		case 'posticons':
		{
			$strSection = $_REQUEST['section'];
			PostIcons();
		}

		case 'censored':
		{
			$strSection = $_REQUEST['section'];
			CensoredWords();
		}

		case 'general':
		default:
		{
			$strSection = 'general';
			General();
		}
	}

// *************************************************************************** \\

function General()
{
	global $CFG, $dbConn;

	// Load the current settings verbatim from the database.
	$dbConn->query("SELECT content FROM configuration WHERE name='settings'");
	list($strValue) = $dbConn->getresult();
	$aConfigs = unserialize($strValue);

	// Are they coming for the first time or submitting information?
	if(isset($_REQUEST['submit']))
	{
		// Submitting information, so store it.
		$aOptions['name'] = trim($_REQUEST['name']);
		$aOptions['copyright'] = trim($_REQUEST['copyright']);
		$aOptions['email'] = trim($_REQUEST['email']);
		$aOptions['enablegzip'] = (int)(bool)$_REQUEST['enablegzip'];
		$aOptions['gziplevel'] = (int)$_REQUEST['gziplevel'];
		$aOptions['bufferoutput'] = (int)(bool)$_REQUEST['bufferoutput'];
		$aOptions['timeoffset'] = (int)$_REQUEST['timeoffset'];
		$aOptions['dst'] = (int)(bool)$_REQUEST['dst'];
		$aOptions['dsth'] = abs((int)$_REQUEST['dsth']);
		$aOptions['dstm'] = abs((int)$_REQUEST['dstm']);
		$aOptions['weekstart'] = abs((int)$_REQUEST['weekstart']);
		$aOptions['quickreply'] = (int)(bool)$_REQUEST['quickreply'];
		$aOptions['avatarspath'] = trim($_REQUEST['avatarspath']);
		$aOptions['smiliespath'] = trim($_REQUEST['smiliespath']);
		$aOptions['posticonspath'] = trim($_REQUEST['posticonspath']);
		$aOptions['cookiespath'] = trim($_REQUEST['cookiespath']);
		$aOptions['threadview'] = abs((int)$_REQUEST['threadview']);
		$aOptions['postsperpage'] = abs((int)$_REQUEST['postsperpage']);
		$aOptions['threadsperpage'] = abs((int)$_REQUEST['threadsperpage']);
		$aOptions['showimages'] = (int)(bool)$_REQUEST['showimages'];
		$aOptions['showqueries'] = (int)(bool)$_REQUEST['showqueries'];
		$aOptions['showerrors'] = (int)(bool)$_REQUEST['showerrors'];
		$aOptions['iplogging'] = (int)(bool)$_REQUEST['iplogging'];
		$aOptions['floodcheck'] = abs((int)$_REQUEST['floodcheck']);
		$aOptions['captcha'] = abs((int)$_REQUEST['captcha']);
		$aOptions['invalidlink'] = trim($_REQUEST['invalidlink']);

		// Validate the information, and submit it to the database if everything's okay.
		$aError = ValidateGeneral($aOptions, $aConfigs);
	}
	else
	{
		// Coming for the first time, so set the defaults.
		$aOptions['name'] = $aConfigs['general']['name'];
		$aOptions['copyright'] = $aConfigs['general']['copyright'];
		$aOptions['email'] = $aConfigs['general']['admin']['email'];
		$aOptions['enablegzip'] = (bool)$aConfigs['general']['gzip']['enabled'];
		$aOptions['gziplevel'] = $aConfigs['general']['gzip']['level'];
		$aOptions['bufferoutput'] = (bool)$aConfigs['bufferoutput'];
		$aOptions['timeoffset'] = $aConfigs['time']['display_offset'];
		$aOptions['dst'] = (bool)$aConfigs['time']['dst'];
		$aOptions['dsth'] = floor($aConfigs['time']['dst_offset'] / 3600);
		$aOptions['dstm'] = ($aConfigs['time']['dst_offset'] - ($aOptions['dsth'] * 3600)) / 60;
		$aOptions['weekstart'] = $aConfigs['default']['weekstart'];
		$aOptions['quickreply'] = (bool)$aConfigs['general']['quickreply'];
		$aOptions['avatarspath'] = trim($aConfigs['paths']['avatars']);
		$aOptions['smiliespath'] = trim($aConfigs['paths']['smilies']);
		$aOptions['posticonspath'] = trim($aConfigs['paths']['posticons']);
		$aOptions['cookiespath'] = trim($aConfigs['paths']['cookies']);
		$aOptions['threadview'] = $aConfigs['default']['threadview'];
		$aOptions['postsperpage'] = $aConfigs['default']['postsperpage'];
		$aOptions['threadsperpage'] = $aConfigs['default']['threadsperpage'];
		$aOptions['showimages'] = (bool)$aConfigs['parsing']['showimages'];
		$aOptions['showqueries'] = (bool)$aConfigs['showqueries'];
		$aOptions['showerrors'] = (bool)$aConfigs['showerrors'];
		$aOptions['iplogging'] = (bool)$aConfigs['iplogging'];
		$aOptions['floodcheck'] = $aConfigs['floodcheck'];
		if($aConfigs['reg']['verify_img'] && $aConfigs['reg']['email'])
		{
			$aOptions['captcha'] = 3;
		}
		else if($aConfigs['reg']['verify_img'])
		{
			$aOptions['captcha'] = 1;
		}
		else if($aConfigs['reg']['email'])
		{
			$aOptions['captcha'] = 2;
		}
		else
		{
			$aOptions['captcha'] = 0;
		}
		$aOptions['invalidlink'] = trim($aConfigs['msg']['invalidlink']);
	}

	// Template
	require("./skins/{$CFG['skin']}/admincp/general.tpl.php");

	// Send the page.
	exit;
}

function ValidateGeneral($aOptions, $aConfigs)
{
	global $CFG, $dbConn;

	// Forum name
	if($aOptions['name'] == '')
	{
		// They didn't specify a forum name.
		$aError[] = 'You must specify a name for your forums.';
	}
	$aConfigs['general']['name'] = $aOptions['name'];

	// Copyright notice
	if($aOptions['copyright'] == '')
	{
		// They didn't specify a copyright notice.
		$aError[] = 'You must specify text for a copyright notice.';
	}
	$aConfigs['general']['copyright'] = $aOptions['copyright'];

	// Admin e-mail
	if($aOptions['email'] == '')
	{
		// They didn't specify an admin e-mail.
		$aError[] = 'You must specify an administrator\'s e-mail address.';
	}
	$aConfigs['general']['admin']['email'] = $aOptions['email'];

	// GZip level
	if(($aOptions['enablegzip']) && (($aOptions['gziplevel'] < 0) || ($aOptions['gziplevel'] > 9)))
	{
		// They specified an invalid GZip compression level.
		$aError[] = 'You specified an invalid value for the compression level. Valid ranges are 0 - 9.';
	}
	$aConfigs['general']['gzip']['level'] = $aOptions['gziplevel'];

	// Avatars folder
	if(!is_dir($aOptions['avatarspath']))
	{
		// Invalid path.
		$aError[] = 'You specified an invalid path for the avatars folder.';
	}
	$aConfigs['paths']['avatars'] = $aOptions['avatarspath'];

	// Smilies folder
	if(!is_dir($aOptions['smiliespath']))
	{
		// Invalid path.
		$aError[] = 'You specified an invalid path for the smilies folder.';
	}
	$aConfigs['paths']['smilies'] = $aOptions['smiliespath'];

	// Post icons folder
	if(!is_dir($aOptions['posticonspath']))
	{
		// Invalid path.
		$aError[] = 'You specified an invalid path for the post icons folder.';
	}
	$aConfigs['paths']['posticons'] = $aOptions['posticonspath'];

	// Did we get any errors?
	if($aError)
	{
		return $aError;
	}

	// Cookies folder
	$aConfigs['paths']['cookies'] = $aOptions['cookiespath'];

	// Enable GZip?
	$aConfigs['general']['gzip']['enabled'] = $aOptions['enablegzip'];

	// Buffer output?
	$aConfigs['bufferoutput'] = $aOptions['bufferoutput'];

	// Time offset
	$aConfigs['time']['display_offset'] = $aOptions['timeoffset'];

	// DST
	$aConfigs['time']['dst'] = $aOptions['dst'];

	// DST offset
	$aConfigs['time']['dst_offset'] = ($aOptions['dsth'] * 3600) + ($aOptions['dstm'] * 60);

	// Week start
	$aConfigs['default']['weekstart'] = $aOptions['weekstart'];

	// Defaults
	$aConfigs['default']['threadview'] = $aOptions['threadview'];
	if($aOptions['threadview'] > 1000)
	{
		$aOptions['threadview'] = 1000;
	}
	$aConfigs['default']['postsperpage'] = $aOptions['postsperpage'];
	$aConfigs['default']['threadsperpage'] = $aOptions['threadsperpage'];

	// Enable Quick Reply?
	$aConfigs['general']['quickreply'] = $aOptions['quickreply'];

	// Show [img] tags as images?
	$aConfigs['parsing']['showimages'] = $aOptions['showimages'];

	// Show SQL queries?
	$aConfigs['showqueries'] = $aOptions['showqueries'];

	// Show PHP errors?
	$aConfigs['showerrors'] = $aOptions['showerrors'];

	// Log IP addresses?
	$aConfigs['iplogging'] = $aOptions['iplogging'];

	// Floodcheck
	$aConfigs['floodcheck'] = $aOptions['floodcheck'];

	// Use CAPTCHA image during user registration?
	$aConfigs['reg']['verify_img'] = (($aOptions['captcha'] == 1) || ($aOptions['captcha'] == 3)) ? 1 : 0;

	// Use e-mail validation during user registration?
	$aConfigs['reg']['email'] = ($aOptions['captcha'] >= 2) ? 1 : 0;

	// Invalid link text
	if($aOptions['invalidlink'] != '')
	{
		$aConfigs['msg']['invalidlink'] = " {$aOptions['invalidlink']}";
	}

	// Serialize and sanitize the new settings.
	$strSettings = $dbConn->sanitize(serialize($aConfigs));

	// Store the new settings.
	$dbConn->query("UPDATE configuration SET content='{$strSettings}' WHERE name='settings'");

	// Let the user know it was a success.
	Msg("<b>The forum's General settings have been updated.</b><br /><br /><span class=\"smaller\">You should be redirected to the Admin Control Panel momentarily. Click <a href=\"admincp.php\">here</a><br />if you do not want to wait any longer or if you are not redirected.</span>", 'admincp.php');
}

// *************************************************************************** \\

function Style()
{
	global $CFG, $dbConn;

	// Load the current settings verbatim from the database.
	$dbConn->query("SELECT content FROM configuration WHERE name='settings'");
	list($strValue) = $dbConn->getresult();
	$aConfigs = unserialize($strValue);

	// Load the current skins verbatim from the database.
	$dbConn->query("SELECT content FROM configuration WHERE name='skins'");
	list($strValue) = $dbConn->getresult();
	$aSkins = unserialize($strValue);

	// Are they coming for the first time or submitting information?
	if(isset($_REQUEST['submit']) || isset($_REQUEST['editskins']))
	{
		// Submitting information, so store it.
		$aStyles['skin'] = (int)$_REQUEST['skinid'];
		$aStyles['page_bgcolor'] = trim($_REQUEST['page_bgcolor']);
		$aStyles['forum_bgcolor'] = trim($_REQUEST['forum_bgcolor']);
		$aStyles['forum_txtcolor'] = trim($_REQUEST['forum_txtcolor']);
		$aStyles['table_bgcolor'] = trim($_REQUEST['table_bgcolor']);
		$aStyles['table_cella'] = trim($_REQUEST['table_cella']);
		$aStyles['table_cellb'] = trim($_REQUEST['table_cellb']);
		$aStyles['table_width'] = trim($_REQUEST['table_width']);
		$aStyles['content_width'] = trim($_REQUEST['content_width']);
		$aStyles['heading_bgcolor'] = trim($_REQUEST['heading_bgcolor']);
		$aStyles['heading_txtcolor'] = trim($_REQUEST['heading_txtcolor']);
		$aStyles['section_bgcolor'] = trim($_REQUEST['section_bgcolor']);
		$aStyles['section_txtcolor'] = trim($_REQUEST['section_txtcolor']);
		$aStyles['time'] = trim($_REQUEST['time']);
		$aStyles['errors'] = trim($_REQUEST['errors']);
		$aStyles['credits'] = trim($_REQUEST['credits']);
		$aStyles['stats'] = trim($_REQUEST['stats']);
		$aStyles['stats_bold'] = trim($_REQUEST['stats_bold']);
		$aStyles['cal_datea_bgcolor'] = trim($_REQUEST['cal_datea_bgcolor']);
		$aStyles['cal_datea_txtcolor'] = trim($_REQUEST['cal_datea_txtcolor']);
		$aStyles['cal_dateb_bgcolor'] = trim($_REQUEST['cal_dateb_bgcolor']);
		$aStyles['cal_dateb_txtcolor'] = trim($_REQUEST['cal_dateb_txtcolor']);
		$aStyles['cal_today_bgcolor'] = trim($_REQUEST['cal_today_bgcolor']);
		$aStyles['cal_today_txtcolor'] = trim($_REQUEST['cal_today_txtcolor']);
		$aStyles['link_l'] = trim($_REQUEST['link_l']);
		$aStyles['link_v'] = trim($_REQUEST['link_v']);
		$aStyles['link_a'] = trim($_REQUEST['link_a']);
		$aStyles['link_h'] = trim($_REQUEST['link_h']);

		// Validate the information, and submit it to the database if everything's okay.
		$aError = ValidateStyle($aStyles, $aConfigs);
	}
	else
	{
		// Coming for the first time, so set the defaults.
		$aStyles['skin'] = $aConfigs['skin'];
		$aStyles['page_bgcolor'] = $aConfigs['style']['page']['bgcolor'];
		$aStyles['forum_bgcolor'] = $aConfigs['style']['forum']['bgcolor'];
		$aStyles['forum_txtcolor'] = $aConfigs['style']['forum']['txtcolor'];
		$aStyles['table_bgcolor'] = $CFG['style']['table']['bgcolor'];
		$aStyles['table_cella'] = $aConfigs['style']['table']['cella'];
		$aStyles['table_cellb'] = $aConfigs['style']['table']['cellb'];
		$aStyles['table_width'] = $aConfigs['style']['table']['width'];
		$aStyles['content_width'] = $aConfigs['style']['content_table']['width'];
		$aStyles['heading_bgcolor'] = $aConfigs['style']['table']['heading']['bgcolor'];
		$aStyles['heading_txtcolor'] = $aConfigs['style']['table']['heading']['txtcolor'];
		$aStyles['section_bgcolor'] = $aConfigs['style']['table']['section']['bgcolor'];
		$aStyles['section_txtcolor'] = $aConfigs['style']['table']['section']['txtcolor'];
		$aStyles['time'] = $aConfigs['style']['table']['timecolor'];
		$aStyles['errors'] = $aConfigs['style']['errors'];
		$aStyles['credits'] = $aConfigs['style']['credits'];
		$aStyles['stats'] = $aConfigs['style']['stats'];
		$aStyles['stats_bold'] = $aConfigs['style']['stats_bold'];
		$aStyles['cal_datea_bgcolor'] = $aConfigs['style']['calcolor']['datea']['bgcolor'];
		$aStyles['cal_datea_txtcolor'] = $aConfigs['style']['calcolor']['datea']['txtcolor'];
		$aStyles['cal_dateb_bgcolor'] = $aConfigs['style']['calcolor']['dateb']['bgcolor'];
		$aStyles['cal_dateb_txtcolor'] = $aConfigs['style']['calcolor']['dateb']['txtcolor'];
		$aStyles['cal_today_bgcolor'] = $aConfigs['style']['calcolor']['today']['bgcolor'];
		$aStyles['cal_today_txtcolor'] = $aConfigs['style']['calcolor']['today']['txtcolor'];
		$aStyles['link_l'] = $aConfigs['style']['l_normal']['l'];
		$aStyles['link_v'] = $aConfigs['style']['l_normal']['v'];
		$aStyles['link_a'] = $aConfigs['style']['l_normal']['a'];
		$aStyles['link_h'] = $aConfigs['style']['l_normal']['h'];
	}

	// Template
	require("./skins/{$CFG['skin']}/admincp/style.tpl.php");

	// Send the page.
	exit;
}

// TODO: Maybe we should validate skins, colors, and widths?
function ValidateStyle($aStyles, $aConfigs)
{
	global $CFG, $dbConn;

	$aConfigs['skin'] = $aStyles['skin'];
	$aConfigs['style']['page']['bgcolor'] = $aStyles['page_bgcolor'];
	$aConfigs['style']['forum']['bgcolor'] = $aStyles['forum_bgcolor'];
	$aConfigs['style']['forum']['txtcolor'] = $aStyles['forum_txtcolor'];
	$aConfigs['style']['table']['bgcolor'] = $aStyles['table_bgcolor'];
	$aConfigs['style']['table']['cella'] = $aStyles['table_cella'];
	$aConfigs['style']['table']['cellb'] = $aStyles['table_cellb'];
	$aConfigs['style']['table']['width'] = $aStyles['table_width'];
	$aConfigs['style']['content_table']['width'] = $aStyles['content_width'];
	$aConfigs['style']['table']['heading']['bgcolor'] = $aStyles['heading_bgcolor'];
	$aConfigs['style']['table']['heading']['txtcolor'] = $aStyles['heading_txtcolor'];
	$aConfigs['style']['table']['section']['bgcolor'] = $aStyles['section_bgcolor'];
	$aConfigs['style']['table']['section']['txtcolor'] = $aStyles['section_txtcolor'];
	$aConfigs['style']['table']['timecolor'] = $aStyles['time'];
	$aConfigs['style']['errors'] = $aStyles['errors'];
	$aConfigs['style']['credits'] = $aStyles['credits'];
	$aConfigs['style']['stats'] = $aStyles['stats'];
	$aConfigs['style']['stats_bold'] = $aStyles['stats_bold'];
	$aConfigs['style']['calcolor']['datea']['bgcolor'] = $aStyles['cal_datea_bgcolor'];
	$aConfigs['style']['calcolor']['datea']['txtcolor'] = $aStyles['cal_datea_txtcolor'];
	$aConfigs['style']['calcolor']['dateb']['bgcolor'] = $aStyles['cal_dateb_bgcolor'];
	$aConfigs['style']['calcolor']['dateb']['txtcolor'] = $aStyles['cal_dateb_txtcolor'];
	$aConfigs['style']['calcolor']['today']['bgcolor'] = $aStyles['cal_today_bgcolor'];
	$aConfigs['style']['calcolor']['today']['txtcolor'] = $aStyles['cal_today_txtcolor'];
	$aConfigs['style']['l_normal']['l'] = $aStyles['link_l'];
	$aConfigs['style']['l_normal']['v'] = $aStyles['link_v'];
	$aConfigs['style']['l_normal']['a'] = $aStyles['link_a'];
	$aConfigs['style']['l_normal']['h'] = $aStyles['link_h'];

	// Serialize and sanitize the new settings.
	$strSettings = $dbConn->sanitize(serialize($aConfigs));

	// Store the new settings.
	$dbConn->query("UPDATE configuration SET content='{$strSettings}' WHERE name='settings'");

	// Let the user know it was a success.
	$strRedirect = ($_REQUEST['editskins']) ? 'admincp.php?section=skins' : 'admincp.php';
	Msg("<b>The forum's style has been updated.</b><br /><br /><span class=\"smaller\">You should be redirected to the Admin Control Panel momentarily. Click <a href=\"{$strRedirect}\">here</a><br />if you do not want to wait any longer or if you are not redirected.</span>", $strRedirect);
}

// *************************************************************************** \\

function Skins()
{
	global $CFG, $dbConn;

	// Load the current skins verbatim from the database.
	$dbConn->query("SELECT content FROM configuration WHERE name='skins'");
	list($aSkins) = $dbConn->getresult();
	$aSkins = unserialize($aSkins);

	// What are they wanting to do?
	switch($_REQUEST['action'])
	{
		case 'add':
		{
			AddSkin($aSkins);
		}

		case 'remove':
		{
			RemoveSkin($aSkins);
		}

		case 'edit':
		{
			EditSkin($aSkins);
		}
	}

	// Template
	require("./skins/{$CFG['skin']}/admincp/skins.tpl.php");

	// Send the page.
	exit;
}

// User wants to add a new skin.
function AddSkin($aSkins)
{
	global $CFG;

	// Are they coming for the first time or submitting information?
	if(isset($_REQUEST['submit']))
	{
		// Submitting information, so store it.
		$aSkin['title'] = trim($_REQUEST['title']);
		$aSkin['folder'] = trim($_REQUEST['folder']);

		// Validate the information, and submit it to the database if everything's okay.
		$aError = AddSkinNow($aSkin, $aSkins);
	}

	// Template
	require("./skins/{$CFG['skin']}/admincp/addskin.tpl.php");

	// Send the page.
	exit;
}

// Adds a new skin.
function AddSkinNow($aSkin, $aSkins)
{
	global $CFG, $dbConn;

	// Validate the skin's information.
	list($aSkin, $aError) = ValidateSkin($aSkin);

	// Did we get any errors?
	if($aError)
	{
		return $aError;
	}

	// Add the new avatar.
	$aSkins[] = $aSkin;
	$strSkins = $dbConn->sanitize(serialize($aSkins));
	$dbConn->query("UPDATE configuration SET content='{$strSkins}' WHERE name='skins'");

	// Let the user know it was a success.
	Msg("<b>Skin successfully added.</b><br /><br /><span class=\"smaller\">You should be redirected to the Admin Control Panel momentarily. Click <a href=\"admincp.php?section=skins\">here</a><br />if you do not want to wait any longer or if you are not redirected.</span>", 'admincp.php?section=skins');
}

// User wants to edit a skin.
function EditSkin($aSkins)
{
	global $CFG;

	// Which skin do they want to edit?
	$aSkin['id'] = (int)$_REQUEST['skinid'];

	// Does the skin exist?
	if(!is_array($aSkins[$aSkin['id']]))
	{
		Msg("Invalid skin specified.{$CFG['msg']['invalidlink']}");
	}

	// Are they coming for the first time or submitting information?
	if(isset($_REQUEST['submit']))
	{
		// Submitting information, so store it.
		$aSkin['title'] = trim($_REQUEST['title']);
		$aSkin['folder'] = trim($_REQUEST['folder']);

		// Validate the information, and submit it to the database if everything's okay.
		$aError = EditSkinNow($aSkin, $aSkins);
	}
	else
	{
		// Coming for the first time, so get the skin's data.
		$aSkin = array_merge($aSkin, $aSkins[$aSkin['id']]);
	}

	// Template
	require("./skins/{$CFG['skin']}/admincp/editskin.tpl.php");

	// Send the page.
	exit;
}

// Edits a skin.
function EditSkinNow($aSkin, $aSkins)
{
	global $CFG, $dbConn;

	// Grab the skin ID.
	$iSkinID = $aSkin['id'];
	unset($aSkin['id']);

	// Validate skin's information.
	list($aSkin, $aError) = ValidateSkin($aSkin);

	// Did we get any errors?
	if($aError)
	{
		return $aError;
	}

	// Replace the obsolete skin information with the new information.
	$aSkins[$iSkinID] = $aSkin;

	// Update the skins.
	$strSkins = $dbConn->sanitize(serialize($aSkins));
	$dbConn->query("UPDATE configuration SET content='{$strSkins}' WHERE name='skins'");

	// Let the user know it was a success.
	Msg("<b>Skin successfully updated.</b><br /><br /><span class=\"smaller\">You should be redirected to the Admin Control Panel momentarily. Click <a href=\"admincp.php?section=skins\">here</a><br />if you do not want to wait any longer or if you are not redirected.</span>", 'admincp.php?section=skins');
}

// Validates a skin's information to ensure it conforms.
function ValidateSkin($aSkin)
{
	global $CFG;

	// Title
	if($aSkin['title'] == '')
	{
		$aError[] = 'You must specify a title for the skin.';
	}
	else if(strlen($aSkin['title']) > 255)
	{
		$aError[] = 'The skin title is longer than 255 characters.';
	}

	// Folder
	if($aSkin['folder'] == '')
	{
		$aError[] = 'You must specify the folder of the skin.';
	}
	else if(!is_dir("skins/{$aSkin['folder']}"))
	{
		$aError[] = 'The folder you specified does not exist. Make sure you have uploaded it to your skins folder.';
	}

	// Return the skin's information and any errors we encountered.
	return array($aSkin, $aError);
}

// User wants to remove a skin.
function RemoveSkin($aSkins)
{
	global $CFG;

	// What skin do they want to delete?
	$iSkinID = (int)$_REQUEST['skinid'];

	// Does the skin exist?
	if(!is_array($aSkins[$iSkinID]))
	{
		Msg("Invalid skin specified.{$CFG['msg']['invalidlink']}");
	}

	// Are they submitting?
	if((bool)$_REQUEST['removeskin'] && isset($_REQUEST['submit']))
	{
		// Yes, so remove the skin.
		RemoveSkinNow($iSkinID, $aSkins);
	}

	// Template
	require("./skins/{$CFG['skin']}/admincp/removeskin.tpl.php");

	// Send the page.
	exit;
}

// Removes the specified skin.
function RemoveSkinNow($iSkinID, $aSkins)
{
	global $CFG, $dbConn;

	// Update the skins.
	unset($aSkins[$iSkinID]);
	$strSkins = $dbConn->sanitize(serialize($aSkins));
	$dbConn->query("UPDATE configuration SET content='{$strSkins}' WHERE name='skins'");

	// Let the user know it was a success.
	Msg("<b>The skin was removed successfully.</b><br /><br /><span class=\"smaller\">You should be redirected to the Admin Control Panel momentarily. Click <a href=\"admincp.php?section=skins\">here</a><br />if you do not want to wait any longer or if you are not redirected.</span>", 'admincp.php?section=skins');
}

// *************************************************************************** \\

function Forums()
{
	global $CFG, $dbConn;

	// Constants
	define('NAME',       0);
	define('DISPORDER',  1);
	define('PARENT',     2);

	// What are they wanting to do?
	switch($_REQUEST['action'])
	{
		case 'add':
		{
			AddForum();
		}

		case 'remove':
		{
			RemoveForum();
		}

		case 'edit':
		{
			EditForum();
		}

		case 'update':
		{
			UpdateForums();
		}
	}

	// Get the information of the forums.
	$dbConn->query("SELECT id, displaydepth, parent, disporder, name FROM board ORDER BY disporder ASC");
	while($aSQLResult = $dbConn->getresult())
	{
		// Is this a 'Level 0' or a 'Level 1' forum?
		switch($aSQLResult[1])
		{
			// Level 0: Category
			case 0:
			{
				// Store the category information into the Category array.
				$iCategoryID = $aSQLResult[0];
				$aCategory[$iCategoryID][DISPORDER] = $aSQLResult[3];
				$aCategory[$iCategoryID][NAME] = $aSQLResult[4];
				break;
			}

			// Level 1: Forum
			case 1:
			{
				// Store the forum information into the Forum array.
				$iForumID = $aSQLResult[0];
				$aForum[$iForumID][PARENT] = $aSQLResult[2];
				$aForum[$iForumID][DISPORDER] = $aSQLResult[3];
				$aForum[$iForumID][NAME] = $aSQLResult[4];
				break;
			}
		}
	}

	// Template
	require("./skins/{$CFG['skin']}/admincp/forums.tpl.php");

	// Send the page.
	exit;
}

// User wants to add a new forum.
function AddForum()
{
	global $CFG;

	// Are they coming for the first time or submitting information?
	if(isset($_REQUEST['submit']))
	{
		// Submitting information, so store it.
		$aForum['title'] = trim($_REQUEST['title']);
		$aForum['description'] = trim($_REQUEST['description']);
		$aForum['displayorder'] = (int)$_REQUEST['displayorder'];
		$aForum['parent'] = (int)$_REQUEST['parent'];

		// Validate the information, and submit it to the database if everything's okay.
		$aError = AddForumNow($aForum);
	}

	// Get the forums.
	list($aForums) = GetForumInfo();

	// Template
	require("./skins/{$CFG['skin']}/admincp/addforum.tpl.php");

	// Send the page.
	exit;
}

// Adds a new forum.
// TODO: Input is only sanitized; orders need to be cleaned up.
function AddForumNow($aForum)
{
	global $CFG, $dbConn;

	// Validate forum's information.
	list($aForum, $aError) = ValidateForum($aForum);

	// Did we get any errors?
	if($aError)
	{
		return $aError;
	}

	// Are we a category or a forum?
	if($aForum['parent'])
	{
		$aForum['displaydepth'] = 1;
	}
	else
	{
		$aForum['displaydepth'] = 0;
	}

	// Add the new forum.
	$dbConn->query("INSERT INTO board(disporder, name, description, displaydepth, parent) VALUES({$aForum['displayorder']}, '{$aForum['title']}', '{$aForum['description']}', {$aForum['displaydepth']}, {$aForum['parent']})");

	// Let the user know it was a success.
	Msg("<b>Forum successfully added.</b><br /><br /><span class=\"smaller\">You should be redirected to the Admin Control Panel momentarily. Click <a href=\"admincp.php?section=forums\">here</a><br />if you do not want to wait any longer or if you are not redirected.</span>", 'admincp.php?section=forums');
}

// User wants to remove a forum.
function RemoveForum()
{
	global $CFG, $dbConn;

	// What forum do they want to delete?
	$iForumID = (int)$_REQUEST['forumid'];

	// Get the forum's information.
	$dbConn->query("SELECT id FROM board WHERE id={$iForumID} OR parent={$iForumID}");
	while(list($iBoardID) = $dbConn->getresult())
	{
		$aForums[] = $iBoardID;
	}

	// Does the forum exist?
	if(!is_array($aForums))
	{
		Msg("Invalid forum specified.{$CFG['msg']['invalidlink']}");
	}

	// Are they submitting?
	if((bool)$_REQUEST['removeforum'] && isset($_REQUEST['submit']))
	{
		// Yes, so remove the forum(s).
		RemoveForumNow($aForums);
	}

	// Template
	require("./skins/{$CFG['skin']}/admincp/removeforum.tpl.php");

	// Send the page.
	exit;
}

// Removes the specified forum(s) and everything contained within.
function RemoveForumNow($aForums)
{
	global $dbConn;

	// Get ready to query.
	$strForums = implode(', ', $aForums);

	// Get a list of threads and posts in the forum(s) to be removed.
	$dbConn->query("SELECT post.id, post.author, thread.id, thread.visible FROM post LEFT JOIN thread ON (post.parent = thread.id) WHERE thread.parent IN ({$strForums})");
	while(list($iPostID, $iAuthorID, $iThreadID, $bVisible) = $dbConn->getresult())
	{
		// Save the post and thread to the lists.
		$aPosts[] = $iPostID;
		$aThreads[] = $iThreadID;

		// Is the thread visible?
		if($bVisible)
		{
			// Yes, so increment the post count for the post's author.
			$aPostCounts[$iAuthorID]++;

			// Increment the post count.
			$iPostCount++;

			// Have we already counted this thread?
			if((!isset($iThreadCount)) || (!in_array($iThreadID, $aThreads)))
			{
				// No, so increment the thread count.
				$iThreadCount++;
			}
		}
	}

	// Remove the forums.
	$dbConn->query("DELETE FROM board WHERE id IN ({$strForums})");

	// Remove the posts, attachments, and search indexes.
	if(is_array($aPosts))
	{
		$strPosts = implode(', ', array_unique($aPosts));
		$dbConn->query("DELETE FROM post WHERE id IN ({$strPosts})");
		$dbConn->query("DELETE FROM attachment WHERE parent IN ({$strPosts})");
		$dbConn->query("DELETE FROM searchindex WHERE postid IN ({$strPosts})");

		// Update the forum statistics.
		$dbConn->query("UPDATE stats SET content=content-{$iPostCount} WHERE name='postcount'");
	}

	// Remove the threads and polls.
	if(is_array($aThreads))
	{
		$strThreads = implode(', ', array_unique($aThreads));
		$dbConn->query("DELETE FROM thread WHERE id IN ({$strThreads})");
		$dbConn->query("DELETE FROM poll WHERE id IN ({$strThreads})");
		$dbConn->query("DELETE FROM pollvote WHERE parent IN ({$strThreads})");

		// Update the forum statistics.
		$dbConn->query("UPDATE stats SET content=content-{$iThreadCount} WHERE name='threadcount'");
	}

	// Update the authors' post counts.
	if(is_array($aPostCounts))
	{
		foreach($aPostCounts as $iAuthorID => $iPostCount)
		{
			$dbConn->query("UPDATE citizen SET postcount=postcount-{$iPostCount} WHERE id={$iAuthorID}");
		}
	}

	// Let the user know it was a success.
	Msg("<b>The forum was removed successfully.</b><br /><br /><span class=\"smaller\">You should be redirected to the Admin Control Panel momentarily. Click <a href=\"admincp.php?section=forums\">here</a><br />if you do not want to wait any longer or if you are not redirected.</span>", 'admincp.php?section=forums');
}

// User wants to edit forum.
function EditForum()
{
	global $CFG, $dbConn;

	// What forum do they want to edit?
	$aForum['id'] = (int)$_REQUEST['forumid'];

	// Get the forum's information.
	$dbConn->query("SELECT name, description, disporder, parent FROM board WHERE id={$aForum['id']}");
	list($aForum['title'], $aForum['description'], $aForum['displayorder'], $aForum['parent']) = $dbConn->getresult();

	// Does the forum exist?
	if(!$aForum['title'])
	{
		Msg("Invalid forum specified.{$CFG['msg']['invalidlink']}");
	}

	// Are they coming for the first time or submitting information?
	if(isset($_REQUEST['submit']))
	{
		// Submitting information, so store it.
		$aForum['title'] = trim($_REQUEST['title']);
		$aForum['description'] = trim($_REQUEST['description']);
		$aForum['displayorder'] = (int)$_REQUEST['displayorder'];
		$aForum['parent'] = (int)$_REQUEST['parent'];

		// Validate the information, and submit it to the database if everything's okay.
		$aError = EditForumNow($aForum);
	}

	// Get the forums.
	list($aForums) = GetForumInfo();

	// Template
	require("./skins/{$CFG['skin']}/admincp/editforum.tpl.php");

	// Send the page.
	exit;
}

// Edits a forum.
// TODO: Input is only sanitized; orders need to be cleaned up.
function EditForumNow($aForum)
{
	global $CFG, $dbConn;

	// Validate forum's information.
	list($aForum, $aError) = ValidateForum($aForum);

	// Did we get any errors?
	if($aError)
	{
		return $aError;
	}

	// Are we a category or a forum?
	if($aForum['parent'])
	{
		$aForum['displaydepth'] = 1;
	}
	else
	{
		$aForum['displaydepth'] = 0;
	}

	// Add the new forum.
	$dbConn->query("UPDATE board SET disporder={$aForum['displayorder']}, name='{$aForum['title']}', description='{$aForum['description']}', displaydepth={$aForum['displaydepth']}, parent={$aForum['parent']} WHERE id={$aForum['id']}");

	// Let the user know it was a success.
	Msg("<b>Forum successfully updated.</b><br /><br /><span class=\"smaller\">You should be redirected to the Admin Control Panel momentarily. Click <a href=\"admincp.php?section=forums\">here</a><br />if you do not want to wait any longer or if you are not redirected.</span>", 'admincp.php?section=forums');
}

// Validates a forum's information to ensure it conforms.
function ValidateForum($aForum)
{
	global $dbConn;

	// Title
	if($aForum['title'] == '')
	{
		$aError[] = 'You must specify a title for the forum.';
	}
	else if(strlen($aForum['title']) > 255)
	{
		$aError[] = 'The forum title is longer than 255 characters.';
	}
	else
	{
		$aForum['title'] = $dbConn->sanitize($aForum['title']);
	}

	// Description
	if(strlen($aForum['description']) > 255)
	{
		$aError[] = 'The forum description is longer than 255 characters.';
	}
	else
	{
		$aForum['description'] = $dbConn->sanitize($aForum['description']);
	}

	// Return the forum's information and any errors we encountered.
	return array($aForum, $aError);
}

// Updates forums display orders.
// TODO: Input is only sanitized; orders need to be cleaned up.
function UpdateForums()
{
	global $dbConn;

	// Get the array of display orders.
	$aOrder = $_REQUEST['forumid'];

	// Update each forum's display order.
	foreach($aOrder as $iForumID => $iDisplayOrder)
	{
		$iForumID = (int)$iForumID;
		$iDisplayOrder = (int)$iDisplayOrder;
		$dbConn->query("UPDATE board SET disporder={$iDisplayOrder} WHERE id={$iForumID}");
	}

	// Let the user know it was a success.
	Msg("<b>Forum display orders were updated successfully.</b><br /><br /><span class=\"smaller\">You should be redirected to the Admin Control Panel momentarily. Click <a href=\"admincp.php?section=forums\">here</a><br />if you do not want to wait any longer or if you are not redirected.</span>", 'admincp.php?section=forums');
}

// *************************************************************************** \\

function Attachments()
{
	global $CFG;

	// What are they wanting to do?
	switch($_REQUEST['action'])
	{
		case 'add':
		{
			AddAttachment();
		}

		case 'remove':
		{
			RemoveAttachment();
		}

		case 'edit':
		{
			EditAttachment();
		}
	}

	// Are they coming for the first time or submitting information?
	if(isset($_REQUEST['submit']))
	{
		// Submitting information, so store it.
		$aOptions['maxsize'] = (int)$_REQUEST['maxsize'];

		// Validate the information, and submit it to the database if everything's okay.
		UpdateAttachments($aOptions);
	}
	else
	{
		// Coming for the first time, so set the defaults.
		$aOptions['maxsize'] = $CFG['uploads']['maxsize'];
	}

	// Template
	require("./skins/{$CFG['skin']}/admincp/attachments.tpl.php");

	// Send the page.
	exit;
}

// Updates the attachment options.
function UpdateAttachments($aOptions)
{
	global $CFG, $dbConn;

	// Load the current settings verbatim from the database.
	$dbConn->query("SELECT content FROM configuration WHERE name='settings'");
	list($strValue) = $dbConn->getresult();
	$aConfigs = unserialize($strValue);

	// Apply the new settings.
	$aConfigs['uploads']['maxsize'] = $aOptions['maxsize'];

	// Serialize and sanitize the new settings.
	$strSettings = $dbConn->sanitize(serialize($aConfigs));

	// Store the new settings.
	$dbConn->query("UPDATE configuration SET content='{$strSettings}' WHERE name='settings'");

	// Let the user know it was a success.
	Msg("<b>The attachment settings have been updated.</b><br /><br /><span class=\"smaller\">You should be redirected to the Admin Control Panel momentarily. Click <a href=\"admincp.php?section=attachments\">here</a><br />if you do not want to wait any longer or if you are not redirected.</span>", 'admincp.php?section=attachments');
}

// User wants to add a new attachment type.
function AddAttachment()
{
	global $CFG, $dbConn;

	// Load the current settings verbatim from the database.
	$dbConn->query("SELECT content FROM configuration WHERE name='settings'");
	list($strValue) = $dbConn->getresult();
	$aConfigs = unserialize($strValue);

	// Are they coming for the first time or submitting information?
	if(isset($_REQUEST['submit']))
	{
		// Submitting information, so store it.
		$aAttachment['extension'] = trim($_REQUEST['extension']);
		$aAttachment['filename'] = trim($_REQUEST['filename']);
		$aAttachment['mime'] = trim($_REQUEST['mime']);

		// Validate the information, and submit it to the database if everything's okay.
		$aError = AddAttachmentNow($aAttachment, $aConfigs);
	}

	// Template
	require("./skins/{$CFG['skin']}/admincp/addattachment.tpl.php");

	// Send the page.
	exit;
}

// Adds a new attachment type.
function AddAttachmentNow($aAttachment, $aConfigs)
{
	global $CFG, $dbConn;

	// Validate attachment type's information.
	list($aAttachment, $aError) = ValidateAttachment($aAttachment);

	// Did we get any errors?
	if($aError)
	{
		return $aError;
	}

	// Add the new attachment type and update the settings
	$aConfigs['uploads']['oktypes'][$aAttachment['extension']][0] = $aAttachment['filename'];
	$aConfigs['uploads']['oktypes'][$aAttachment['extension']][1] = $aAttachment['mime'];
	$strSettings = $dbConn->sanitize(serialize($aConfigs));
	$dbConn->query("UPDATE configuration SET content='{$strSettings}' WHERE name='settings'");

	// Let the user know it was a success.
	Msg("<b>Attachment type successfully added.</b><br /><br /><span class=\"smaller\">You should be redirected to the Admin Control Panel momentarily. Click <a href=\"admincp.php?section=attachments\">here</a><br />if you do not want to wait any longer or if you are not redirected.</span>", 'admincp.php?section=attachments');
}

// User wants to edit an acceptable attachment type.
function EditAttachment()
{
	global $CFG, $dbConn;

	// Load the current settings verbatim from the database.
	$dbConn->query("SELECT content FROM configuration WHERE name='settings'");
	list($strValue) = $dbConn->getresult();
	$aConfigs = unserialize($strValue);

	// Which attachment type do they want to edit?
	$aAttachment['type'] = trim($_REQUEST['type']);

	// Does the attachment type exist?
	if(!isset($aConfigs['uploads']['oktypes'][$aAttachment['type']]) && !isset($_REQUEST['submit']))
	{
		Msg("Invalid attachment type specified.{$CFG['msg']['invalidlink']}");
	}

	// Are they coming for the first time or submitting information?
	if(isset($_REQUEST['submit']))
	{
		// Submitting information, so store it.
		$aAttachment['extension'] = trim($_REQUEST['extension']);
		$aAttachment['filename'] = trim($_REQUEST['filename']);
		$aAttachment['mime'] = trim($_REQUEST['mime']);

		// Validate the information, and submit it to the database if everything's okay.
		$aError = EditAttachmentNow($aAttachment, $aConfigs);
	}
	else
	{
		// Coming for the first time, so get the attachment's icon.
		$aAttachment['extension'] = $aAttachment['type'];
		$aAttachment['filename'] = $aConfigs['uploads']['oktypes'][$aAttachment['type']][0];
		$aAttachment['mime'] = $aConfigs['uploads']['oktypes'][$aAttachment['type']][1];
	}

	// Template
	require("./skins/{$CFG['skin']}/admincp/editattachment.tpl.php");

	// Send the page.
	exit;
}

// Edits an acceptable attachment type.
function EditAttachmentNow($aAttachment, $aConfigs)
{
	global $CFG, $dbConn;

	// Validate attachment type's information.
	list($aAttachment, $aError) = ValidateAttachment($aAttachment);

	// Did we get any errors?
	if($aError)
	{
		return $aError;
	}

	// Update the information.
	if($aAttachment['type'] != $aAttachment['extension'])
	{
		unset($aConfigs['uploads']['oktypes'][$aAttachment['type']]);
	}
	$aConfigs['uploads']['oktypes'][$aAttachment['extension']][0] = $aAttachment['filename'];
	$aConfigs['uploads']['oktypes'][$aAttachment['extension']][1] = $aAttachment['mime'];

	// Update the settings.
	$strSettings = $dbConn->sanitize(serialize($aConfigs));
	$dbConn->query("UPDATE configuration SET content='{$strSettings}' WHERE name='settings'");

	// Let the user know it was a success.
	Msg("<b>Attachment type successfully updated.</b><br /><br /><span class=\"smaller\">You should be redirected to the Admin Control Panel momentarily. Click <a href=\"admincp.php?section=attachments\">here</a><br />if you do not want to wait any longer or if you are not redirected.</span>", 'admincp.php?section=attachments');
}

// Validates an attachment type's information to ensure it conforms.
function ValidateAttachment($aAttachment)
{
	global $CFG;

	// Extension
	if($aAttachment['extension'] == '')
	{
		$aError[] = 'You must specify a file extension for the attachment type.';
	}
	else if(strlen($aAttachment['extension']) > 255)
	{
		$aError[] = 'The attachment type\'s file extension is longer than 255 characters.';
	}

	// Filename
	if($aAttachment['filename'] == '')
	{
		$aError[] = 'You must specify the filename of the attachment type\'s icon.';
	}
	else if(!file_exists("images/attach/{$aAttachment['filename']}"))
	{
		$aError[] = 'The attachment type\'s icon you specified does not exist. Make sure you have uploaded it to your attachment icons folder.';
	}

	// Internet media (MIME) type
	if($aAttachment['mime'] == '')
	{
		$aError[] = 'You must specify a MIME type for the attachment type.';
	}
	else if(strlen($aAttachment['mime']) > 255)
	{
		$aError[] = 'The attachment type\'s MIME type is longer than 255 characters.';
	}

	// Return the attachment type's information and any errors we encountered.
	return array($aAttachment, $aError);
}

// User wants to remove an attachment type.
function RemoveAttachment()
{
	global $CFG, $dbConn;

	// Load the current settings verbatim from the database.
	$dbConn->query("SELECT content FROM configuration WHERE name='settings'");
	list($strValue) = $dbConn->getresult();
	$aConfigs = unserialize($strValue);

	// What attachment type do they want to delete?
	$strType = trim($_REQUEST['type']);

	// Does the attachment type exist?
	if(!isset($aConfigs['uploads']['oktypes'][$strType]))
	{
		Msg("Invalid attachment type specified.{$CFG['msg']['invalidlink']}");
	}

	// Are they submitting?
	if((bool)$_REQUEST['removeattachment'] && isset($_REQUEST['submit']))
	{
		// Yes, so remove the attachment type.
		RemoveAttachmentNow($strType, $aConfigs);
	}

	// Template
	require("./skins/{$CFG['skin']}/admincp/removeattachment.tpl.php");

	// Send the page.
	exit;
}

// Removes the attachment type.
function RemoveAttachmentNow($strType, $aConfigs)
{
	global $CFG, $dbConn;

	// Update the settings.
	unset($aConfigs['uploads']['oktypes'][$strType]);
	$strSettings = $dbConn->sanitize(serialize($aConfigs));
	$dbConn->query("UPDATE configuration SET content='{$strSettings}' WHERE name='settings'");

	// Let the user know it was a success.
	Msg("<b>The attachment type was removed successfully.</b><br /><br /><span class=\"smaller\">You should be redirected to the Admin Control Panel momentarily. Click <a href=\"admincp.php?section=attachments\">here</a><br />if you do not want to wait any longer or if you are not redirected.</span>", 'admincp.php?section=attachments');
}

// *************************************************************************** \\

function Usergroups()
{
	global $CFG, $aGroup;

	// What are they wanting to do?
	switch($_REQUEST['action'])
	{
		case 'add':
		{
			AddUsergroup();
		}

		case 'remove':
		{
			RemoveUsergroup();
		}

		case 'edit':
		{
			EditUsergroup();
		}

		case 'adduser':
		{
			AddUsergroupUser();
		}
	}

	// Template
	require("./skins/{$CFG['skin']}/admincp/usergroups.tpl.php");

	// Send the page.
	exit;
}

// User wants to add a new usergroup.
function AddUsergroup()
{
	global $CFG;

	// Are they coming for the first time or submitting information?
	if(isset($_REQUEST['submit']))
	{
		// Submitting information, so store it.
		$aUsergroup['groupname'] = trim($_REQUEST['groupname']);
		$aUsergroup['usertitle'] = trim($_REQUEST['usertitle']);
		$aUsergroup['cviewattachments'] = (int)(bool)$_REQUEST['cviewattachments'];
		$aUsergroup['ccalendar'] = (int)(bool)$_REQUEST['ccalendar'];
		$aUsergroup['cmakeevent'] = (int)(bool)$_REQUEST['cmakeevent'];
		$aUsergroup['ceditposts'] = (int)(bool)$_REQUEST['ceditposts'];
		$aUsergroup['cviewprofiles'] = (int)(bool)$_REQUEST['cviewprofiles'];
		$aUsergroup['cviewmembers'] = (int)(bool)$_REQUEST['cviewmembers'];
		$aUsergroup['cviewips'] = (int)(bool)$_REQUEST['cviewips'];
		$aUsergroup['creply'] = (int)(bool)$_REQUEST['creply'];
		$aUsergroup['creplyclosed'] = (int)(bool)$_REQUEST['creplyclosed'];
		$aUsergroup['cmakethreads'] = (int)(bool)$_REQUEST['cmakethreads'];
		$aUsergroup['cviewonline'] = (int)(bool)$_REQUEST['cviewonline'];
		$aUsergroup['cviewinvisible'] = (int)(bool)$_REQUEST['cviewinvisible'];
		$aUsergroup['cmakepolls'] = (int)(bool)$_REQUEST['cmakepolls'];
		$aUsergroup['cvotepolls'] = (int)(bool)$_REQUEST['cvotepolls'];
		$aUsergroup['csearch'] = (int)(bool)$_REQUEST['csearch'];
		$aUsergroup['cmakepubevent'] = (int)(bool)$_REQUEST['cmakepubevent'];
		$aUsergroup['cmeditposts'] = (int)(bool)$_REQUEST['cmeditposts'];
		$aUsergroup['cmopenclosethreads'] = (int)(bool)$_REQUEST['cmopenclosethreads'];
		$aUsergroup['cmstickythreads'] = (int)(bool)$_REQUEST['cmstickythreads'];
		$aUsergroup['cmdeletethreads'] = (int)(bool)$_REQUEST['cmdeletethreads'];
		$aUsergroup['cmdeleteposts'] = (int)(bool)$_REQUEST['cmdeleteposts'];
		$aUsergroup['cmovethreads'] = (int)(bool)$_REQUEST['cmovethreads'];
		$aUsergroup['cviewadmincp'] = (int)(bool)$_REQUEST['cviewadmincp'];
		$aUsergroup['cbypassflood'] = (int)(bool)$_REQUEST['cbypassflood'];

		// Validate the information, and submit it to the database if everything's okay.
		$aError = AddUsergroupNow($aUsergroup);
	}

	// Template
	require("./skins/{$CFG['skin']}/admincp/addusergroup.tpl.php");

	// Send the page.
	exit;
}

// Adds a usergroup.
function AddUsergroupNow($aUsergroup)
{
	global $CFG, $dbConn, $aGroup;

	// Validate the usergroup's information.
	list($aUsergroup, $aError) = ValidateUsergroup($aUsergroup);

	// Did we get any errors?
	if($aError)
	{
		return $aError;
	}

	// Add the new usergroup.
	$aGroup[] = $aUsergroup;
	$strUsergroups = $dbConn->sanitize(serialize($aGroup));
	$dbConn->query("UPDATE configuration SET content='{$strUsergroups}' WHERE name='usergroups'");

	// Let the user know it was a success.
	Msg("<b>Usergroup successfully added.</b><br /><br /><span class=\"smaller\">You should be redirected to the Admin Control Panel momentarily. Click <a href=\"admincp.php?section=usergroups\">here</a><br />if you do not want to wait any longer or if you are not redirected.</span>", 'admincp.php?section=usergroups');
}

// User wants to edit usergroup.
function EditUsergroup()
{
	global $CFG, $aGroup;

	// What usergroup do they want to edit?
	$aUsergroup['id'] = (int)$_REQUEST['usergroupid'];

	// Does the usergroup exist?
	if(!is_array($aGroup[$aUsergroup['id']]))
	{
		Msg("Invalid usergroup specified.{$CFG['msg']['invalidlink']}");
	}

	// Are they coming for the first time or submitting information?
	if(isset($_REQUEST['submit']))
	{
		// Submitting information, so store it.
		$aUsergroup['groupname'] = trim($_REQUEST['groupname']);
		$aUsergroup['usertitle'] = trim($_REQUEST['usertitle']);
		$aUsergroup['cviewattachments'] = (int)(bool)$_REQUEST['cviewattachments'];
		$aUsergroup['ccalendar'] = (int)(bool)$_REQUEST['ccalendar'];
		$aUsergroup['cmakeevent'] = (int)(bool)$_REQUEST['cmakeevent'];
		$aUsergroup['ceditposts'] = (int)(bool)$_REQUEST['ceditposts'];
		$aUsergroup['cviewprofiles'] = (int)(bool)$_REQUEST['cviewprofiles'];
		$aUsergroup['cviewmembers'] = (int)(bool)$_REQUEST['cviewmembers'];
		$aUsergroup['cviewips'] = (int)(bool)$_REQUEST['cviewips'];
		$aUsergroup['creply'] = (int)(bool)$_REQUEST['creply'];
		$aUsergroup['creplyclosed'] = (int)(bool)$_REQUEST['creplyclosed'];
		$aUsergroup['cmakethreads'] = (int)(bool)$_REQUEST['cmakethreads'];
		$aUsergroup['cviewonline'] = (int)(bool)$_REQUEST['cviewonline'];
		$aUsergroup['cviewinvisible'] = (int)(bool)$_REQUEST['cviewinvisible'];
		$aUsergroup['cmakepolls'] = (int)(bool)$_REQUEST['cmakepolls'];
		$aUsergroup['cvotepolls'] = (int)(bool)$_REQUEST['cvotepolls'];
		$aUsergroup['csearch'] = (int)(bool)$_REQUEST['csearch'];
		$aUsergroup['cmakepubevent'] = (int)(bool)$_REQUEST['cmakepubevent'];
		$aUsergroup['cmeditposts'] = (int)(bool)$_REQUEST['cmeditposts'];
		$aUsergroup['cmopenclosethreads'] = (int)(bool)$_REQUEST['cmopenclosethreads'];
		$aUsergroup['cmstickythreads'] = (int)(bool)$_REQUEST['cmstickythreads'];
		$aUsergroup['cmdeletethreads'] = (int)(bool)$_REQUEST['cmdeletethreads'];
		$aUsergroup['cmdeleteposts'] = (int)(bool)$_REQUEST['cmdeleteposts'];
		$aUsergroup['cmovethreads'] = (int)(bool)$_REQUEST['cmovethreads'];
		$aUsergroup['cviewadmincp'] = (int)(bool)$_REQUEST['cviewadmincp'];
		$aUsergroup['cbypassflood'] = (int)(bool)$_REQUEST['cbypassflood'];

		// Validate the information, and submit it to the database if everything's okay.
		$aError = EditUsergroupNow($aUsergroup);
	}
	else
	{
		// Coming for the first time, so get the usergroup's data.
		$aUsergroup = array_merge($aUsergroup, $aGroup[$aUsergroup['id']]);
	}

	// Template
	require("./skins/{$CFG['skin']}/admincp/editusergroup.tpl.php");

	// Send the page.
	exit;
}

// Edits a usergroup.
function EditUsergroupNow($aUsergroup)
{
	global $CFG, $dbConn, $aGroup;

	// Grab the usergroup ID.
	$iUsergroupID = $aUsergroup['id'];
	unset($aUsergroup['id']);

	// Validate usergroup's information.
	list($aUsergroup, $aError) = ValidateUsergroup($aUsergroup);

	// Did we get any errors?
	if($aError)
	{
		return $aError;
	}

	// Set the usergroup data and sanitize it.
	$aGroup[$iUsergroupID] = $aUsergroup;
	$strUsergroups = $dbConn->sanitize(serialize($aGroup));

	// Update the usergroups' record.
	$dbConn->query("UPDATE configuration SET content='{$strUsergroups}' WHERE name='usergroups'");

	// Let the user know it was a success.
	Msg("<b>Usergroup successfully updated.</b><br /><br /><span class=\"smaller\">You should be redirected to the Admin Control Panel momentarily. Click <a href=\"admincp.php?section=usergroups\">here</a><br />if you do not want to wait any longer or if you are not redirected.</span>", 'admincp.php?section=usergroups');
}

// Validates a usergroup's information to ensure it conforms.
function ValidateUsergroup($aUsergroup)
{
	// Usergroup name
	if($aUsergroup['groupname'] == '')
	{
		$aError[] = 'You must specify a name for the usergroup.';
	}
	else if(strlen($aUsergroup['name']) > 255)
	{
		$aError[] = 'The usergroup name is longer than 255 characters.';
	}

	// User status
	if($aUsergroup['usertitle'] == '')
	{
		$aError[] = 'You must specify a user status for the usergroup.';
	}
	else if(strlen($aUsergroup['usertitle']) > 255)
	{
		$aError[] = 'The user status is longer than 255 characters.';
	}

	// Return the usergroup's information and any errors we encountered.
	return array($aUsergroup, $aError);
}

// User wants to remove usergroup.
function RemoveUsergroup()
{
	global $CFG, $aGroup;

	// What usergroup do they want to remove?
	$iUsergroupID = (int)$_REQUEST['usergroupid'];

	// What usergroup do they want to move the existing members to?
	$iNewGroupID = (int)$_REQUEST['newgroupid'];

	// Does the (source) usergroup exist?
	if(!is_array($aGroup[$iUsergroupID]))
	{
		Msg("Invalid usergroup specified.{$CFG['msg']['invalidlink']}");
	}

	// Are they submitting?
	if((bool)$_REQUEST['removegroup'] && isset($_REQUEST['submit']))
	{
		// Yes, so remove the usergroup.
		RemoveUsergroupNow($iUsergroupID, $iNewGroupID);
	}

	// Template
	require("./skins/{$CFG['skin']}/admincp/removeusergroup.tpl.php");

	// Send the page.
	exit;
}

// Removes the specified usergroup and moves all its members to the specified group.
function RemoveUsergroupNow($iUsergroupID, $iDestinationGroupID)
{
	global $CFG, $dbConn, $aGroup;

	// Does the (destination) usergroup exist?
	if(!is_array($aGroup[$iDestinationGroupID]))
	{
		return(array("Invalid destination usergroup specified.{$CFG['msg']['invalidlink']}"));
	}

	// Remove the usergroup from the array.
	unset($aGroup[$iUsergroupID]);

	// Build a new sanitary serialized group string for SQL.
	$strUsergroups = $dbConn->sanitize(serialize($aGroup));

	// Update the configuration setting.
	$dbConn->query("UPDATE configuration SET content='{$strUsergroups}' WHERE name='usergroups'");

	// Update the member's records.
	$dbConn->query("UPDATE citizen SET usergroup={$iDestinationGroupID} WHERE usergroup={$iUsergroupID}");

	// Let the user know it was a success.
	Msg("<b>The usergroup was removed successfully.</b><br /><br /><span class=\"smaller\">You should be redirected to the Admin Control Panel momentarily. Click <a href=\"admincp.php?section=usergroups\">here</a><br />if you do not want to wait any longer or if you are not redirected.</span>", 'admincp.php?section=usergroups');
}

// User wants to add a member to the specified usergroup.
function AddUsergroupUser()
{
	global $CFG, $aGroup;

	// Get any passed data.
	$strUsername = $_REQUEST['username'];
	$iUsergroupID = (int)$_REQUEST['usergroupid'];

	// Make sure the usergroup we've been given exists.
	if(!is_array($aGroup[$iUsergroupID]))
	{
		// Invalid usergroup given.
		Msg("Invalid usergroup specified.{$CFG['msg']['invalidlink']}");
	}

	// Are they coming for the first time or submitting information?
	if(isset($_REQUEST['submit']))
	{
		// Validate the information, and submit it to the database if everything's okay.
		$aError = AddUsergroupUserNow($strUsername, $iUsergroupID);
	}

	// Template
	require("./skins/{$CFG['skin']}/admincp/addusergroupuser.tpl.php");

	// Send the page.
	exit;
}

// Adds a user to the specified usergroup.
function AddUsergroupUserNow($strUsername, $iUsergroupID)
{
	global $CFG, $dbConn, $aGroup;

	// Sanitize the username.
	$strUsername = $dbConn->sanitize($strUsername);

	// Get the user ID for the username given.
	$dbConn->query("SELECT id FROM citizen WHERE username='{$strUsername}'");
	if(!(list($iUserID) = $dbConn->getresult()))
	{
		// User doesn't exist.
		return(array('There is no member with the specified username.'));
	}

	// Make the change.
	$dbConn->query("UPDATE citizen SET usergroup={$iUsergroupID} WHERE id={$iUserID}");

	// Let the user know it was a success.
	Msg("<b>User successfully added to usergroup.</b><br /><br /><span class=\"smaller\">You should be redirected to the Admin Control Panel momentarily. Click <a href=\"admincp.php?section=usergroups\">here</a><br />if you do not want to wait any longer or if you are not redirected.</span>", 'admincp.php?section=usergroups');
}

// *************************************************************************** \\

function Avatars()
{
	global $CFG, $aAvatars;

	// What are they wanting to do?
	switch($_REQUEST['action'])
	{
		case 'add':
		{
			AddAvatar();
		}

		case 'remove':
		{
			RemoveAvatar();
		}

		case 'edit':
		{
			EditAvatar();
		}
	}

	// Are they coming for the first time or submitting information?
	if(isset($_REQUEST['submit']))
	{
		// Submitting information, so store it.
		$aOptions['maxdims'] = abs((int)$_REQUEST['maxdims']);
		$aOptions['maxsize'] = abs((int)$_REQUEST['maxsize']);

		// Validate the information, and submit it to the database if everything's okay.
		UpdateAvatars($aOptions);
	}
	else
	{
		// Coming for the first time, so set the defaults.
		$aOptions['maxdims'] = $CFG['avatars']['maxdims'];
		$aOptions['maxsize'] = $CFG['avatars']['maxsize'];
	}

	// Template
	require("./skins/{$CFG['skin']}/admincp/avatars.tpl.php");

	// Send the page.
	exit;
}

// Updates the avatar options.
function UpdateAvatars($aOptions)
{
	global $CFG, $dbConn;

	// Load the current settings verbatim from the database.
	$dbConn->query("SELECT content FROM configuration WHERE name='settings'");
	list($strValue) = $dbConn->getresult();
	$aConfigs = unserialize($strValue);

	// Apply the new settings.
	$aConfigs['avatars']['maxdims'] = $aOptions['maxdims'];
	$aConfigs['avatars']['maxsize'] = $aOptions['maxsize'];

	// Serialize and sanitize the new settings.
	$strSettings = $dbConn->sanitize(serialize($aConfigs));

	// Store the new settings.
	$dbConn->query("UPDATE configuration SET content='{$strSettings}' WHERE name='settings'");

	// Let the user know it was a success.
	Msg("<b>The avatar settings have been updated.</b><br /><br /><span class=\"smaller\">You should be redirected to the Admin Control Panel momentarily. Click <a href=\"admincp.php?section=avatars\">here</a><br />if you do not want to wait any longer or if you are not redirected.</span>", 'admincp.php?section=avatars');
}

// User wants to add a new public avatar.
function AddAvatar()
{
	global $CFG;

	// Are they coming for the first time or submitting information?
	if(isset($_REQUEST['submit']))
	{
		// Submitting information, so store it.
		$aAvatar['title'] = trim($_REQUEST['title']);
		$aAvatar['filename'] = trim($_REQUEST['filename']);

		// Validate the information, and submit it to the database if everything's okay.
		$aError = AddAvatarNow($aAvatar);
	}

	// Template
	require("./skins/{$CFG['skin']}/admincp/addavatar.tpl.php");

	// Send the page.
	exit;
}

// Adds a new public avatar.
function AddAvatarNow($aAvatar)
{
	global $CFG, $dbConn, $aAvatars;

	// Validate avatar's information.
	list($aAvatar, $aError) = ValidateAvatar($aAvatar);

	// Did we get any errors?
	if($aError)
	{
		return $aError;
	}

	// Add the new avatar.
	$aAvatars[] = $aAvatar;
	$strAvatars = $dbConn->sanitize(serialize($aAvatars));
	$dbConn->query("UPDATE configuration SET content='{$strAvatars}' WHERE name='avatars'");

	// Let the user know it was a success.
	Msg("<b>Avatar successfully added.</b><br /><br /><span class=\"smaller\">You should be redirected to the Admin Control Panel momentarily. Click <a href=\"admincp.php?section=avatars\">here</a><br />if you do not want to wait any longer or if you are not redirected.</span>", 'admincp.php?section=avatars');
}

// User wants to edit a public avatar.
function EditAvatar()
{
	global $CFG, $aAvatars;

	// Which avatar do they want to edit?
	$aAvatar['id'] = (int)$_REQUEST['avatarid'];

	// Does the avatar exist?
	if(!is_array($aAvatars[$aAvatar['id']]))
	{
		Msg("Invalid avatar specified.{$CFG['msg']['invalidlink']}");
	}

	// Are they coming for the first time or submitting information?
	if(isset($_REQUEST['submit']))
	{
		// Submitting information, so store it.
		$aAvatar['title'] = trim($_REQUEST['title']);
		$aAvatar['filename'] = trim($_REQUEST['filename']);

		// Validate the information, and submit it to the database if everything's okay.
		$aError = EditAvatarNow($aAvatar);
	}
	else
	{
		// Coming for the first time, so get the avatar's data.
		$aAvatar = array_merge($aAvatar, $aAvatars[$aAvatar['id']]);
	}

	// Template
	require("./skins/{$CFG['skin']}/admincp/editavatar.tpl.php");

	// Send the page.
	exit;
}

// Edits a public avatar.
function EditAvatarNow($aAvatar)
{
	global $CFG, $dbConn, $aAvatars;

	// Grab the avatar ID.
	$iAvatarID = $aAvatar['id'];
	unset($aAvatar['id']);

	// Validate avatar's information.
	list($aAvatar, $aError) = ValidateAvatar($aAvatar);

	// Did we get any errors?
	if($aError)
	{
		return $aError;
	}

	// Replace the obsolete avatar information with the new information.
	$aAvatars[$iAvatarID] = $aAvatar;

	// Update the avatars.
	$strAvatars = $dbConn->sanitize(serialize($aAvatars));
	$dbConn->query("UPDATE configuration SET content='{$strAvatars}' WHERE name='avatars'");

	// Let the user know it was a success.
	Msg("<b>Avatar successfully updated.</b><br /><br /><span class=\"smaller\">You should be redirected to the Admin Control Panel momentarily. Click <a href=\"admincp.php?section=avatars\">here</a><br />if you do not want to wait any longer or if you are not redirected.</span>", 'admincp.php?section=avatars');
}

// Validates an avatar's information to ensure it conforms.
function ValidateAvatar($aAvatar)
{
	global $CFG;

	// Title
	if($aAvatar['title'] == '')
	{
		$aError[] = 'You must specify a title for the avatar.';
	}
	else if(strlen($aAvatar['title']) > 255)
	{
		$aError[] = 'The avatar title is longer than 255 characters.';
	}

	// Filename
	if($aAvatar['filename'] == '')
	{
		$aError[] = 'You must specify the filename of the avatar.';
	}
	else if(!file_exists("{$CFG['paths']['avatars']}{$aAvatar['filename']}"))
	{
		$aError[] = 'The avatar file you specified does not exist. Make sure you have uploaded it to your avatars folder.';
	}

	// Return the avatar's information and any errors we encountered.
	return array($aAvatar, $aError);
}

// User wants to remove an avatar.
function RemoveAvatar()
{
	global $CFG, $aAvatars;

	// What avatar do they want to delete?
	$iAvatarID = (int)$_REQUEST['avatarid'];

	// Does the avatar exist?
	if(!is_array($aAvatars[$iAvatarID]))
	{
		Msg("Invalid avatar specified.{$CFG['msg']['invalidlink']}");
	}

	// Are they submitting?
	if((bool)$_REQUEST['removeavatar'] && isset($_REQUEST['submit']))
	{
		// Yes, so remove the avatar.
		RemoveAvatarNow($iAvatarID);
	}

	// Template
	require("./skins/{$CFG['skin']}/admincp/removeavatar.tpl.php");

	// Send the page.
	exit;
}

// Removes the specified avatar.
function RemoveAvatarNow($iAvatarID)
{
	global $CFG, $dbConn, $aAvatars;

	// Remove all instances of the public avatar.
	$dbConn->query("DELETE FROM avatar WHERE datum={$iAvatarID} AND filename IS NULL");

	// Update the avatars.
	unset($aAvatars[$iAvatarID]);
	$strAvatars = $dbConn->sanitize(serialize($aAvatars));
	$dbConn->query("UPDATE configuration SET content='{$strAvatars}' WHERE name='avatars'");

	// Let the user know it was a success.
	Msg("<b>The avatar was removed successfully.</b><br /><br /><span class=\"smaller\">You should be redirected to the Admin Control Panel momentarily. Click <a href=\"admincp.php?section=avatars\">here</a><br />if you do not want to wait any longer or if you are not redirected.</span>", 'admincp.php?section=avatars');
}

// *************************************************************************** \\

function Smilies()
{
	global $CFG, $aSmilies;

	// What are they wanting to do?
	switch($_REQUEST['action'])
	{
		case 'add':
		{
			AddSmilie();
		}

		case 'remove':
		{
			RemoveSmilie();
		}

		case 'edit':
		{
			EditSmilie();
		}
	}

	// Template
	require("./skins/{$CFG['skin']}/admincp/smilies.tpl.php");

	// Send the page.
	exit;
}

// User wants to add a new smilie.
function AddSmilie()
{
	global $CFG;

	// Are they coming for the first time or submitting information?
	if(isset($_REQUEST['submit']))
	{
		// Submitting information, so store it.
		$aSmilie['title'] = trim($_REQUEST['title']);
		$aSmilie['code'] = trim($_REQUEST['code']);
		$aSmilie['filename'] = trim($_REQUEST['filename']);

		// Validate the information, and submit it to the database if everything's okay.
		$aError = AddSmilieNow($aSmilie);
	}

	// Template
	require("./skins/{$CFG['skin']}/admincp/addsmilie.tpl.php");

	// Send the page.
	exit;
}

// Adds a new smilie.
function AddSmilieNow($aSmilie)
{
	global $CFG, $dbConn, $aSmilies;

	// Validate smilie's information.
	list($aSmilie, $aError) = ValidateSmilie($aSmilie);

	// Did we get any errors?
	if($aError)
	{
		return $aError;
	}

	// Add the new smilie.
	$aSmilies[] = $aSmilie;
	$strSmilies = $dbConn->sanitize(serialize($aSmilies));
	$dbConn->query("UPDATE configuration SET content='{$strSmilies}' WHERE name='smilies'");

	// Let the user know it was a success.
	Msg("<b>Smilie successfully added.</b><br /><br /><span class=\"smaller\">You should be redirected to the Admin Control Panel momentarily. Click <a href=\"admincp.php?section=smilies\">here</a><br />if you do not want to wait any longer or if you are not redirected.</span>", 'admincp.php?section=smilies');
}

// User wants to edit a smilie.
function EditSmilie()
{
	global $CFG, $aSmilies;

	// Which smilie do they want to edit?
	$aSmilie['id'] = (int)$_REQUEST['smilieid'];

	// Does the smilie exist?
	if(!is_array($aSmilies[$aSmilie['id']]))
	{
		Msg("Invalid smilie specified.{$CFG['msg']['invalidlink']}");
	}

	// Are they coming for the first time or submitting information?
	if(isset($_REQUEST['submit']))
	{
		// Submitting information, so store it.
		$aSmilie['title'] = trim($_REQUEST['title']);
		$aSmilie['code'] = trim($_REQUEST['code']);
		$aSmilie['filename'] = trim($_REQUEST['filename']);

		// Validate the information, and submit it to the database if everything's okay.
		$aError = EditSmilieNow($aSmilie);
	}
	else
	{
		// Coming for the first time, so get the smilie's data.
		$aSmilie = array_merge($aSmilie, $aSmilies[$aSmilie['id']]);
	}

	// Template
	require("./skins/{$CFG['skin']}/admincp/editsmilie.tpl.php");

	// Send the page.
	exit;
}

// Edits a smilie.
function EditSmilieNow($aSmilie)
{
	global $CFG, $dbConn, $aSmilies;

	// Grab the smilie ID.
	$iSmilieID = $aSmilie['id'];
	unset($aSmilie['id']);

	// Validate smilie's information.
	list($aSmilie, $aError) = ValidateSmilie($aSmilie, $iSmilieID);

	// Did we get any errors?
	if($aError)
	{
		return $aError;
	}

	// Replace the old smilie with the new one.
	$aSmilies[$iSmilieID] = $aSmilie;

	// Update the smilies.
	$strSmilies = $dbConn->sanitize(serialize($aSmilies));
	$dbConn->query("UPDATE configuration SET content='{$strSmilies}' WHERE name='smilies'");

	// Let the user know it was a success.
	Msg("<b>Smilie successfully updated.</b><br /><br /><span class=\"smaller\">You should be redirected to the Admin Control Panel momentarily. Click <a href=\"admincp.php?section=smilies\">here</a><br />if you do not want to wait any longer or if you are not redirected.</span>", 'admincp.php?section=smilies');
}

// Validates a smilie's information to ensure it conforms.
function ValidateSmilie($aSmilie, $iSmilieID = NULL)
{
	global $CFG, $aSmilies;

	// Title
	if($aSmilie['title'] == '')
	{
		$aError[] = 'You must specify a title for the smilie.';
	}
	else if(strlen($aSmilie['title']) > 255)
	{
		$aError[] = 'The smilie title is longer than 255 characters.';
	}

	// Code
	if($aSmilie['code'] == '')
	{
		$aError[] = 'You must specify a code for the smilie.';
	}
	else if(strlen($aSmilie['code']) > 255)
	{
		$aError[] = 'The smilie code is longer than 255 characters.';
	}
	else
	{
		foreach($aSmilies as $iID => $temp)
		{
			if(($iID != $iSmilieID) && ($aSmilies[$iID]['code'] == $aSmilie['code']))
			{
				$aError[] = 'The smilie code you specified is already in use.';
				break;
			}
		}
	}

	// Filename
	if($aSmilie['filename'] == '')
	{
		$aError[] = 'You must specify the filename of the smilie.';
	}
	else if(!file_exists("{$CFG['paths']['smilies']}{$aSmilie['filename']}"))
	{
		$aError[] = 'The smilie image you specified does not exist. Make sure you have uploaded it to your smilies folder.';
	}

	// Return the smilie's information and any errors we encountered.
	return array($aSmilie, $aError);
}

// User wants to remove a smilie.
function RemoveSmilie()
{
	global $CFG, $aSmilies;

	// What smilie do they want to delete?
	$iSmilieID = (int)$_REQUEST['smilieid'];

	// Does the smilie exist?
	if(!is_array($aSmilies[$iSmilieID]))
	{
		Msg("Invalid smilie specified.{$CFG['msg']['invalidlink']}");
	}

	// Are they submitting?
	if((bool)$_REQUEST['removesmilie'] && isset($_REQUEST['submit']))
	{
		// Yes, so remove the avatar.
		RemoveSmilieNow($iSmilieID);
	}

	// Template
	require("./skins/{$CFG['skin']}/admincp/removesmilie.tpl.php");

	// Send the page.
	exit;
}

// Removes the specified smilie.
function RemoveSmilieNow($iSmilieID)
{
	global $CFG, $dbConn, $aSmilies;

	// Update the avatars.
	unset($aSmilies[$iSmilieID]);
	$strSmilies = $dbConn->sanitize(serialize($aSmilies));
	$dbConn->query("UPDATE configuration SET content='{$strSmilies}' WHERE name='smilies'");

	// Let the user know it was a success.
	Msg("<b>The smilie was removed successfully.</b><br /><br /><span class=\"smaller\">You should be redirected to the Admin Control Panel momentarily. Click <a href=\"admincp.php?section=smilies\">here</a><br />if you do not want to wait any longer or if you are not redirected.</span>", 'admincp.php?section=smilies');
}

// *************************************************************************** \\

function PostIcons()
{
	global $CFG, $aPostIcons;

	// What are they wanting to do?
	switch($_REQUEST['action'])
	{
		case 'add':
		{
			AddIcon();
		}

		case 'remove':
		{
			RemoveIcon();
		}

		case 'edit':
		{
			EditIcon();
		}
	}

	// They shouldn't mess with the "no icon" icon ID.
	unset($aPostIcons[0]);

	// Template
	require("./skins/{$CFG['skin']}/admincp/posticons.tpl.php");

	// Send the page.
	exit;
}

// User wants to add a new post icon.
function AddIcon()
{
	global $CFG;

	// Are they coming for the first time or submitting information?
	if(isset($_REQUEST['submit']))
	{
		// Submitting information, so store it.
		$aPostIcon['title'] = trim($_REQUEST['title']);
		$aPostIcon['code'] = trim($_REQUEST['code']);
		$aPostIcon['filename'] = trim($_REQUEST['filename']);

		// Validate the information, and submit it to the database if everything's okay.
		$aError = AddIconNow($aPostIcon);
	}

	// Template
	require("./skins/{$CFG['skin']}/admincp/addposticon.tpl.php");

	// Send the page.
	exit;
}

// Adds a new post icon.
function AddIconNow($aPostIcon)
{
	global $CFG, $dbConn, $aPostIcons;

	// Validate icon's information.
	list($aPostIcon, $aError) = ValidateIcon($aPostIcon);

	// Did we get any errors?
	if($aError)
	{
		return $aError;
	}

	// Add the new post icon.
	$aPostIcons[] = $aPostIcon;
	$strPostIcons = $dbConn->sanitize(serialize($aPostIcons));
	$dbConn->query("UPDATE configuration SET content='{$strPostIcons}' WHERE name='posticons'");

	// Let the user know it was a success.
	Msg("<b>Post icon successfully added.</b><br /><br /><span class=\"smaller\">You should be redirected to the Admin Control Panel momentarily. Click <a href=\"admincp.php?section=posticons\">here</a><br />if you do not want to wait any longer or if you are not redirected.</span>", 'admincp.php?section=posticons');
}

// User wants to edit a post icon.
function EditIcon()
{
	global $CFG, $aPostIcons;

	// What icon do they want to edit?
	$aPostIcon['id'] = (int)$_REQUEST['posticonid'];

	// Does the icon exist?
	if(!is_array($aPostIcons[$aPostIcon['id']]) || ($aPostIcon['id'] == 0))
	{
		Msg("Invalid post icon specified.{$CFG['msg']['invalidlink']}");
	}

	// Are they coming for the first time or submitting information?
	if(isset($_REQUEST['submit']))
	{
		// Submitting information, so store it.
		$aPostIcon['title'] = trim($_REQUEST['title']);
		$aPostIcon['filename'] = trim($_REQUEST['filename']);

		// Validate the information, and submit it to the database if everything's okay.
		$aError = EditIconNow($aPostIcon);
	}
	else
	{
		// Coming for the first time, so get the icon's data.
		$aPostIcon = array_merge($aPostIcon, $aPostIcons[$aPostIcon['id']]);
	}

	// Template
	require("./skins/{$CFG['skin']}/admincp/editposticon.tpl.php");

	// Send the page.
	exit;
}

// Edits a post icon.
function EditIconNow($aPostIcon)
{
	global $CFG, $dbConn, $aPostIcons;

	// Grab the post icon ID.
	$iPostIconID = $aPostIcon['id'];
	unset($aPostIcon['id']);

	// Validate icon's information.
	list($aPostIcon, $aError) = ValidateIcon($aPostIcon);

	// Did we get any errors?
	if($aError)
	{
		return $aError;
	}

	// Replace the old post icon with the new one.
	$aPostIcons[$iPostIconID] = $aPostIcon;

	// Update the post icons.
	$strPostIcons = $dbConn->sanitize(serialize($aPostIcons));
	$dbConn->query("UPDATE configuration SET content='{$strPostIcons}' WHERE name='posticons'");

	// Let the user know it was a success.
	Msg("<b>Post icon successfully updated.</b><br /><br /><span class=\"smaller\">You should be redirected to the Admin Control Panel momentarily. Click <a href=\"admincp.php?section=posticons\">here</a><br />if you do not want to wait any longer or if you are not redirected.</span>", 'admincp.php?section=posticons');
}

// Validates a post icon's information to ensure it conforms.
function ValidateIcon($aPostIcon)
{
	global $CFG;

	// Title
	if($aPostIcon['title'] == '')
	{
		$aError[] = 'You must specify a title for the post icon.';
	}
	else if(strlen($aPostIcon['title']) > 255)
	{
		$aError[] = 'The post icon title is longer than 255 characters.';
	}

	// Filename
	if($aPostIcon['filename'] == '')
	{
		$aError[] = 'You must specify the filename of the post icon.';
	}
	else if(!file_exists("{$CFG['paths']['posticons']}{$aPostIcon['filename']}"))
	{
		$aError[] = 'The post icon image you specified does not exist. Make sure you have uploaded it to your post icons folder.';
	}

	// Return the post icon's information and any errors we encountered.
	return array($aPostIcon, $aError);
}

// User wants to remove a post icon.
function RemoveIcon()
{
	global $CFG, $aPostIcons;

	// What post icon do they want to delete?
	$iPostIconID = (int)$_REQUEST['posticonid'];

	// Does the post icon exist?
	if(!is_array($aPostIcons[$iPostIconID]) || ($iPostIconID == 0))
	{
		Msg("Invalid post icon specified.{$CFG['msg']['invalidlink']}");
	}

	// Are they submitting?
	if((bool)$_REQUEST['removeicon'] && isset($_REQUEST['submit']))
	{
		// Yes, so remove the post icon.
		RemoveIconNow($iPostIconID);
	}

	// Template
	require("./skins/{$CFG['skin']}/admincp/removeposticon.tpl.php");

	// Send the page.
	exit;
}

// Removes the specified post icon.
function RemoveIconNow($iPostIconID)
{
	global $CFG, $dbConn, $aPostIcons;

	// Set all posts, threads, and PMs that use this icon to use no icon.
	$dbConn->query("UPDATE post SET icon=0 WHERE icon={$iPostIconID}");
	$dbConn->query("UPDATE thread SET icon=0 WHERE icon={$iPostIconID}");
	$dbConn->query("UPDATE pm SET icon=0 WHERE icon={$iPostIconID}");

	// Update the post icons.
	unset($aPostIcons[$iPostIconID]);
	$strPostIcons = $dbConn->sanitize(serialize($aPostIcons));
	$dbConn->query("UPDATE configuration SET content='{$strPostIcons}' WHERE name='posticons'");

	// Let the user know it was a success.
	Msg("<b>The post icon was removed successfully.</b><br /><br /><span class=\"smaller\">You should be redirected to the Admin Control Panel momentarily. Click <a href=\"admincp.php?section=posticons\">here</a><br />if you do not want to wait any longer or if you are not redirected.</span>", 'admincp.php?section=posticons');
}

function CensoredWords()
{
	global $CFG, $aCensored;

	// What are they wanting to do?
	switch($_REQUEST['action'])
	{
		case 'Add':
		{
			AddCensoredWord();
		}

		case 'Update':
		{
			UpdateCensoredWord();
		}

		case 'Remove':
		{
			RemoveCensoredWord();
		}
	}

	// Template
	require("./skins/{$CFG['skin']}/admincp/censored.tpl.php");

	// Send the page.
	exit;
}

// Adds a new censored word.
function AddCensoredWord()
{
	global $CFG, $dbConn, $aCensored;

	$word = trim($_REQUEST['word']);

	// Did the user enter a word?
	if($word == '')
	{
		Msg('You must specify a censored word.', 'admincp.php?section=censored');
	}

	// Is the word already in use?
	while(list($key, $aCensoredWord) = each($aCensored)) {
		if($aCensoredWord[0] == $word)
		{
			Msg('The censored word you specified is already in use.', 'admincp.php?section=censored');
		}
	}

	// Add the new censored word.
	$aCensored[] = array($word, trim($_REQUEST['replacement']));
	$strCensored = $dbConn->sanitize(serialize($aCensored));
	$dbConn->query("UPDATE configuration SET content='{$strCensored}' WHERE name='censored'");

	// Let the user know it was a success.
	Msg("<b>Censored Word successfully added.</b><br /><br /><span class=\"smaller\">You should be redirected to the Admin Control Panel momentarily. Click <a href=\"admincp.php?section=censored\">here</a><br />if you do not want to wait any longer or if you are not redirected.</span>", 'admincp.php?section=censored');
}

// Updates censored word.
function UpdateCensoredWord()
{
	global $CFG, $dbConn, $aCensored;

	// Get the censored word's key.
	while(list($key, $aCensoredWord) = each($aCensored)) {
		if($aCensoredWord[0] == $_REQUEST['word'])
		{
			$wordToUpdate = $key; break;
		}
	}

	// Update the censored word.
	$aCensored[$wordToUpdate][1] = trim($_REQUEST['replacement']);
	$strCensored = $dbConn->sanitize(serialize($aCensored));
	$dbConn->query("UPDATE configuration SET content='{$strCensored}' WHERE name='censored'");

	// Let the user know it was a success.
	Msg("<b>Censored Word successfully updated.</b><br /><br /><span class=\"smaller\">You should be redirected to the Admin Control Panel momentarily. Click <a href=\"admincp.php?section=censored\">here</a><br />if you do not want to wait any longer or if you are not redirected.</span>", 'admincp.php?section=censored');
}

// Removes a censored word.
function RemoveCensoredWord()
{
	global $CFG, $dbConn, $aCensored;

	// Get the censored word's key.
	while(list($key, $aCensoredWord) = each($aCensored)) {
		if($aCensoredWord[0] == $_REQUEST['word'])
		{
			$wordToRemove = $key; break;
		}
	}

	// Remove the censored word.
	unset($aCensored[$wordToRemove]);

	$strCensored = $dbConn->sanitize(serialize($aCensored));
	$dbConn->query("UPDATE configuration SET content='{$strCensored}' WHERE name='censored'");

	// Let the user know it was a success.
	Msg("<b>Censored Word successfully removed.</b><br /><br /><span class=\"smaller\">You should be redirected to the Admin Control Panel momentarily. Click <a href=\"admincp.php?section=censored\">here</a><br />if you do not want to wait any longer or if you are not redirected.</span>", 'admincp.php?section=censored');
}

// *************************************************************************** \\

function PrintCPMenu()
{
	global $CFG, $strSection;

	// Template
	require("./skins/{$CFG['skin']}/admincp/menu.tpl.php");
}
?>