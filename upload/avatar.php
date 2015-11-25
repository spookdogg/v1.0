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

	// Constants
	define('FILENAME',   0);
	define('DATETIME',   1);
	define('DATATYPE',   2);
	define('DATUM',      3);

	// Whose avatar do they want?
	$iUserID = (int)$_REQUEST['userid'];

	// Get the avatar information.
	$dbConn->query("SELECT filename, datetime, datatype, datum FROM avatar WHERE id={$iUserID}");
	$aSQLResult = $dbConn->getresult();

	// Does this user have an avatar?
	if((!$aSQLResult) || (!$_SESSION['showavatars']))
	{
		// No, so send them a blank image.
		$strAvatarData = file_get_contents('images/space.png');
		header('Cache-control: max-age=31536000');
		header('Expires: ' . gmdate('D, d M Y H:i:s', $CFG['globaltime']+31536000) . ' GMT');
		header('Last-modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Content-disposition: inline; filename="space.png"');
		header('Content-length: ' . strlen($strAvatarData));
		header('Content-type: image/png');
		echo($strAvatarData);
		exit;
	}

	// What type is this avatar?
	if($aSQLResult[FILENAME])
	{
		// Custom avatar.
		$strFilename = $aSQLResult[FILENAME];
		$tLastModified = $aSQLResult[DATETIME];
		$strAvatarData = $dbConn->unescape($aSQLResult[DATUM]);
	}
	else
	{
		// Public avatar.
		$strFilename = $aAvatars[$aSQLResult[DATUM]]['filename'];
		$tLastModified = $aSQLResult[DATETIME];
		$strAvatarData = file_get_contents("{$CFG['paths']['avatars']}{$strFilename}");
	}

	// Tell them how to cache it.
	header('Cache-control: max-age=31536000');

	// Tell them when it expires.
	header('Expires: ' . gmdate('D, d M Y H:i:s', $tLastModified+31536000) . ' GMT');

	// Tell them the last modification time.
	header('Last-modified: ' . gmdate('D, d M Y H:i:s', $tLastModified) . ' GMT');

	// Tell them the filename.
	header('Content-disposition: inline; filename="'.$strFilename.'"');

	// Tell them how big the attachment is.
	header('Content-length: ' . strlen($strAvatarData));

	// Tell them what kind of file it is.
	switch($aSQLResult[DATATYPE])
	{
		// BMP
		case IMAGETYPE_BMP:
		{
			header('Content-type: image/bmp');
			break;
		}

		// GIF
		case IMAGETYPE_GIF:
		{
			header('Content-type: image/gif');
			break;
		}

		// JPEG
		case IMAGETYPE_JPEG:
		{
			header('Content-type: image/jpeg');
			break;
		}

		// PNG
		case IMAGETYPE_PNG:
		{
			header('Content-type: image/png');
			break;
		}

		// Who knows?
		default:
		{
			header('Content-type: unknown/unknown');
		}
	}

	// Send the file.
	echo($strAvatarData);
?>