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
	define('FILENAME',  0);
	define('DATA',      1);

	// Do they have authorization to view this attachment?
	if(!$_SESSION['permissions']['cviewattachments'])
	{
		// No. Let them know the bad news.
		Unauthorized();
	}

	// What attachment do they want?
	$iAttachmentID = (int)$_REQUEST['id'];

	// Get the attachment's information.
	$dbConn->query("SELECT filename, filedata FROM attachment WHERE id={$iAttachmentID}");
	$aAttachment = $dbConn->getresult();

	$aAttachment[DATA] = $dbConn->unescape($aAttachment[DATA]);

	// Tell them the filename.
	header('Content-disposition: inline; filename="' . $aAttachment[FILENAME] . '"');

	// Tell them how big the attachment is.
	header('Content-length: ' . strlen($aAttachment[DATA]));

	// Tell them what kind of file it is.
	$strMIME = isset($CFG['uploads']['oktypes'][strtolower(substr(strrchr($aAttachment[FILENAME], '.'), 1))]) ? $CFG['uploads']['oktypes'][strtolower(substr(strrchr($aAttachment[FILENAME], '.'), 1))][1] : 'unknown/unknown';
	header("Content-type: {$strMIME}");

	// Send the file.
	echo($aAttachment[DATA]);

	// Update the attachment's viewcount.
	$dbConn->query("UPDATE attachment SET viewcount=viewcount+1 WHERE id={$iAttachmentID}");
?>